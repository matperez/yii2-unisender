<?php
/**
 * Created by PhpStorm.
 * Filename: Response.php
 * User: andrey
 * Date: 01.10.15
 * Time: 18:57
 */

namespace matperez\yii2unisender;

/**
 * Class Response
 * @package matperez\yii2unisender
 */
class Response
{
    /**
     * @var array
     */
    private $apiResponse;

    /**
     * @param array $apiResponse
     */
    public function __construct($apiResponse)
    {
        if (!is_array($apiResponse)) {
            throw new \InvalidArgumentException('API response should be an array!');
        }
        $this->apiResponse = $apiResponse;
    }

    public function getError()
    {
        $code = array_key_exists('code', $this->apiResponse) ? $this->apiResponse['code'] : '';
        $text = array_key_exists('error', $this->apiResponse) ? $this->apiResponse['error'] : '';
        return new Error($text, $code);
    }

    /**
     * @return array
     */
    public function getWarnings()
    {
        if (!array_key_exists('warnings', $this->apiResponse)) {
            return [];
        }
        return array_map(function($warning) {
            return $warning['warning'];
        }, $this->apiResponse['warnings']);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return !array_key_exists('error', $this->apiResponse) && array_key_exists('result', $this->apiResponse);
    }

    /**
     * @return array|string
     */
    public function getResult()
    {
        return $this->isSuccess() && array_key_exists('result', $this->apiResponse) ? $this->apiResponse['result'] : '';
    }

    /**
     * @return array
     */
    public function getApiResponse()
    {
        return $this->apiResponse;
    }
}
