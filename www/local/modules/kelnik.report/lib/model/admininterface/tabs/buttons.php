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

<?php if($this->report->getStatusId() === \Kelnik\Report\Model\StatusTable::DONE): ?>
    <input type="submit"
           name="checking"
           value="<?=Loc::getMessage('KELNIK_REPORT_BTN_CHECKING') ?>"
           title="<?=Loc::getMessage('KELNIK_REPORT_BTN_CHECKING') ?>"
           class="adm-btn-add"
    />
<?php endif; ?>

<input type="button"
       name="cancel"
       onClick="backToList()"
       value="<?=GetMessage("admin_lib_edit_cancel") ?>"
       title="<?=Loc::getMessage("admin_lib_edit_cancel") ?>"
/>

<script>
    backToList = function() {
        //top.window.location='<?= CUtil::addslashes(\Kelnik\Report\Model\AdminInterface\ReportsListHelper::getUrl()); ?>'
        top.window.location=top.window.location + '&goBack=Y';
    }

    document.querySelectorAll("input").forEach((element) => {
        element.onkeydown = (event) => {
            if (event.keyCode === 13) {
                return false;
            }
        }
    })

</script>
