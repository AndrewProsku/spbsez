<?php
namespace Kelnik\Report\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Report\Model\StatusTable;

class StatusListHelper extends AdminListHelper
{
    protected static $model = StatusTable::class;
}
