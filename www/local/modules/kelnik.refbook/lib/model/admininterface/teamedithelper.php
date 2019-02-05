<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Refbook\Model\TeamTable;

class TeamEditHelper extends AdminEditHelper
{
    protected static $model = TeamTable::class;
}
