<?php
/**
 * Created by PhpStorm.
 * Filename: SubscriberTest.php
 * User: andrey
 * Date: 05.10.15
 * Time: 21:09
 */

namespace matperez\yii2unisender\tests;

use matperez\yii2unisender\Subscriber;

class SubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_subscription_tags_serialized()
    {
        self::assertEquals('', $this->model->getTags());

        $this->model->setTags('a,b');
        self::assertEquals('a,b', $this->model->getTags());

        $this->model->addTag('c');
        self::assertEquals('a,b,c', $this->model->getTags());
    }

    /**
     * @test
     */
    public function it_should_return_current_request_date_if_not_set()
    {
        self::assertEquals(date('Y-m-d'), $this->model->getRequestTime());
    }

    /**
     * @test
     */
    public function it_should_return_default_request_ip_if_not_set()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        self::assertEquals('127.0.0.1', $this->model->getRequestIp());
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_ther_is_no_email_nor_phone_number()
    {
        self::setExpectedException(\InvalidArgumentException::class);
        new Subscriber($this->faker->name);
    }

    /**
     * @test
     */
    public function it_can_be_created()
    {
        self::assertInstanceOf(Subscriber::class, $this->model);
        self::assertNotEmpty($this->model->getName());
        self::assertNotEmpty($this->model->getEmail());
        self::assertNotEmpty($this->model->getPhone());
        self::assertEquals(3, $this->model->getDoubleOptin());
        self::assertEquals(0, $this->model->getOverwrite());
    }

    /**
     * @var Subscriber
     */
    private $model;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->model = new Subscriber($this->faker->name, $this->faker->email, $this->faker->phoneNumber);
    }
}

