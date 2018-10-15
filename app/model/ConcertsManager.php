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
}