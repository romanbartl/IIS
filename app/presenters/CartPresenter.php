<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 17.10.2018
 * Time: 13:47
 */

namespace App\Presenters;

use Nette\Http\Session;

class CartPresenter extends BasePresenter
{
    private $tickets;


    public function actionDefault()
    {

    }


    public function handleAhoj()
    {
        if($this->isAjax()) {
            $this->cart = $this->getSession('cart');
            $this->cart->count += 1;
            $this->template->cart = $this->cart;

            $this->redrawControl("cart");
        }
    }

    public function handleSmazat()
    {
        if($this->isAjax()) {
            $this->cart = $this->getSession('cart');
            $this->cart->count -= 1;
            $this->template->cart = $this->cart;

            $this->redrawControl("cart");
        }
    }


    public function renderDefault()
    {
        $this->template->cart = $this->cart;
    }
}