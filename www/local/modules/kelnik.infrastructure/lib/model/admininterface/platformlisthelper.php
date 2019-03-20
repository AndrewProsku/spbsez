<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Infrastructure\Model\PlatformTable;

class PlatformListHelper extends AdminListHelper
{
	protected static $model = PlatformTable::class;
}
