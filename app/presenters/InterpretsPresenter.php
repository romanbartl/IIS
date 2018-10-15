<?php

namespace App\Presenters;

use App\Model\AlbumsManager;
use App\Model\InterpretsManager;
use App\Model\MembersManager;


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
     * @var MembersManager
     */
    public $membersManager;


    /**
     * @var
     */
    private $interpretId;


    /**
     * InterpretsPresenter constructor.
     * @param InterpretsManager $interpretsManager
     * @param AlbumsManager $albumsManager
     * @param MembersManager $membersManager
     */
    public function __construct(InterpretsManager $interpretsManager, AlbumsManager $albumsManager, MembersManager $membersManager)
    {
        $this->interpretsManager = $interpretsManager;
        $this->albumsManager = $albumsManager;
        $this->membersManager = $membersManager;
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
        $this->template->members = $this->membersManager->getMembersByInterpretId($this->interpretId);
    }
}
