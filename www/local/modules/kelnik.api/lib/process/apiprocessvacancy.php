<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Api\Api;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Vacancy\Model\ResponseTable;

/**
 * Class ApiProcessVacancy
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessVacancy extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        $contextRequest = Context::getCurrent()->getRequest();
        $userHash = Api::getUserHash();

        if (!$contextRequest->isPost() || !ResponseTable::userCanAddRow($userHash)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REQUEST_ERROR');

            return false;
        }

        $fields = [
            'NAME' => 'fio',
            'VACANCY_ID' => 'type',
            'PHONE' => 'phone',
            'EMAIL' => 'email'
        ];

        $data = [];

        foreach ($fields as $k => $v) {
            $data[$k] = htmlentities(trim(ArrayHelper::getValue($request, $v)), ENT_QUOTES, 'UTF-8');
            if (empty($data[$k])) {
                $this->errors[] = Loc::getMessage('KELNIK_API_VACANCY_ERROR_' . $k);
                return false;
            }
        }

        $data['VACANCY_ID'] = (int) $data['VACANCY_ID'];

        $userFile = ArrayHelper::getValue($_FILES, 'resume');

        if (empty($userFile['tmp_name']) || !is_uploaded_file($userFile['tmp_name'])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_VACANCY_ERROR_FILE');

            return false;
        }

        $userFile['MODULE_ID'] = 'kelnik.vacancy';
        $data['FILE_ID'] = \CFile::SaveFile($userFile, $userFile['MODULE_ID'], true);
        $data['USER_HASH'] = $userHash;

        try {
            ResponseTable::add($data);
        } catch (\Exception $e) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        return true;
    }
}
