<section class="neon">
	<div class="container paper">
        <div class="">
    		<div class="stage">
				<h1>Member Login</h1>
				<?= validation_errors() ?>
				<form class="form-vertical" action="<?= $form_location ?>" method="post">
					<fieldset>
						<label>Username</label>
						<input type="text" value="<?= $username ?>" name="username" placeholder="Enter your username here">
						<label>Password</label>
						<input type="password" name="pword" placeholder="Enter your password here">
						<div style="margin-bottom: 33px;">
							<input type="checkbox" name="remember" id="remember" value="1"> remember me
						</div>
						<input type="submit" name="submit" value="Submit" class="button">
						<a href="<?= BASE_URL ?>" class="button button-outline">Cancel</a>
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

