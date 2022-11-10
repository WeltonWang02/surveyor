<div class="response__container">
    <?php foreach ($response['items'] as $item) { ?>
        <div class="response__question">
            <h2><?php echo ucfirst($item['label']) . ": " . $item['question'] ?></h2>
            <p><?php echo $item['answer'] ?></p>
        </div>
    <?php } ?>
    <div class="response__question">
        <h2>Group:</h2>
        <p><?php echo $response['group']; ?></p>
    </div>
</div>