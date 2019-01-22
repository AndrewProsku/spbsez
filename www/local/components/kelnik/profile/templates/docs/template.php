<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['DOCS'])): ?>
<div class="b-empty-page is-active">
    <p>
        Еще не загружен ни один документ
    </p>
</div>
<?php endif; ?>

<?php if(!empty($arResult['ERROR'])): ?>
<div class="b-profile-document-error is-active">
    <div class="b-profile-document-error__close"></div>
    <p><?= $arResult['ERROR']; ?></p>
</div>
<?php endif; ?>

<?php if(!empty($arResult['DOCS'])): ?>
<div class="b-profile-document__list">
    <?php foreach ($arResult['DOCS'] as $doc): ?>
    <div class="b-profile-document__item j-profile-document__item b-profile-document__item--ext_<?= $doc['FILE_DATA']['EXT']; ?>"
        data-id="<?= $doc['ID']; ?>"
        data-ext="<?= $doc['FILE_DATA']['EXT']; ?>"
        data-can-delete="true">
        <div class="b-profile-document__item-title">
            <a href="<?= $doc['FILE_DATA']['SRC']; ?>" class="b-profile-document__item-title-link b-link-line"><?= $doc['FILE_DATA']['ORIGINAL_NAME']; ?></a>
            <span class="b-profile-document__item-title-weight"><?= $doc['FILE_DATA']['FILE_SIZE_FORMAT']; ?></span>
        </div>
        <div class="b-profile-document__item-desc">
            <span class="b-profile-document__item-name"><?= $doc['USER_NAME']; ?></span>
            <time datetime="<?= $doc['DATE_MODIFIED_FORMAT']; ?>" class="b-profile-document__item-date"><?= $doc['DATE_MODIFIED_FORMAT_HUMAN']; ?></time>
            <div class="b-profile-document__item-delete j-profile-document__item-delete">
                <button type="button" class="b-profile-document__item-delete-button j-delete-doc-button">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="9"
                         height="12" viewBox="0 0 9 12">
                        <defs>
                            <path id="wl62a" d="M1163 503v1h-9v-1h2.5v-1h4v1zm-1 11h-7l-1-9h9z"/>
                        </defs>
                        <g>
                            <g transform="translate(-1154 -502)">
                                <use xlink:href="#wl62a"/>
                            </g>
                        </g>
                    </svg>
                </button>
                <div class="b-profile-document__item-delete-tooltip">
                    <a href="#" class="b-link-line j-delete-doc-row">Удалить</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="b-profile-add">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="addDoc">
        <label class="b-profile-add__label">
            <input type="file"
                   name="doc"
                   class="b-profile-add__input-file j-profile__file-picker"
                   accept="<?= $arResult['ACCEPT']; ?>"
            >
            <span class="button-add">Загрузить документ</span>
        </label>
    </form>
</div>

<?/*<div class="b-empty-page b-empty-page_padding_top is-active">
    <p>
        Еще не загружен ни один документ. Чтобы загрузить нажмите на кнопку справа.
    </p>
</div>*/?>
