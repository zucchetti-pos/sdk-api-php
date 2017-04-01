<?php

namespace Compufacil\Service;

class CompufacilException extends \Exception
{
    public static function invalidToken()
    {
        throw new self('Invalid token', 401);
    }
}
