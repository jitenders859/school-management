<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>School Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">
        <p>
            <label class="w3-text-dark-grey"><b>School Name</b> </label>
            <input type="text" name="school_name" value="<?= $school_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter School Name">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Mobile Number</b></label>
            <input type="text" name="mobile_number" value="<?= $mobile_number ?>" class="w3-input w3-border w3-sand" placeholder="Enter Mobile Number">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Telephone Number</b></label>
            <input type="text" name="telephone_number" value="<?= $telephone_number ?>" class="w3-input w3-border w3-sand" placeholder="Enter Telephone Number">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Email Address</b> </label>
            <input type="email" name="your_email_address" value="<?= $your_email_address ?>" class="w3-input w3-border w3-sand" placeholder="Enter Email Address">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Address Line 1</b></label>
            <input type="text" name="address_line_1" value="<?= $address_line_1 ?>" class="w3-input w3-border w3-sand" placeholder="Enter Address Line 1">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>City</b> </label>
            <input type="text" name="city" value="<?= $city ?>" class="w3-input w3-border w3-sand" placeholder="Enter City">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>State / Province / Region</b> </label>
            <input type="text" name="state__province__region" value="<?= $state__province__region ?>" class="w3-input w3-border w3-sand" placeholder="Enter State / Province / Region">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Zip / Postal Code</b> </label>
            <input type="text" name="zip__postal_code" value="<?= $zip__postal_code ?>" class="w3-input w3-border w3-sand" placeholder="Enter Zip / Postal Code">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Country</b> </label>
            <input type="text" name="country" value="<?= $country ?>" class="w3-input w3-border w3-sand" placeholder="Enter Country">
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