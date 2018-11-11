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

            if (isset($this->cart->list[$concertId]['C'])) {
                if (isset($this->cart->list[$concertId]['C'][$ticketType])) {
                    $this->cart->list[$concertId]['C'][$ticketType] += $amount;
                    $this->cart->count += $amount;
                } else {
                    $this->cart->list[$concertId]['C'][$ticketType] = $amount;
                    $this->cart->count += $amount;
                }

            } else {
                $this->cart->list[$concertId]['C'][$ticketType] = $amount;
                $this->cart->count += $amount;
            }

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
        $concert = $this->concertsManager->getConcertById($this->concertId);

        $ticketsMaxAmounts = array();
        $firstType = "";
        $firstAmount = 0;

        foreach ($concert['tickets'] as $key => $ticket) {
            if (isset($this->cart->list[$this->concertId]['C'][$ticket->type])) {
                //is in cart
                $count = $ticket->count - $this->cart->list[$this->concertId]['C'][$ticket->type];
                $ticketsMaxAmounts[] = $count;

                if (($count != 0 && $key == 0 && $firstType != "") || ($count != 0 && $key != 0 && $firstType == "")
                    || ($count != 0 && $key == 0 && $firstType == "")) {
                    $firstType = $ticket->type;
                    $firstAmount = $count;
                }

            } else {
                //not in cart
                $ticketsMaxAmounts[] = $ticket->count;

                if ($firstType == "") {
                    $firstType = $ticket->type;
                    $firstAmount = $ticket->count;
                }
            }
        }

        $this->template->concert = $this->concertsManager->getConcertById($this->concertId);
        $this->template->ticketsMaxAmounts = $ticketsMaxAmounts;
        $this->template->firstType = $firstType;
        $this->template->firstAmount = $firstAmount;
    }
}
