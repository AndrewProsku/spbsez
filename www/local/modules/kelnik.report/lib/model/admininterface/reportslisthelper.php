<?php
namespace Kelnik\Report\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Report\Model\ReportsTable;

class ReportsListHelper extends AdminListHelper
{
    protected static $model = ReportsTable::class;
}
