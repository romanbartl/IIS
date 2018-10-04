<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2.10.18
 * Time: 13:39
 */

namespace App\Presenters;


class InterpretsPresenter extends BasePresenter
{
    private $interpretId;

    public function renderDefault()
    {

    }

    public function renderDetail()
    {
        $this->template->interpretId = $this->interpretId;
    }

    public function actionDetail($id)
    {
        $this->interpretId = $id;
    }
}
