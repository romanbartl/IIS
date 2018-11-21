<?php

namespace App\Model;

use Nette;

class TicketsManager extends BaseManager
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


    public function addTicketsToConcert($values) {
        for($i = 0; $i < $values->amount; $i++) {
            $this->database->table(self::TABLE_TICKET)
                ->insert([
                    self::TICKET_COLUMN_PRICE => $values->price,
                    self::TICKET_COLUMN_ID_CONCERT => $values->idConcert,
                    self::TICKET_COLUMN_TYPE => $values->ticketType
                ]);
        }
    }


    public function getTicketsConcertByType($idConcert) {
        $tickets['all'] = $this->database->query('SELECT type, count(*) AS cnt, price FROM `Ticket` WHERE idConcert=? GROUP BY type', $idConcert)
            ->fetchAll();

        $tickets['reserved'] = $this->database->query('SELECT type, count(*) AS cnt, price FROM `Ticket` WHERE idConcert=? AND (bought=1 OR inCart=1) GROUP BY type', $idConcert)
            ->fetchAll();

        return $tickets;
    }


    public function tryRemoveTickets($idConcert, $type) {
        return $this->database->table(self::TABLE_TICKET)
            ->where(self::TICKET_COLUMN_ID_CONCERT, $idConcert)
            ->where(self::TICKET_COLUMN_TYPE, $type)
            ->where(self::TICKET_COLUMN_IN_CART, 0)
            ->where(self::TICKET_COLUMN_BOUGHT, 0)
            ->delete();
    }


    public function changeAmountOfTickets($values) {
        $limit = abs($values->currentAmount - $values->amount);
        if($values->currentAmount < $values->amount) {
            for($i = 0; $i < $limit; $i++) {
                $this->database->table(self::TABLE_TICKET)
                    ->insert([
                        self::TICKET_COLUMN_PRICE => $values->price,
                        self::TICKET_COLUMN_ID_CONCERT => $values->idConcert,
                        self::TICKET_COLUMN_TYPE => $values->type
                    ]);
            }
        }
        else {
            return $this->database->table(self::TABLE_TICKET)
                ->where(self::TICKET_COLUMN_ID_CONCERT, $values->idConcert)
                ->where(self::TICKET_COLUMN_TYPE, $values->type)
                ->where(self::TICKET_COLUMN_IN_CART, 0)
                ->where(self::TICKET_COLUMN_BOUGHT, 0)
                ->limit($limit)
                ->delete();
        }
    }
}
