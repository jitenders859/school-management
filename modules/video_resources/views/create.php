<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Video Resource Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Button Title</b></label>
            <input type="text" name="button_title" value="<?= $button_title ?>" class="w3-input w3-border w3-sand" placeholder="Enter Button Title">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Target URL</b></label>
            <input type="text" name="target_url" value="<?= $target_url ?>" class="w3-input w3-border w3-sand" placeholder="Enter Target URL">
        </p>
        <p>
            <label class="w3-text-dark-grey">Downloadable</label>
            <input name="downloadable" class="w3-check" type="checkbox" value="1"<?php if ($downloadable==1) { echo ' checked="checked"'; } ?>>
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