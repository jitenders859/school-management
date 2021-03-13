<h1>Create Welcome Email</h1>
<?= validation_errors() ?>
<div class="w3-card-4">
    <div class="w3-container primary">
        <h4>Customer Name</h4>
    </div>
    <form class="w3-container" action="<?= $form_location ?>" method="post">
        <p>
            <label class="w3-text-dark-grey"><b>Customer Name</b> <span class="w3-text-green">(optional)</span></label>
            <input type="text" name="customer_name" value="<?= $customer_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter Customer Name">
        </p>
        <p>
            <?php
            $attributes['class'] = 'w3-button w3-white w3-border';
            echo anchor($cancel_url, 'CANCEL', $attributes);
            ?>
            <button type="submit" name="submit" value="Submit" class="w3-button w3-medium primary">Send</button>
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