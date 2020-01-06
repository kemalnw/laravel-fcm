<?php

namespace Kemalnw\Fcm;

use Illuminate\Support\Facades\Facade;

class FcmFacade extends Facade
{
    public function getFacadeAccessor()
    {
        return 'fcm';
    }
}
