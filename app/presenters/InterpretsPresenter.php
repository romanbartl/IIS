<?php

namespace App\Presenters;

use App\Forms\IMemberFormFactory;
use App\Forms\MemberForm;
use App\Forms\MemberFormFactory;
use App\Model\AlbumsManager;
use App\Model\ConcertsManager;
use App\Model\DuplicateNameException;
use App\Model\InterpretsManager;
use App\Model\MembersManager;
use App\Model\UserManager;
use Nette\Application\UI\Multiplier;
use Nette\Forms\Form;


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


    /** @var UserManager  */
    private $userManager;


    /**
     * @var
     */
    private $interpretId;


    /** @var IMemberFormFactory */
    private $memberForm;


    /**
     * InterpretsPresenter constructor.
     * @param InterpretsManager $interpretsManager
     * @param AlbumsManager $albumsManager
     * @param MembersManager $membersManager
     * @param ConcertsManager $concertsManager
     * @param UserManager $userManager
     */
    public function __construct(InterpretsManager $interpretsManager, AlbumsManager $albumsManager,
                                MembersManager $membersManager, ConcertsManager $concertsManager,
                                UserManager $userManager)
    {
        $this->interpretsManager = $interpretsManager;
        $this->albumsManager = $albumsManager;
        $this->membersManager = $membersManager;
        $this->concertsManager = $concertsManager;
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

    protected function createComponentMemberForm()
    {
        return new Multiplier(function ($idMember, $onSuccess) {
            $member = $this->membersManager->getMember($idMember);
            $form = new \Nette\Application\UI\Form;
            $form->addHidden('idMember', $idMember);
            $form->addText('name', 'Jméno:')
                ->setDefaultValue($member->name);
            $form->addText('surname', 'Příjmení:')
                ->setDefaultValue($member->surname);
            $form->addText('birth', 'Datum narození:')
                ->setType('date')
                ->setDefaultValue($member->birth->format('Y-m-d'));
            $form->addSubmit('send', 'Uložit');

            $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
                $this->membersManager->editMember($values);
                $this->redirect('this');
            };

            return $form;
        });
    }

    public function renderDetail()
    {
        $this->template->interpret = $this->interpretsManager->getInterpretById($this->interpretId);
        $this->template->isFavourite = $this->userManager->checkFavouriteInterpret($this->user->id, $this->interpretId);
        $this->template->albums = $this->albumsManager->getAlbumsByInterpretId($this->interpretId);
        $this->template->members = $this->membersManager->getMembersByInterpretId($this->interpretId);
        $this->template->expiredConcerts = $this->concertsManager->getExpiredConcertsByInterpretId($this->interpretId);
        $this->template->upcomingConcerts = $this->concertsManager->getUpcomingConcertsByInterpretId($this->interpretId);
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


    public function handleDeleteMember($idInterpret, $idMember) {
        if($this->isAjax()) {
            $this->membersManager->deleteMemberFromInterpret($idInterpret, $idMember);
            $this->redrawControl('editMembers');
        }
    }
}
