<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 15.10.2018
 * Time: 17:21
 */

namespace App\Model;

use Nette;

class MembersManager extends BaseManager
{
    /**
     * @var Nette\Database\Context
     */
    private $database;


    /**
     * AlbumsManager constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    /**
     * @param $interpretId
     * @return Nette\Database\ResultSet
     */
    public function getMembersByInterpretId($interpretId)
    {
        return $this->database->query('SELECT m.* FROM Member AS m LEFT JOIN Interpret_has_Member AS ihm ON ihm.idMember = m.idMember WHERE ihm.idInterpret = ?', $interpretId);
    }
}