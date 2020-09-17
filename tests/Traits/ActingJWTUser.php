<?php

namespace Tests\Traits;

use App\Models\AdminUser;

trait ActingJWTUser
{
    public function JWTActingAs(AdminUser $adminUser)
    {
        $token = auth('api')->fromUser($adminUser);
        $this->withHeaders(['Authorization' => 'Bearer '.$token]);

        return $this;
    }
}