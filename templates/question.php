<div class="question" id="item-<?php echo $index ?>">
    <div class="question__content">
        <h2><?php echo $question['text']; ?></h2>
    </div>
    <div name="<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>" class="question__answers" onclick="document.getElementById('next-<?php echo $index; ?>').disabled = false;">

        <?php
        $answers = (!empty($question['answers'])) ? json_decode($question['answers'], true) : ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
        foreach ($answers as $aindex => $answer) {
        ?>
            <label class="question__choice">
                <input type="radio" name="<?php echo $question['id']; ?>" value="<?php echo $aindex + 1; ?>"><?php echo $answer; ?>
            </label>
        <?php
        }
        ?>

    </div>

    <button class="question__next" id="next-<?php echo $index; ?>" disabled type="button" onclick="document.getElementById('item-<?php echo $index; ?>').style.display = 'none';">Next</button>
</div>