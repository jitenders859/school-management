<p><h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Video Lesson Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Title</b></label>
            <input type="text" name="title" value="<?= $title ?>" class="w3-input w3-border w3-sand" placeholder="Enter Title">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Video Path</b></label>
            <textarea name="video_path" class="w3-input w3-border w3-sand" placeholder="Enter Video Path here..."><?= $video_path ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Video Teaser</b></label>
            <textarea name="video_teaser" class="w3-input w3-border w3-sand" placeholder="Enter Video Teaser here..."><?= $video_teaser ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Synopsis</b></label>
            <textarea name="synopsis" class="w3-input w3-border w3-sand" placeholder="Enter Synopsis here..."><?= $synopsis ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Essentialometer</b></label>
            <input type="text" name="essentialometer" value="<?= $essentialometer ?>" class="w3-input w3-border w3-sand" placeholder="Enter Essentialometer">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Priority</b></label>
            <input type="text" name="priority" value="<?= $priority ?>" class="w3-input w3-border w3-sand" placeholder="Enter Priority">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Code Snippets</b></label>
            <textarea name="code_snippets" class="w3-input w3-border w3-sand" placeholder="Enter Code Snippets here..."><?= $code_snippets ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey">Published</label>
            <input name="published" class="w3-check" type="checkbox" value="1"<?php if ($published==1) { echo ' checked="checked"'; } ?>>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Section</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('sections_id', $sections_options, $sections_id, $attributes);
            ?>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Schools ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('schools_id', $schools_options, $schools_id, $attributes);
            ?>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Classes ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('classes_id', $classes_options, $classes_id, $attributes);
            ?>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Subjects ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('subjects_id', $subjects_options, $subjects_id, $attributes);
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