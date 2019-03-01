<?php

namespace Kelnik\Multisites\Settings;

/**
 * Class CurrentSite
 *
 * @package Kelnik\Multisites\Settings
 */
class CurrentSite
{
    /**
     * CurrentSite instance
     *
     * @var null
     */
    protected static $instance = null;

    private $data = [];

    /**
     * Creates new site instance.
     */
    protected function __construct()
    {
        if (!isset(static::$instance)) {
            static::$instance = $this;
        }
        return static::$instance;
    }

    /**
     * Returns current instance of the CurrentSite.
     *
     * @return null
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new CurrentSite();
        }

        return static::$instance;
    }

    public function getData()
    {
        if (!$this->data) {
            $this->data = SitesTable::getCurrentSite();
            if ($this->getField('PHONE')) {
                $this->data['PHONE_F'] = preg_replace("/[^0-9]/", '', $this->getField('PHONE'));
                $this->data['PHONE_F'] = '+7' . substr($this->data['PHONE_F'], -10);
            }
        }
        return $this->data;
    }

    public function getField($name)
    {
        $upName = mb_strtoupper($name);
        if (isset($this->data[$upName])) {
            return $this->data[$upName];
        }
        return false;
    }
}
