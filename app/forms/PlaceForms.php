<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 20.11.2018
 * Time: 11:11
 */

namespace App\Forms;


use App\Model\ConcertsManager;
use App\Model\FestivalsManager;
use App\Model\PlaceManager;
use Nette\Forms\Form;

class PlaceForms
{
    private $factory;

    private $placeManager;

    private $concertsManager;

    private $festivalsManager;


    public function __construct(FormFactory $factory, PlaceManager $placeManager, ConcertsManager $concertsManager,
                                FestivalsManager $festivalsManager)
    {
        $this->factory = $factory;
        $this->placeManager = $placeManager;
        $this->concertsManager = $concertsManager;
        $this->festivalsManager = $festivalsManager;
    }


    public function createAddNewPlaceForm(callable $onSuccess) {
        $form = $this->factory->create();

        $form->addText('name', 'Název:')
            ->setRequired("Vyplňte prosím název!");

        $form->addText('address', 'Adresa:')
            ->setRequired("Vyplňte prosím adresu!");

        $form->addText('gpsLat', 'GPS (šířka):');

        $form->addText('gpsLng', 'GPS (výška):');

        $form->addText('zipCode', 'PSČ:');

        $form->addText('city', 'Město:');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->placeManager->addNewPlace($values);
            $onSuccess();
        };

        return $form;
    }


    public function createAddExistingPlaceConcertForm(callable $onSuccess, $idConcert) {
        $form = $this->factory->create();

        $places = $this->placeManager->getAllPlaces();
        $plac = [];
        foreach($places as $place) {
            $plac[$place->idPlace] = $place->name;
        }

        $form->addHidden('idConcert', $idConcert);
        $form->addSelect('places', 'Výběr místa konání:', $plac);

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->concertsManager->connectConcertAndPlace($values->idConcert, $values->places);
            $onSuccess();
        };

        return $form;
    }


    public function createAddExistingPlaceFestivalForm(callable $onSuccess, $idYear) {
        $form = $this->factory->create();

        $places = $this->placeManager->getAllPlaces();
        $plac = [];
        foreach($places as $place) {
            $plac[$place->idPlace] = $place->name;
        }

        $form->addHidden('idYear', $idYear);
        $form->addSelect('places', 'Výběr místa konání:', $plac)
            ->setDefaultValue($this->festivalsManager->getIdPlaceFromYear($idYear));

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->festivalsManager->connectFestivalAndPlace($values->idYear, $values->places);
            $onSuccess();
        };

        return $form;
    }


    public function createEditPlaceForm(callable $onSuccess, $idPlace) {
        $form = $this->factory->create();

        $placeValues = $this->placeManager->getPlaceById($idPlace);

        $form->addHidden('idPlace', $idPlace);

        $form->addText('name', 'Název:')
            ->setRequired("Vyplňte prosím název!")
            ->setDefaultValue($placeValues->name);

        $form->addText('address', 'Adresa:')
            ->setRequired("Vyplňte prosím adresu!")
            ->setDefaultValue($placeValues->address);

        $form->addText('gpsLat', 'GPS (šířka):')
            ->setDefaultValue($placeValues->gpsLat);

        $form->addText('gpsLng', 'GPS (výška):')
            ->setDefaultValue($placeValues->gpsLng);

        $form->addText('zipCode', 'PSČ:')
            ->setDefaultValue($placeValues->zipCode);

        $form->addText('city', 'Město:')
            ->setDefaultValue($placeValues->city);

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->placeManager->updatePlace($values);
            $onSuccess();
        };

        return $form;
    }


    public function createEditPlaceFestivalForm(callable $onSuccess, $idPlace) {
        $form = $this->factory->create();

        $placeValues = $this->placeManager->getPlaceById($idPlace);

        $form->addHidden('idPlace', $idPlace);

        $form->addText('name', 'Název:')
            ->setRequired("Vyplňte prosím název!")
            ->setDefaultValue($placeValues->name);

        $form->addText('address', 'Adresa:')
            ->setRequired("Vyplňte prosím adresu!")
            ->setDefaultValue($placeValues->address);

        $form->addText('gpsLat', 'GPS (šířka):')
            ->setDefaultValue($placeValues->gpsLat);

        $form->addText('gpsLng', 'GPS (výška):')
            ->setDefaultValue($placeValues->gpsLng);

        $form->addText('zipCode', 'PSČ:')
            ->setDefaultValue($placeValues->zipCode);

        $form->addText('city', 'Město:')
            ->setDefaultValue($placeValues->city);

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->placeManager->updatePlace($values);
            $onSuccess();
        };

        return $form;
    }
}