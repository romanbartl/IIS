<?php

namespace App\Presenters;


use App\Forms\FestivalForms;
use App\Forms\PlaceForms;
use App\Model\FestivalsManager;
use App\Model\InterpretsManager;
use App\Model\TicketsManager;
use App\Model\UserManager;
use Nette\Application\UI\Multiplier;
use Nette\Forms\Form;

class FestivalsPresenter extends BasePresenter
{
    /**
     * @var FestivalsManager
     */
    private $festivalManager;

    /**
     * @var Integer $festivalId
     */
    private $festivalId;


    /**
     * @var FestivalForms
     */
    private $festivalForms;


    /**
     * @var InterpretsManager
     */
    private $interpretsManager;


    /**
     * @var TicketsManager
     */
    private $ticketsManager;


    /**
     * @var PlaceForms
     */
    private $placeForms;


    /**
     * @var
     */
    private $festival;

    private $userManager;


    /**
     * FestivalsPresenter constructor.
     * @param FestivalsManager $festivalsManager
     * @param FestivalForms $festivalForms
     * @param InterpretsManager $interpretsManager
     * @param TicketsManager $ticketsManager
     * @param PlaceForms $placeForms
     * @param UserManager $userManager
     */
    public function __construct(FestivalsManager $festivalsManager, FestivalForms $festivalForms,
                                InterpretsManager $interpretsManager, TicketsManager $ticketsManager,
                                PlaceForms $placeForms, UserManager $userManager)
    {
        parent::__construct();
        $this->festivalManager = $festivalsManager;
        $this->festivalForms = $festivalForms;
        $this->interpretsManager = $interpretsManager;
        $this->ticketsManager = $ticketsManager;
        $this->placeForms = $placeForms;
        $this->userManager = $userManager;
    }


    public function renderDefault()
    {
        $this->template->festivals = $this->festivalManager->getAllFestivalsWithTickets();
    }


    public function renderEditFestivals()
    {
        $this->template->festivals = $this->festivalManager->getFestivalsIds();
    }


    protected function createComponentAddNewFestival()
    {
        return $this->festivalForms->createAddNewFestival(function () {
            $this->flashMessage('Festival byl úspěšně přidán.');
            $this->redirect('Festivals:editFestivals');
        });
    }


    protected function createComponentAddNewYear()
    {
        return $this->festivalForms->createAddNewYear(function ($idYear) {
            if ($idYear == null) {
                $this->flashMessage('Stejný ročník již existuje!', 'error');
                $this->redirect('this');

            } else {
                $this->flashMessage('Ročník byl úspěšně přidán.', 'success');
                $this->redirect('Festivals:editYear', $idYear);
            }
        });
    }


