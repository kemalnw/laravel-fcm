<?php

namespace Kemalnw\Fcm;

use Kemalnw\Fcm\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Fcm
{
    /**
     * Constant
     */
    const PRIORITY_HIGH = 'high';
    const PRIORITY_NORMAL = 'normal';

    /**
     * @var Client
     */
    protected $httpRequest;

    /**
     * @var string|array
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $notification;

    /**
     * @var int
     */
    protected $timeToLive;

    /**
     * @var string  normal|high
     */
    protected $priority;

    /**
     * Constructor
     */
    public function __construct()
    {
        $serverKey = config('fcm.server_key');
        $this->httpRequest = new Client([
            'base_uri'=> 'https://fcm.googleapis.com',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=' . $serverKey,
            ],
        ]);
        /**
         * Set Default Value
         */
        $this->timeToLive = 0;
        $this->priority   = self::PRIORITY_HIGH;
        $this->data       = [];
        $this->notification = [];
    }

    /**
     * @param string|array $recipients
     * @return $this
     */
    public function to($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * @param string $topic
     * @return $this
     */
    public function toTopic(string $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function data(array $data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $notification
     * @return $this
     */
    public function notification(array $notification = [])
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * @param string $priority
     * @return $this
     */
    public function priority(string $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param integer $timeToLive
     * @return $this
     */
    public function timeToLive(int $timeToLive)
    {
        if ($timeToLive < 0) {
            $timeToLive = 0;
        }
        /**
         * Maximum value is 4 weeks
         */
        if ($timeToLive > 2419200) {
            $timeToLive = 2419200; // (28 days)
        }
        $this->timeToLive = $timeToLive;

        return $this;
    }

    /**
     * Retrieve recipient data
     *
     * @return array|null
     */
    public function getRecipients()
    {
        if ($this->topic) {
            return ['to' => "/topics/{$this->topic}"];
        }

        if (empty($this->recipients)) {
            return null;
        } elseif (is_array($this->recipients)) {
            return ['registration_ids'  => $this->recipients];
        } else {
            return ['to' => $this->recipients];
        }
    }

    /**
     * @return void
     */
    public function send()
    {
        $payloads = array_merge([
            'content_available' => true,
            'priority'          => $this->priority,
            'notification'      => $this->notification,
            'time_to_live'      => (int) $this->timeToLive
        ], $this->getRecipients());

        if (! empty($this->data)) {
            $payloads['data'] = $this->data;
        }

        try {
            $response = $this->httpRequest
                ->post('/fcm/send', ['json' => $payloads]);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            throw (new RequestException('Invalid request options.'))
                ->setResponse($e->getResponse());
        }
    }
}
