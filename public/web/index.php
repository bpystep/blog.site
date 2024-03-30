<?php

require(__DIR__ . '/../../vendor/autoload.php');

$isSafe = false; //if want use php:getenv(), set true
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/..', ['.env.local', '.env'], false);
$isSafe ? $dotenv->safeLoad() : $dotenv->load();

use common\components\Environment as Env;
$env = new Env(dirname(__DIR__) . '/config', $isSafe, true, false);
$env->setup();

$dotenv->required(['YII_DEBUG', 'YII_ENV', 'APP_MAINTENANCE', 'COOKIE_VALIDATION_KEY'])->notEmpty();
$dotenv->required(['DB_HOSTNAME', 'DB_PORT', 'DB_NAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_CHARSET'])->notEmpty();
$dotenv->required(['REDIS_HOSTNAME', 'REDIS_PORT', 'REDIS_PASSWORD'])->notEmpty();
$dotenv->required(['ROBOTS'])->isBoolean();

(new \public\components\Application($env->web))->run();
