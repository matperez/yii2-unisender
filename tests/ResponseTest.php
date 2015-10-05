<?php
/**
 * Created by PhpStorm.
 * Filename: ResponseTest.php
 * User: andrey
 * Date: 05.10.15
 * Time: 21:46
 */

namespace matperez\yii2unisender\tests;

use matperez\yii2unisender\Response;

class ResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_return_warnings()
    {
        self::assertEquals([], (new Response([]))->getWarnings());
        self::assertEquals(
            ['one', 'two'],
            (new Response([
                'warnings' => [
                    ['warning' => 'one'],
                    ['warning' => 'two'],
                ]
            ]))->getWarnings()
            );
    }

    /**
     * @test
     */
    public function it_can_return_an_error()
    {
        $response = [
            'error' => $this->faker->sentence(),
            'code' => $this->faker->word,
        ];
        $error = (new Response($response))->getError();
        self::assertEquals($response['error'], $error->getMessage());
        self::assertEquals($response['code'], $error->getCode());
    }

    /**
     * @test
     */
    public function it_can_return_result()
    {
        self::assertEquals('', (new Response([]))->getResult());
        self::assertEquals('test response', (new Response(['result' => 'test response']))->getResult());
    }

    /**
     * @test
     */
    public function it_can_return_api_response()
    {
        self::assertEquals([], (new Response([]))->getApiResponse());
    }

    /**
     * @test
     */
    public function it_should_know_is_it_successful_or_not()
    {
        self::assertFalse((new Response([]))->isSuccess());
        self::assertFalse((new Response(['error' => $this->faker->sentence(), 'result' => '']))->isSuccess());
        self::assertTrue((new Response(['result' => '']))->isSuccess());
    }

    /**
     * @test
     */
    public function it_requires_api_response_to_be_an_array()
    {
        self::setExpectedException(\InvalidArgumentException::class);
        new Response('');
    }

    /**
     * @test
     */
    public function it_can_be_created()
    {
        self::assertInstanceOf(Response::class, $this->model);
    }

    /**
     * @var Response
     */
    protected $model;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->model = new Response([]);
    }

}

