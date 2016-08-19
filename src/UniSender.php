<?php
/**
 * Created by PhpStorm.
 * Filename: UniSender.php
 * User: andrey
 * Date: 01.10.15
 * Time: 2:08
 */

namespace matperez\yii2unisender;

use omgdef\unisender\UniSenderWrapper;
use yii\base\Component;

class UniSender extends Component implements UniSenderInterface
{
    /**
     * @var UniSenderWrapper
     */
    private $api;

    /**
     * @var array api wrapper config
     */
    public $apiConfig = [
        'apiKey' => '',
        'testMode' => false,
    ];

    /**
     * Get user fields array
     * @see https://support.unisender.com/index.php?/Knowledgebase/Article/View/76/0/getfields---poluchit-spisok-polzovtelskikh-polejj
     * @return Response
     */
    public function getFields()
    {
        return new Response($this->getApi()->getFields());
    }

    /**
     * @param Subscriber $subscriber
     * @param array $listIds
     * @return Response
     */
    public function unsubscribe(Subscriber $subscriber, array $listIds)
    {
        $response = $this->getApi()->unsubscribe([
            'contact_type' => 'email',
            'contact' => $subscriber->getEmail(),
            'list_ids' => implode(',', $listIds),
        ]);
        return new Response($response);
    }

    /**
     * Subscribe user to a lists
     * @param Subscriber $subscriber
     * @param array $listIds
     * @return Response
     */
    public function subscribe(Subscriber $subscriber, array $listIds)
    {
        $data = [
            'list_ids' => implode(',', $listIds),
            'fields' => [
                'Name' => $subscriber->getName(),
                'email' => $subscriber->getEmail(),
                'phone' => $subscriber->getPhone(),
            ],
            'double_optin' => $subscriber->getDoubleOptin(),
            'overwrite' => $subscriber->getOverwrite(),
        ];
        if ($subscriber->getTags()) {
            $data['tags'] = $subscriber->getTags();
        }
        if ($subscriber->getRequestIp()) {
            $data['request_ip'] = $subscriber->getRequestIp();
        }
        if ($subscriber->getRequestTime()) {
            $data['request_time'] = $subscriber->getRequestTime();
        }
        if (($tags = $subscriber->getTags()) && is_array($tags)) {
            $data['tags'] = implode(',', $tags);
        }
        $apiResponse = $this->getApi()->subscribe($data);
        return new Response($apiResponse);
    }

    /**
     * Get subscription lists
     * @return Response
     * @throws \Exception
     */
    public function getLists()
    {
        return new Response($this->getApi()->getLists());
    }

    /**
     * @return UniSenderWrapper
     */
    public function getApi()
    {
        if (!$this->api) {
            $this->api = $this->createApi();
        }
        return $this->api;
    }

    /**
     * @param UniSenderWrapper $api
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * @return UniSenderWrapper
     */
    protected function createApi()
    {
        $defaults = [
            'senderPhone' => '+380999999999',
            'senderName' => 'Hamster',
            'senderEmail' => 'xxxxxx@gmail.com',
            'testMode' => false,
            'apiKey' => '',
            'encoding' => 'UTF8',
            'timeout' => 10,
            'retryCount' => 0,
        ];
        $config = array_merge($defaults, $this->apiConfig);

        $api = new UniSenderWrapper();
        foreach ($config as $name => $value) {
            $api->$name = $value;
        }
        return $api;
    }
}
