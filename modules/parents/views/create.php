<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Parent Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>Email Address</b></label>
            <input type="email" name="email_address" value="<?= $email_address ?>" class="w3-input w3-border w3-sand" placeholder="Enter Email Address">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Mobile Number</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="mobile_number" value="<?= $mobile_number ?>" class="w3-input w3-border w3-sand" placeholder="Enter Mobile Number">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Code</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="code" value="<?= $code ?>" class="w3-input w3-border w3-sand" placeholder="Enter Code">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Occupation</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="occupation" value="<?= $occupation ?>" class="w3-input w3-border w3-sand" placeholder="Enter Occupation">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Child ID</b></label>
            <input type="text" name="child_id" value="<?= $child_id ?>" class="w3-input w3-border w3-sand" placeholder="Enter Child ID">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Member ID</b></label>
            <input type="text" name="member_id" value="<?= $member_id ?>" class="w3-input w3-border w3-sand" placeholder="Enter Member ID">
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