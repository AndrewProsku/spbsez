<?php
namespace Kelnik\Messages\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Messages\Model\MessagesTable;

class MessagesEditHelper extends AdminEditHelper
{
    protected static $model = MessagesTable::class;

    public function hasWriteRights()
    {
        $origData = $this->loadElement();
        if (!empty($origData['ACTIVE']) && $origData['ACTIVE'] === MessagesTable::YES) {
            return false;
        }

        return parent::hasWriteRights();
    }

    public function hasWriteRightsElement($element = [])
    {
        if (empty($element['ID']) || empty($element['ACTIVE']) || !$this->hasRights()) {
            return false;
        }

        return $element['ACTIVE'] !== MessagesTable::YES;
    }
}
