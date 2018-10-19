<?php

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls;


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
		$form->addEmail('email')
            ->setHtmlAttribute('placeholder', 'Email')
			->setRequired('Vložte prosím svoji emailovou adresu.');

		$form->addPassword('password')
            ->setHtmlAttribute('placeholder', 'Zvolte své heslo')
			->setRequired('Musíte zvolit své heslo.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

        $form->addPassword('password_again')
            ->setHtmlAttribute('placeholder', 'Zvolte své heslo')
            ->setRequired('Musíte zvolit své heslo.')
            ->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            if ($values['password'] !== $values['password_again']) {
		        $form['password_again']->addError('Hesla se musí shodovat!');
		        return;
            }
			try {
				$this->userManager->add($values->email, $values->password);
			} catch (Model\DuplicateNameException $e) {
				$form['email']->addError('Zadaný email je již obsazen.');
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
