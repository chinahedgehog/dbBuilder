#!/bin/env php
<?php
define('DB_BUILDER_APP_PATH', __DIR__);
include (DB_BUILDER_APP_PATH . "/Library/DbBuilder.class.php");
\DbBuilder\Library\DbBuilder::$argv = $argv;
\DbBuilder\Library\DbBuilder::init();
\DbBuilder\Console\Main::run();

