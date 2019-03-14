<?php
namespace Kelnik\Info\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Info\Model\DocsTable;

class DocsEditHelper extends AdminEditHelper
{
    protected static $model = DocsTable::class;
}
