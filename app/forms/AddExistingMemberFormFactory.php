<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 11.11.2018
 * Time: 16:13
 */

namespace App\Forms;


use App\Model\MembersManager;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;

class AddExistingMemberFormFactory
{
    private $factory;

    private $membersManager;

    public function __construct(FormFactory $factory, MembersManager $membersManager)
    {
        $this->factory = $factory;
        $this->membersManager = $membersManager;
    }


    public function create(callable $onSuccess, $idInterpret, callable $onError) {
        $form = $this->factory->create();

        $members = $this->membersManager->getAllMembers();
        $memb = [];
        foreach($members as $member) {
            $memb[$member->idMember] = $member->name . ' ' . $member->surname;
        }

        $form->addHidden('idInterpret', $idInterpret);
        $form->addSelect('members', 'Výběr existujícího člena:', $memb);

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $onError) {
            try {
                $this->membersManager->connectMemberAndInterpret($values->idInterpret, $values->members);
            }
            catch (UniqueConstraintViolationException $e) {
                $onError();
            }
            $onSuccess();
        };

        return $form;
    }
}