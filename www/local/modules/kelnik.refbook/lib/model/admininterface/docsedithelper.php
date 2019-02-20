<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Refbook\Model\DocsTable;

class DocsEditHelper extends AdminEditHelper
{
    protected static $model = DocsTable::class;
}
