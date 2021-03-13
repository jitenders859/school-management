<section class="neon">
	<div class="container paper">
        <div class="">
    		<div class="stage">
				<h1>Create New School</h1>
				<?= validation_errors() ?>
				<form class="form-vertical" action="<?= $form_location ?>" method="post">
					<fieldset>
						<label class="w3-text-dark-grey"><b>School Name</b> </label>
						<input type="text" name="school_name" value="<?= $school_name ?>" class="w3-input w3-border w3-sand" placeholder="Enter School Name">
						<label class="w3-text-dark-grey"><b>Mobile Number</b></label>
						<input type="text" name="mobile_number" value="<?= $mobile_number ?>" class="w3-input w3-border w3-sand" placeholder="Enter Mobile Number">
						<label class="w3-text-dark-grey"><b>Telephone Number</b></label>
						<input type="text" name="telephone_number" value="<?= $telephone_number ?>" class="w3-input w3-border w3-sand" placeholder="Enter Telephone Number">
						<label class="w3-text-dark-grey"><b>Email Address</b> </label>
						<input type="email" name="your_email_address" value="<?= $your_email_address ?>" class="w3-input w3-border w3-sand" placeholder="Enter Email Address">
						<label class="w3-text-dark-grey"><b>Address Line 1</b></label>
						<input type="text" name="address_line_1" value="<?= $address_line_1 ?>" class="w3-input w3-border w3-sand" placeholder="Enter Address Line 1">
						<label class="w3-text-dark-grey"><b>City</b> </label>
						<input type="text" name="city" value="<?= $city ?>" class="w3-input w3-border w3-sand" placeholder="Enter City">
						<label class="w3-text-dark-grey"><b>State / Province / Region</b> </label>
						<input type="text" name="state__province__region" value="<?= $state__province__region ?>" class="w3-input w3-border w3-sand" placeholder="Enter State / Province / Region">
						<label class="w3-text-dark-grey"><b>Zip / Postal Code</b> </label>
						<input type="text" name="zip__postal_code" value="<?= $zip__postal_code ?>" class="w3-input w3-border w3-sand" placeholder="Enter Zip / Postal Code">
						<label class="w3-text-dark-grey"><b>Country</b> </label>
						<input type="text" name="country" value="<?= $country ?>" class="w3-input w3-border w3-sand" placeholder="Enter Country">
						<button type="submit" name="submit" value="Submit" class="button"><?= $btn_text ?></button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</section>

<style>
.stage input[type='color'], .stage input[type='date'], .stage input[type='datetime'], .stage input[type='datetime-local'], .stage input[type='email'], .stage input[type='month'], .stage input[type='number'], .stage input[type='password'], .stage input[type='search'], .stage input[type='tel'], .stage input[type='text'], .stage input[type='url'], .stage input[type='week'], .stage input:not([type]), .stage textarea {
	background-color: #fff;
}
</style>