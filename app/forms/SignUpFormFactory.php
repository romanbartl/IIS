<?php

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;


class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 7;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;


	public function __construct(FormFactory $factory, Model\UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('email', 'Email:')
			->setRequired('Vložte prosím svoji emailovou adresu.');

		$form->addPassword('password', 'Zvolte své heslo:')
			->setOption('description', sprintf('alespoň %d znaků', self::PASSWORD_MIN_LENGTH))
			->setRequired('Musíte zvolit své heslo.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->userManager->add($values->email, $values->password);
			} catch (Model\DuplicateNameException $e) {
				$form['email']->addError('Zadaný email je již obsazen.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
