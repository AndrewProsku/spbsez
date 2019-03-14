<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Requests\Model\AreaTable;

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */
class AreaListHelper extends AdminListHelper
{
	protected static $model = AreaTable::class;
}
