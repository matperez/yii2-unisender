<?php
/**
 * Created by PhpStorm.
 * Filename: UniSenderInterface.php
 * User: andrey
 * Date: 02.10.15
 * Time: 1:35
 */
namespace matperez\yii2unisender;

use omgdef\unisender\UniSenderWrapper;

/**
 * Interface UniSenderInterface
 * @package matperez\yii2unisender
 */
interface UniSenderInterface
{
    /**
     * @return UniSenderWrapper
     */
    public function getApi();

    /**
     * @param UniSenderWrapper $api
     */
    public function setApi($api);

    /**
     * @param Subscriber $subscriber
     * @param array $listIds
     * @return Response
     */
    public function subscribe(Subscriber $subscriber, array $listIds);

    /**
     * @param Subscriber $subscriber
     * @param array $listIds
     */
    public function unsubscribe(Subscriber $subscriber, array $listIds);

    /**
     * @return Response
     * @throws \Exception
     */
    public function getLists();

    /**
     * Get user fields array
     * @return Response
     */
    public function getFields();
}