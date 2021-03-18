<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAuthIdentifierName()
    {
        return null;
    }

    public function getAuthIdentifier()
    {
        return $this->attributes;
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        return '';
    }

    public function getRememberTokenName()
    {
        return '';
    }
}
