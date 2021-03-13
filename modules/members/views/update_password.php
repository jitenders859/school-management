<section class="section-a">
	<h1><?= $headline ?></h1>
	<div style="max-width: 600px; margin: 66px auto 0 auto;">
		<?php
		echo validation_errors();
		echo form_open($form_location);
		?>
			<fieldset>
				<?php
				echo '<label>Password</label>';
				$attributes['placeholder'] = 'Enter your password here';
				echo form_password('pword', '', $attributes);

				echo '<label>Repeat Password</label>';
				$attributes['placeholder'] = 'Enter your password again';
				echo form_password('repeat_pword', '', $attributes);
				
				unset($attributes);
				$attributes['class'] = 'button';
				echo form_submit('submit', 'Submit', $attributes).' ';

				if ($headline !== 'Please Create A Password') {
					$attributes['class'] = 'button button-outline';
					echo anchor('members/your_account', 'Cancel', $attributes);
				}
				?>
			</fieldset>
		<?= form_close() ?>
	</div>
</section>