<?php

use Bitrix\Main\Localization\Loc;
?>

<?php if($this->report->getStatusId() === \Kelnik\Report\Model\StatusTable::CHECKING): ?>
    <input type="submit"
           name="done"
           value="<?=Loc::getMessage('KELNIK_REPORT_BTN_DONE') ?>"
           title="<?=Loc::getMessage('KELNIK_REPORT_BTN_DONE') ?>"
           class="adm-btn-save"
    />

    <input type="submit"
           name="decline"
           value="<?=Loc::getMessage('KELNIK_REPORT_BTN_DECLINE') ?>"
           title="<?=Loc::getMessage('KELNIK_REPORT_BTN_DECLINE') ?>"
           class="kelnik-btn-decline"
    />
<?php endif; ?>

<input type="button"
       name="cancel"
       onClick="top.window.location='<?= htmlspecialcharsbx(CUtil::addslashes(\Kelnik\Report\Model\AdminInterface\ReportsListHelper::getUrl())); ?>'"
       value="<?=GetMessage("admin_lib_edit_cancel") ?>"
       title="<?=Loc::getMessage("admin_lib_edit_cancel") ?>"
/>
