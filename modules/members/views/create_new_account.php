<section class="neon">
	<div class="container paper">
        <div class="">
    		<div class="stage">
				<h1>Create New Account</h1>
				<?= validation_errors() ?>
				<form class="form-vertical" action="<?= $form_location ?>" method="post">
					<fieldset>
						<label>First Name</label>
						<input type="text" value="<?= $first_name ?>" name="first_name" placeholder="Enter your first name here">
						<label>Last Name</label>
						<input type="text" value="<?= $last_name ?>" name="last_name" placeholder="Enter your last name here">
						<label>Username</label>
						<input type="text" value="<?= $username ?>" name="username" placeholder="Enter your username here">
						<label>Contact Email Address</label>
						<input type="email" value="<?= $email ?>" name="email" placeholder="Enter your contact email address here">
						<label>Mobile Number</label>
						<input type="text" value="<?= $mobile_number ?>" name="mobile_number" placeholder="Enter your mobile number here">
						<input type="submit" name="submit" value="Create Account" class="button">
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