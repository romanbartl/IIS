<?php

namespace App\Presenters;

use App\Model\AlbumsManager;
use App\Model\ConcertsManager;
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
     * @var ConcertsManager
     */
    public $concertsManager;


    /**
     * @var
     */
    private $interpretId;


    /**
     * InterpretsPresenter constructor.
     * @param InterpretsManager $interpretsManager
     * @param AlbumsManager $albumsManager
     * @param MembersManager $membersManager
     * @param ConcertsManager $concertsManager
     */
    public function __construct(InterpretsManager $interpretsManager, AlbumsManager $albumsManager,
                                MembersManager $membersManager, ConcertsManager $concertsManager)
    {
        $this->interpretsManager = $interpretsManager;
        $this->albumsManager = $albumsManager;
        $this->membersManager = $membersManager;
        $this->concertsManager = $concertsManager;
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
        $this->template->expiredConcerts = $this->concertsManager->getExpiredConcertsByInterpretId($this->interpretId);
        $this->template->upcomingConcerts = $this->concertsManager->getUpcomingConcertsByInterpretId($this->interpretId);
    }
}
