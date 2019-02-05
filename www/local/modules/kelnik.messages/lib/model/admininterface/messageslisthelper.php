<?php
namespace Kelnik\Messages\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Messages\Model\MessagesTable;

class MessagesListHelper extends AdminListHelper
{
    protected static $model = MessagesTable::class;

    public function hasWriteRightsElement($element = [])
    {
        if (empty($element['ID']) || empty($element['ACTIVE']) || !$this->hasRights()) {
            return false;
        }

        return $element['ACTIVE'] !== MessagesTable::YES;
    }
}
