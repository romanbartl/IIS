<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 20.11.2018
 * Time: 11:12
 */

namespace App\Model;


use Nette\Database\Context;

class PlaceManager
{
    /**
     * @var Context
     */
    private $database;


    /**
     * AlbumsManager constructor.
     * @param Context $database
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }


    public function addNewPlace($values) {

    }
}