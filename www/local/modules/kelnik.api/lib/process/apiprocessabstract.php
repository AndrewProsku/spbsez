<?php

namespace Kelnik\Api\Process;

abstract class ApiProcessAbstract
{
    protected $data;
    protected $errors;

    public function __construct()
    {
        return true;
    }

    public function getResult(array $request): array
    {
        if (method_exists($this, 'execute')) {
            $this->execute($request);
        }

        return [
            'data' => $this->data,
            'errors' => $this->errors
        ];
    }

    protected function initComponent($componentName, $arParams, $tmpl = false): \CBitrixComponent
    {
        $className = \CBitrixComponent::includeComponentClass($componentName);

        /* @var \CBitrixComponent */
        $component = new $className();

        $component->initComponent($componentName, $tmpl);
        $component->onIncludeComponentLang();
        $component->arParams = $component->onPrepareComponentParams($arParams);
        $component->__prepareComponentParams($component->arParams);

        return $component;
    }

    abstract public function execute(array $request): bool;
}
