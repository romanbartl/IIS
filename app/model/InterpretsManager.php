<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 14.10.2018
 * Time: 11:19
 */

namespace App\Model;

use Nette;

class InterpretsManager
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