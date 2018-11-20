<?php

namespace App\Presenters;


use App\Forms\FestivalForms;
use App\Model\FestivalsManager;
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
     * @var
     */
    private $festival;


    /**
     * FestivalsPresenter constructor.
     * @param FestivalsManager $festivalsManager
     * @param FestivalForms $festivalForms
     */
    public function __construct(FestivalsManager $festivalsManager, FestivalForms $festivalForms)
    {
        parent::__construct();
        $this->festivalManager = $festivalsManager;
        $this->festivalForms = $festivalForms;
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
            $this->flashMessage('Ročník byl úspěšně přidán.');
            $this->redirect('Festivals:editYear', $idYear);
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
        $form->addTextArea('info', '')
            ->setDefaultValue($this->festival['info']->info);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Form $form, $values) {
            $this->festivalManager->editFestivalInfo($this->festivalId, $values->info);
            $this->redirect('this');
        };

        return $form;
    }

    protected function createComponentAddStageToYearForm()
    {

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
                $this->cart = $this->getSession('cart');

                if (isset($this->cart->list[$festivalId]['F'])) {
                    if (isset($this->cart->list[$festivalId]['F'][$ticketType])) {
                        $this->cart->list[$festivalId]['F'][$ticketType] += $amount;
                        $this->cart->count += $amount;
                    } else {
                        $this->cart->list[$festivalId]['F'][$ticketType] = $amount;
                        $this->cart->count += $amount;
                    }

                } else {
                    $this->cart->list[$festivalId]['F'][$ticketType] = $amount;
                    $this->cart->count += $amount;
                }
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
