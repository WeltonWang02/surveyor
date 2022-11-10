<?php

require "core/load.php";
require "templates/header.php";

if ($_GET['access'] != $config['access']) {
    echo "Access denied.";
    exit;
}

$responses = $handler->get_responses();

foreach ($responses as $response) {
    require "templates/response.php";
}
?>

<?php
require "templates/footer.php";
