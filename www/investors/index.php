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
                    <div class="investors-calc__fields-subtitle">Выберите, что необходимо Вам  для реализации проекта</div>
	                <label for="offices">
                        <input type="checkbox" data-name="offices">
                        <span>Аренда офисов</span>
	                    <input type="text" disabled id="offices" name="offices" class="investors-calc__fields-field" placeholder="Площадь офиса, кв.м.">
	                </label>
	                <label for="production">
                        <input type="checkbox" data-name="production">
                        <span>Аренда производственных помещений</span>
	                    <input type="text" disabled id="production" name="production" class="investors-calc__fields-field" placeholder="Площадь производства, кв.м.">
	                </label>
	                <label for="land">
                        <input type="checkbox" data-name="land" onchange="sezApp.showLandRelated(this)">
                        <span>Аренда земельного участка</span>
	                    <input type="text" disabled id="land" name="land" class="investors-calc__fields-field" placeholder="Площадь земельного участка, Га">
	                </label>
	                <p class="investors-calc__subtitle land-related">
	                	Планируемые объекты капитального строительства общей площадью не меньше 40% площади земельного участка (1га = 10000кв.м.):
	                </p>
	                <label for="light" class="land-related">
                        <input type="checkbox" data-name="light">
                        <span>Из легковозводимых конструкций</span>
	                    <input type="text" disabled id="light" name="light" class="investors-calc__fields-field" placeholder="Площадь производства, кв.м.">
	                </label>
	                <label for="administrative" class="land-related">
                        <input type="checkbox" data-name="administrative">
                        <span>Административно-производственный корпус</span>
	                    <input type="text" disabled id="administrative" name="administrative" class="investors-calc__fields-field" placeholder="Площадь производства, кв.м.">
	                </label>
	                <label for="science" class="land-related">
                        <input type="checkbox" data-name="science">
                        <span>Научно-производственный объект высокой сложности</span>
	                    <input type="text" disabled id="science" name="science" class="investors-calc__fields-field" placeholder="Площадь производства, кв.м.">
	                </label>
	                <label for="full_area" class="land-related">
                        <input type="checkbox" data-name="full_area" checked disabled>
                        <span class="investors-calc__utilities">Подключение к инженерным сетям</span>
	                    <span class="investors-calc__utilities-description">Технологическое присоединение к инженерным сетям осуществляется <b>без отдельной платы за присоединение</b></span>
	                </label>
	                <p class="investors-calc__error"></p>
	                <button type="submit" class="investors-calc__fields-button button">Рассчитать</button>
            	</div>
                <div class="investors-calc__result">
                    <h4>Затраты на реализацию проекта включают:</h4>
                    <div class="investors-calc__result-item _hidden">
                        <p>Стоимость аренды офиса от: </p><span id="office_cost_rent"></span><span class="investors-calc__result-measure">млн руб/год</span>
                    </div>
                    <div class="investors-calc__result-item _hidden">
                        <p>Стоимость аренды производственных помещений от: </p><span id="production_cost_rent"></span><span class="investors-calc__result-measure">млн руб/год</span>
                    </div>
                    <div class="investors-calc__result-item _hidden">
                        <p>Стоимость аренды ЗУ от: </p><span id="land_cost_rent"></span><span class="investors-calc__result-measure">млн руб/год</span>
                    </div>
                    <div class="investors-calc__result-item _hidden">
                        <p>Стоимость выкупа ЗУ от: </p><span id="land_cost_buy"></span><span class="investors-calc__result-measure">млн руб</span>
                    </div>
                    <div class="investors-calc__result-item _hidden">
                        <p>Минимальный объем капитальных вложений на строительство: </p><span id="min_invest"></span><span class="investors-calc__result-measure">млн руб</span>
                    </div>
                    <div class="investors-calc__result-item">
                        <p>Плата за технологическое присоединение к инженерным сетям: </p><span>-</span>
                    </div>                    
                </div>
                <a href="" class="investors-calc__fields-button button j-message-button open-calcres" data-href="#calcres">Отправить результат расчета на e-mail</a>
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
