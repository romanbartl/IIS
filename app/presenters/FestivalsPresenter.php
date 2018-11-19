<?php

namespace App\Presenters;


use App\Model\FestivalsManager;

class FestivalsPresenter extends BasePresenter
{
    /**
     * @var FestivalsManager
     */
    private $festivalManager;

    /**
     * @var Integer $festivalId
     */
    private $festivalId;


    /**
     * FestivalsPresenter constructor.
     * @param FestivalsManager $festivalsManager
     */
    public function __construct(FestivalsManager $festivalsManager)
    {
        parent::__construct();
        $this->festivalManager = $festivalsManager;
    }


    public function renderDefault()
    {
        $this->template->festivals = $this->festivalManager->getAllFestivalsWithTickets();
    }


    /**
     * @param $festivalId
     * @param $ticketType
     * @param $amount
     */
    public function handleAddToCart($festivalId, $ticketType, $amount)
    {
        if($this->isAjax()) {
            $amount = $this->festivalManager->lockFestivalTickets($festivalId, $ticketType, $amount);

            if ($amount != 0) {
                $this->cart = $this->getSession('cart');

                if (isset($this->cart->list[$festivalId]['F'])) {
                    if (isset($this->cart->list[$festivalId]['F'][$ticketType])) {
                        $this->cart->list[$festivalId]['F'][$ticketType] += $amount;
                        $this->cart->count += $amount;
                    } else {
                        $this->cart->list[$festivalId]['F'][$ticketType] = $amount;
                        $this->cart->count += $amount;
                    }

                } else {
                    $this->cart->list[$festivalId]['F'][$ticketType] = $amount;
                    $this->cart->count += $amount;
                }
            }

            $this->redrawControl('cart');
            $this->redrawControl('ticketsSnippet');
        }
    }


    public function renderDetail()
    {
        $this->template->festivalId = $this->festivalId;

        $festival = $this->festivalManager->getFestivalById($this->festivalId);

        if ($festival['info'] == null) $this->redirect('Notfound:default');

        $ticketsMaxAmounts = array();
        $firstType = "";
        $firstAmount = 0;

        foreach ($festival['tickets'] as $key => $ticket) {
            if (isset($this->cart->list[$this->festivalId]['F'][$ticket->type])) {
                //is in cart
                $count = $ticket->count;
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

        $this->template->festival = $this->festivalManager->getFestivalById($this->festivalId);
        $this->template->ticketsMaxAmounts = $ticketsMaxAmounts;
        $this->template->firstType = $firstType;
        $this->template->firstAmount = $firstAmount;
    }


    public function actionDetail($id)
    {
        $this->festivalId = $id;
    }
}
