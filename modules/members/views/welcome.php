<section class="section-a">
	<h1>Welcome To <?= WEBSITE_NAME ?>!</h1>
	<div style="max-width: 600px; margin: 66px auto 0 auto;">
		<p>Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  Add a welcome message here for members who have just logged on for the first time.  </p>
		<p>
			<?php 
			$attributes['class'] = 'button';

			$icon_code = '<i class="fa fa-tachometer-alt"></i>';
			echo anchor('dashboard', $icon_code.' Go To The Dashboard', $attributes);

			$icon_code = '<i class="fa fa-user"></i>';
			$attributes['class'] = 'button button-outline';
			$attributes['style'] = 'float: right; position: relative;';
			echo anchor('members/your_account', $icon_code.' View Your Account Details', $attributes);
			?>
		</p>
	</div>
</section>