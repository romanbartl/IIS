<?php

namespace App\Model;

use Nette;

class TicketsManager
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
     * @param $concertId
     * @param $ticketType
     * @return false|mixed
     */
    public function getTicketPriceByConcertIdAndType($concertId, $ticketType)
    {
        return $this->database->query('SELECT price 
                                            FROM Ticket 
                                            WHERE idConcert = ?
                                            AND type = ?
                                            LIMIT 1', $concertId, $ticketType)->fetchField('price');
    }


    public function getTicketPriceByFestivalIdAndType($concertId, $ticketType)
    {
        return $this->database->query('SELECT price 
                                            FROM Ticket 
                                            WHERE idYear = ?
                                            AND type = ?
                                            LIMIT 1', $concertId, $ticketType)->fetchField('price');
    }


    public function getAmountOfUnsoldTicketsByConcertIdAndType($ticketId, $ticketType)
    {

    }


    /**
     * @param $userId
     * @param $amount
     * @param $type
     * @param $festId
     */
    public function buyFestivalTickets($userId, $amount, $type, $festId)
    {
        $this->database->query('LOCK TABLES Ticket WRITE;');

        $this->database->query('UPDATE Ticket SET bought = 1, idUser = ?, inCart = 0
                                    WHERE type = ? AND idYear = ? AND inCart = 1
                                    LIMIT ?', $userId, $type, $festId, intval($amount));

        $this->database->query('UNLOCK TABLES;');
    }


    /**
     * @param $userId
     * @param $amount
     * @param $type
     * @param $concertId
     */
    public function buyConcertTickets($userId, $amount, $type, $concertId)
    {
        $this->database->query('LOCK TABLES Ticket WRITE;');

        $this->database->query('UPDATE Ticket SET bought = 1, idUser = ?, inCart = 0
                                    WHERE type = ? AND idConcert = ? AND inCart = 1
                                    LIMIT ?', $userId, $type, $concertId, intval($amount));

        $this->database->query('UNLOCK TABLES;');
    }
}
