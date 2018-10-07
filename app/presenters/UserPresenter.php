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

class UserPresenter extends BasePresenter
{
    /** @var  */
    private $userSettingsFactory;

    public function __construct(Forms\UserSettingsFormFactory $userSettingsFactory)
    {
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
}