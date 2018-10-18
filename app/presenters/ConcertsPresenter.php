<?php

namespace App\Presenters;

use App\Model\ConcertsManager;


class ConcertsPresenter extends BasePresenter
{
    /**
     * @var ConcertsManager
     */
    public $concertsManager;


    /**
     * @var Integer $concertId
     */
    private $concertId;


    /**
     * ConcertsPresenter constructor.
     * @param ConcertsManager $concertsManager
     */
    public function __construct(ConcertsManager $concertsManager)
    {
        parent::__construct();
        $this->concertsManager = $concertsManager;
    }


    /**
     * When template detail is loaded with GET parameters (id of concert) it stores this GET value to global var
     * @param $id
     */
    public function actionDetail($id)
    {
        $this->concertId = $id;
    }


    /**
     * Adding new items to the cart
     * AJAX request
     * @param $concertId
     * @param $ticketType
     * @param $amount
     */
    public function handleAddToCart($concertId, $ticketType, $amount)
    {
        if($this->isAjax()) {
            $this->cart = $this->getSession('cart');

            //TODO change getConcertById by some method from TicketsManager after it's working
            //TODO change max of the amount input number if is in cart

            $concert = $this->concertsManager->getConcertById($this->concertId);

            /*$help = false;

            foreach ($concert['tickets'] as $ticket) {
               if (isset($this->cart->list[$concertId][$ticket->type]) &&
                    $this->cart->list[$concertId][$ticket->type] == $ticket->count) {

                   $help = true;
                   continue;
               }

                $this->template->firstAmount = $ticket->count;
                $this->template->firstType = $ticket->type;
                break;
            }*/

            if (isset($this->cart->list[$concertId])) {
                if (isset($this->cart->list[$concertId][$ticketType])) {

                    //if (!$help) {
                        $this->cart->list[$concertId][$ticketType] += $amount;
                        $this->cart->count += $amount;
                    //}

                } else {
                    $this->cart->list[$concertId][$ticketType] = $amount;
                    $this->cart->count += $amount;
                }

            } else {
                $this->cart->list[$concertId][$ticketType] = $amount;
                $this->cart->count += $amount;
            }

            $this->template->cart = $this->cart;
            $this->template->concert = $concert;


            /*$this->template->firstAmount = 0;
            $this->template->firstType = "";

            foreach ($concert['tickets'] as $ticket) {
                if ((isset($this->cart->list[$this->concertId][$ticket->type]) &&
                    $this->cart->list[$this->concertId][$ticket->type] == $ticket->count) || (
                    isset($this->cart->list[$this->concertId][$ticketType]) &&
                    $this->cart->list[$this->concertId][$ticketType] + $amount == $ticket->count))

                    continue;

                $this->template->firstAmount = $ticket->count;

                if (isset($this->cart->list[$this->concertId][$ticket->type]))
                    $this->template->firstAmount -= $this->cart->list[$this->concertId][$ticket->type];

                $this->template->firstType = $ticket->type;
                break;
            }*/

            //TODO - set first amount to 0 if all tickets are in cart

            $this->redrawControl('cart');
            $this->redrawControl('ticketsSnippet');
        }
    }


    /**
     * On template default creates new template variable 'concerts'
     */
    public function renderDefault()
    {
        $this->template->concerts = $this->concertsManager->getAllConcertsWithTickets();
    }


    /**
     * On template detail creates new template variable 'concertId'
     */
    public function renderDetail()
    {
        $this->template->concertId = $this->concertId;

        //TODO change getConcertById by some method from TicketsManager after it's working
        $this->template->concert = $this->concertsManager->getConcertById($this->concertId);

        $this->template->firstAmount = 0;
        $this->template->firstType = "";

        //if (!isset($this->template->firstAmount)) {
            foreach ($this->template->concert['tickets'] as $ticket) {
                if (isset($this->cart->list[$this->concertId][$ticket->type]) &&
                    $this->cart->list[$this->concertId][$ticket->type] == $ticket->count)
                    continue;

                $this->template->firstAmount = $ticket->count;

                if (isset($this->cart->list[$this->concertId][$ticket->type]))
                    $this->template->firstAmount -= $this->cart->list[$this->concertId][$ticket->type];

                $this->template->firstType = $ticket->type;
                break;
            }
        //}

        $this->template->concert = $this->concertsManager->getConcertById($this->concertId);
    }
}
