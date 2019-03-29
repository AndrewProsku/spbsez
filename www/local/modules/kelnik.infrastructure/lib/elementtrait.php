<?php

namespace Kelnik\Infrastructure;

use Kelnik\Helpers\ArrayHelper;

trait ElementTrait
{
    protected function getElementUrl(array $element)
    {
        return $this->arParams['SEF_FOLDER'] .
            str_replace(
                [
                    '#ELEMENT_ID#',
                    '#ELEMENT_CODE#'
                ],
                [
                    $element['ID'],
                    $element['ALIAS']
                ],
                ArrayHelper::getValue(
                    $this->getParent()->arParams,
                    'SEF_URL_TEMPLATES.detail'
                )
            );
    }
}
