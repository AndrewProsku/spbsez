<?php
namespace Kelnik\Refbook\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Refbook\Model\DocsTable;

class DocsListHelper extends AdminListHelper
{
    protected static $model = DocsTable::class;
}
