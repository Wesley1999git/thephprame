<?php
use App\Controllers\HomeController;
use Library\Routes;

Routes::get("", HomeController::class, "index");
