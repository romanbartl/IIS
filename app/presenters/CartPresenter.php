<?php

namespace App\Presenters;

use App\Model\ConcertsManager;
use App\Model\TicketsManager;


class CartPresenter extends BasePresenter
{
    /**
     * @var ConcertsManager
     */
    private $concertsManager;


    /**
     * @var TicketsManager
     */
    private $ticketsManager;


    /**
     * CartPresenter constructor.
     * @param ConcertsManager $concertsManager
     * @param TicketsManager $ticketsManager
     */
    public function __construct(ConcertsManager $concertsManager, TicketsManager $ticketsManager)
    {
        parent::__construct();
        $this->concertsManager = $concertsManager;
        $this->ticketsManager = $ticketsManager;
    }


    public function renderDefault()
    {
        $concertsNames = array();
        $ticketsPrices = array();

        foreach ($this->cart->list as $concertId => $ticket) {
            $concertsNames[$concertId] = $this->concertsManager->getConcertNameById($concertId);

            foreach ($ticket as $type => $amount)
                $ticketsPrices[$concertId][$type] = $this->ticketsManager->getTicketPriceByConcertIdAndType($concertId, $type);
        }

        $this->template->concertsNames = $concertsNames;
        $this->template->ticketsPrices = $ticketsPrices;
    }
}