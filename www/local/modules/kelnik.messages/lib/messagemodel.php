<?php

namespace Kelnik\Messages;

use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\ORM\Query\Query;
use Kelnik\Messages\Model\MessageUsersTable;
use Kelnik\Requests\Model\NotifyTable;
use Kelnik\Userdata\Profile\ProfileModel;

class MessageModel
{
    private $profile;

    private static $instance;

    protected function __construct(ProfileModel $profile)
    {
        $this->profile = $profile;
    }

    public static function getInstance(ProfileModel $profile)
    {
        if (!empty(self::$instance[$profile->getId()])) {
            return self::$instance[$profile->getId()];
        }

        return self::$instance[$profile->getId()] = new self($profile);
    }

    public function getCount()
    {
        if (!$this->checkPermissions()) {
            return 0;
        }

//        $sql = "SELECT SUM(CNT) FROM ( " .
//                "{$this->getMessagesCountQuery()} " .
//                " UNION " .
//                "{$this->getNotifiesCountQuery()}";
        return 1;
    }

    protected function getMessagesCountQuery()
    {
        return $this->getQuery(MessageUsersTable::class)->getQuery();

    }

    protected function getNotifiesCountQuery()
    {
        return $this->getQuery(NotifyTable::class)->getQuery();
    }

    protected function getQuery($nameSpace): Query
    {
        return (new Query($nameSpace::getEntity()))
                ->setSelect([new ExpressionField(
                    'CNT',
                    'COUNT(DISTINCT %s)',
                    'ID'
                )])
                ->setFilter([
                    '=USER_ID' => $this->profile->getId(),
                    '=IS_NEW' => $nameSpace::YES
                ]);
    }

    protected function checkPermissions()
    {
        return $this->profile->canMessages();
    }
}
