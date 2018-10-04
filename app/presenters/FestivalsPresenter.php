<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2.10.18
 * Time: 13:39
 */

namespace App\Presenters;


class FestivalsPresenter extends BasePresenter
{
    private $festivalId;

    public function renderDefault()
    {

    }

    public function renderDetail()
    {
        $this->template->festivalId = $this->festivalId;
    }

    public function actionDetail($id)
    {
        $this->festivalId = $id;
    }
}
