<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Teacher Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>ID No</b></label>
            <input type="text" name="id_no" value="<?= $id_no ?>" class="w3-input w3-border w3-sand" placeholder="Enter ID No">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Class ID</b></label>
            <input type="text" name="class_id" value="<?= $class_id ?>" class="w3-input w3-border w3-sand" placeholder="Enter Class ID">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Section ID</b></label>
            <input type="text" name="section_id" value="<?= $section_id ?>" class="w3-input w3-border w3-sand" placeholder="Enter Section ID">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Code</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="code" value="<?= $code ?>" class="w3-input w3-border w3-sand" placeholder="Enter Code">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Member ID</b></label>
            <input type="text" name="member_id" value="<?= $member_id ?>" class="w3-input w3-border w3-sand" placeholder="Enter Member ID">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Subject ID</b></label>
            <input type="text" name="subject_id" value="<?= $subject_id ?>" class="w3-input w3-border w3-sand" placeholder="Enter Subject ID">
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