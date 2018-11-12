<?php declare(strict_types=1);

namespace MazenTouati\Simple2wayConfig;

class S2WConfigException extends \RuntimeException
{
    const BACKUP_FAIL = 1;

    public static function backupFail($path): S2WConfigException
    {
        return new static(
            'Configuration sync is unable to save a backup for { '
            . $path . ' }, please check your permissions',
            self::BACKUP_FAIL
        );
    }
}
