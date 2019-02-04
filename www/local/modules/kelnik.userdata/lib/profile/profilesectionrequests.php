<?php


namespace Kelnik\Userdata\Profile;


use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\StandartTable;

class ProfileSectionRequests extends ProfileSectionAbstract
{
    public function add(array $data)
    {
        if (!$data
            || !is_array($data)
            || !$this->profile->canRequest()
            || !$this->canAddNewRow()
        ) {
            return false;
        }

        $data['USER_ID'] = $this->profile->getId();

        try {
            $res = StandartTable::add($data);
        } catch (\Exception $e) {
            return false;
        }

        if (!$res->isSuccess()) {
            return false;
        }

        return ArrayHelper::getValue($res->getData(), 'CODE', false);
    }

    /**
     * Поиск последнего запроса
     *
     * @return bool|DateTime
     */
    public function getLastRequest()
    {
        try {
            $lastRequest = StandartTable::getRow([
                'select' => ['DATE_CREATED'],
                'filter' => [
                    '=USER_ID' => $this->profile->getId()
                ],
                'order' => [
                    'DATE_CREATED' => 'DESC'
                ]
            ]);
        } catch (\Exception $e) {
            $lastRequest = false;
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

        return !($lastRequest instanceof DateTime && (time() - $lastRequest->getTimestamp()) < StandartTable::REQUEST_TIME_LEFT);
    }
}
