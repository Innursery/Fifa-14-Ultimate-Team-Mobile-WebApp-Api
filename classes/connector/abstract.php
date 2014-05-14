<?php

/**
 * Class Connector_Abstract
 */
abstract class Connector_Abstract
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $answerHash;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $platform;

    /**
     * @var Guzzle\Http\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $answer;

    /**
     * @var string
     */
    protected $sid;

    /**
     * @var string
     */
    protected $nucId;

    /**
     * @var string
     */
    protected $phishingToken;

    /**
     * creates a connector with given credentials
     *
     * @param Guzzle\Http\Client $client
     * @param string $email
     * @param string $password
     * @param string $answer
     * @param string $platform
     */
    public function __construct($client, $email, $password, $answer, $platform)
    {
        $this->email = $email;
        $this->password = $password;
        $this->answer = $answer;
        $this->platform = $platform;
        $this->answerHash = EAHashor::hash($answer);
        $this->client = $client;
    }

    /**
     * connects to the api
     *
     * @return $this
     */
    abstract public function connect();

    /**
     * exports the login data
     *
     * @return array
     */
    abstract public function exportLoginData();

    /**
     * initialize a request forge and returns it
     *
     * @param string $url
     * @param string $method
     * @return Request_Forge
     */
    protected function getForge($url, $method)
    {
        return new Request_Forge($this->client, $url, $method);
    }

    /**
     * define setter and getter
     *
     * @param string $method
     * @param array $args
     *
     * @return $this|string|void
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) === 'get') {
            $attr = substr($method, 3);
            if (property_exists(__CLASS__, $attr)) {
                return $attr;
            }
        } elseif (substr($method, 0, 3) === 'set') {
            $attr = substr($method, 3);
            if (property_exists(__CLASS__, $attr) && isset($args[0])) {
                $this->$attr = $args[0];

                return $this;
            }
        }
    }
}