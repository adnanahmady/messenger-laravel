<?php

namespace App\Traits;

trait TokenKey
{
    /**
     * gets json web token (jwt) and returns
     * last part of token that is user token
     * for authorize user
     *
     * @param $token
     *
     * @return mixed
     */
    public function tokenKey($token)
    {
        $splitToken = explode('.', $token);

        return end($splitToken);
    }
}
