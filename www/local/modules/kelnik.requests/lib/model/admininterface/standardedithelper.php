<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;

class StandardEditHelper extends AdminEditHelper
{
    protected static $model = '\Kelnik\Requests\Model\StandardTable';

    protected function hasWriteRightsElement($element = array())
    {
        return !empty($element['ID']);
    }
}
