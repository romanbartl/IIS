<?php

namespace App\Presenters;

use App\Forms;
use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter
{
	/** @persistent */
	public $backlink = '';

	/** @var Forms\SignInFormFactory */
	private $signInFactory;

	/** @var Forms\SignUpFormFactory */
	private $signUpFactory;


	public function __construct(Forms\SignInFormFactory $signInFactory, Forms\SignUpFormFactory $signUpFactory)
	{
		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
	}


	public function actionIn() {
	    if ($this->user->isLoggedIn()) {
	        $this->redirect('News:');
        }
    }


    public function actionUp() {
	    if ($this->user->isLoggedIn()) {
	        $this->redirect('News:');
        }
    }


	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	protected function createComponentSignInForm()
	{
		return $this->signInFactory->create(function () {
			$this->restoreRequest($this->backlink);
			$this->redirect('News:');
		});
	}


	/**
	 * Sign-up form factory.
	 * @return Form
	 */
	protected function createComponentSignUpForm()
	{
		return $this->signUpFactory->create(function () {
			$this->redirect('News:');
		});
	}


	public function actionOut()
	{
		$this->getUser()->logout();
        $this->redirect('News:');
	}
}
