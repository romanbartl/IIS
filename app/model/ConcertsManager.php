<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 15.10.2018
 * Time: 18:54
 */

namespace App\Model;

use Nette;

class ConcertsManager
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
     * @param $interpretId
     * @return Nette\Database\ResultSet
     */
    public function getExpiredConcertsByInterpretId($interpretId)
    {
        return $this->database->query('SELECT c.idConcert AS idConcert, c.name AS name, c.date AS date 
                        FROM Concert AS c 
                        LEFT JOIN Concert_has_Interpret AS chi ON chi.idConcert = c.idConcert
                        WHERE chi.idInterpret = ? AND date < CURDATE()', $interpretId);
    }


    /**
     * @param $interpretId
     * @return Nette\Database\ResultSet
     */
    public function getUpcomingConcertsByInterpretId($interpretId)
    {
        return $this->database->query('SELECT c.idConcert AS idConcert, c.name AS name, c.date AS date 
                        FROM Concert AS c 
                        LEFT JOIN Concert_has_Interpret AS chi ON chi.idConcert = c.idConcert
                        WHERE chi.idInterpret = ? AND date >= CURDATE()', $interpretId);
    }


    /**
     * @return Nette\Database\ResultSet
     */
    public function getAllConcertsWithTickets()
    {
        return
            $this->database->query('SELECT c.idConcert AS idConcert, c.name AS name, c.date AS date, p.name AS placeName, 
                                        ci.name AS city, COUNT(c.idConcert) AS tickets, i.label AS label 
                                        FROM Concert AS c 
                                        LEFT JOIN Place AS p ON p.idPlace = c.idPlace 
                                        LEFT JOIN City AS ci ON ci.idCity = p.idCity 
                                        LEFT JOIN Ticket AS t ON t.idConcert = c.idConcert 
                                        LEFT JOIN Concert_has_Interpret AS chi ON chi.idConcert = c.idConcert 
                                        LEFT JOIN Interpret AS i ON i.idInterpret = chi.idInterpret 
                                        WHERE t.bought = 0 AND chi.headliner = 1 
                                        GROUP BY c.idConcert ORDER BY c.date DESC');
    }


    /**
     * @param $concertId
     * @return mixed
     */
    public function getConcertById($concertId)
    {
        $concert['info'] = $this->database->query('SELECT c.idConcert AS idConcert, c.name AS name, c.date AS date, 
                                                  c.capacity AS capacity, c.info AS info, p.name AS place, 
                                                  p.address AS address, p.zipCode AS zipCode, ci.name AS city, i.label AS label
                                                  FROM Concert AS c 
                                                  LEFT JOIN Place AS p ON p.idPlace = c.idPlace
                                                  LEFT JOIN City AS ci ON ci.idCity = p.idCity 
                                                  LEFT JOIN Concert_has_Interpret AS chi ON chi.idConcert = c.idConcert
                                                  LEFT JOIN Interpret AS i ON i.idInterpret = chi.idInterpret
                                                  WHERE chi.headliner = 1 AND c.idConcert = ? LIMIT 1', $concertId)->fetch();

        $concert['interprets'] = $this->database->query('SELECT i.idInterpret AS idInterpret, i.name AS name, chi.headliner AS headliner
                                                            FROM Interpret AS i 
                                                            LEFT JOIN Concert_has_Interpret AS chi ON chi.idInterpret = i.idInterpret 
                                                            WHERE chi.idConcert = ? ORDER BY chi.headliner DESC', $concertId);

        $concert['tickets'] = $this->database->query('SELECT price, type, COUNT(type) AS count 
                                                          FROM Ticket WHERE bought = 0 AND idConcert = ? GROUP BY type ORDER BY type ASC', $concertId);

        return $concert;
    }
}
