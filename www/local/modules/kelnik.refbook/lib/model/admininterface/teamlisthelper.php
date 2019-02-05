<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Refbook\Model\TeamTable;

class TeamListHelper extends AdminListHelper
{
    protected static $model = TeamTable::class;
}
