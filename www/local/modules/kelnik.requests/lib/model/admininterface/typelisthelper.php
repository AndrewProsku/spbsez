<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */
class TypeListHelper extends AdminListHelper
{
	protected static $model = '\Kelnik\Requests\Model\TypeTable';
}
