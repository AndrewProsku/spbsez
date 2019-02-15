<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Requests\Model\SiteMsgTable;

class SiteMsgEditHelper extends AdminEditHelper
{
    protected static $model = SiteMsgTable::class;
}
