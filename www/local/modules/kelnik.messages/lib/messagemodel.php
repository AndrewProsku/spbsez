<?php

namespace Kelnik\Messages;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\ORM\Query\Query;
use Kelnik\Messages\Model\MessagesTable;
use Kelnik\Messages\Model\MessageUsersTable;
use Kelnik\Requests\Model\NotifyTable;
use Kelnik\Userdata\Profile\ProfileModel;

class MessageModel
{
    private $profile;
    private $cntTotal = false;
    private $cntNew = false;
    private $years = [];

    private static $instance;

    private function __construct(ProfileModel $profile)
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

    public function getYears()
    {
        $res = array_keys($this->years);

        natsort($res);

        return array_reverse($res);
    }

    public function getCountTotal()
    {
        return (int) $this->cntTotal;
    }

    public function getCountNew()
    {
        return (int) $this->cntNew;
    }

    /**
     * Подсчет общего кол-ва сообщения и новых сообщений по годам
     *
     * @return $this
     */
    public function calcCount()
    {
        if (!$this->checkPermissions()) {
            return $this;
        }

        if (false !== $this->cntTotal) {
            return $this;
        }

        try {
            $sql = "SELECT `MSG_YEAR`, SUM(`CNT`) CNT, SUM(`CNT_NEW`) CNT_NEW FROM ( " .
                    "({$this->getMessagesCountSql()}) " .
                    " UNION " .
                    "({$this->getNotifiesCountSql()}) " .
                    ") as msg " .
                    "GROUP BY `MSG_YEAR`";

            $res = Application::getConnection()->query($sql);

            $this->years = [];

            while ($row = $res->fetch()) {
                foreach (['TOTAL', 'NEW'] as $type) {
                    if (isset($this->years[$row['MSG_YEAR']][$type])) {
                        continue;
                    }

                    $this->years[$row['MSG_YEAR']][$type] = 0;
                }
                $this->years[$row['MSG_YEAR']]['TOTAL'] += $row['CNT'];
                $this->years[$row['MSG_YEAR']]['NEW'] += $row['CNT_NEW'];
            }

            $this->cntTotal = array_sum(array_column($this->years, 'TOTAL'));
            $this->cntNew = array_sum(array_column($this->years, 'NEW'));
        } catch (\Exception $e) {
            $this->years = [];
            $this->cntTotal = $this->cntNew = 0;
        }

        return $this;
    }

    public function reCalcCount()
    {
        $this->cntTotal = $this->cntNew = false;

        return $this->calcCount();
    }

    public function getList($year = false, $searchText = false)
    {
        if (!$this->checkPermissions() || !$this->cntTotal) {
            return [];
        }
    }

    private function getMessagesCountSql()
    {
        return $this->getEntityQuery(MessageUsersTable::class)->getQuery();

    }

    private function getNotifiesCountSql()
    {
        return $this->getEntityQuery(NotifyTable::class)->getQuery();
    }

    private function getEntityQuery($nameSpace, array $params = []): Query
    {
        $isCount = true;
        $select = self::getSelectByEntity($nameSpace);

        $filter = [
            '=USER_ID' => $this->profile->getId()
        ];

        foreach (['select', 'filter'] as $type) {
            if (!empty($params[$type])) {
                $isCount = false;
                ${$type} = $params[$type];
            }
        }

        $query = new Query($nameSpace::getEntity());
        $query->setSelect($select)->setFilter($filter);

        if ($isCount) {
            $query->setGroup(['MSG_YEAR']);
        }

        return $query;
    }

    private static function getSelectByEntity($nameSpace): array
    {
        $res = [
            new ExpressionField(
                'CNT',
                'COUNT(DISTINCT %s)',
                'ID'
            ),
            new ExpressionField(
                'CNT_NEW',
                'SUM(IF(%s = \'' . $nameSpace::YES . '\', 1, 0))',
                'IS_NEW'
            )
        ];

        if ($nameSpace === MessageUsersTable::class) {
            $res[] = new ExpressionField(
                'MSG_YEAR',
                '(SELECT YEAR(`DATE_CREATED`) FROM `' . MessagesTable::getTableName() . '` WHERE `ID` = %s)',
                'MESSAGE_ID'
            );

            return $res;
        }

        $res[] = new ExpressionField(
            'MSG_YEAR',
            'YEAR(%s)',
            'DATE_CREATED'
        );

        return $res;
    }

    private function checkPermissions()
    {
        return $this->profile->canMessages();
    }
}
