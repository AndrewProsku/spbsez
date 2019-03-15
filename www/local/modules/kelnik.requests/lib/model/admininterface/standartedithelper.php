<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;

class StandartEditHelper extends AdminEditHelper
{
    protected static $model = '\Kelnik\Requests\Model\StandartTable';

    protected function hasWriteRightsElement($element = array())
    {
        return !empty($element['ID']);
    }
}
