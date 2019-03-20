<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Infrastructure\Model\PlatformTable;

class PlatformEditHelper extends AdminEditHelper
{
    protected static $model = PlatformTable::class;

    public function saveElement($id = null)
    {
        $platformData = self::filterFields($this->data);
        $entityManager = new static::$entityManager(static::getModel(), $platformData, $id, $this);
        $saveResult = $entityManager->save();
        $this->addNotes($entityManager->getNotes());

        return $saveResult;
    }

    protected function filterFields(array $data)
    {
        $textData = [];

        foreach (PlatformTable::getFields() as $field) {
            foreach (PlatformTable::getLangs() as $fieldLang) {
                if (isset($data[$field . '_' . $fieldLang])) {
                    $textData[$field . '_' . $fieldLang] = $data[$field . '_' . $fieldLang];
                    unset($data[$field . '_' . $fieldLang]);
                }
            }
        }

        return $data;
    }
}
