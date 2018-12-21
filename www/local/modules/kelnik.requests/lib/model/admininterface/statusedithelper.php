<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class StatusEditHelper extends AdminEditHelper
{
    protected static $model = '\Kelnik\Requests\Model\StatusTable';
}
