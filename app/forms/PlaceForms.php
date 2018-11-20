<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 20.11.2018
 * Time: 11:11
 */

namespace App\Forms;


class PlaceForms
{
    private $factory;


    public function __construct(FormFactory $factory, ConcertsManager $concertsManager)
    {
        $this->factory = $factory;
        $this->concertsManager = $concertsManager;
    }
}