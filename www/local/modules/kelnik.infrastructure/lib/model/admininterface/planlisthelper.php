<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Infrastructure\Model\PlanTable;

class PlanListHelper extends AdminListHelper
{
	protected static $model = PlanTable::class;
}
