<?php

require "core/load.php";
require "templates/header.php";
?>

<form action="/submit.php" method="post" id="form">
    <input type="hidden" name="0" value="0" id="group">
    <?php
    $questions = array_reverse($handler->process_questions()); // reverse order so questions are rendered in the correct order
    foreach ($questions as $index => $question) {
        require "templates/question.php";
    }
    ?>
</form>

<?php
require "templates/footer.php";
