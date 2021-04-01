<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Books Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Book Name</b></label>
            <input type="text" name="book_name" value="<?= $book_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter Book Name">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Subject</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="subject" value="<?= $subject ?>" class="w3-input w3-border w3-sand" placeholder="Enter Subject">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Writter Name</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="writter_name" value="<?= $writter_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter Writter Name">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>ID No</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="id_no" value="<?= $id_no ?>" class="w3-input w3-border w3-sand" placeholder="Enter ID No">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Published Date</b></label>
            <input type="text" name="published_date" value="<?= $published_date ?>" class="w3-input w3-border w3-sand" placeholder="Enter Published Date">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Classes ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('classes_id', $classes_options, $classes_id, $attributes);
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