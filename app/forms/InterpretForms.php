<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 19.11.2018
 * Time: 15:12
 */

namespace App\Forms;


use App\Model\InterpretsManager;
use Nette\Application\UI\Form;

class InterpretForms
{
    private $factory;

    private $interpretsManager;


    public function __construct(FormFactory $factory, InterpretsManager $interpretsManager)
    {
        $this->factory = $factory;
        $this->interpretsManager = $interpretsManager;
    }


    public function createAddNewInterpret(callable $onSuccess) {
        $form = $this->factory->create();

        $form->addText('name', 'Název interpreta:');

        $form->addText('label', 'Obrázek (URL):');

        $form->addText('founded', 'Datum založení:')
            ->setType('date');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $row = $this->interpretsManager->addInterpret($values);
            $onSuccess($row->idInterpret);
        };

        return $form;
    }


    public function createEditInterpretForm(callable $onSuccess, $idInterpret) {
        $form = $this->factory->create();

        $interpret = $this->interpretsManager->getInterpretById($idInterpret);

        $form->addHidden('idInterpret', $idInterpret);
        $form->addText('name', 'Název interpreta:')
            ->setDefaultValue($interpret->name);

        $form->addText('label', 'Obrázek (URL):')
            ->setDefaultValue($interpret->label);

        $form->addText('founded', 'Datum založení:')
            ->setType('date')
            ->setDefaultValue($interpret->founded->format('Y-m-d'));

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->interpretsManager->updateInterpret($values);
            $onSuccess();
        };

        return $form;
    }
}