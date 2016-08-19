<?php
/**
 * Created by PhpStorm.
 * Filename: UniSenderTest.php
 * User: andrey
 * Date: 05.10.15
 * Time: 22:04
 */

namespace matperez\yii2unisender\tests;

use matperez\yii2unisender\Response;
use matperez\yii2unisender\Subscriber;
use matperez\yii2unisender\UniSender;
use omgdef\unisender\UniSenderWrapper;

class UniSenderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_new_api_instance()
    {
        $model = new UniSender();
        self::assertInstanceOf(UniSenderWrapper::class, $model->getApi());
    }

    public function it_can_unsubsribe_clients()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $subscriber = new Subscriber($this->faker->name, $this->faker->email, $this->faker->phoneNumber);
        $subscriber->setTags('a,b');

        $data = [
            'client_type' => 'email',
            'client' => $subscriber->getEmail(),
        ];

        $expectation = [
            'result' => '',
        ];

        $this->api->shouldReceive('subscribe')->with(\Mockery::subset($data))->andReturn($expectation);

        $response = $this->model->unsubscribe($subscriber, [1111, 2222]);

        self::assertEquals($expectation, $response->getApiResponse());
    }

    /**
     * @test
     */
    public function it_can_subscribe_someone_to_a_list()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $subscriber = new Subscriber($this->faker->name, $this->faker->email, $this->faker->phoneNumber);
        $subscriber->setTags('a,b');

        $data = [
            'fields' => [
                'Name' => $subscriber->getName(),
                'email' => $subscriber->getEmail(),
                'phone' => $subscriber->getPhone()
            ],
            'tags' => 'a,b',
            'request_ip' => '127.0.0.1',
            'request_time' => date('Y-m-d'),
            'list_ids' => '1111,2222',
            'double_optin' => 3,
            'overwrite' => 0
        ];

        $expectation = [
            'result' => [
                'person_id' => 2500767342
            ],
        ];

        $this->api->shouldReceive('subscribe')->with(\Mockery::subset($data))->andReturn($expectation);

        $response = $this->model->subscribe($subscriber, [1111, 2222]);

        self::assertEquals($expectation, $response->getApiResponse());
    }

    /**
     * @test
     */
    public function it_can_return_user_fields_list()
    {
        $data = ['result' => [
            [
                'id' => $this->faker->numberBetween(),
                'name' => 'gender',
                'public_name' => 'Пол',
                'type' => 'string',
                'is_visible' => 1,
                'view_pos' => 1,
            ],
            [
                'id' => $this->faker->numberBetween(),
                'name' => 'Name',
                'public_name' => 'Имя',
                'type' => 'string',
                'is_visible' => 1,
                'view_pos' => 1,
            ],
        ]];
        $this->api->shouldReceive('getFields')->andReturn($data);
        self::assertEquals($data['result'], $this->model->getFields()->getResult());
    }

    /**
     * @test
     */
    public function it_can_return_subscriptions_list()
    {
            $data = ['result' => [
                ['id' => 4880702, 'title' => 'EDU-общий'],
                ['id' => 4336971, 'title' => 'edutravel'],
                ['id' => 5506014, 'title' => 'Litmus-test'],
            ]];

            $this->api->shouldReceive('getLists')->andReturn($data, null);

            $response = $this->model->getLists();

            self::assertInstanceOf(Response::class, $response);
            self::assertTrue($response->isSuccess());
            self::assertEquals($data['result'], $response->getResult());
    }

    /**
     * @test
     */
    public function it_can_be_created()
    {
        self::assertInstanceOf(UniSender::class, $this->model);
    }

    /**
     * @var UniSender
     */
    protected $model;

    /**
     * @var UniSenderWrapper|\Mockery\Mock
     */
    protected $api;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->api = \Mockery::mock(UniSenderWrapper::class);
        $this->model = new UniSender([
            'api' => $this->api
        ]);
    }

}

