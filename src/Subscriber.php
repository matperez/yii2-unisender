<?php
/**
 * Created by PhpStorm.
 * Filename: Subscription.php
 * User: andrey
 * Date: 01.10.15
 * Time: 3:26
 */

namespace matperez\yii2unisender;

/**
 * Class Subscriber
 * @see https://support.unisender.com/index.php?/Knowledgebase/Article/View/57/0/subscribe---podpist-drest-n-odin-ili-neskolko-spiskov-rssylki
 * @package matperez\yii2unisender
 */
class Subscriber
{
    /**
     * Request time date format
     */
    const DATETIME_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var array метки, которые добавляются к подписчику.
     */
    private $tags = [];

    /**
     * @var string IP-адрес подписчика, с которого поступила просьба о подписке, в формате «NNN.NNN.NNN.NNN».
     * Можно не указывать в случае подписки через анкету, но без указания действуют более строгие ограничения
     * на количество подписчиков в сутки. IP-адреса из «внутренних» подсетей (например, 192.168.0.1 или 10.0.0.2)
     * не засчитываются.
     */
    private $requestIp;

    /**
     * @var string Дата и время поступления просьбы о подписке в формате «YYYY-MM-DD» или «YYYY-MM-DD hh:mm:ss».
     * Часовой пояс — по Гринвичу (UTC). Если аргумент отсутствует, используются дата и время вызова метода.
     */
    private $requestTime;

    /**
     * @var int Число от 0 до 3 - есть ли подтверждённое согласие подписчика, и что делать, если превышен лимит
     * подписок
     *
     * - Если 0, то мы считаем, что подписчик только высказал желание подписаться, но ещё не подтвердил подписку.
     * В этом случае подписчику будет отправлено письмо-приглашение подписаться. Текст письма будет взят из
     * свойств первого списка из list_ids. Кстати, текст можно поменять с помощью метода updateOptInEmail или
     * через веб-интерфейс.
     * - Если 1, то мы считаем, что у Вас уже есть согласие подписчика. Но при этом для защиты от злоупотреблений
     * есть суточный лимит подписок. Если он не превышен, мы не посылаем письмо-приглашение. Если же он превышен,
     * подписчику высылается письмо с просьбой подтвердить подписку. Текст этого письма можно настроить для каждого
     * списка с помощью метода updateOptInEmail или через веб-интерфейс. Лимиты мы согласовываем в индивидуальном
     * порядке.
     * - Если 2, то также считается, что у Вас согласие подписчика уже есть, но в случае превышения лимита мы
     * возвращаем код ошибки too_many_double_optins.
     * - Если 3, то также считается, что у Вас согласие подписчика уже есть, но в случае превышения лимита подписчик
     * добавляется со статусом «новый».
     */
    private $doubleOptin = 3;

    /**
     * @var string IP-адрес подписчика, с которого поступило подтверждение подписки, в формате "NNN.NNN.NNN.NNN".
     * Имеет смысл только при значении double_optin=1, игнорируется при double_optin=0. Можно не указывать в случае
     * подписки через анкету, но без указания действуют более строгие ограничения на количество подписчиков в сутки.
     */
    private $confirmIp;

    /**
     * @var string Дата и время подтверждения подписки в формате «YYYY-MM-DD» или «YYYY-MM-DD hh:mm:ss».
     * Часовой пояс — по Гринвичу (UTC). Имеет смысл только при значении double_optin=1, игнорируется
     * при double_optin=0. В случае наличия именно это время считается моментом подписки для автоматических
     * серий писем. Если аргумент отсутствует, используются дата и время вызова метода.
     */
    private $confirmTime;

    /**
     * @var int Режим перезаписывания полей и меток, число от 0 до 2 (по умолчанию 0). Задаёт, что делать в случае
     * существования подписчика (подписчик определяется по email-адресу и/или телефону).
     * - Если 0 — происходит только добавление новых полей и меток, уже существующие поля сохраняют своё значение.
     * - Если 1 — все старые поля удаляются и заменяются новыми, все старые метки также удаляются и заменяются новыми.
     * - Если 2 — заменяются значения переданных полей, если у существующего подписчика есть и другие поля, то они
     * сохраняют своё значение. В случае передачи меток они перезаписываются, если же метки не передаются,
     * то сохраняются старые значения меток.
     */
    private $overwrite = 0;

    /**
     * @param string $name
     * @param string $email
     * @param string $phone
     * Обязательно должно присутствовать хотя бы поле «email» или «phone», иначе метод возвратит ошибку.
     * В случае наличия и e-mail, и телефона, подписчик будет включён и в e-mail, и в SMS списки рассылки.
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $email = null, $phone = null)
    {
        $this->name = $name;
        if (!$email && !$phone) {
            throw new \InvalidArgumentException('You must specify either phone or email!');
        }
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addTag($tag)
    {
        if (!array_key_exists($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return implode(',', $this->tags);
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = array_map('trim', explode(',', $tags));
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestIp()
    {
        if (!$this->requestIp) {
            $this->requestIp = \Yii::$app->request->userIP;
        }
        return $this->requestIp;
    }

    /**
     * @param string $requestId
     * @return $this
     */
    public function setRequestIp($requestId)
    {
        $this->requestIp = $requestId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestTime()
    {
        if (!$this->requestTime) {
            $this->requestTime = date(self::DATETIME_FORMAT);
        }
        return $this->requestTime;
    }

    /**
     * @param string $requestTime
     * @return $this
     */
    public function setRequestTime($requestTime)
    {
        $this->requestTime = $requestTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getDoubleOptin()
    {
        return $this->doubleOptin;
    }

    /**
     * @param int $doubleOptin
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setDoubleOptin($doubleOptin)
    {
        if (!in_array($doubleOptin, [0, 1, 2, 3], true)) {
            throw new \InvalidArgumentException(
                sprintf('Argument should be either 0, 1, 2 or 3. %s given.', $doubleOptin)
            );
        }
        $this->doubleOptin = $doubleOptin;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmIp()
    {
        return $this->confirmIp;
    }

    /**
     * @param string $confirmIp
     * @return $this
     */
    public function setConfirmIp($confirmIp)
    {
        $this->confirmIp = $confirmIp;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmTime()
    {
        return $this->confirmTime;
    }

    /**
     * @param string $confirmTime
     * @return $this
     */
    public function setConfirmTime($confirmTime)
    {
        $this->confirmTime = $confirmTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * @param int $overwrite
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setOverwrite($overwrite)
    {
        if (!in_array($overwrite, [0, 1, 2], true)) {
            throw new \InvalidArgumentException(
                sprintf('Argument should be in either 0, 1 or 2. %s given.', $overwrite)
            );
        }
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Subscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Subscriber
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
}
