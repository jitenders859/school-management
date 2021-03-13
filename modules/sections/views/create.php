<p><h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Section Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Title</b></label>
            <input type="text" name="title" value="<?= $title ?>" class="w3-input w3-border w3-sand" placeholder="Enter Title">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Introduction</b></label>
            <textarea name="introduction" class="w3-input w3-border w3-sand" placeholder="Enter Introduction here..."><?= $introduction ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Priority</b></label>
            <input type="text" name="priority" value="<?= $priority ?>" class="w3-input w3-border w3-sand" placeholder="Enter Priority">
        </p>
        <p>
            <label class="w3-text-dark-grey">Published</label>
            <input name="published" class="w3-check" type="checkbox" value="1"<?php if ($published==1) { echo ' checked="checked"'; } ?>>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Section Types ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('section_types_id', $section_types_options, $section_types_id, $attributes);
            ?>
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