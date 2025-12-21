<?php
use App\Controllers\HomeController;
use ThePHPrame\Core\Library\Routes;

Routes::get("", HomeController::class, "index");
