<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Infrastructure\Model\MapTable;

class MapEditHelper extends AdminEditHelper
{
    protected static $model = MapTable::class;
}
