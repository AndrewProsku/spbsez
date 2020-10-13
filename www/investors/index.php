<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Инвесторам");
$APPLICATION->SetTitle("Инвесторам");
?>

<div class="l-investors">
    <div class="l-investors__top">
        <h1 class="b-title"><? $APPLICATION->ShowTitle(false); ?></h1>

        <div class="b-investors-info">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_1"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__priorities" id="investors-priorities">
        <div class="b-priorities-direction">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_2"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__advantage" id="investors-advantage">
        <div class="b-advantage-location">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_3"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__privileges">
        <div class="b-investors-info b-investors-info_big_title">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_4"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__taxes" id="investors-privileges">
        <div class="b-income-tax">
            <div class="b-income-tax__top">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_5"
                    )
                ); ?>
            </div>

            <div class="b-income-tax__bottom" id="investors-taxes">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_6"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div class="l-investors__rate">
        <div class="b-reduced-rates">
            <div id="investors-rate"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_7"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__post">
        <div class="b-invest-post">
            <div id="investors-post"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_8"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__conditions" id="investors-conditions">
        <div class="b-invest-conditions">
            <div id="investors-conditions"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_9"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__infrastructure">
        <div class="b-invest-infr">
            <div id="investors-infrastructure"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_10"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__conditions" id="investors-calc">
        <div class="b-invest-conditions ">            
            <h2 class="b-invest-conditions__title">Калькулятор затрат на реализацию инвестиционного проекта</h2>           
            <form action="" class="investors-calc">
            	<div class="investors-calc__fields">
	                <label for="offices">Офисы (кв.м)
	                    <input type="text" id="offices" name="offices" class="investors-calc__fields-field" placeholder="введите значение">
	                </label>
	                <label for="production">Производственные помещения (кв.м)
	                    <input type="text" id="production" name="production" class="investors-calc__fields-field" placeholder="введите значение">
	                </label>
	                <label for="land">Земельный участок (минимум 1 га)
	                    <input type="text" id="land" name="land" class="investors-calc__fields-field" placeholder="введите значение">
	                </label>
	                <p>Объекты капитального строительства:</p>
	                <label for="light">Легковозводимый (кв.м)
	                    <input type="text" id="light" name="light" class="investors-calc__fields-field" placeholder="введите значение">
	                </label>
	                <label for="administrative">Административно-производственный (кв.м)
	                    <input type="text" id="administrative" name="administrative" class="investors-calc__fields-field" placeholder="введите значение">
	                </label>
	                <label for="science">Научно-производственный высокой сложности (кв.м)
	                    <input type="text" id="science" name="science" class="investors-calc__fields-field" placeholder="введите значение">
	                </label>
	                <label for="full_area">Общая площадь объектов (минимум 40% от площади ЗУ)
	                    <input type="text" disabled id="full_area" name="full_area" class="investors-calc__fields-field" placeholder="">
	                </label>
	                <p class="investors-calc__error"></p>
	                <button type="submit" class="investors-calc__fields-button button">Рассчитать</button>
            	</div>
                <div class="investors-calc__result">
                    <h4>Затраты на реализацию проекта:</h4>
                    <div class="investors-calc__result-item">
                        <p>Стоимость аренды офиса в год от: </p><span id="office_cost_rent"></span>
                    </div>
                    <div class="investors-calc__result-item">
                        <p>Стоимость аренды производственных помещений в год от: </p><span id="production_cost_rent"></span>
                    </div>
                    <div class="investors-calc__result-item">
                        <p>Стоимость аренды ЗУ в год от: </p><span id="land_cost_rent"></span>
                    </div>
                    <div class="investors-calc__result-item">
                        <p>Стоимость выкупа ЗУ в год от: </p><span id="land_cost_buy"></span>
                    </div>
                    <div class="investors-calc__result-item">
                        <p>Минимальный объем капитальных вложений на строительство в год: </p><span id="min_invest"></span>
                    </div>
                    <div class="investors-calc__result-item">
                        <p>Плата за технологическое присоединение к инженерным сетям: </p><span>-</span>
                    </div>
                </div>
            </form>
            <?
			$APPLICATION->IncludeFile($APPLICATION->GetCurDir()."hl_inc.php", Array(), Array(
			    "MODE" => "php",                                       
			    "NAME" => "Опции калькулятора"   
			));			
			?>                   
        </div>
    </div>

    <div class="l-investors__benefits">
        <div class="b-invest-benefits">
            <div class="b-invest-benefits__content">
                <div id="investors-benefits"></div>
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_11"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div class="l-investors__resident"  id="investors-resident">
        <div class="b-invest-resident">
            <div id="investors-resident"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_12"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__documents">
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "docs",
            array(
                "COMPONENT_TEMPLATE" => "docs",
                "SECTION" => "5",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            array()
        ); ?>
    </div>

    <div class="l-investors__department">
        <div class="b-invest-depart">
            <div id="investors-department"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_13"
                )
            ); ?>
        </div>
    </div>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
