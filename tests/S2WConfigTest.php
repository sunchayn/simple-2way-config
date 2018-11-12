<?php
namespace MazenTouati\Simple2wayConfig;

use PHPUnit\Framework\TestCase;

class S2WConfigTest extends TestCase
{
    public $path;

    public function setUp()
    {
        $this->path =  __DIR__ . '/../demo';
    }

    /**
     * @covers \MazenTouati\Simple2wayConfig\S2WConfigFactory::<public>
     * @covers \MazenTouati\Simple2wayConfig\S2WConfig::<public>
     */
    public function testCore()
    {
        $config = S2WConfigFactory::create($this->path);

        // it implements the configuration interfaces
        $this->assertInstanceOf(S2WConfigInterface::class, $config);
        $this->assertInstanceOf(S2WConfig::class, $config);

        // it can get existing values
        $this->assertSame('mysql', $config->get('database.driver'));
        $this->assertSame('your_host', $config->get('database.drivers.mysql.host'));

        // it returns default value 'null' for values that do not exist
        $this->assertNull($config->get('where.i.can.find.it'));

        // it returns default value 'null' when pass no values after the file name
        $this->assertNull($config->get('only_one_part'));

        // it can set existing values
        $config->set('database.drivers.mysql.password', 'a_secure_password');
        $this->assertSame('a_secure_password', $config->get('database.drivers.mysql.password'));

        // it can set custom values
        $config->set('imaginary.value', true);
        $this->assertTrue($config->get('imaginary.value'));
    }

    /**
     * @covers \MazenTouati\Simple2wayConfig\S2WConfig::sync
     * @covers \MazenTouati\Simple2wayConfig\S2WConfigException::<public>
     */
    public function testSync()
    {

        // it can sync news value with a source file
        $config = S2WConfigFactory::create($this->path);
        $config->set('database.drivers.mysql.password', 'new_password');
        $config->sync('database');
        $config = S2WConfigFactory::create($this->path);
        $this->assertSame('new_password', $config->get('database.drivers.mysql.password'));

        // it can sync all files, i don't include news files to keep the directory clean
        $config->set('database.drivers.mysql.password', 'your_password');
        $config->sync();

        // it throw an Exception when sync backup fail
        $config = S2WConfigFactory::create($this->path);
        // $config->set('custom.key.option', false);
        disableCopy();
        $this->expectException(S2WConfigException::class);
        $config->sync('database');
    }
}


function disableCopy()
{
    global $shouldCopySucced;
    $shouldCopySucced = false;
}

function copy($s, $d)
{
    global $shouldCopySucced;

    if ($shouldCopySucced === true) {
        return \copy($s, $d);
    }

    return false;
}
