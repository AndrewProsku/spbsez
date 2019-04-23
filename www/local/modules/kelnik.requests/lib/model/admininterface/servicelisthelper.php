<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Requests\Model\ServiceTable;

class ServiceListHelper extends AdminListHelper
{
    protected static $model = ServiceTable::class;
}
