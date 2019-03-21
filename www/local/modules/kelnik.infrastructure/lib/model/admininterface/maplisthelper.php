<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Infrastructure\Model\MapTable;

class MapListHelper extends AdminListHelper
{
	protected static $model = MapTable::class;
}
