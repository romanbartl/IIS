<?php
/**
 * Created by PhpStorm.
 * User: Roxem Wincle
 * Date: 20.11.2018
 * Time: 10:33
 */

namespace App\Forms;


use App\Model\FestivalsManager;
use Nette\Application\UI\Form;

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
     * FestivalForms constructor.
     * @param FormFactory $factory
     * @param FestivalsManager $festivalsManager
     */
    public function __construct(FormFactory $factory, FestivalsManager $festivalsManager)
    {
        $this->factory = $factory;
        $this->festivalsManager = $festivalsManager;
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


    public function createAddNewYear(callable $onSuccess) {
        $festivals = $this->festivalsManager->getFestivalsIds();
        $result = array();

        foreach ($festivals as $festival) {
            $result[$festival->idFestival] = $festival->name;
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

        $form->addSelect('festivalId', 'Festival:', $result);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $id = $this->festivalsManager->addNewYear($values->volume, $values->season, $values->startDate, $values->startTime,
                $values->endDate, $values->endTime, $values->festivalId);
            $onSuccess($id);
        };

        return $form;
    }
}
