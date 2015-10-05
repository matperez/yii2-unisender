<?php
/**
 * Created by PhpStorm.
 * Filename: TestCase.php
 * User: andrey
 * Date: 05.10.15
 * Time: 21:09
 */

namespace matperez\yii2unisender\tests;

use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Container;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array|string the application configuration that will be used for creating an application instance for each test.
     * You can use a string to represent the file path or path alias of a configuration file.
     * The application configuration array may contain an optional `class` element which specifies the class
     * name of the application instance to be created. By default, a [[\yii\web\Application]] instance will be created.
     */
    public $appConfig = '@tests/config/unit.php';

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->destroyApplication();
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Mocks up the application instance.
     * @param array $config the configuration that should be used to generate the application instance.
     * If null, [[appConfig]] will be used.
     * @return \yii\web\Application|\yii\console\Application the application instance
     * @throws InvalidConfigException if the application configuration is invalid
     */
    protected function mockApplication($config = null)
    {
        Yii::$container = new Container();
        $config = $config === null ? $this->appConfig : $config;
        if (is_string($config)) {
            $configFile = Yii::getAlias($config);
            if (!is_file($configFile)) {
                throw new InvalidConfigException("The application configuration file does not exist: $config");
            }
            $config = require($configFile);
        }
        if (is_array($config)) {
            if (!isset($config['class'])) {
                $config['class'] = 'yii\web\Application';
            }
            return Yii::createObject($config);
        } else {
            throw new InvalidConfigException('Please provide a configuration array to mock up an application.');
        }
    }

    /**
     * Destroys the application instance created by [[mockApplication]].
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }
}
