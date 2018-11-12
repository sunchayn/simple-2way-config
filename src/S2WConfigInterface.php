<?php declare(strict_types=1);

namespace MazenTouati\Simple2wayConfig;

interface S2WConfigInterface
{

    /**
     * get a value from the configuration array using dot-notation path
     *
     * @param  string $path    a dot notation path for the requested value
     * @param  mixed  $default a default value if the path is not resolved
     * @return mixed
     */
    public function get(string $path, $default = null);

    /**
     * set a value in the configuration array using dot-notation
     *
     * @param string $path  a dot notation path for the requested value
     * @param mixed  $value the value to set
     */
    public function set(string $path, $value);

    /**
     * syncs the runtime configuration with the source file
     *
     * @param mixed $specificConfiguration indicate which files to sync => false:
     *  sync all files || string: sync the passed filename.
     */
    public function sync($specificConfiguration = false);

    /**
     * load a configuration from a file into runtime configuration
     *
     * @param  string $fileName
     * @return bool Returns if successfuly import the configuration or not
     */
    public function loadConfigurationFile(string $fileName);
}
