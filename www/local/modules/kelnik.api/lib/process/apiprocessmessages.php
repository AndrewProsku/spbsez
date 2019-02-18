<?php
namespace Kelnik\Api\Process;


use Bitrix\Main\Localization\Loc;
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

        if (!empty($request['step'])) {
            $offset = (int) $request['step'] * MessageModel::MONTHS_COUNT;
            $year = date('Y');
            $months = $messages->getMonthsByYear($year);

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

            $this->data['IS_END'] = count($months) <= $offset + MessageModel::MONTHS_COUNT;

            return true;
        }

        return true;
    }
}
