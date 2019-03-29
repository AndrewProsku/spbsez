<?php

namespace Kelnik\News\News\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\News\News\TagsTable;

class TagsEditHelper extends AdminEditHelper
{
    protected static $model = TagsTable::class;
}
