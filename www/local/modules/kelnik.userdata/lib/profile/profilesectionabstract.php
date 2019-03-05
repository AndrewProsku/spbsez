<?php

namespace Kelnik\Userdata\Profile;


abstract class ProfileSectionAbstract
{
    public const MODULE_ID = 'kelnik.userdata';

    protected $profile;
    protected $lastError = '';

    public function __construct(Profile &$profile)
    {
        $this->profile = $profile;
    }

    public function getLastError()
    {
        return $this->lastError;
    }
}
