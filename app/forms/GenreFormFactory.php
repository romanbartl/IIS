<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 11.11.2018
 * Time: 21:23
 */

namespace App\Forms;


use App\Model\AlbumsManager;
use App\Model\DuplicateNameException;
use App\Model\GenreManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class GenreFormFactory extends Control
{
    private $factory;

    private $albumsManager;

    private $genreManager;


    public function __construct(FormFactory $factory, AlbumsManager $albumsManager, GenreManager $genreManager)
    {
        $this->factory = $factory;
        $this->albumsManager = $albumsManager;
        $this->genreManager = $genreManager;
    }


    public function createConnectGenreAlbumForm(callable $onSuccess, $idInterpret, callable $onError) {
        $form = $this->factory->create();

        $albums = $this->albumsManager->getAlbumsByInterpretId($idInterpret);
        $alb = [];
        foreach($albums as $album) {
            $alb[$album->idAlbum] = $album->name;
        }

        $genres = $this->genreManager->getAllGenres();
        $gen = [];
        foreach($genres as $genre) {
            $gen[$genre->idGenre] = $genre->name;
        }

        $form->addSelect('albums', 'Výběr alba:', $alb);

        $form->addSelect('genres', 'Výber žánru:', $gen);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $onError) {
            try {
                $this->genreManager->connectGenreWithAlbum($values->albums, $values->genres);
            }
            catch (DuplicateNameException $e) {
                $onError();
            }
            $onSuccess();
        };

        return $form;
    }


    public function createAddNewGenreForm(callable $onSuccess) {
        $form = $this->factory->create();

        $form->addText('genreName', 'Název žánru:');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->genreManager->addGenre($values->genreName);
            $onSuccess();
        };

        return $form;
    }
}