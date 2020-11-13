<?php

use Bitrix\Main\Loader; 

Loader::includeModule("highloadblock"); 

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

$hlbl = 1;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 

$rsData = $entity_data_class::getList(array(
   "select" => array("*"),
   "order" => array("ID" => "ASC"),
   "filter" => array()
));

$arCalcOptions = array();
while($arData = $rsData->Fetch()){
   $arCalcOptions[$arData["UF_KEY"]] = $arData["UF_VALUE"];
}
?>
<script>    
    document.querySelector('#investors-calc').onsubmit = function(){
        sezApp.investCalcOptions = {
            "t1": <?=$arCalcOptions['light_rate']?>,
            "t2": <?=$arCalcOptions['administrative_rate']?>,
            "t3": <?=$arCalcOptions['science_rate']?>,
            "rate1": <?=$arCalcOptions['office_rent_rate']?>,
            "rate2": <?=$arCalcOptions['production_rent_rate']?>,
            "rate3": <?=$arCalcOptions['land_rent_rate']?>,
            "rate4": <?=$arCalcOptions['land_sell_rate']?>
        };
        sezApp.investCalc();
        return false;
    }
</script>