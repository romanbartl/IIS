<?php

namespace App\Presenters;

use App\Model\AlbumsManager;
use App\Model\ConcertsManager;
use App\Model\FestivalsManager;
use App\Model\InterpretsManager;
use App\Model\MembersManager;
use App\Model\UserManager;


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
     * @var FestivalsManager
     */
    public $festivalsManager;


    /** @var UserManager  */
    private $userManager;


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
     * @param FestivalsManager $festivalsManager
     * @param UserManager $userManager
     */
    public function __construct(InterpretsManager $interpretsManager, AlbumsManager $albumsManager,
                                MembersManager $membersManager, ConcertsManager $concertsManager,
                                FestivalsManager $festivalsManager, UserManager $userManager)
    {
        $this->interpretsManager = $interpretsManager;
        $this->albumsManager = $albumsManager;
        $this->membersManager = $membersManager;
        $this->concertsManager = $concertsManager;
        $this->festivalsManager = $festivalsManager;
        $this->userManager = $userManager;
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
        $upcomingActions = 0;

        $this->template->interpret = $this->interpretsManager->getInterpretById($this->interpretId);
        $this->template->isFavourite = $this->userManager->checkFavouriteInterpret($this->user->id, $this->interpretId);
        $this->template->albums = $this->albumsManager->getAlbumsByInterpretId($this->interpretId);
        $this->template->members = $this->membersManager->getMembersByInterpretId($this->interpretId);
        $this->template->expiredConcerts = $this->concertsManager->getExpiredConcertsByInterpretId($this->interpretId);
        $this->template->expiredFestivals = $this->festivalsManager->getExpiredFestivalsByInterpretId($this->interpretId);

        $upcomingConcerts = $this->concertsManager->getUpcomingConcertsByInterpretId($this->interpretId);
        $upcomingFestivals = $this->festivalsManager->getUpcomingFestivalsByInterpretId($this->interpretId);

        foreach ($upcomingConcerts as $concert) {$upcomingActions++;}
        foreach ($upcomingFestivals as $festival) {$upcomingActions++;}

        $this->template->upcomingConcerts = $this->concertsManager->getUpcomingConcertsByInterpretId($this->interpretId);
        $this->template->upcomingFestivals = $this->festivalsManager->getUpcomingFestivalsByInterpretId($this->interpretId);
        $this->template->upcomingActions = $upcomingActions;
    }


    public function handleChangeFavourite($idUser, $idInterpret, $isFavourite) {
        if($this->isAjax()) {
            if($isFavourite == 1) {
                $this->userManager->saveFavouriteInterpret($idUser, $idInterpret);
            }
            else {
                $this->userManager->removeFavouriteInterpret($idUser, $idInterpret);
            }
            $this->redrawControl('changeFavourite');
        }
    }
}
