<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 14.10.2018
 * Time: 12:52
 */

namespace App\Model;

use Nette;

class AlbumsManager extends BaseManager
{
    /**
     * @var Nette\Database\Context
     */
    private $database;


    /**
     * InterpretsManager constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    
}