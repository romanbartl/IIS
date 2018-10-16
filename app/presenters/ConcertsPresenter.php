<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2.10.18
 * Time: 13:39
 */

namespace App\Presenters;


use App\Model\ConcertsManager;

class ConcertsPresenter extends BasePresenter
{
    /**
     * @var ConcertsManager
     */
    public $concertsManager;


    /**
     * @var concert id
     */
    private $concertId;


    /**
     * ConcertsPresenter constructor.
     * @param ConcertsManager $concertsManager
     */
    public function __construct(ConcertsManager $concertsManager)
    {
        $this->concertsManager = $concertsManager;
    }


    public function renderDefault()
    {
        $this->template->concerts = $this->concertsManager->getAllConcertsWithTickets();
    }


    public function renderDetail()
    {
        $this->template->concertId = $this->concertId;
    }


    public function actionDetail($id)
    {
        $this->concertId = $id;
        $this->template->concert = $this->concertsManager->getConcertById($id);
    }
}
