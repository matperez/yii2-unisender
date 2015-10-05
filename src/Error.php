<?php
/**
 * Created by PhpStorm.
 * Filename: Error.php
 * User: andrey
 * Date: 02.10.15
 * Time: 1:46
 */

namespace matperez\yii2unisender;

class Error
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $text
     * @param string $code
     */
    public function __construct($text = '', $code = '')
    {
        $this->message = $text;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
