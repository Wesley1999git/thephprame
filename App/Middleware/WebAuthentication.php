<?php

namespace App\Middleware;

use App\Exceptions\NotAuthenticated;
use Library\Middleware;
use Library\Request;

class WebAuthentication extends Middleware{

    public function handle(Request $request) {
        throw new NotAuthenticated();
    }

}
