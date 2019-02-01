<?php
namespace Kelnik\Vacancy\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Vacancy\Model\VacancyTable;

class VacancyEditHelper extends AdminEditHelper
{
    protected static $model = VacancyTable::class;
}
