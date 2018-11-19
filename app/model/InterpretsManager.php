<?php

namespace App\Model;

use Nette;

class InterpretsManager extends BaseManager
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


    /**
     * @return Nette\Database\Table\Selection - Returns all interprets from database
     */
    public function getAllInterprets()
    {
        return $this->database->table(self::TABLE_INTERPRET)->order(self::INTERPRET_NAME);
    }


    /**
     * Returns interpret by given ID
     * @param $id - ID of interpret
     * @return false|Nette\Database\Table\ActiveRow - interpret info
     */
    public function getInterpretById($id)
    {
        return $this->database->table(self::TABLE_INTERPRET)->where(self::INTERPRET_ID, $id)->fetch();
    }


    public function addInterpret($values) {
        return $this->database->table(self::TABLE_INTERPRET)
            ->insert([
                self::INTERPRET_NAME => $values->name,
                self::INTERPRET_LABEL => $values->label,
                self::INTERPRET_FOUNDED => $values->founded
            ]);
    }
}