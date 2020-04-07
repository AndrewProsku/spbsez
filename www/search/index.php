<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
require($_SERVER['DOCUMENT_ROOT']. '/ajax/globalSearch.php');
$APPLICATION->SetPageProperty("title", "Результаты поиска");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Результаты поиска");

$search = new Search($_REQUEST);
$search->doSearch();
?>

    <div class="l-profile-messages">

        <div class="b-title b-profile-message-title">
            <h1>Результаты поиска:</h1>
        </div>

        <div class="b-message-block-wrap">

            <div class="b-message-block">
                <?foreach ($search->json['data'] as $category):?>
                    <?foreach (current($category) as $item):?>
                        <div class="b-message-block__item">
                            <div class="b-message-block__item-title">
                                <a href="<?=$item['LINK']?>" class="b-link-line"><?=$item['NAME']?></a>
                            </div>
                          <!--  <div class="b-message-block__item-desc">
                                <span class="b-message-block__item-time">11:30</span>
                                <time datetime="2017-12-26" class="b-message-block__item-date">26.12.2017</time>
                            </div>-->
                        </div>
                    <?endforeach;?>
                <?endforeach;?>
            </div>
        </div>
    </div>



<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>