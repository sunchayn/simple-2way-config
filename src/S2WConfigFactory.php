<?php declare(strict_types=1);

namespace MazenTouati\Simple2wayConfig;

class S2WConfigFactory
{
    public static function create($directory = '')
    {
        return new S2WConfig($directory);
    }
}
