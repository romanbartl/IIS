<?php

namespace App\Presenters;

use App\Model\ConcertsManager;
use App\Model\FestivalsManager;
use App\Model\TicketsManager;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;


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

            if ($actionType == 'C')
                $this->concertsManager->unlockConcertTickets($actionId, $type, $amount);
            else if ($actionType == 'F')
                $this->festivalsManager->unlockFestivalTickets($actionId, $type, $amount);

            $this->redrawControl('cart');
            $this->redrawControl('cartBody');
        }
    }


    public function handleSendOrder()
    {
        if($this->isAjax()) {
            $userId = $this->user->getId();

            foreach ($this->cart->list as $actionId => $tickets) {
                foreach ($tickets as $action => $ticket) {
                    foreach ($ticket as $type => $amount) {
                        if ($action == 'C') $this->ticketsManager->buyConcertTickets($userId, $amount, $type, $actionId);
                        else if ($action == 'F') $this->ticketsManager->buyFestivalTickets($userId, $amount, $type, $actionId);
                    }
                }
            }

            $this->sendEmail();

            $this->cart->list = null;
            $this->cart->count = 0;
            $this->redrawControl('cart');
            $this->redrawControl('cartBody');

            $this->flashMessage('Objednávka byla vyřízena a odeslali jsme Vám ověřovací e-mail.');
            $this->redirect('Cart:default');




        }
    }


    private function sendEmail()
    {
        $subject = "Objednávka na ticketsonline.4fan.cz";
        $headers = "From: noreply@ticketsonline.4fan.cz" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $message = "
            <html>
            <head>
            <title>HTML email</title>
            </head>
            <body>
            <p>Dobrý den, děkujeme Vám za Vaši objednávku. Doufáme, že si akci užijete!</p>
            ";


        $message .= "
            </body>
            </html>";

        mail($this->user->getIdentity()->email, $subject, $message, $headers);
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
