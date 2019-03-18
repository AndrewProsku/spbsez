<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Requests\Model\PermitTable;

class PermitEditHelper extends AdminEditHelper
{
    protected static $model = PermitTable::class;

    protected function hasWriteRightsElement($element = array())
    {
        return !empty($element['ID']);
    }
}
