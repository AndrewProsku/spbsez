<?php
namespace Kelnik\Vacancy\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Vacancy\Model\VacancyResidentsTable;

class VacancyResidentsEditHelper extends AdminEditHelper
{
    protected static $model = VacancyResidentsTable::class;
}
