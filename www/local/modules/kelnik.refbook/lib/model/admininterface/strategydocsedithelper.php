<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Refbook\Model\StrategyDocsTable;

class StrategyDocsEditHelper extends AdminEditHelper
{
    protected static $model = StrategyDocsTable::class;
}
