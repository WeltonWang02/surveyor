<?php

require "db.php";
require "bootstrap.php";
require "config.php";
require "handler.php";

// Instantiate database
$db = new DB($config["db"]["host"], $config["db"]["user"], $config["db"]["name"], $config["db"]["pass"]);

// Bootstrap
$bootstrap = new Bootstrap($db);

$handler = new Handler($db);