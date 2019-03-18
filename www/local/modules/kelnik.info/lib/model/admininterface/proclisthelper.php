<?php
namespace Kelnik\Info\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Info\Model\ProcTable;

class ProcListHelper extends AdminListHelper
{
    protected static $model = ProcTable::class;
}
