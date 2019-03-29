<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Requests\Model\ServiceTable;

class ServiceEditHelper extends AdminEditHelper
{
    protected static $model = ServiceTable::class;
}
