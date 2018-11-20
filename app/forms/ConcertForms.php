<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 20.11.2018
 * Time: 11:00
 */

namespace App\Forms;


use App\Model\ConcertsManager;

class ConcertForms
{
    private $factory;

    private $concertsManager;

    public function __construct(FormFactory $factory, ConcertsManager $concertsManager)
    {
        $this->factory = $factory;
        $this->concertsManager = $concertsManager;
    }


    public function createBasicInfoForm(callable $onSuccess, $idConcert) {
        $form = $this->factory->create();

        $form->addHidden('idConcert', $idConcert);
        $form->addText('name', 'Název:');

        $form->addText('label', 'Obrázek:')
            ->setRequired("Vyplňte prosím URL obrázku!");

        $form->addText('release', 'Datum vydání:')
            ->setType('date');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->albumsManager->addAlbum($values);
            $onSuccess();
        };

        return $form;
    }
}