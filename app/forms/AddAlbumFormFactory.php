<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 11.11.2018
 * Time: 19:12
 */

namespace App\Forms;


use App\Model\AlbumsManager;
use Nette\Application\UI\Form;

class AddAlbumFormFactory
{
    private $factory;

    private $albumsManager;


    public function __construct(FormFactory $factory, AlbumsManager $albumsManager)
    {
        $this->factory = $factory;
        $this->albumsManager = $albumsManager;
    }


    public function create(callable $onSuccess, $idInterpret) {
        $form = $this->factory->create();

        $form->addHidden('idInterpret', $idInterpret);
        $form->addText('name', 'Název:');

        $form->addText('label', 'Obrázek:');

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