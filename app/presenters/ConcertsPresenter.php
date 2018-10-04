<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2.10.18
 * Time: 13:39
 */

namespace App\Presenters;


class ConcertsPresenter extends BasePresenter
{
    private $concertId;

    public function renderDefault()
    {

    }

    public function renderDetail()
    {
        $this->template->concertId = $this->concertId;
    }

    public function actionDetail($id)
    {
        $this->concertId = $id;
    }
}
