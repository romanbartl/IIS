<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 10.11.2018
 * Time: 10:22
 */

namespace App\Model;

use Nette;

class FestivalsManager
{
    /**
     * @var Nette\Database\Context
     */
    private $database;


    /**
     * AlbumsManager constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    /**
     * @return Nette\Database\ResultSet
     */
    public function getAllFestivalsWithTickets()
    {
        return
            $this->database->query('SELECT Y.idYear AS idYear, F.name AS festival, F.label AS label, season, volume, start, 
                                        end, P.name AS place, P.city AS city, COUNT(T.idYear) AS tickets
                                        FROM Year AS Y 
                                        LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival 
                                        LEFT JOIN Place AS P ON P.idPlace = Y.idPlace
                                        LEFT JOIN Ticket AS T ON T.idYear = Y.idYear
                                        GROUP BY Y.idYear
                                        ORDER BY start DESC');
    }


    /**
     * @param $festivalId
     * @param $type
     * @param $count
     * @return false|mixed
     */
    public function lockFestivalTickets($festivalId, $type, $count)
    {
        $this->database->query('LOCK TABLES Ticket WRITE;');

        $dbCount = $this->database->query('SELECT COUNT(*) AS count
                                    FROM Ticket
                                    WHERE bought = 0 AND idUser IS NULL AND inCart = 0 AND idYear = ? AND type = ?', $festivalId, $type)->fetchField('count');

        if ($dbCount < $count) $count = $dbCount;

        if ($count == 0) {
            $this->database->query('UNLOCK TABLES;');
            return $count;
        }

        $this->database->query("UPDATE Ticket SET inCart = 1
                                    WHERE type = ? AND idYear = ? AND inCart = 0 AND idUser IS NULL
                                    LIMIT ?", $type, $festivalId, intval($count));

        $this->database->query('UNLOCK TABLES;');

        return $count;
    }


    /**
     * @param $festivalId
     * @param $type
     * @param $count
     */
    public function unlockFestivalTickets($festivalId, $type, $count)
    {
        $this->database->query('UPDATE Ticket SET inCart = 0
                                    WHERE type = ? AND idYear = ? AND inCart = 1
                                    LIMIT ?', $type, $festivalId, intval($count));
    }


    /**
     * @param $yearId
     * @return mixed
     */
    public function getFestivalById($yearId)
    {
        $festival['info'] = $this->database->query('SELECT idYear, F.idFestival AS idFest, F.name AS festival, F.label AS label, 
                                                        season, volume, start, end, info, P.name AS place, P.address AS address, 
                                                        P.gpsLat AS lat, P.gpsLng AS lng, P.zipCode zipCode, Y.idPlace AS idPlace,
                                                        P.city AS city 
                                                        FROM Year AS Y 
                                                        LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival 
                                                        LEFT JOIN Place AS P ON P.idPlace = Y.idPlace
                                                        WHERE Y.idYear = ?', $yearId)->fetch();


        // TODO should be request from TicketsManager!!!!
        $festival['tickets'] = $this->database->query('SELECT price, type, COUNT(type) AS count 
                                                          FROM Ticket WHERE idUser IS NULL AND bought = 0 AND inCart = 0 AND idYear = ? GROUP BY type ORDER BY type ASC', $yearId);


        $stages = $this->database->query('SELECT S.idStage AS id, S.name AS stage 
                                              FROM Stage AS S 
                                              LEFT JOIN Stage_has_Interpret_in_Year AS SHIIY ON SHIIY.idStage = S.idStage 
                                              WHERE SHIIY.idYear = ?
                                              GROUP BY S.idStage ORDER BY S.idStage ASC', $yearId);

        $festival['stagesProgram'] = array();

        $festStart = new \DateTime($festival['info']['start']);
        $festEnd = new \DateTime($festival['info']['end']);

        $firstStageId = null;
        $first = true;

        foreach ($stages as $stage) {
            $festival['stagesProgram'][$stage['id']] = array();

            for ($i = $festStart; $i <= $festEnd; $i->modify('+1 day')) {
                $festival['stagesProgram'][$stage['id']][$i->format('Y-m-d')] = array();

                $program =
                    $this->database->query('SELECT I.idInterpret AS id, I.name AS interpret, SHI.start AS start, SHI.end AS end, SHI.headliner AS headliner
                                            FROM Interpret AS I 
                                            LEFT JOIN Stage_has_Interpret_in_Year AS SHI ON SHI.idInterpret = I.idInterpret
                                            WHERE SHI.idStage = ? AND SHI.idYear = ? AND DATE_FORMAT(SHI.start, "%Y-%m-%d") = ?
                                            ORDER BY start ASC', $stage['id'], $yearId, $i->format('Y-m-d'));

                foreach ($program as $interpret) {
                    $festival['stagesProgram'][$stage['id']][$i->format('Y-m-d')][] = $interpret;
                }
            }

            $festStart = new \DateTime($festival['info']['start']);
            $festEnd = new \DateTime($festival['info']['end']);

            if ($first) {
                $firstStageId = $stage['id'];
                $first = false;
            }
        }


        $festival['stages'] = $this->database->query('SELECT S.idStage AS id, S.name AS stage 
                                                          FROM Stage AS S 
                                                          LEFT JOIN Stage_has_Interpret_in_Year AS SHIIY ON SHIIY.idStage = S.idStage 
                                                          WHERE SHIIY.idYear = ?
                                                          GROUP BY S.idStage', $yearId)->fetchAll();

        $festival['firstStageId'] = $firstStageId;

        return $festival;
    }


    /**
     * @param $festivalId
     * @return false|mixed
     */
    public function getFestivalNameById($festivalId)
    {
        return $this->database->query("SELECT CONCAT(F.name, ' ', Y.volume, ' (', Y.season, ')') AS name 
                                            FROM Year AS Y 
                                            LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival 
                                            WHERE Y.idYear = ?", $festivalId)->fetchField('name');
    }


    public function getExpiredFestivalsByInterpretId($interpretId)
    {
        return
            $this->database->query("SELECT Y.idYear AS id, CONCAT(F.name, ' ', Y.volume, ' (', Y.season, ')') AS name, Y.start AS start, Y.end AS end
                                    FROM Festival AS F 
                                    LEFT JOIN Year AS Y ON Y.idFestival = F.idFestival
                                    LEFT JOIN Stage_has_Interpret_in_Year AS SHIIY ON Y.idYear = SHIIY.idYear
                                    WHERE SHIIY.idInterpret = ? AND Y.start < CURDATE()
                                    ORDER BY Y.start ASC", $interpretId);
    }


    public function getUpcomingFestivalsByInterpretId($interpretId)
    {
        return
            $this->database->query("SELECT Y.idYear AS id, CONCAT(F.name, ' ', Y.volume, ' (', Y.season, ')') AS name, Y.start AS start, Y.end AS end
                                    FROM Festival AS F 
                                    LEFT JOIN Year AS Y ON Y.idFestival = F.idFestival
                                    LEFT JOIN Stage_has_Interpret_in_Year AS SHIIY ON Y.idYear = SHIIY.idYear
                                    WHERE SHIIY.idInterpret = ? AND Y.start >= CURDATE()
                                    ORDER BY Y.start ASC", $interpretId);
    }


    /**
     * @return Nette\Database\ResultSet
     */
    public function getFestivalsNews()
    {
        return
            $this->database->query('SELECT Y.idYear AS idYear, F.name AS festival, F.label AS label, season, volume, start, 
                                        end, P.name AS place, P.city AS city, COUNT(T.idYear) AS tickets
                                        FROM Year AS Y 
                                        LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival 
                                        LEFT JOIN Place AS P ON P.idPlace = Y.idPlace
                                        LEFT JOIN Ticket AS T ON T.idYear = Y.idYear
                                        WHERE Y.start > NOW() 
                                        GROUP BY Y.idYear
                                        ORDER BY start ASC');
    }


    /**
     * @param $limit
     * @return Nette\Database\ResultSet
     */
    public function getNewsFestivalsSliderPages($limit)
    {
        return
            $this->database->query('SELECT I.name AS interpret, I.label AS label, F.name AS festName, 
                                        Y.volume AS volume, Y.info AS info, "fest" AS "type" 
                                        FROM Interpret AS I
                                        LEFT JOIN Stage_has_Interpret_in_Year AS SHIIY on I.idInterpret = SHIIY.idInterpret 
                                        LEFT JOIN Year AS Y ON Y.idYear = SHIIY.idYear
                                        LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival
                                        WHERE Y.idYear IS NOT NULL AND Y.start > NOW() AND SHIIY.headliner = 1
                                        ORDER BY Y.start ASC
                                        LIMIT ?', intval($limit));
    }


    /**
     * @param $name
     * @param $label
     */
    public function addNewFestival($name, $label)
    {
        $this->database->query('INSERT INTO Festival(name, label) VALUES (?, ?)', $name, $label);
    }


    /**
     * @return Nette\Database\ResultSet
     */
    public function getFestivalsIds()
    {
        return $this->database->query('SELECT * FROM Festival ORDER BY name ASC');
    }


    /**
     * @param $idFestival
     * @return Nette\Database\ResultSet
     */
    public function getFestival($idFestival)
    {
        return $this->database->query('SELECT * FROM Festival WHERE idFestival = ?', $idFestival)->fetch();
    }


    /**
     * @param $idFestival
     * @param $name
     * @param $label
     */
    public function editFestival($idFestival, $name, $label)
    {
        $this->database->query('UPDATE Festival SET name = ?, label = ? WHERE idFestival = ?', $name, $label, $idFestival);
    }


    /**
     * @param $volume
     * @param $season
     * @param $startDate
     * @param $startTime
     * @param $endDate
     * @param $endTime
     * @param $festivalId
     * @return string
     */
    public function addNewYear($volume, $season, $startDate, $startTime, $endDate, $endTime, $festivalId, $placeId)
    {
        $year = $this->database->query('SELECT idYear FROM Year WHERE volume = ? AND season = ? AND idFestival = ?',
            $volume, $season, $festivalId)->fetchField('idYear');

        if ($year != null) return false;

        $startDateTime = date('Y-m-d H:i:s', strtotime("$startDate $startTime"));
        $endDateTime = date('Y-m-d H:i:s', strtotime("$endDate $endTime"));

        $this->database->query('INSERT INTO Year (idPlace, season, volume, start, end, idFestival, idPlace) 
                                        VALUES (NULL, ?, ?, ?, ?, ?, ?)',
            $season, $volume, $startDateTime, $endDateTime, $festivalId, $placeId);

        return $this->database->getInsertId('Year');
    }


    /**
     * @param $idYear
     * @param $idFestival
     * @param $volume
     * @param $season
     * @param $info
     * @param $startDate
     * @param $startTime
     * @param $endDate
     * @param $endTime
     * @return bool
     */
    public function editFestivalInfo($idYear, $idFestival, $volume, $season, $info, $startDate, $startTime, $endDate, $endTime)
    {
        $year = $this->database->query('SELECT idYear FROM Year WHERE volume = ? AND season = ? AND idFestival = ?',
            $volume, $season, $idFestival)->fetchField('idYear');

        if ($year != null && $year != $idYear) {
            return false;
        }

        $start = $this->database->query('SELECT start FROM Year WHERE idYear = ?', $idYear)->fetchField('start');
        $end = $this->database->query('SELECT end FROM Year WHERE idYear = ?', $idYear)->fetchField('end');

        $startDateTimeDb = date('Y-m-d H:i:s', strtotime("$start"));
        $endDateTimeDb = date('Y-m-d H:i:s', strtotime("$end"));

        $startDateTime = date('Y-m-d H:i:s', strtotime("$startDate $startTime"));
        $endDateTime = date('Y-m-d H:i:s', strtotime("$endDate $endTime"));


        if ($startDateTimeDb < $startDateTime ||  $endDateTimeDb > $endDateTime) {
            $program = $this->database->query('SELECT * 
                                FROM Stage_has_Interpret_in_Year WHERE idYear = ?', $idYear)->fetchAll();

            foreach ($program as $part) {
                if ($part->start < $startDateTime || $part->end > $endDateTime) {
                    $this->database->query('DELETE FROM Stage_has_Interpret_in_Year WHERE idYear = ? AND idStage = ? AND idInterpret = ?',
                        $idYear, $part->idStage, $part->idInterpret);
                }
            }
        }

        $this->database->query('UPDATE Year SET start = ?, end = ?, volume = ?, season = ?, idFestival = ?, info = ? WHERE idYear = ?',
            $startDateTime, $endDateTime, $volume, $season, $idFestival, $info, $idYear);

        return true;
    }


    /**
     * @return Nette\Database\ResultSet
     */
    public function getStages()
    {
        return
            $this->database->query('SELECT S.idStage AS idStage, S.name AS name 
                                    FROM Stage AS S 
                                    WHERE S.idStage
                                    ORDER BY S.name ASC');
    }


    public function addStageToYear($stageId, $interpretId, $yearId, $start, $end, $headliner)
    {
        $this->database->query('INSERT INTO Stage_has_Interpret_in_Year(idStage, idInterpret, idYear, headliner, start, end) 
                                    VALUES (?, ?, ?, ?, ?, ?)', $stageId, $interpretId, $yearId, $headliner, $start, $end);
    }


    public function addStage($name)
    {
        $this->database->query('INSERT INTO Stage (name) VALUES (?)', $name);
    }


    public function deleteInterpretFromStage($interpretId, $stageId, $festivalId)
    {
        $this->database->query('DELETE FROM Stage_has_Interpret_in_Year WHERE idStage = ? AND idInterpret = ? AND idYear = ?',
            $stageId, $interpretId, $festivalId);
    }


    public function deleteStageInYear($stageId, $yearId)
    {
        $this->database->query('DELETE FROM Stage_has_Interpret_in_Year WHERE idStage = ? AND idYear = ?',
            $stageId, $yearId);
    }


    public function getAllStages()
    {
        return $this->database->query('SELECT * FROM Stage');
    }


    public function getStageById($stageId)
    {
        return $this->database->query('SELECT * FROM Stage WHERE idStage = ?', $stageId)->fetch();
    }


    public function editStage($stageId, $name)
    {
        $this->database->query('UPDATE Stage SET name = ? WHERE idStage = ?', $name, $stageId);
    }


    public function deleteStage($stageId)
    {
        $stage = $this->database->query('SELECT idStage FROM Stage_has_Interpret_in_Year WHERE idStage = ?', $stageId)->fetchField('idStage');

        if ($stage == null) {
            $this->database->query('DELETE FROM Stage WHERE idStage = ?', $stageId);
            return true;
        }

        return false;
    }


    public function getAllFestivals()
    {
        return $this->database->query('SELECT * FROM Festival')->fetchAll();
    }


    public function connectFestivalAndPlace($idYear, $idPlace)
    {
        $this->database->query('UPDATE Year SET idPlace = ? WHERE idYear = ?', $idPlace, $idYear);
    }

    public function getIdPlaceFromYear($idYear)
    {
        return
            $this->database->query('SELECT idPlace FROM Year WHERE idYear = ?', $idYear)->fetchField('idPlace');
    }
}
