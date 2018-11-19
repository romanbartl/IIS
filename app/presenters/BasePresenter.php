<?php

namespace App\Presenters;

use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var \ArrayObject $cart
     */
    public $cart;


    /**
     * This method sets template variable '$cart' from session for every page
     */
    public function startup()
    {
        parent::startup();
        $this->cart = $this->getSession('cart');

        if ($this->cart->count == null) {
            $this->cart->count = 0;
            $this->cart->list = array();
        } else {

            // TODO check if some of the tickets in basket are not already sold out
            // TODO also what if someone buys 1 ticket and other kokot has max of tickets ... must that one bought ticket delete from cart
            //foreach ($this->cart->list as $item) {
                //$this->ticketsManager->getAmountOfUnsoldTicketsByConcertIdAndType($item->);
            //}
        }

        $this->template->cart = $this->cart;
    }
}
