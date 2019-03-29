<?php
namespace Kelnik\Report\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Report\Model\ReportsTable;

class ReportsEditHelper extends AdminEditHelper
{
    protected static $model = ReportsTable::class;
}
