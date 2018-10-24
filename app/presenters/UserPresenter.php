<?php
/**
 * Created by PhpStorm.
 * User: Tomas_Odehnal
 * Date: 07.10.2018
 * Time: 18:35
 */

namespace App\Presenters;

use App\Forms;
use Nette\Application\UI\Form;
use Tracy\Debugger;
use App;

class UserPresenter extends BasePresenter
{
    /** @var App\Model\UserManager  */
    private $userManager;

    /** @var  */
    private $userSettingsFactory;


    public function __construct(App\Model\UserManager $userManager, Forms\UserSettingsFormFactory $userSettingsFactory)
    {
        $this->userManager = $userManager;
        $this->userSettingsFactory = $userSettingsFactory;
    }

    public function renderSettings() {

    }

    protected function createComponentUserSettingsForm($name)
    {
        return $this->userSettingsFactory->create(function () {
            $this->redirect('User:settings');
        });
    }

    public function renderList() {
        $this->template->users = $this->userManager->getUsers();
    }


    public function handleChangeUserRole($idUser, $isAdmin)
    {
        if($this->isAjax()) {
            $role = 0;
            if ($isAdmin == "0") {
                $role = 1;
            }
            $this->userManager->changeUserRole($idUser, $role);
            $this->redrawControl('userListSnippet');
        }
    }
}