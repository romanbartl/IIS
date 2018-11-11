<?php

namespace App\Presenters;

use App\Model\ConcertsManager;
use App\Model\FestivalsManager;
use App\Model\TicketsManager;


class CartPresenter extends BasePresenter
{
    /**
     * @var ConcertsManager
     */
    private $concertsManager;


    /**
     * @var FestivalsManager
     */
    private $festivalsManager;


    /**
     * @var TicketsManager
     */
    private $ticketsManager;


    /**
     * CartPresenter constructor.
     * @param ConcertsManager $concertsManager
     * @param FestivalsManager $festivalsManager
     * @param TicketsManager $ticketsManager
     */
    public function __construct(ConcertsManager $concertsManager, FestivalsManager $festivalsManager,
                                TicketsManager $ticketsManager)
    {
        parent::__construct();
        $this->concertsManager = $concertsManager;
        $this->festivalsManager = $festivalsManager;
        $this->ticketsManager = $ticketsManager;
    }


    /**
     * @param $actionId
     * @param $actionType
     * @param $type
     */
    public function handleDeleteItemFromCart($actionId, $actionType, $type)
    {
        if($this->isAjax()) {
            $amount = $this->cart->list[$actionId][$actionType][$type];
            $this->cart->count -= $amount;

            unset($this->cart->list[$actionId][$actionType][$type]);

            $this->redrawControl('cart');
            $this->redrawControl('cartBody');
        }
    }


    /**
     * @param $actionId
     * @param $actionType
     * @param $type
     * @param $amount
     */
    public function handleUpdateCart($actionId, $actionType, $type, $amount)
    {
        if($this->isAjax()) {

        }
    }


    /**
     *
     */
    public function renderDefault()
    {
        $actionsNames = array();
        $ticketsPrices = array();
        $price = 0;

        foreach ($this->cart->list as $actionId => $tickets) {
            foreach ($tickets as $action => $ticket) {
                $name = "";

                if ($action == 'C') {
                    $name = $this->concertsManager->getConcertNameById($actionId);

                    foreach ($ticket as $type => $amount) {
                        $ticketPrice = $this->ticketsManager->getTicketPriceByConcertIdAndType($actionId, $type);
                        $ticketsPrices[$actionId][$action][$type] = $ticketPrice;

                        $price += $amount * $ticketPrice;
                    }

                } else if ($action == 'F') {
                    $name = $this->festivalsManager->getFestivalNameById($actionId);

                    foreach ($ticket as $type => $amount) {
                        $ticketPrice = $this->ticketsManager->getTicketPriceByFestivalIdAndType($actionId, $type);;
                        $ticketsPrices[$actionId][$action][$type] = $ticketPrice;

                        $price += $amount * $ticketPrice;
                    }
                }

                $actionsNames[$actionId][$action] = $name;
            }
        }

        $this->template->actionsNames = $actionsNames;
        $this->template->ticketsPrices = $ticketsPrices;
        $this->template->price = $price;
    }
}
