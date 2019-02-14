<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Requests\Model\SiteMsgTable;

class SiteMsgListHelper extends AdminListHelper
{
	protected static $model = SiteMsgTable::class;
}
