<?php

namespace Compufacil;

class CompufacilException extends \Exception
{
    public static function invalidToken()
    {
        throw new self('Invalid token', 401);
    }
}
