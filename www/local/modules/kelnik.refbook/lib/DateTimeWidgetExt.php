<?php

namespace Kelnik\Refbook;

use Kelnik\AdminHelper\Widget\DateTimeWidget;

class DateTimeWidgetExt extends DateTimeWidget
{
    /**
     * Сконвертируем дату в формат Mysql
     * @return boolean
     */
    public function processEditAction()
    {
        $value = null;
        try {
            if ($this->getSettings('FORMAT') == 'TIMESTAMP') {
                $value = strtotime($this->getValue());
            } elseif ($this->getValue()) {
                $value = new \Bitrix\Main\Type\Datetime($this->getValue());
            }
            $this->setValue($value);
        } catch (\Exception $e) {
        }
        if (!$this->checkRequired()) {
            $this->addError('KELNIK_AH_REQUIRED_FIELD_ERROR');
        }
    }
}
