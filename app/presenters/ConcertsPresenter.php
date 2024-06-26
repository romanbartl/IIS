<?php

namespace App\Presenters;

use App\Forms\ConcertForms;
use App\Forms\PlaceForms;
use App\Model\ConcertsManager;
use App\Model\PlaceManager;
use App\Model\TicketsManager;
use Nette\Application\UI\BadSignalException;
use Nette\Application\UI\Multiplier;
use Nette\Forms\Form;


class ConcertsPresenter extends BasePresenter
{
    /**
     * @var ConcertsManager
     */
    public $concertsManager;


    /**
     * @var Integer $concertId
     */
    private $concertId;

    private $placeForms;

    private $placeManager;

    private $concertForms;

    private $ticketManager;


    /**
     * ConcertsPresenter constructor.
     * @param ConcertsManager $concertsManager
     * @param PlaceForms $placeForms
     * @param PlaceManager $placeManager
     * @param ConcertForms $concertForms
     * @param TicketsManager $ticketsManager
     */
    public function __construct(ConcertsManager $concertsManager, PlaceForms $placeForms, PlaceManager $placeManager,
                                ConcertForms $concertForms, TicketsManager $ticketsManager)
    {
        parent::__construct();
        $this->concertsManager = $concertsManager;
        $this->placeForms = $placeForms;
        $this->placeManager = $placeManager;
        $this->concertForms = $concertForms;
        $this->ticketManager = $ticketsManager;
    }


    /**
     * When template detail is loaded with GET parameters (id of concert) it stores this GET value to global var
     * @param $id
     */
    public function actionDetail($id)
    {
        $this->concertId = $id;
    }


    /**
     * Adding new items to the cart
     * AJAX request
     * @param $concertId
     * @param $ticketType
     * @param $amount
     */
    public function handleAddToCart($concertId, $ticketType, $amount)
    {
        if($this->isAjax()) {
            $amount = $this->concertsManager->lockConcertTickets($concertId, $ticketType, $amount);

            if ($amount != 0) {
                if (isset($this->cart->list[$concertId]['C'][$ticketType])) {
                    $this->cart->list[$concertId]['C'][$ticketType] += $amount;
                } else {
                    $this->cart->list[$concertId]['C'][$ticketType] = $amount;
                }

                $this->cart->count += $amount;
            }

            $this->redrawControl('cart');
            $this->redrawControl('ticketsSnippet');
        }
    }


    /**
     * On template default creates new template variable 'concerts'
     */
    public function renderDefault()
    {
        $this->template->concerts = $this->concertsManager->getAllConcertsWithTickets();
    }


    /**
     * On template detail creates new template variable 'concertId'
     */
    public function renderDetail()
    {
        $this->template->concertId = $this->concertId;

        //TODO change getConcertById by some method from TicketsManager after it's working
        $concert = $this->concertsManager->getConcertById($this->concertId);

        if ($concert['info'] == null) $this->redirect('Notfound:default');

        $ticketsMaxAmounts = array();
        $firstType = "";
        $firstAmount = 0;

        foreach ($concert['tickets'] as $key => $ticket) {
            if (isset($this->cart->list[$this->concertId]['C'][$ticket->type])) {
                //is in cart
                $count = $ticket->count;
                $ticketsMaxAmounts[] = $count;

                if ($count != 0 && $firstType == "")
                {
                    $firstType = $ticket->type;
                    $firstAmount = $count;
                }

            } else {
                //not in cart
                $ticketsMaxAmounts[] = $ticket->count;

                if ($firstType == "") {
                    $firstType = $ticket->type;
                    $firstAmount = $ticket->count;
                }
            }
        }

        $this->template->concert = $this->concertsManager->getConcertById($this->concertId);
        $this->template->ticketsMaxAmounts = $ticketsMaxAmounts;
        $this->template->firstType = $firstType;
        $this->template->firstAmount = $firstAmount;
    }


    public function actionEdit($id) {
        $this->concertId = $id;
        if(!$this->user->isLoggedIn() || !$this->user->isAllowed('concert', 'edit')){
            throw new BadSignalException;
        }
    }


    public function renderEdit()
    {
        $this->template->concertId = $this->concertId;

        //TODO change getConcertById by some method from TicketsManager after it's working
        $concert = $this->concertsManager->getConcertById($this->concertId);

        if ($concert['info'] == null) $this->redirect('Notfound:default');

        $ticketsMaxAmounts = array();
        $firstType = "";
        $firstAmount = 0;

        foreach ($concert['tickets'] as $key => $ticket) {
            if (isset($this->cart->list[$this->concertId]['C'][$ticket->type])) {
                //is in cart
                $count = $ticket->count;
                $ticketsMaxAmounts[] = $count;

                if (($count != 0 && $key == 0 && $firstType != "") || ($count != 0 && $key != 0 && $firstType == "")
                    || ($count != 0 && $key == 0 && $firstType == "")) {
                    $firstType = $ticket->type;
                    $firstAmount = $count;
                }

            } else {
                //not in cart
                $ticketsMaxAmounts[] = $ticket->count;

                if ($firstType == "") {
                    $firstType = $ticket->type;
                    $firstAmount = $ticket->count;
                }
            }
        }

        $this->template->concert = $this->concertsManager->getConcertById($this->concertId);
        $this->template->ticketsMaxAmounts = $ticketsMaxAmounts;
        $this->template->firstType = $firstType;
        $this->template->firstAmount = $firstAmount;
        $this->template->ticketsByType = $this->ticketManager->getTicketsConcertByType($this->concertId);
    }


