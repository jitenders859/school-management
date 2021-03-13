<p><h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Member Details</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">

        <p>
            <label class="w3-text-dark-grey"><b>First Name</b></label>
            <input type="text" name="first_name" value="<?= $first_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter First Name">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Last Name</b></label>
            <input type="text" name="last_name" value="<?= $last_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter Last Name">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Username</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="username" value="<?= $username ?>" class="w3-input w3-border w3-sand" placeholder="Enter Username">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Email</b></label>
            <input type="email" name="email" value="<?= $email ?>" class="w3-input w3-border w3-sand" placeholder="Enter Email">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Mobile Number</b></label>
            <input type="text" name="mobile_number" value="<?= $mobile_number ?>" class="w3-input w3-border w3-sand" placeholder="Enter Mobile Number">
        </p>
        <p>
            <label class="w3-text-dark-grey"><b>Parents ID</b></label>
            <?php
            $attributes['class'] = 'w3-select w3-border w3-sand';
            echo form_dropdown('parents_id', $parents_options, $parents_id, $attributes);
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