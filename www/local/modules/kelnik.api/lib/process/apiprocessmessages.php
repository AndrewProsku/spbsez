<?php
namespace Kelnik\Api\Process;


use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\MessageModel;
use Kelnik\Userdata\Profile\ProfileModel;

/**
 * Class ApiProcessMessages
 *
 * Сообщения ОЭЗ
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessMessages extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        global $USER;

        $messages = MessageModel::getInstance(
            ProfileModel::getInstance($USER->GetID())
        );
        $messages->calcCount();

        $offset = (int) ArrayHelper::getValue($request, 'step', 0) * MessageModel::MONTHS_COUNT;
        $year   = (int) ArrayHelper::getValue($request, 'year', date('Y'));
        $months = count($messages->getMonthsByYear($year));

        $this->data['IS_END'] = true;

        if ($offset > $months) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        $this->data['months'] = array_values(
            $messages::prepareList(
                $messages->getList(
                    $year,
                    false,
                    $offset
                )
            )
        );

        $this->data['YEAR'] = $year;
        $this->data['IS_END'] = $months <= $offset + MessageModel::MONTHS_COUNT;

        return true;
    }
}
