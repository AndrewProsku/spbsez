<?php

namespace Kelnik\Multisites\Settings\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */
class SitesListHelper extends AdminListHelper
{
	protected static $model = 'Kelnik\Multisites\Settings\SitesTable';
}
