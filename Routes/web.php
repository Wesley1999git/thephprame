<?php
use App\Controllers\HomeController;
use ThePHPrame\Router\Routes;

Routes::get("", HomeController::class, "index");