<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class StandartEditHelper extends AdminEditHelper
{
    protected static $model = '\Kelnik\Requests\Model\StandartTable';
}
