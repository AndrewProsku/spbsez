<?php

namespace Kelnik\Messages;

use Bitrix\Main\Application;
use Bitrix\Main\FileTable;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\Model\MessagesTable;
use Kelnik\Messages\Model\MessageUsersTable;
use Kelnik\Requests\Model\NotifyTable;
use Kelnik\Userdata\Profile\Profile;

class MessageService
{
    public const MONTHS_COUNT = 2;
    public const BASE_URL = '/cabinet/messages/';

    /**
     * @var Profile
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

    private function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    public static function getInstance(Profile $profile)
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

    public function getMonthsByYear($year)
    {
        return ArrayHelper::getValue($this->years, $year . '.MONTHS', []);
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
                    "GROUP BY `MSG_YEAR`, `MSG_MONTH` " .
                    "ORDER BY `MSG_YEAR` DESC, `MSG_MONTH` DESC";

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

    public function setViewed($type, int $id)
    {
        if (!self::checkType($type) || !$id) {
            return false;
        }

        $sqlHelper = Application::getConnection()->getSqlHelper();
        $nameSpace = NotifyTable::class;
        $where = [
            '`USER_ID` = ' . $sqlHelper->convertToDbInteger($this->profile->getId()),
            '`ID` = ' . $sqlHelper->convertToDbInteger($id)
        ];

        if ($type === 'm') {
            $nameSpace = MessageUsersTable::class;
            unset($where[1]);
            $where[] = '`MESSAGE_ID` = ' . $sqlHelper->convertToDbInteger($id);
        }

        try {
            Application::getConnection()->query(
                'UPDATE `' . $nameSpace::getTableName() . '` ' .
                'SET `IS_NEW` = ' . $sqlHelper->convertToDbString(NotifyTable::NO) . ' ' .
                'WHERE ' . implode(' AND ', $where) . ' ' .
                'LIMIT 1'
            );
        } catch (\Exception $e) {
            return false;
        }

        clearKelnikComponentCache('messages');

        return true;
    }

    public function getMessage($type, int $id)
    {
        if (!self::checkType($type) || !$id) {
            return false;
        }

        try {
            $nameSpace = NotifyTable::class;
            $select = ['ID', 'DATE_CREATED', 'NAME', 'TEXT'];
            $params = [
                'select' => array_merge($select, ['IS_NEW']),
                'filter' => [
                    '=ID' => $id,
                    '=USER_ID' => $this->profile->getId()
                ]
            ];

            if ($type === 'm') {
                $nameSpace = MessagesTable::class;
                $select['USER'] = 'USERS.USER_ID';
                $select['IS_NEW'] = 'USERS.IS_NEW';
                $select[] = new ExpressionField(
                    'FILE',
                    'GROUP_CONCAT(%s)',
                    'FILES.VALUE'
                );
                $params = [
                    'select' => $select,
                    'filter' => [
                        '=ID' => $id,
                        '=ACTIVE' => MessagesTable::YES,
                        '=USERS.USER_ID' => $this->profile->getId()
                    ],
                    'group' => [
                        'ID'
                    ]
                ];
            }

            $element = $nameSpace::getRow($params);
        } catch (\Exception $e) {
            $element = false;
        }

        if (!$element) {
            return false;
        }

        return self::prepareMessage($element);
    }

    public static function prepareMessage(array $row): array
    {
        if (empty($row['ID'])) {
            return $row;
        }

        if (!empty($row['FILE'])) {
            try {
                $row['FILE'] = explode(',', $row['FILE']);
                $row['FILES'] = FileTable::getList([
                    'filter' => [
                        '=ID' => $row['FILE']
                    ]
                ])->fetchAll();

                unset($row['FILE']);
            } catch (\Exception $e) {
            }
        }

        if (!empty($row['FILES'])) {
            foreach ($row['FILES'] as &$v) {
                $v['EXT'] = pathinfo($v['ORIGINAL_NAME'], PATHINFO_EXTENSION);
                $v['SRC'] = \CFile::GetFileSRC($v);
            }
            unset($v);
        }

        if ($row['DATE_CREATED'] instanceof DateTime) {
            $row['DATE_TIME_FORMAT'] = self::formatDate($row['DATE_CREATED']);
            $row['DATE_FORMAT'] = $row['DATE_CREATED']->format('d.m.Y');
        }

        return $row;
    }

    /**
     * @param int $year
     * @param bool $searchText
     * @param int $monthsOffset
     * @param int $monthsCount
     * @return array
     */
    public function getList(int $year, $searchText = false, $monthsOffset = 0, $monthsCount = self::MONTHS_COUNT)
    {
        if (!$this->checkPermissions() || !$this->cntTotal) {
            return [];
        }

        $yearMonths = array_values(ArrayHelper::getValue($this->years, $year . '.MONTHS', []));

        if (!$yearMonths && !$searchText) {
            return [];
        }

        $select = [
            '`MSG_ID` `ID`', '`REAL_ID`', '`DATE_CREATED`',
            '`MSG_YEAR`', '`MSG_MONTH`', '`IS_NEW`', '`NAME`'
        ];
        $where = $limit = '';

        if (!$searchText) {
            $filterMonths = array_slice($yearMonths, $monthsOffset, $monthsCount);

            $params = [
                'filter' => [
                    '=USER_ID'  => $this->profile->getId(),
                    '=MSG_YEAR' => $year
                ]
            ];

            if ($filterMonths) {
                $params['filter']['=MSG_MONTH'] = $filterMonths;
            }
        }

        $isSearch = false;

        if ($searchText) {
            $isSearch = true;
            $params['filter'] = [
                '=USER_ID' => $this->profile->getId(),
                ''
            ];

            $select[] = '`TEXT`';

            $searchText = Application::getConnection()->getSqlHelper()->convertToDbString('%' . $searchText . '%');
            $where = 'WHERE `NAME` LIKE ' . $searchText . ' OR `TEXT` LIKE ' . $searchText . ' ';
            $limit = 'LIMIT 50';
        }

        try {
            $sql = "SELECT " . implode(', ', $select) . " " .
                "FROM ( (" .
                $this->getEntityQuery(MessageUsersTable::class, $params, $isSearch)->getQuery() .
                ") UNION (" .
                $this->getEntityQuery(NotifyTable::class, $params, $isSearch)->getQuery() .
                ") ) AS msg " .
                $where .
                "GROUP BY `MSG_ID` " .
                "ORDER BY `DATE_CREATED` DESC " .
                $limit;
            $res = Application::getConnection()->query($sql)->fetchAll();
        } catch (\Exception $e) {
            $res = [];
        }

        return $res;
    }

