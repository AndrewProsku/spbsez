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

    abstract public function execute(array $request): bool;
}
