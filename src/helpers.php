<?php declare(strict_types=1);

namespace MazenTouati\Simple2wayConfig;

/*
* copyright disclaimer: orignally written by stemar:
* [ https://gist.github.com/stemar/bb7c5cd2614b21b624bf57608f995ac0 ]
*/

/**
 * Alternative to var_export that exports an array to short array syntax
 * @param  mixed $expression variable to export
 * @param  boolean $return whenever to return or print the result
 * @return string|void
 */
function varexport($expression, $return = false)
{
    $export = var_export($expression, true);
    $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
    $array = preg_split("/\r\n|\n|\r/", $export);

    $array = preg_replace(
        ["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"],
        [null, ']$1', ' => ['],
        $array
    );

    $export = join(PHP_EOL, array_filter(["["] + $array));

    if ((bool)$return) {
        return $export;
    } else {
        echo $export;
    }
}
