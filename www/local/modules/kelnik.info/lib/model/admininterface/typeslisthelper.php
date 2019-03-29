<?php
namespace Kelnik\Info\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Info\Model\TypesTable;

class TypesListHelper extends AdminListHelper
{
    protected static $model = TypesTable::class;
}
