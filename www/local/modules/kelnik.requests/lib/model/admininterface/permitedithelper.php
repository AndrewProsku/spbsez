<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Requests\Model\PermitTable;

class PermitEditHelper extends AdminEditHelper
{
    protected static $model = PermitTable::class;

    public function show()
    {
        if (empty($this->data['ID'])) {
            $this->addErrors(Loc::getMessage('KELNIK_ADMIN_HELPER_ACCESS_FORBIDDEN'));
            $this->showMessages();

            return false;
        }

        return parent::show();
    }

    protected function editAction()
    {
        if (empty($this->data['ID'])) {
            $this->setContext(AdminEditHelper::OP_EDIT_ACTION_BEFORE);
            $this->addErrors(Loc::getMessage('KELNIK_ADMIN_HELPER_EDIT_WRITE_FORBIDDEN'));

            return false;
        }

        return parent::editAction();
    }
}
