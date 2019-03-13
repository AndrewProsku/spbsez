<?php
namespace Kelnik\Info\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\Info\Model\DocsTable;

class DocsListHelper extends AdminListHelper
{
    protected static $model = DocsTable::class;
}
