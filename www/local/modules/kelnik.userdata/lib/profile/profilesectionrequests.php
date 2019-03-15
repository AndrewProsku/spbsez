<?php


namespace Kelnik\Userdata\Profile;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\PermitTable;
use Kelnik\Requests\Model\StandartTable;
use Kelnik\Requests\Model\TypeTable;

class ProfileSectionRequests extends ProfileSectionAbstract
{
    protected $formType = null;
    protected $entityClass;

    protected $errors = [];

    protected $types = [
        TypeTable::SUB_TYPE_STANDARD => StandartTable::class,
        TypeTable::SUB_TYPE_PERMIT => PermitTable::class
    ];

    public function setFormType($type)
    {
        if (isset($this->types[$type])) {
            return;
        }

        $this->formType = $type;
        $this->entityClass = $this->types[$type];
    }

    public function prepareData(array $postData)
    {
        $this->errors = [];
        $res = [];

        foreach ($this->getFormFields() as $k => $v) {
            $postKey = strtolower($k);

            $row = ArrayHelper::getValue($postData, $postKey);
            $row = $postKey == 'theme'
                    ? (int) $row
                    : htmlentities($row, ENT_QUOTES, 'UTF-8');
            $res[$k] = $row;

            if ($postKey == 'message' && mb_strlen($row) < 4) {
                $row = null;
            }

            if (!$row) {
                $this->errors[$k] = Loc::getMessage('KELNIK_REQ_FIELD_REQUIRED');
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

    protected function getFormFields()
    {
        $res = [
            'NAME' => 'name',
            'TYPE_ID' => 'theme',
            'BODY' => 'message'
        ];

        if ($this->types === TypeTable::SUB_TYPE_PERMIT) {
            $res = [
                'TYPE_ID' => 'area',
                'NAME' => 'name',
                'DATE_START' => 'timeFrom',
                'TARGET' => 'visitTarget',
                'EXECUTIVE_COMPANY' => 'executiveCompany',
                'EXECUTIVE_VISIT' => 'executiveVisit',
                'PHONE' => 'phone',
                '_PASS_' => [
                    'FIO' => 'fio',
                    'ORG_NAME' => 'organizationPass',
                    'CAR_VENDOR' => 'carModel',
                    'CAR_NUMBER' => 'stateNumber',
                    'PERSON' => 'accompanying'
                ]
            ];
        }

        return $res;
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
            $lastRequest = DateTime::createFromTimestamp(time() - $this->entityClass::REQUEST_TIME_LEFT);
        }

        return $lastRequest;
    }

    /**
     * Проверка времени последнего запроса
     *
     * @return bool
     */
    public function canAddNewRow()
    {
        $lastRequest = $this->getLastRequest();

        return !(time() - $lastRequest->getTimestamp()) < $this->entityClass::REQUEST_TIME_LEFT;
    }
}
