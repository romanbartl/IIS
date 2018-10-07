<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Forms\Controls;


class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addEmail('email')
            ->setHtmlAttribute('placeholder', 'Email')
            ->setHtmlAttribute('autofocus')
			->setRequired('Vložte prosím svůj email.');

		$form->addPassword('password')
            ->setHtmlAttribute('placeholder', 'Heslo')
			->setRequired('Vložte prosím své heslo.');

		$form->addCheckbox('remember', 'Zůstat přihlášen');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->email, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('Špatný email nebo heslo.');
				return;
			}
			$onSuccess();
		};

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;

        $form->getElementPrototype()->class('dd');
        foreach ($form->getControls() as $control) {
            if ($control instanceof Controls\Button) {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-lg btn-primary btn-block' : 'btn btn-default');
                $usedPrimary = TRUE;
            } elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
                $control->getSeparatorPrototype()->setName('div')->addClass('checkbox mb-3');
            }
        }

		return $form;
	}
}
