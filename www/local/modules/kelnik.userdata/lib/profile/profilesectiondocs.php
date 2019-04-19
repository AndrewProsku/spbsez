<?php


namespace Kelnik\Userdata\Profile;


use Bitrix\Main\Entity\AddResult;
use Bitrix\Main\Localization\Loc;
use Kelnik\Userdata\Model\DocsTable;

Loc::loadMessages(__FILE__);

class ProfileSectionDocs extends ProfileSectionAbstract
{
    /**
     * @param array $data
     * @return array|bool
     */
    public function add(array $data)
    {
        $allowExt = DocsTable::getAllowExt();
        $fileId = $res = false;

        if (!in_array($data['type'], array_keys($allowExt))) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_EXT_ERROR');

            return false;
        }

        try {
            $doc['MODULE_ID'] = self::MODULE_ID;
            $fileId = \CFile::SaveFile($data, $doc['MODULE_ID'], true);
        } catch (\Exception $e) {
        }

        if (!$fileId) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');

            return false;
        }

        try {
            $res = DocsTable::add([
                'USER_ID' => $this->profile->getId(),
                'FILE_ID' => $fileId
            ]);
        } catch (\Exception $e) {
        }

        if (!($res instanceof AddResult) || !$res->isSuccess()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');

            return false;
        }

        return [
            'rowId' => $res->getId(),
            'fileId' => $fileId
        ];
    }

    public function delete($id)
    {
        if (!$id) {
            return false;
        }

        try {
            $res = DocsTable::getRow([
                'select' => ['ID'],
                'filter' => [
                    '=ID' => $id,
                    '=USER_ID' => $this->profile->getId()
                ]
            ]);
            if (empty($res['ID'])) {
                $this->lastError = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                return false;
            }
            DocsTable::delete($id);
        }catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getList()
    {
        return DocsTable::getListByUser($this->profile->getId());
    }
}
