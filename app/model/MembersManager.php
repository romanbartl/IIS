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


    public function getAllMembers() {
        return $this->database->table(self::TABLE_MEMBER)->fetchAll();
    }


    public function deleteMemberFromInterpret($idInterpret, $idMember) {
        $this->database->table(self::TABLE_INTERPRET_MEMBER)
            ->where(self::INTERPRET_MEMBER_INTERPRET_ID, $idInterpret)
            ->where(self::INTERPRET_MEMBER_MEMBER_ID, $idMember)
            ->delete();

        $member = $this->database->table(self::TABLE_INTERPRET_MEMBER)
            ->where(self::INTERPRET_MEMBER_MEMBER_ID, $idMember)
            ->fetch();

        if($member == false){
            $this->database->table(self::TABLE_MEMBER)
                ->where(self::MEMBER_COLUMN_ID, $idMember)
                ->delete();
        }
    }


    public function getMember($idMember) {
        return $this->database->table(self::TABLE_MEMBER)
            ->where(self::MEMBER_COLUMN_ID, $idMember)
            ->fetch();
    }


    public function editMember($values) {
        $this->database->table(self::TABLE_MEMBER)
            ->where(self::MEMBER_COLUMN_ID, $values->idMember)
            ->update([
                self::MEMBER_COLUMN_NAME => $values->name,
                self::MEMBER_COLUMN_SURNAME => $values->surname,
                self::MEMBER_COLUMN_BIRTH => $values->birth
                ]);
    }



    public function addMember($values) {
        $row = $this->database->table(self::TABLE_MEMBER)
            ->insert([
                self::MEMBER_COLUMN_NAME => $values->name,
                self::MEMBER_COLUMN_SURNAME => $values->surname,
                self::MEMBER_COLUMN_BIRTH => $values->birth
            ]);
        return $row->idMember;
    }


    public function connectMemberAndInterpret($idInterpret, $idMember) {
        try {
            $this->database->table(self::TABLE_INTERPRET_MEMBER)
                ->insert([
                    self::INTERPRET_MEMBER_INTERPRET_ID => $idInterpret,
                    self::INTERPRET_MEMBER_MEMBER_ID => $idMember
                ]);
        }
        catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw $e;
        }
    }
}