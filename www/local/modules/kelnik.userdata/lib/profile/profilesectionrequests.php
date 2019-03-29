<?php


namespace Kelnik\UserData\Profile;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\PermitPassTable;
use Kelnik\Requests\Model\PermitTable;
use Kelnik\Requests\Model\StandardTable;
use Kelnik\Requests\Model\TypeTable;

class ProfileSectionRequests extends ProfileSectionAbstract
{
    protected $formType = null;
    protected $entityClass;

    protected $formErrors = [];

    protected $types = [
        TypeTable::SUB_TYPE_STANDARD => StandardTable::class,
        TypeTable::SUB_TYPE_PERMIT => PermitTable::class
    ];

    public function setFormType($type)
    {
        if (!isset($this->types[$type])) {
            return;
        }

        $this->formType = $type;
        $this->entityClass = $this->types[$type];
    }

    public function prepareData(array $postData)
    {
        $this->formErrors = [];
        $res = [];

        $fields = $this->getFormFields();

        foreach ($fields as $k => $field) {

            $row = self::getField($postData, $field);

            if ($k == 'message' && mb_strlen($row) < 4) {
                $row = null;
            }

            $res[$k] = $row;

            if (!$row) {
                $this->formErrors[$k] = Loc::getMessage('KELNIK_REQ_FIELD_REQUIRED');
            }
        }

        return $res;
    }

    public function add(array $data)
    {
        if (!$data
            || !is_array($data)
            || !$this->profile->canRequest()
            || !$this->canAddNewRow()
        ) {
            return false;
        }

        $method = 'add' . ucfirst($this->formType);

        if (!method_exists($this, $method)) {
            return false;
        }

        return $this->{$method}($data);
    }

    public function addStandard(array $data)
    {
        $data['USER_ID'] = $this->profile->getId();

        try {
            $res = $this->entityClass::add($data);
        } catch (\Exception $e) {
            return false;
        }

        if (!$res->isSuccess()) {
            return false;
        }

        return ArrayHelper::getValue($res->getData(), 'CODE', false);
    }

    public function addPermit(array $data)
    {
        $child = ArrayHelper::getValue($data, '_PASS_');
        unset($data['_PASS_']);

        $db = PermitTable::getEntity()->getConnection();
        $db->startTransaction();

        try {
            $data['USER_ID'] = $this->profile->getId();
            $res = PermitTable::add($data);

            if (!$res->isSuccess()) {
                $db->rollbackTransaction();
                return false;
            }

            if ($child) {
                foreach ($child as $row) {
                    $row['PERMIT_ID'] = $res->getId();
                    PermitPassTable::add($row);
                }
            }
        } catch (\Exception $e) {
            $db->rollbackTransaction();
            return false;
        }

        $db->commitTransaction();

        return ArrayHelper::getValue($res->getData(), 'CODE', false);
    }

    protected function getFormFields()
    {
        $res = [
            'NAME' => [
                'name' => 'name'
            ],
            'TYPE_ID' => [
                'name' => 'theme',
                'type' => 'int'
            ],
            'BODY' => [
                'name' => 'message'
            ]
        ];

        if ($this->formType === TypeTable::SUB_TYPE_PERMIT) {
            $res = [
                'TYPE_ID' => [
                    'name' => 'area'
                ],
                'NAME' => [
                    'name' => 'name'
                ],
                'DATE_START' => [
                    'name' => 'timeFrom',
                    'type' => 'date'
                ],
                'TARGET' => [
                    'name' => 'visitTarget'
                ],
                'EXECUTIVE_COMPANY' => [
                    'name' => 'executiveCompany'
                ],
                'EXECUTIVE_VISIT' => [
                    'name' => 'executiveVisit'
                ],
                'PHONE' => [
                    'name' => 'phone'
                ],
                '_PASS_' => [
                    'type' => 'child',
                    'key' => 'pass',
                    'fields' => [
                        'FIO' => [
                            'name' => 'fio'
                        ],
                        'ORG_NAME' => [
                            'name' => 'organizationPass'
                        ],
                        'CAR_VENDOR' => [
                            'name' => 'carModel'
                        ],
                        'CAR_NUMBER' => [
                            'name' => 'stateNumber'
                        ],
                        'PERSON' => [
                            'name' => 'accompanying'
                        ]
                    ]
                ]
            ];
        }

        return $res;
    }

    protected static function getField(array $data, array $field)
    {
        $val = ArrayHelper::getValue($data, $field['name']);

        if (empty($field['type']) || $field['type'] === 'string') {
            return htmlentities(trim($val), ENT_QUOTES, 'UTF-8');
        }

        if ($field['type'] === 'array') {
            return $val;
        }

        if ($field['type'] === 'date') {
            return DateTime::createFromText($val);
        }

        if ($field['type'] === 'child') {
            if (empty($field['fields']) || empty($field['key'])) {
                return [];
            }
            $fields = $field['fields'];
            $childCnt = count(ArrayHelper::getValue($data, $field['key'] . '.' . $fields[key($fields)]['name'], 0));

            $res = [];

            for ($i = 0; $i < $childCnt; $i++) {
                foreach ($fields as $childKey => $childField) {
                    $childField['name'] .= '.' . $i;
                    $row[$childKey] = htmlentities(self::getField($data[$field['key']], $childField), ENT_QUOTES, 'UTF-8');
                }
                $res[] = $row;
            }

            return $res;
        }

        return $field['type'] === 'float'
                ? (float) $val
                : (int) $val;
    }

    /**
     * Поиск последнего запроса
     *
     * @return DateTime
     */
    public function getLastRequest()
    {
        try {
            $lastRequest = $this->entityClass::getRow([
                'select' => ['DATE_CREATED'],
                'filter' => [
                    '=USER_ID' => $this->profile->getId()
                ],
                'order' => [
                    'DATE_CREATED' => 'DESC'
                ]
            ]);
        } catch (\Exception $e) {
        }

        return !empty($lastRequest['DATE_CREATED'])
                ? $lastRequest['DATE_CREATED']
                : DateTime::createFromTimestamp(time() - $this->entityClass::REQUEST_TIME_LEFT - 10);
    }

    /**
     * Проверка времени последнего запроса
     *
     * @return bool
     */
    public function canAddNewRow()
    {
        return (time() - $this->getLastRequest()->getTimestamp()) > $this->entityClass::REQUEST_TIME_LEFT;
    }

    public function getFormErrors()
    {
        return $this->formErrors;
    }
}
