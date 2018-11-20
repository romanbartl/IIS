<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 20.11.2018
 * Time: 11:00
 */

namespace App\Forms;


use App\Model\ConcertsManager;
use App\Model\DuplicateNameException;
use App\Model\InterpretsManager;
use Nette\Application\UI\Form;

class ConcertForms
{
    private $factory;

    private $concertsManager;

    private $interpretsManager;


    public function __construct(FormFactory $factory, ConcertsManager $concertsManager, InterpretsManager $interpretsManager)
    {
        $this->factory = $factory;
        $this->concertsManager = $concertsManager;
        $this->interpretsManager = $interpretsManager;
    }


    public function createBasicInfoForm(callable $onSuccess, $idConcert) {
        $form = $this->factory->create();

        $concert = $this->concertsManager->getConcert($idConcert);

        $form->addHidden('idConcert', $idConcert);
        $form->addText('name', 'Název akce:')
            ->setDefaultValue($concert->name)
            ->setRequired('Vyplňte prosím název koncertu');

        $form->addText('date', 'Datum koncertu:')
            ->setType('date')
            ->setDefaultValue($concert->date->format('Y-m-d'))
            ->setRequired("Vyplňte prosím datum koncertu!");

        $form->addText('time', 'Začátek koncertu:')
            ->setType('time')
            ->setDefaultValue($concert->date->format('H:i:s'))
            ->setRequired("Vyplňte prosím čas začátku koncertu!");

        $form->addText('capacity', 'Kapacita:')
            ->setDefaultValue($concert->capacity)
            ->setRequired('Vyplňte prosím kapacitu!');

        $form->addTextArea('info', 'Detail akce:')
            ->setDefaultValue($concert->info)
            ->setRequired('Vyplňte prosím info!');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->concertsManager->updateConcert($values);
            $onSuccess();
        };

        return $form;
    }


    public function createAddHeadlinerToConcertForm(callable $onSuccess, $idConcert) {
        $form = $this->factory->create();

        $interprets = $this->interpretsManager->getAllInterprets();
        $inter = [];
        foreach($interprets as $interpret) {
            $inter[$interpret->idInterpret] = $interpret->name;
        }

        $form->addHidden('idConcert', $idConcert);
        $form->addSelect('interprets', 'Výběr headlinera:', $inter);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->concertsManager->addHeadliner($values);
            $onSuccess();
        };

        return $form;
    }


    public function createAddInterpretToConcertForm(callable $onSuccess, $idConcert, callable $onError) {
        $form = $this->factory->create();

        $interprets = $this->interpretsManager->getAllInterprets();
        $inter = [];
        foreach($interprets as $interpret) {
            $inter[$interpret->idInterpret] = $interpret->name;
        }

        $form->addHidden('idConcert', $idConcert);
        $form->addSelect('interprets', 'Výběr interpreta:', $inter);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $onError) {
            try {
                $this->concertsManager->addInterpret($values);
            }
            catch (DuplicateNameException $e) {
                $onError();
            }
            $onSuccess();
        };

        return $form;
    }
}