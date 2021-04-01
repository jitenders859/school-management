<p><h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Subject Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Subject Name</b></label>
            <input type="text" name="subject_name" value="<?= $subject_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter Subject Name">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Subject Intro</b></label>
            <textarea name="subject_intro" class="w3-input w3-border w3-sand" placeholder="Enter Subject Intro here..."><?= $subject_intro ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Date Created</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="date_created" value="<?= $date_created ?>" class="w3-input w3-border w3-sand" placeholder="Enter Date Created">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Classes ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('classes_id', $classes_options, $classes_id, $attributes);
            ?>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Video Lessons ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('video_lessons_id', $video_lessons_options, $video_lessons_id, $attributes);
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