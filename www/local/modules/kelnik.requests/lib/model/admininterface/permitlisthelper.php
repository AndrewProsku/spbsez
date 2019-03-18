<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Requests\Model\PermitTable;

class PermitListHelper extends AdminListHelper
{
	protected static $model = PermitTable::class;
}
