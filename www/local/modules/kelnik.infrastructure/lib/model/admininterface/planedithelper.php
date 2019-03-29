<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Infrastructure\Model\PlanTable;

class PlanEditHelper extends AdminEditHelper
{
    protected static $model = PlanTable::class;
}