    protected function createComponentEditFestivalForm()
    {
        return new Multiplier(function ($idFestival, $onSuccess) {
            $festival = $this->festivalManager->getFestival($idFestival);

            $form = new \Nette\Application\UI\Form;
            $form->addHidden('idFestival', $idFestival);
            $form->addText('name', "Název:")
                ->setDefaultValue($festival->name);
            $form->addText('label', "Obrázek:")
                ->setRequired("Vyplňte prosím URL obrázku!")
                ->setDefaultValue($festival->label);

            $form->addSubmit('send', 'Uložit');

            $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
                $this->festivalManager->editFestival($values->idFestival, $values->name, $values->label);
                $this->redirect('this');
            };

            return $form;
        });
    }


    protected function createComponentEditFestivalInfoForm()
    {
        $form = new \Nette\Application\UI\Form;

        $allFestivals = array();
        $festivals = $this->festivalManager->getAllFestivals();

        foreach ($festivals as $festival) {
            $allFestivals[$festival->idFestival] = $festival->name;
        }

        $form->addSelect('festivalId', 'Festival:', $allFestivals)
             ->setDefaultValue($this->festival['info']->idFest)
            ->setRequired('Vyplňte prosím festival.');

        $form->addText('volume', "Ročník:")
            ->setDefaultValue($this->festival['info']->volume)
            ->setRequired('Vyplňte prosím ročník.');

        $form->addText('season', "Období:")
            ->setDefaultValue($this->festival['info']->season)
            ->setRequired('Vyplňte prosím období.');

        $form->addText('startDate', 'Začátek festivalu (datum):')
            ->setType('date')
            ->setDefaultValue($this->festival['info']->start->format('Y-m-d'))
            ->setRequired('Vyplňte prosím začátek festivalu.');

        $form->addText('startTime', 'Začátek festivalu (čas):')
            ->setType('time')
            ->setDefaultValue($this->festival['info']->start->format('H:i'))
            ->setRequired('Vyplňte prosím konec festivalu.');

        $form->addText('endDate', 'Konec festivalu (datum):')
            ->setType('date')
            ->setDefaultValue($this->festival['info']->end->format('Y-m-d'))
            ->setRequired('Vyplňte prosím začátek festivalu.');

        $form->addText('endTime', 'Konec festivalu (čas):')
            ->setType('time')
            ->setDefaultValue($this->festival['info']->end->format('H:i'))
            ->setRequired('Vyplňte prosím začátek festivalu.');

        $form->addTextArea('info', 'Detail akce: ')
            ->setDefaultValue($this->festival['info']->info);

        $form->addSubmit('send', 'Opravdu upravit');

        $form->onSuccess[] = function (Form $form, $values) {
            if ($this->festivalManager->editFestivalInfo($this->festivalId, $values->festivalId, $values->volume,
                $values->season, $values->info, $values->startDate, $values->startTime, $values->endDate, $values->endTime)) {
                $this->flashMessage('Ročník byl úspěšně upraven.', 'success');

            } else {
                $this->flashMessage('Ujistěte se, zda-li Vámi zadaný ročník již neexistuje.', 'error');
            }

            $this->redirect('this');
        };

        return $form;
    }


    protected function createComponentAddNewStage()
    {
        $form = new \Nette\Application\UI\Form;

        $form->addText('name', 'Název stage:')
            ->setRequired('Vyplňte prosím název stage.')
            ->setRequired();;

        $form->addSubmit('send', 'Uložit');


        $form->onSuccess[] = function (Form $form, $values) {
            $this->festivalManager->addStage($values->name);
            $this->flashMessage('Stage byla přidána', 'success');
            $this->redirect('this');
        };

        return $form;
    }


    protected function createComponentChangeTicketsForm()
    {
        return new Multiplier(function ($idArray, $onSuccess) {
            $ticketsByType = $this->ticketsManager->getTicketsFestivalByType($this->festivalId);

            $form = new \Nette\Application\UI\Form;

            $form->addHidden('idYear', $this->festivalId);

            $form->addHidden('price', $ticketsByType['all'][$idArray]->price);

            $form->addHidden('type', $ticketsByType['all'][$idArray]->type);

            $form->addHidden('currentAmount', $ticketsByType['all'][$idArray]->cnt);

            $form->addText('amount', "Počet vstupenek:")
                ->setRequired("Vyplňte prosím počet vstupenek!")
                ->setDefaultValue($ticketsByType['all'][$idArray]->cnt);
            $form->addSubmit('send', 'Uložit');

            $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
                $cntRemoved = $this->ticketsManager->changeAmountOfTicketsFestival($values);
                $limit = abs($values->currentAmount - $values->amount);
                if(($values->amount - $values->currentAmount) > 0) {
                    $add = $values->amount - $values->currentAmount;
                    $this->flashMessage("Bylo přidáno $add vstupenek!", 'success');
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


    protected function createComponentAddStageToYearForm()
    {
        $allStages = array();
        $allInterprets = array();
        $stages = $this->festivalManager->getStages();
        $interprets = $this->interpretsManager->getAllInterprets();

        foreach ($stages as $stage) {
            $allStages[$stage->idStage] = $stage->name;
        }

        foreach ($interprets as $interpret) {
            $allInterprets[$interpret->idInterpret] = $interpret->name;
        }

        $form = new \Nette\Application\UI\Form;
        $form->addSelect('stageId', 'Stage:', $allStages)->setRequired();
        $form->addSelect('interpretId', 'Interpret:', $allInterprets)->setRequired();

        $form->addText('date', 'Datum:')
            ->setType('date')
            ->setDefaultValue($this->festival['info']->start->format('Y-m-d'))
            ->setAttribute('min', $this->festival['info']->start->format('Y-m-d'))
            ->setAttribute('max', $this->festival['info']->end->format('Y-m-d'))
            ->setRequired('Vyplňte prosím datum vystoupení.');

        $form->addText('startTime', 'Začátek vystoupení (čas):')
            ->setType('time')
            ->setDefaultValue($this->festival['info']->start->format('H:i'))
            ->setRequired('Vyplňte prosím začátek vystoupení.');

        $form->addText('endTime', 'Konec vystoupení (čas):')
            ->setType('time')
            ->setDefaultValue($this->festival['info']->end->format('H:i'))
            ->setRequired('Vyplňte prosím konec vystoupení.');

        $form->addCheckbox('headliner', "Headliner");


        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) {
            $startDateTime = date('Y-m-d H:i:s', strtotime("$values->date $values->startTime"));
            $endDateTime = date('Y-m-d H:i:s', strtotime("$values->date $values->endTime"));

            if ($startDateTime < $this->festival['info']->start) {
                $this->flashMessage('Vystoupení nesmí začít před začátkem festivalu!', 'error');

            } elseif ($endDateTime > $this->festival['info']->end) {
                $this->flashMessage('Vystoupení nesmí začít po konci festivalu!', 'error');

            } else if ($startDateTime > $endDateTime) {
                $this->flashMessage('Vystoupení nesmí skončit dříve, než začne!', 'error');

            } else {
                $this->festivalManager->addStageToYear($values->stageId, $values->interpretId, $this->festivalId, $startDateTime, $endDateTime, $values->headliner);
                $this->userManager->setIsNew($values->interpretId);
            }

            $this->redirect('this');
        };

        return $form;
    }


    protected function createComponentEditStagesNames()
    {
        return new Multiplier(function ($idStage, $onSuccess) {
            $stage = $this->festivalManager->getStageById($idStage);

            $form = new \Nette\Application\UI\Form;

            $form->addText('name', "Název:")
                ->setDefaultValue($stage->name)
                ->setRequired('Vyplňte prosím název stage.');;

            $form->addHidden('idStage', $idStage);

            $form->addSubmit('send', 'Uložit');

            $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
                $this->festivalManager->editStage($values->idStage, $values->name);
                $this->redirect('this');
            };

            return $form;
        });
    }


    protected function createComponentAddNewTicketsForm() {
        return $this->festivalForms->createAddNewTicketsForm(function () {
            $this->flashMessage('Vstupenky byly přidány.', 'success');
            $this->redirect('this');
        }, $this->festivalId, function () {
            $this->flashMessage('Nelze přidat lístky! Lístky tohoto typu jsou již v databázi, ale s jinou cenou.', 'error');
            $this->redirect('this');
        });
    }


    public function handleDeleteTickets($idYear, $type, $cnt) {
        if ($this->isAjax()) {
            $cntRemoved = $this->ticketsManager->tryRemoveTicketsFestival($idYear, $type);
            if ($cnt != $cntRemoved) {
                $this->flashMessage("Některé vstupenky jsou v košíku nebo prodány! Smazáno $cntRemoved vstupenek místo $cnt!");
            } else {
                $this->flashMessage("Všech $cnt vstupenek bylo smazáno!", "success");
            }

            $this->redirect('this');
        }
    }


    protected function createComponentAddNewPlaceForm() {
        return $this->placeForms->createAddNewPlaceForm(function () {
            $this->flashMessage('Nové místo úspěšně přidáno.', 'success');
            $this->redirect('this');
        });
    }


    protected function createComponentAddExistingPlaceForm() {
        return $this->placeForms->createAddExistingPlaceFestivalForm(function () {
            $this->flashMessage('Nové místo úspěšně přidáno.', 'success');
            $this->redirect('this');
        }, $this->festivalId);
    }


    protected function createComponentEditPlaceForm() {
        $idPlace = $this->festival['info']->idPlace;
        return $this->placeForms->createEditPlaceFestivalForm(function () {
            $this->flashMessage('Aktuální místo upraveno.', 'success');
            $this->redirect('this');
        }, $idPlace);
    }


    public function handleDeleteStage($stageId)
    {
        if ($this->festivalManager->deleteStage($stageId)) {
            $this->flashMessage('Stage byla smazána', 'success');

        } else {
            $this->flashMessage('Stage nelze smazat, dokud bude přiřazena alespoň u jednoho ročníku!', 'error');
        }
    }


    /**
     * @param $festivalId
     * @param $ticketType
     * @param $amount
     */
    public function handleAddToCart($festivalId, $ticketType, $amount)
    {
        if($this->isAjax()) {
            $amount = $this->festivalManager->lockFestivalTickets($festivalId, $ticketType, $amount);

            if ($amount != 0) {
                if (isset($this->cart->list[$festivalId]['F'][$ticketType])) {
                    $this->cart->list[$festivalId]['F'][$ticketType] += $amount;
                } else {
                    $this->cart->list[$festivalId]['F'][$ticketType] = $amount;
                }

                $this->cart->count += $amount;
            }

            $this->redrawControl('cart');
            $this->redrawControl('ticketsSnippet');
        }
    }


    public function actionEditYear($id)
    {
        $this->festivalId = $id;
        $this->festival = $this->festivalManager->getFestivalById($id);
    }


    public function renderEditYear()
    {
        $this->template->festival = $this->festivalManager->getFestivalById($this->festivalId);
        $this->template->festivalId = $this->festivalId;
        $this->template->allStages = $this->festivalManager->getAllStages();
        $this->template->ticketsByType = $this->ticketsManager->getTicketsFestivalByType($this->festivalId);
    }


    public function handleDeleteInterpretFromStage($interpretId, $stageId)
    {
        $this->festivalManager->deleteInterpretFromStage($interpretId, $stageId, $this->festivalId);
    }


    public function handleDeleteStageInYear($stageId)
    {
        $this->festivalManager->deleteStageInYear($stageId, $this->festivalId);
    }


    public function renderDetail()
    {
        $this->template->festivalId = $this->festivalId;

        $festival = $this->festivalManager->getFestivalById($this->festivalId);

        if ($festival['info'] == null) $this->redirect('Notfound:default');

        $ticketsMaxAmounts = array();
        $firstType = "";
        $firstAmount = 0;

        foreach ($festival['tickets'] as $key => $ticket) {
            if (isset($this->cart->list[$this->festivalId]['F'][$ticket->type])) {
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

        $this->template->festival = $this->festivalManager->getFestivalById($this->festivalId);
        $this->template->ticketsMaxAmounts = $ticketsMaxAmounts;
        $this->template->firstType = $firstType;
        $this->template->firstAmount = $firstAmount;
    }


    public function actionDetail($id)
    {
        $this->festivalId = $id;
    }
}
