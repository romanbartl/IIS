<?php

namespace App\Presenters;

use App\Model\InterpretsManager;

class InterpretsPresenter extends BasePresenter
{
    /**
     * @var InterpretsManager
     */
    public $interpretsManager;

    /**
     * @var
     */
    private $interpretId;


    /**
     * InterpretsPresenter constructor.
     * @param InterpretsManager $interpretsManager
     */
    public function __construct(InterpretsManager $interpretsManager)
    {
        $this->interpretsManager = $interpretsManager;
    }

    public function renderDefault()
    {
        $this->template->interprets = $this->interpretsManager->getAllInterprets();
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
