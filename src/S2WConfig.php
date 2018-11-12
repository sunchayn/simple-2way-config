<?php declare(strict_types=1);

namespace MazenTouati\Simple2wayConfig;

class S2WConfig implements S2WConfigInterface
{

    /**
     * a directory where the configuration files are served.
     *
     * @var string
     */
    private $directory = '';

    /**
     * a runtime instance of the configuration
     *
     * @var array
     */
    private $config = [];

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function get(string $path, $default = null)
    {

        // when the path is empty or has only one part
        $explodedPath = explode('.', $path, 2);
        if (count($explodedPath) != 2) {
            return $default;
        }

        list($fileName, $valuePath) = $explodedPath;

        if (!isset($this->config[$fileName])) {
            $this->config[$fileName] = $this->loadConfigurationFile($fileName);
        }

        $resolvedValue = $this->config[$fileName];

        foreach (explode('.', $valuePath) as $key) {
            if (isset($resolvedValue[$key])) {
                $resolvedValue = $resolvedValue[$key];
            } else {
                $resolvedValue = $default;
                break;
            }
        }

        return $resolvedValue;
    }

    public function set(string $path, $value)
    {
        list($fileName, $valuePath) = explode('.', $path, 2);
        if (!isset($this->config[$fileName])) {
            $this->config[$fileName] = $this->loadConfigurationFile($fileName);
        }

        $reference = &$this->config[$fileName];

        foreach (explode('.', $valuePath) as $key) {
            if (!isset($reference[$key])) {
                $reference[$key] = [];
            }

            $reference = &$reference[$key];
        }

        $reference = $value;
    }

    public function sync($specificConfiguration = false)
    {
        $toSync = [];

        if ($specificConfiguration !== false) {
            $toSync[$specificConfiguration] = &$this->config[$specificConfiguration];
        } else {
            $toSync = &$this->config;
        }

        foreach ($toSync as $fileName => $configuration) {
            $filePath = $this->directory . DIRECTORY_SEPARATOR .  $fileName;
            if (is_file("{$filePath}.php")
            && !copy("{$filePath}.php", "{$filePath}.backup.php")) {
                throw S2WConfigException::backupFail("{$filePath}.php");
            }

            $content = "<?php " . PHP_EOL
            ."return ".varexport($toSync[$fileName], true).';'
            .PHP_EOL;

            file_put_contents("{$filePath}.php", $content);
        }
    }

    public function loadConfigurationFile(string $fileName)
    {
        $filePath = $this->directory . DIRECTORY_SEPARATOR .  $fileName . '.php';
        return is_file($filePath) ? include $filePath : [];
    }
}