    protected function createComponentAddNewPlaceForm() {
        return $this->placeForms->createAddNewPlaceForm(function () {
            $this->flashMessage('Nové místo úspěšně přidáno.', 'success');
            $this->redirect('this');
        });
    }


    protected function createComponentAddExistingPlaceForm() {
        return $this->placeForms->createAddExistingPlaceConcertForm(function () {
            $this->flashMessage('Nové místo úspěšně přidáno.', 'success');
            $this->redirect('this');
        }, $this->concertId);
    }


    protected function createComponentEditPlaceForm() {
        $concert = $this->concertsManager->getConcertById($this->concertId);
        $idPlace = $concert['info']->idPlace;
        return $this->placeForms->createEditPlaceForm(function () {
            $this->flashMessage('Aktuální místo upraveno.', 'success');
            $this->redirect('this');
        }, $idPlace);
    }


    protected function createComponentBasicInfoForm() {
        return $this->concertForms->createBasicInfoForm(function () {
            $this->flashMessage('Informace o koncertu byly upraveny.', 'success');
            $this->redirect('this');
        }, $this->concertId);
    }

    protected function createComponentAddHeadlinerToConcertForm() {
        return $this->concertForms->createAddHeadlinerToConcertForm(function () {
            $this->flashMessage('Headliner byl přidán.', 'success');
            $this->redirect('this');
        }, $this->concertId);
    }


    public function handleDeleteInterpret($idConcert, $idInterpret) {
        if($this->isAjax()) {
            $this->concertsManager->deleteInterpretFromConcert($idConcert, $idInterpret);
            $this->redrawControl('concertInterprets');
        }
    }


    protected function createComponentAddInterpretToConcertForm() {
        return $this->concertForms->createAddInterpretToConcertForm(function () {
            $this->flashMessage('Interpret byl přidán.', 'success');
            $this->redirect('this');
        }, $this->concertId, function () {
            $this->flashMessage('Interpret je již na koncertu.', 'error');
            $this->redirect('this');
        });
    }


    protected function createComponentAddNewTicketsForm() {
        return $this->concertForms->createAddNewTicketsForm(function () {
            $this->flashMessage('Vstupenky byly přidány.', 'success');
            $this->redirect('this');
        }, $this->concertId, function () {
            $this->flashMessage('Nelze přidat lístky! Lístky tohoto typu jsou již v databázi, ale s jinou cenou.', 'error');
            $this->redirect('this');
        });
    }


    public function handleDeleteTickets($idConcert, $type, $cnt) {
        if($this->isAjax()) {
            $cntRemoved = $this->ticketManager->tryRemoveTickets($idConcert, $type);
            if($cnt != $cntRemoved) {
                $this->flashMessage("Některé vstupenky jsou v košíku nebo prodány! Smazáno $cntRemoved vstupenek místo $cnt!");
            }
            else {
                $this->flashMessage("Všech $cnt vstupenek bylo smazáno!", "success");
            }
            $this->redrawControl('flashMessages');
            $this->redrawControl('ticketsEdit');
        }
    }


    protected function createComponentChangeTicketsForm()
    {
        return new Multiplier(function ($idArray, $onSuccess) {
            $ticketsByType = $this->ticketManager->getTicketsConcertByType($this->concertId);

            $form = new \Nette\Application\UI\Form;

            $form->addHidden('idConcert', $this->concertId);

            $form->addHidden('price', $ticketsByType['all'][$idArray]->price);

            $form->addHidden('type', $ticketsByType['all'][$idArray]->type);

            $form->addHidden('currentAmount', $ticketsByType['all'][$idArray]->cnt);

            $form->addText('amount', "Počet vstupenek:")
                ->setRequired("Vyplňte prosím počet vstupenek!")
                ->setDefaultValue($ticketsByType['all'][$idArray]->cnt);
            $form->addSubmit('send', 'Uložit');

            $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
                $cntRemoved = $this->ticketManager->changeAmountOfTickets($values);
                $limit = abs($values->currentAmount - $values->amount);
                if(($values->amount - $values->currentAmount) > 0) {
                    $add = $values->amount - $values->currentAmount;
                    $this->flashMessage("Bylo přidáno $add vstupenek!");
                }
                else if(($values->amount - $values->currentAmount) < 0) {
                    if($limit > $cntRemoved) {
                        $this->flashMessage("Některé vstupenky jsou v košíku nebo prodány! Smazáno $cntRemoved vstupenek místo $limit!");
                    }
                    else {
                        $this->flashMessage("Všech $cntRemoved vstupenek bylo smazáno!", "success");
                    }
                }
                $this->redirect('this');
            };

            return $form;
        });
    }


    public function renderAdd() {
        if(!$this->user->isLoggedIn() || !$this->user->isAllowed('concert', 'add')) {
            throw new BadSignalException;
        }
    }


    protected function createComponentAddNewConcertForm() {
        return $this->concertForms->createNewConcertForm(function ($idConcerts) {
            $this->redirect("Concerts:edit", $idConcerts);
            $this->flashMessage("Koncert úspěšně vytvořen.", "success");
        });
    }
}
