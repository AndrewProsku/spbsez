<?php
namespace Kelnik\Vacancy\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Vacancy\Model\VacancyResidentsTable;

class VacancyResidentsListHelper extends AdminListHelper
{
    protected static $model = VacancyResidentsTable::class;
}
