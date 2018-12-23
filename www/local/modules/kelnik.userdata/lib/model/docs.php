<?php

namespace Kelnik\Userdata\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Composer\Application\Bitrix;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Helpers\Database\DataManager;

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
                    'this.USER_ID' => 'ref.ID'
                ]
            ),
            new Main\Entity\ReferenceField(
                'FILE',
                Main\FileTable::class,
                [
                    'this.FILE_ID' => 'ref.ID'
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

    public static function getListByUser($userId)
    {
        $userId = (int) $userId;

        if (!$userId) {
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
                    '=USER_ID' => $userId
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
}
