<?php

namespace Kelnik\UserData\Model;

use Bitrix\Main;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(__FILE__);

class DocsTable extends DataManager
{
    protected static $allowExt = [
        'application/msword'                                                      => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/pdf'                                                         => 'pdf',
        'application/octet-stream'                                                => 'pdf',
        'application/excel'                                                       => 'xl',
        'application/msexcel'                                                     => 'xls',
        'application/x-msexcel'                                                   => 'xls',
        'application/x-ms-excel'                                                  => 'xls',
        'application/x-excel'                                                     => 'xls',
        'application/x-dos_ms_excel'                                              => 'xls',
        'application/xls'                                                         => 'xls',
        'application/x-xls'                                                       => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'       => 'xlsx',
        'application/vnd.ms-excel'                                                => 'xlsx'
    ];

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_userdata_docs';
    }

    public static function getUfId()
    {
        return 'USER_DOCS';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            new Main\Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_USERDATA_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_USER_ID'),
                ]
            ),
            new Main\Entity\IntegerField(
                'FILE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_FILE_ID'),
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_DATE_CREATED')
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_DATE_MODIFIED')
                ]
            ),

            new Main\Entity\ReferenceField(
                'USER',
                Main\UserTable::class,
                [
                    '=this.USER_ID' => 'ref.ID'
                ]
            ),
            new Main\Entity\ReferenceField(
                'FILE',
                Main\FileTable::class,
                [
                    '=this.FILE_ID' => 'ref.ID'
                ]
            ),

            new Main\Entity\ExpressionField(
                'COMPANY_ID',
                'IF(%s, %s, %s)',
                [
                    'USER.' . Profile::OWNER_FIELD,
                    'USER.' . Profile::OWNER_FIELD,
                    'USER.ID'
                ]
            ),
            new Main\Entity\ExpressionField(
                'COMPANY_NAME',
                'IF(%s, (SELECT `' . Profile::COMPANY_NAME_FIELD .'` FROM `' . Main\UserTable::getTableName() . '` WHERE `ID`= %s), (SELECT `' . Profile::COMPANY_NAME_FIELD .'` FROM `' . Main\UserTable::getTableName() . '` WHERE `ID`= %s))',
                [
                    'USER.' . Profile::OWNER_FIELD,
                    'USER.' . Profile::OWNER_FIELD,
                    'USER.ID'
                ]
            ),
            new Main\Entity\ExpressionField(
                'USER_NAME',
                '(SELECT CONCAT(\'(\', `EMAIL`, \') \', `LAST_NAME`, \' \', `NAME`, \' \', `SECOND_NAME`) FROM `' . Main\UserTable::getTableName() . '` WHERE `ID`= %s)',
                [
                    'USER.ID'
                ]
            )
        ];
    }

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new Main\Type\DateTime();

        return parent::add($data);
    }

    public static function update($primary, array $data)
    {
        $data['DATE_MODIFIED'] = new Main\Type\DateTime();
        return parent::update($primary, $data);
    }

    public static function getListByUser(array $userIds)
    {
        $userIds = array_map('intval', $userIds);

        if (!$userIds) {
            return [];
        }

        try {
            $res = self::getAssoc([
                'select' => [
                    '*',
                    new Main\Entity\ExpressionField(
                        'USER_NAME',
                        'CONCAT_WS(\' \', %s, %s, %s)',
                        [
                            'USER.LAST_NAME',
                            'USER.NAME',
                            'USER.SECOND_NAME'
                        ]
                    )
                ],
                'filter' => [
                    '=USER_ID' => $userIds
                ],
                'order'  => [
                    'ID' => 'DESC'
                ]
            ], 'ID');
        } catch (\Exception $e) {
            $res = [];
        }

        if (!$res) {
            return $res;
        }

        $res = BitrixHelper::prepareFileFields($res, ['FILE_ID' => 'full']);

        foreach ($res as &$v) {
            $v['FILE_DATA'] = ArrayHelper::getValue($v, 'FILE_ID');
            $v['FILE_DATA']['FILE_SIZE_FORMAT'] = \CFile::FormatSize($v['FILE_DATA']['FILE_SIZE']);
            $v['FILE_DATA']['EXT'] = strtolower(pathinfo($v['FILE_DATA']['ORIGINAL_NAME'], PATHINFO_EXTENSION));
            $v['DATE_MODIFIED_FORMAT'] = $v['DATE_MODIFIED']->format('Y-m-d');
            $v['DATE_MODIFIED_FORMAT_HUMAN'] = $v['DATE_MODIFIED']->format('d.m.Y');
            unset($v['FILE_ID']);
        }
        unset($v);

        return $res;
    }

    public static function getAllowExt()
    {
        return self::$allowExt;
    }

    public static function clearComponentCache(Event $event)
    {
        global $USER;

        try {
            $rowId = ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);

            $userId = Main\Context::getCurrent()->getRequest()->isAdminSection()
                            ? (int)ArrayHelper::getValue(DocsTable::getById($rowId)->fetch(), 'USER_ID')
                            : (int)$USER->GetID();

            if (!$userId) {
                return;
            }

            $profile = Profile::getInstance($userId);

            Main\Application::getInstance()->getTaggedCache()->clearByTag('kelnik:profile_' . $profile->getId());
            Main\Application::getInstance()->getTaggedCache()->clearByTag('kelnik:profileCompany_' . $profile->getCompanyId());
        } catch (\Exception $e) {
        }
    }
}
