<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 20.11.2018
 * Time: 10:33
 */

namespace App\Forms;


use App\Model\FestivalsManager;
use App\Model\PlaceManager;
use App\Model\TicketsManager;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;

class FestivalForms
{
    /**
     * @var Factory
     */
    private $factory;


    /**
     * @var FestivalsManager
     */
    private $festivalsManager;


    /**
     * @var TicketsManager
     */
    private $ticketsManager;


    /**
     * @var PlaceManager
     */
    private $placeManager;


    /**
     * FestivalForms constructor.
     * @param FormFactory $factory
     * @param FestivalsManager $festivalsManager
     * @param TicketsManager $ticketsManager
     * @param PlaceManager $placeManager
     */
    public function __construct(FormFactory $factory, FestivalsManager $festivalsManager,
                                TicketsManager $ticketsManager, PlaceManager $placeManager)
    {
        $this->factory = $factory;
        $this->festivalsManager = $festivalsManager;
        $this->ticketsManager = $ticketsManager;
        $this->placeManager = $placeManager;
    }


    public function createAddNewFestival(callable $onSuccess) {
        $form = $this->factory->create();

        $form->addText('name', 'Název festivalu:')
            ->setRequired('Vyplňte prosím název festivalu.');

        $form->addText('label', 'Obrázek (URL):')
            ->setRequired('Vyplňte prosím URL obrázku.');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->festivalsManager->addNewFestival($values->name, $values->label);
            $onSuccess();
        };

        return $form;
    }


    public function createAddNewTicketsForm(callable $onSuccess, $idYear, callable $onError) {
        $form = $this->factory->create();

        $type = ['VIP' => 'VIP', 'SIT' => 'Na sezení', 'STAND' => 'Na stání'];

        $form->addHidden('idYear', $idYear);
        $form->addText('amount', 'Počet vstupenek:')
            ->setType('number')
            ->setRequired('Vyplňte prosím počet vstupenek!');

        $form->addSelect('ticketType', 'Typ vstupenky:', $type)
            ->setRequired('Vyplňte prosím typ vstupenky!');

        $form->addText('price', 'Cena vstupenek (v Kč):')
            ->setType('number')
            ->setRequired('Vyplňte prosím cenu vstupenek!');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $onError) {
            try {
                $this->ticketsManager->addTicketsToFestival($values);
            }
            catch (UniqueConstraintViolationException $e) {
                $onError();
            }
            $onSuccess();
        };

        return $form;
    }


    public function createAddNewYear(callable $onSuccess) {
        $festivals = $this->festivalsManager->getFestivalsIds();
        $result = array();

        foreach ($festivals as $festival) {
            $result[$festival->idFestival] = $festival->name;
        }

        $places = $this->placeManager->getAllPlaces();
        $allPlaces = array();


        foreach ($places as $place) {
            $allPlaces[$place->idPlace] = $place->name;
        }

        $form = $this->factory->create();

        $form->addText('volume', 'Ročník festivalu:')
            ->setRequired('Vyplňte prosím ročník festivalu.');

        $form->addText('season', 'Období festivalu:')
            ->setRequired('Vyplňte prosím období festivalu.');

        $form->addText('startDate', 'Začátek festivalu (datum):')
            ->setType('date')
            ->setRequired('Vyplňte prosím začátek festivalu.');

        $form->addText('startTime', 'Začátek festivalu (čas):')
            ->setType('time')
            ->setRequired('Vyplňte prosím konec festivalu.');

        $form->addText('endDate', 'Konec festivalu (datum):')
            ->setType('date')
            ->setRequired('Vyplňte prosím začátek festivalu.');

        $form->addText('endTime', 'Konec festivalu (čas):')
            ->setType('time')
            ->setRequired('Vyplňte prosím začátek festivalu.');

        $form->addSelect('festivalId', 'Festival:', $result)->setRequired();

        $form->addSelect('placeId', 'Místo:', $allPlaces)->setRequired();

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $id = $this->festivalsManager->addNewYear($values->volume, $values->season, $values->startDate, $values->startTime,
                $values->endDate, $values->endTime, $values->festivalId, $values->placeId);
            $onSuccess($id);
        };

        return $form;
    }
}
