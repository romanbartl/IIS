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


    public function getAmountOfUnsoldTicketsByConcertIdAndType($ticketId, $ticketType)
    {

    }
}