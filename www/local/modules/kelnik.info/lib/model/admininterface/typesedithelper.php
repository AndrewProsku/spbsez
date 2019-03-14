<?php
namespace Kelnik\Info\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Info\Model\TypesTable;

class TypesEditHelper extends AdminEditHelper
{
    protected static $model = TypesTable::class;
}
