<!-- <form method="post" enctype="multipart/form-data">
    <p><input type="text" name="title" placeholder="Enter Video Title" /></p>
    <p><textarea name="summary" cols="30" rows="10" placeholder="Video description"></textarea></p>
    <p><input type="file" name="file"/></p>
    <p>
        <label>Image</label>
        <input type="file" name="image" accept="image/*" />
    </p>
    <input type="submit" name="submit" value="Submit" />

</form> -->

<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Upload Video</h4>
    </div>
    <form class="w3-container"  enctype="multipart/form-data" action="<?= $form_location ?>" method="post">
      <p>
            <label class="w3-text-dark-grey"><b>Enter Video Title</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="title" value="<?= $title ?>" class="w3-input w3-border w3-sand" placeholder="Enter Video Title">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Video Description</b> <span class="w3-text-green">(optional)</span></label>
            <textarea name="description" cols="30" rows="10" placeholder="Video description"><?= $description ?></textarea>
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>File</b></label>
            <input type="file" name="file"/>
        </p>
        <p>
            <?php
            $attributes['class'] = 'w3-button w3-white w3-border';
            echo anchor($cancel_url, 'CANCEL', $attributes);
            ?>
            <button type="submit" name="submit" value="Submit" class="w3-button w3-medium primary">Submit</button>
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