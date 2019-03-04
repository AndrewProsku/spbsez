<?php

namespace Kelnik\Report;

use Kelnik\Helpers\ArrayHelper;

class ReportEnvelope
{
    protected $id = 0;
    protected $data = [];

    public function __construct($id)
    {
        try {

        } catch (\Exception $e) {

        }
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        return ArrayHelper::getValue($this->data, $key);
    }
}
