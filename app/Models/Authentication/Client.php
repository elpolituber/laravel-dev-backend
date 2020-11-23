<?php

namespace App\Models\Authentication;

use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    public function username()
    {
        return 'username';
    }
}
