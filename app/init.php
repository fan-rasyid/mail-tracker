<?php

require_once 'cores/App.php';
require_once 'cores/Controller.php';
require_once 'cores/Database.php';
require_once 'cores/Flasher.php';

require_once 'config/config.php';

// Load all helper classes
foreach (glob(__DIR__ . '/helpers/*.php') as $helper_file) {
    require_once $helper_file;
}
