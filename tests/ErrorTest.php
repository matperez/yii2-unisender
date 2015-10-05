<?php
/**
 * Created by PhpStorm.
 * Filename: ErrorTest.php
 * User: andrey
 * Date: 05.10.15
 * Time: 21:43
 */

namespace matperez\yii2unisender\tests;

use matperez\yii2unisender\Error;

class ErrorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created()
    {
        self::assertInstanceOf(Error::class, $this->model);
        self::assertNotEmpty($this->model->getCode());
        self::assertNotEmpty($this->model->getMessage());
    }

    /**
     * @var Error
     */
    protected $model;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->model = new Error($this->faker->sentence, $this->faker->word);
    }

}

