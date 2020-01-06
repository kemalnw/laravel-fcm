<?php

namespace Kemalnw\Fcm\Exception;

class RequestException extends FcmException
{
    private $response;

    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
