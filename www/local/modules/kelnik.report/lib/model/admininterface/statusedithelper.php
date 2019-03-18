<?php
namespace Kelnik\Report\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Report\Model\StatusTable;

class StatusEditHelper extends AdminEditHelper
{
    protected static $model = StatusTable::class;
}
