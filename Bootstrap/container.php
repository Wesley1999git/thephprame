<?php

use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAttributes(true);
$builder->addDefinitions(ROOT_FOLDER."//Config//di.php");

$container = $builder->build();