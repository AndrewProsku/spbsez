<?php
namespace Kelnik\Vacancy\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Vacancy\Model\ResponseTable;

class ResponseListHelper extends AdminListHelper
{
    protected static $model = ResponseTable::class;
}
