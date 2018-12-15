<?php

namespace Kelnik\Multisites\Settings\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования.
 *
 * {@inheritdoc}
 */
class SitesEditHelper extends AdminEditHelper
{
    protected static $model = 'Kelnik\Multisites\Settings\SitesTable';
}
