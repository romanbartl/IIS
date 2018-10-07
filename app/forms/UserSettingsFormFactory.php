<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 07.10.2018
 * Time: 18:45
 */

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class UserSettingsFormFactory
{
    /** @var FormFactory  */
    private $factory;

    /** @var Model\UserManager  */
    private $userManager;

    /** @var User  */
    private $user;

    /**
     * UserSettingsFormFactory constructor.
     * @param FormFactory $factory
     * @param Model\UserManager $userManager
     * @param User $user
     */
    public function __construct(FormFactory $factory, Model\UserManager $userManager, User $user)
    {
        $this->factory = $factory;
        $this->userManager = $userManager;
        $this->user = $user;
    }

    /**
     * @param callable $onSuccess
     * @return mixed
     */
    public function create(callable $onSuccess) {
        $values = $this->userManager->updateUserSettings($this->user->getIdentity()->getId());
        foreach ($values as $attribute => $value) {
            $this->user->getIdentity()->$attribute = $value;
        }

        $form = $this->factory->create();

        $form->addText('name', 'Křestní jméno:')
            ->setDefaultValue($this->user->getIdentity()->name);

        $form->addText('surname', 'Příjmení:')
            ->setDefaultValue($this->user->getIdentity()->surname);

        $form->addText('birth', 'Datum narození:')
            ->setType('date')
            ->setDefaultValue($this->user->getIdentity()->birth->format('Y-m-d'));

        $form->addText('address', 'Adresa:')
            ->setDefaultValue($this->user->getIdentity()->address);

        $form->addEmail('email', 'Email:')
            ->setDefaultValue($this->user->getIdentity()->email); // TODO: Email doesn't change

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            $this->userManager->saveUserSettings($values);
            $onSuccess();
        };

        return $form;
    }
}