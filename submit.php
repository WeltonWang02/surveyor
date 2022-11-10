<?php

require "core/load.php";
require "templates/header.php";

if (isset($_POST["0"])) {
    $data = $handler->validate_submission($_POST);
    $handler->store_submission($data);
    unset ($_POST);
}

?>
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<div class="submission__message">
    <h1>Thank you for your submission!</h1>
    <p>Your answers have been recorded.</p>
</div>

<?php
require "templates/footer.php";
