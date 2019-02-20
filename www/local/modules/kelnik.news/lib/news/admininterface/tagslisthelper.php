<?php

namespace Kelnik\News\News\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminListHelper;
use Kelnik\News\News\TagsTable;

class TagsListHelper extends AdminListHelper
{
    protected static $model = TagsTable::class;
}
