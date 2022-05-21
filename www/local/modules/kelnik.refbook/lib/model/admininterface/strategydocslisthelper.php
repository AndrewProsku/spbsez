<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Refbook\Model\StrategyDocsTable;

class StrategyDocsListHelper extends AdminListHelper
{
    protected static $model = StrategyDocsTable::class;
}
