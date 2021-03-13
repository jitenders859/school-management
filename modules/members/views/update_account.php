<section class="section-a">
	<h1>Update Your Account Details</h1>
	<div style="max-width: 600px; margin: 66px auto 0 auto;">
		<?php
		echo validation_errors();
		echo form_open($form_location);
		?>
			<fieldset>
				<?php
				echo '<label>First Name</label>';
				$attributes['placeholder'] = 'Enter your first name here';
				echo form_input('first_name', $first_name, $attributes);

				echo '<label>Last Name</label>';
				$attributes['placeholder'] = 'Enter your last name here';
				echo form_input('last_name', $last_name, $attributes);

				echo '<label>Username Name</label>';
				$attributes['placeholder'] = 'Enter your usernamename here';
				echo form_input('username', $username, $attributes);

				echo '<label>Contact Email Address</label>';
				$attributes['placeholder'] = 'Enter your contact email address here';
				echo form_email('email', $email, $attributes);
				
				unset($attributes);
				$attributes['class'] = 'button';
				echo form_submit('submit', 'Submit', $attributes).' ';

				$attributes['class'] = 'button button-outline';
				echo anchor('members/your_account', 'Cancel', $attributes);
				?>
			</fieldset>
		<?= form_close() ?>
	</div>
</section>