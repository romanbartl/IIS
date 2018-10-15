<?php

namespace App\Presenters;

use App\Model\AlbumsManager;
use App\Model\InterpretsManager;

class InterpretsPresenter extends BasePresenter
{
    /**
     * @var InterpretsManager
     */
    public $interpretsManager;

    /**
     * @var AlbumsManager
     */
    public $albumsManager;

    /**
     * @var
     */
    private $interpretId;


    /**
     * InterpretsPresenter constructor.
     * @param InterpretsManager $interpretsManager
     * @param AlbumsManager $albumsManager
     */
    public function __construct(InterpretsManager $interpretsManager, AlbumsManager $albumsManager)
    {
        $this->interpretsManager = $interpretsManager;
        $this->albumsManager = $albumsManager;

    }

    public function renderDefault()
    {
        $this->template->interprets = $this->interpretsManager->getAllInterprets();
    }

    public function actionDetail($id)
    {
        $this->interpretId = $id;
    }

    public function renderDetail()
    {
        $this->template->interpret = $this->interpretsManager->getInterpretById($this->interpretId);
        $this->template->albums = $this->albumsManager->getAlbumsByInterpretId($this->interpretId);
    }
}
