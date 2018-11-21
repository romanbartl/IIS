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
use App\Model\PlaceManager;
use App\Model\TicketsManager;
use Nette\Application\UI\Form;

class ConcertForms
{
    private $factory;

    private $concertsManager;

    private $interpretsManager;

    private $ticketsManager;

    private $placeManager;


    public function __construct(FormFactory $factory, ConcertsManager $concertsManager, InterpretsManager $interpretsManager,
                                TicketsManager $ticketsManager, PlaceManager $placeManager)
    {
        $this->factory = $factory;
        $this->concertsManager = $concertsManager;
        $this->interpretsManager = $interpretsManager;
        $this->ticketsManager = $ticketsManager;
        $this->placeManager = $placeManager;
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


    public function createAddNewTicketsForm(callable $onSuccess, $idConcert) {
        $form = $this->factory->create();

        $type = ['VIP' => 'VIP', 'SIT' => 'Na sezení', 'STAND' => 'Na stání'];

        $form->addHidden('idConcert', $idConcert);
        $form->addText('amount', 'Počet vstupenek:')
            ->setType('number')
            ->setRequired('Vyplňte prosím počet vstupenek!');

        $form->addSelect('ticketType', 'Typ vstupenky:', $type)
            ->setRequired('Vyplňte prosím typ vstupenky!');

        $form->addText('price', 'Cena vstupenek (v Kč):')
            ->setType('number')
            ->setRequired('Vyplňte prosím cenu vstupenek!');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->ticketsManager->addTicketsToConcert($values);
            $onSuccess();
        };

        return $form;
    }


    public function createNewConcertForm(callable $onSuccess) {
        $form = $this->factory->create();

        $places = $this->placeManager->getAllPlaces();
        $plac = [];
        foreach($places as $place) {
            $plac[$place->idPlace] = $place->name;
        }

        $interprets = $this->interpretsManager->getAllInterprets();
        $inter = [];
        foreach($interprets as $interpret) {
            $inter[$interpret->idInterpret] = $interpret->name;
        }

        $form->addText('name', 'Název akce:')
            ->setRequired('Vyplňte prosím název koncertu');

        $form->addText('date', 'Datum koncertu:')
            ->setType('date')
            ->setRequired("Vyplňte prosím datum koncertu!");

        $form->addText('time', 'Začátek koncertu:')
            ->setType('time')
            ->setRequired("Vyplňte prosím čas začátku koncertu!");

        $form->addSelect('place', 'Výběr místa konání:', $plac);

        $form->addSelect('idHeadliner', 'Výběr headlinera:', $inter);

        $form->addTextArea('info', 'Detail akce:')
            ->setRequired('Vyplňte prosím info!');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $row = $this->concertsManager->addNewConcert($values);
            $onSuccess($row->idConcert);
        };

        return $form;
    }
}