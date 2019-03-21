<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Infrastructure\Model\PlatformTable;

class PlatformEditHelper extends AdminEditHelper
{
    protected static $model = PlatformTable::class;
}
