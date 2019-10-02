<?php
date_default_timezone_set($_ENV["TIMEZONE"]);
define("ENCRYPTION_KEY",$_ENV["ENCRYPTION_KEY"]);
define("SECRET_KEY",$_ENV["SECRET_KEY"]);
define("ENCRYPT_COOKIES",($_ENV["ENCRYPT_COOKIES"] == "true")? true : false);
