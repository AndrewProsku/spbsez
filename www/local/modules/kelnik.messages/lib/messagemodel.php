<?php

namespace Kelnik\Messages;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\Model\MessagesTable;
use Kelnik\Messages\Model\MessageUsersTable;
use Kelnik\Requests\Model\NotifyTable;
use Kelnik\Userdata\Profile\ProfileModel;

class MessageModel
{
    public const MONTHS_COUNT = 2;
    public const BASE_URL = '/cabinet/messages/';

    /**
     * @var ProfileModel
     */
    private $profile;

    /**
     * @var bool|int
     */
    private $cntTotal = false;

    /**
     * @var bool|int
     */
    private $cntNew = false;

    /**
     * @var array - Массив кол-ва сообщений по годам, также список месяцев в году, в которых есть сообщения
     *
     * $this->years = [
     *      [
     *          'TOTAL' => 10,
     *          'NEW' => 3,
     *          'MONTHS' => [
     *              2 => 2,
     *              5 => 5,
     *              6 => 6
     *          ]
     *      ],
     *      [...]
     * ];
     */
    private $years = [];

    private static $instance;
    private static $settings = [];

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

    public function __set($name, $value)
    {
        self::$settings[$name] = $value;
    }

    public function __get($name)
    {
        return ArrayHelper::getValue(self::$settings, $name);
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
     * Подсчет общего кол-ва сообщений и новых сообщений по годам
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
            $sql =  "SELECT `MSG_YEAR`, `MSG_MONTH`, SUM(`CNT`) CNT, SUM(`CNT_NEW`) CNT_NEW " .
                    "FROM ( (" .
                    $this->getEntityQuery(MessageUsersTable::class)->getQuery() .
                    ") UNION (" .
                    $this->getEntityQuery(NotifyTable::class)->getQuery() .
                    ") ) as msg " .
                    "GROUP BY `MSG_YEAR`, `MSG_MONTH`";

            $res = Application::getConnection()->query($sql);

            $this->years = [];

            while ($row = $res->fetch()) {
                foreach (['TOTAL', 'NEW'] as $type) {
                    if (isset($this->years[$row['MSG_YEAR']][$type])) {
                        continue;
                    }

                    $this->years[$row['MSG_YEAR']][$type] = 0;
                    $this->years[$row['MSG_YEAR']]['MONTHS'] = [];
                }
                $this->years[$row['MSG_YEAR']]['TOTAL'] += $row['CNT'];
                $this->years[$row['MSG_YEAR']]['NEW'] += $row['CNT_NEW'];
                $this->years[$row['MSG_YEAR']]['MONTHS'][$row['MSG_MONTH']] = $row['MSG_MONTH'];
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
        $this->years = [];

        return $this->calcCount();
    }

    public function getList(int $year, $searchText = false, $lastMonth = false, $monthsCount = self::MONTHS_COUNT)
    {
        if (!$this->checkPermissions() || !$this->cntTotal) {
            return [];
        }

        $yearMonths = array_values(ArrayHelper::getValue($this->years, $year . '.MONTHS', []));

        if (!$yearMonths) {
            return [];
        }

        $startPos = $lastMonth ? array_search($lastMonth, $yearMonths) : 0;
        $filterMonths = array_slice($yearMonths, $startPos, $startPos + $monthsCount);

        $params = [
            'filter' => [
                '=USER_ID' => $this->profile->getId(),
                '=MSG_YEAR' => $year
            ]
        ];

        if ($filterMonths) {
            $params['filter']['=MSG_MONTH'] = $filterMonths;
        }

        try {
            $sql = "SELECT `MSG_ID` `ID`, `REAL_ID`, `DATE_CREATED`, `MSG_YEAR`, `MSG_MONTH`, `IS_NEW`, `NAME` " .
                "FROM ( (" .
                $this->getEntityQuery(
                    MessageUsersTable::class,
                    $params
                )->getQuery() .
                ") UNION (" .
                $this->getEntityQuery(
                    NotifyTable::class,
                    $params
                )->getQuery() .
                ") ) AS msg " .
                "GROUP BY `MSG_ID` " .
                "ORDER BY `DATE_CREATED` DESC";

            $res = Application::getConnection()->query($sql)->fetchAll();
        } catch (\Exception $e) {
            $res = [];
        }

        return $res;
    }

    public static function prepareList(array $list)
    {
        $res = [];

        if (!$list) {
            return $res;
        }

        foreach ($list as $v) {
            if (empty($v['DATE_CREATED']) || !$v['DATE_CREATED'] instanceof DateTime) {
                continue;
            }

            $monthNum =$v['DATE_CREATED']->format('m');

            if (!isset($res[$monthNum])) {
                $res[$monthNum]['name'] = FormatDate('f', $v['DATE_CREATED']->getTimestamp());
            }

            $v['LINK'] = self::getElementLink($v);
            $v['DATE'] = self::formatDate($v['DATE_CREATED']);
            $res[$monthNum]['elements'][] = $v;
        }

        return $res;
    }

    public static function getElementLink(array $el)
    {
        return ArrayHelper::getValue(self::$settings, 'sefFolder', self::BASE_URL) . $el['ID'] . '/';
    }

    public static function formatDate(DateTime $elementDate)
    {
        return $elementDate->format(
            ArrayHelper::getValue(
                self::$settings,
                'dateFormat',
                'd.m.Y H:i'
            )
        );
    }

    private function getEntityQuery($nameSpace, array $params = []): Query
    {
        $isCount = true;
        $filter = [
            '=USER_ID' => $this->profile->getId()
        ];

        foreach (['select', 'filter'] as $type) {
            if (!empty($params[$type])) {
                $isCount = false;
                ${$type} = $params[$type];
            }
        }

        if (empty($select)) {
            $select = self::getSelectByEntity($nameSpace, $isCount);
        }

        $query = new Query($nameSpace::getEntity());
        $query->setSelect($select)->setFilter($filter);

        if ($isCount) {
            $query->setGroup(['MSG_YEAR']);
        }

        return $query;
    }

    private static function getSelectByEntity($nameSpace, $isCount = false): array
    {
        if (!$isCount) {
            $res = [
                new ExpressionField(
                    'MSG_ID',
                    'CONCAT(\'n-\', %s)',
                    'ID'
                ),
                'REAL_ID' => 'ID',
                'DATE_CREATED',
                new ExpressionField(
                    'MSG_YEAR',
                    'YEAR(%s)',
                    'DATE_CREATED'
                ),
                new ExpressionField(
                    'MSG_MONTH',
                    'MONTH(%s)',
                    'DATE_CREATED'
                ),
                'IS_NEW',
                'NAME'
            ];

            if ($nameSpace === MessageUsersTable::class) {
                $res = [
                    new ExpressionField(
                        'MSG_ID',
                        'CONCAT(\'m-\', %s)',
                        'MESSAGE_ID'
                    ),
                    'REAL_ID' => 'MESSAGE_ID',
                    'DATE_CREATED' => 'MESSAGE.DATE_CREATED',
                    new ExpressionField(
                        'MSG_YEAR',
                        'YEAR(%s)',
                        'MESSAGE.DATE_CREATED'
                    ),
                    new ExpressionField(
                        'MSG_MONTH',
                        'MONTH(%s)',
                        'MESSAGE.DATE_CREATED'
                    ),
                    'IS_NEW',
                    'NAME' => 'MESSAGE.NAME'
                ];
            }

            return $res;
        }

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
                'YEAR(%s)',
                'MESSAGE.DATE_CREATED'
            );

            $res[] = new ExpressionField(
                'MSG_MONTH',
                'MONTH(%s)',
                'MESSAGE.DATE_CREATED'
            );

            return $res;
        }

        $res[] = new ExpressionField(
            'MSG_YEAR',
            'YEAR(%s)',
            'DATE_CREATED'
        );

        $res[] = new ExpressionField(
            'MSG_MONTH',
            'MONTH(%s)',
            'DATE_CREATED'
        );

        return $res;
    }

    private function checkPermissions()
    {
        return $this->profile->canMessages();
    }
}
