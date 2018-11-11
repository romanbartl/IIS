<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 11.11.2018
 * Time: 14:19
 */

namespace App\Forms;

use App\Model\DuplicateNameException;
use App\Model\MembersManager;
use Nette;
use Nette\Application\UI\Form;

class AddNewMemberFormFactory
{
    private $factory;

    private $membersManager;


    public function __construct(FormFactory $factory, MembersManager $membersManager)
    {
        $this->factory = $factory;
        $this->membersManager = $membersManager;
    }


    public function create(callable $onSuccess, $idInterpret) {
        $form = $this->factory->create();

        $form->addHidden('idInterpret', $idInterpret);
        $form->addText('name', 'Jméno:');

        $form->addText('surname', 'Příjmení:');

        $form->addText('birth', 'Datum narození:')
            ->setType('date');

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $idMember = $this->membersManager->addMember($values);
                $this->membersManager->connectMemberAndInterpret($values->idInterpret, $idMember);
            }
            catch (DuplicateNameException $e) {
                $form->addError('špatně.');
            }
            $onSuccess();
        };

        return $form;
    }
}