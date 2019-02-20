<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Refbook\Model\PresTable;

class PresListHelper extends AdminListHelper
{
    protected static $model = PresTable::class;
}