    public static function prepareList(array $list, $byMonth = true)
    {
        $res = [];

        if (!$list) {
            return $res;
        }

        foreach ($list as $v) {
            if (empty($v['DATE_CREATED']) || !$v['DATE_CREATED'] instanceof DateTime) {
                continue;
            }

            $monthNum = $v['DATE_CREATED']->format('m');

            if (!isset($res[$monthNum]) && $byMonth) {
                $res[$monthNum]['NAME'] = FormatDate('f', $v['DATE_CREATED']->getTimestamp());
            }

            $v['LINK'] = self::getElementLink($v);
            $v['DATE_TIME_FORMAT'] = self::formatDate($v['DATE_CREATED']);
            $v['DATE_FORMAT'] = $v['DATE_CREATED']->format('d.m.Y');
            $v['DATE'] = $v['DATE_CREATED']->format('Y-m-d');
            $v['TIME'] = $v['DATE_CREATED']->format('H:i');

            if ($byMonth) {
                $res[$monthNum]['ELEMENTS'][] = $v;
                continue;
            }

            $res[] = $v;
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

    public static function checkType($type)
    {
        return in_array($type, ['n', 'm']);
    }

    private function getEntityQuery($nameSpace, array $params = [], $isSearch = false): Query
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

        if ($nameSpace === MessageUsersTable::class) {
            $filter['=MESSAGE.ACTIVE'] = MessagesTable::YES;
        }

        if (empty($select)) {
            $select = self::getSelectByEntity($nameSpace, $isCount, $isSearch);
        }

        $query = new Query($nameSpace::getEntity());
        $query->setSelect($select)->setFilter($filter);

        if ($isCount) {
            $query->setGroup(['MSG_YEAR']);
        }

        return $query;
    }

    private static function getSelectByEntity($nameSpace, $isCount = false, $isSearch = false): array
    {
        if (!$isCount) {
            $res = [
                new ExpressionField(
                    'MSG_ID',
                    'CONCAT(\'n\', %s)',
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
                'NAME',
                'TEXT'
            ];

            if ($nameSpace === MessageUsersTable::class) {
                $res = [
                    new ExpressionField(
                        'MSG_ID',
                        'CONCAT(\'m\', %s)',
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
                    'NAME' => 'MESSAGE.NAME',
                    'TEXT' => 'MESSAGE.TEXT'
                ];
            }

            if (!$isSearch) {
                array_pop($res);
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
