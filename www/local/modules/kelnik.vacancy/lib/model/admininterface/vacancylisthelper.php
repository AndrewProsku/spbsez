<?php
namespace Kelnik\Vacancy\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Vacancy\Model\VacancyTable;

class VacancyListHelper extends AdminListHelper
{
    protected static $model = VacancyTable::class;
}
