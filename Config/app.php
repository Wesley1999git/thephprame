<?php
$timezone = isset($_ENV['TIMEZONE']) && !empty($_ENV['TIMEZONE']) ? $_ENV['TIMEZONE'] : "UTC";

date_default_timezone_set($timezone);
define("ENCRYPTION_KEY",$_ENV["ENCRYPTION_KEY"]);
define("SECRET_KEY",$_ENV["SECRET_KEY"]);
define("ENCRYPT_COOKIES",($_ENV["ENCRYPT_COOKIES"] == "true")? true : false);
define("SESSION_DRIVER",$_ENV["SESSION_DRIVER"]);
