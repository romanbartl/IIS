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
}
