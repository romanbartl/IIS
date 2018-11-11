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
                                        end, P.name AS place, C.name AS city, COUNT(T.idYear) AS tickets
                                        FROM Year AS Y 
                                        LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival 
                                        LEFT JOIN Place AS P ON P.idPlace = Y.idPlace 
                                        LEFT JOIN City AS C ON C.idCity = P.idCity 
                                        LEFT JOIN Ticket AS T ON T.idYear = Y.idYear
                                        GROUP BY Y.idYear
                                        ORDER BY start DESC');
    }


    /**
     * @param $yearId
     * @return mixed
     */
    public function getFestivalById($yearId)
    {
        $festival['info'] = $this->database->query('SELECT idYear, F.name AS festival, F.label AS label, 
                                                        season, volume, start, end, info, P.name AS place, P.address AS address, 
                                                        P.gpsLat AS lat, P.gpsLng AS lng, P.zipCode zipCode, 
                                                        C.name AS city 
                                                        FROM Year AS Y 
                                                        LEFT JOIN Festival AS F ON F.idFestival = Y.idFestival 
                                                        LEFT JOIN Place AS P ON P.idPlace = Y.idPlace 
                                                        LEFT JOIN City AS C ON C.idCity = P.idCity 
                                                        WHERE Y.idYear = ?', $yearId)->fetch();


        // TODO should be request from TicketsManager!!!!
        $festival['tickets'] = $this->database->query('SELECT price, type, COUNT(type) AS count 
                                                          FROM Ticket WHERE bought = 0 AND idYear = ? GROUP BY type ORDER BY type ASC', $yearId);


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
                                                          GROUP BY S.idStage', $yearId);

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
}