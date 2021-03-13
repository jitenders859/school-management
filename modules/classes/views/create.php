<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Class Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Class Title</b></label>
            <input type="text" name="class_title" value="<?= $class_title ?>" class="w3-input w3-border w3-sand" placeholder="Enter Class Title">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Introduction</b></label>
            <textarea name="introduction" class="w3-input w3-border w3-sand" placeholder="Enter Introduction here..."><?= $introduction ?></textarea>
        </p>
        <p> 
            <?php 
            $attributes['class'] = 'w3-button w3-white w3-border';
            echo anchor($cancel_url, 'CANCEL', $attributes);
            ?> 
            <button type="submit" name="submit" value="Submit" class="w3-button w3-medium primary"><?= $btn_text ?></button>
        </p>
    </form>
</div>

<script>
$('.datepicker').datepicker();
$('.datetimepicker').datetimepicker({
    separator: ' at '
});
$('.timepicker').timepicker();
</script>