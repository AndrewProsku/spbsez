<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Requests\Model\AreaTable;

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class AreaEditHelper extends AdminEditHelper
{
    protected static $model = AreaTable::class;
}
