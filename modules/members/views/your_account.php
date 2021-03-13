<section class="section-a">
	<h1>Your Account</h1>
	<div style="max-width: 600px; margin: 66px auto 0 auto;">
		<?= flashdata() ?>
		<table>
			<tbody>
				<tr>
					<td><b>Username</b></td>
					<td><?= $username ?></td>
				</tr>
				<tr>
					<td><b>First Name</b></td>
					<td><?= $first_name ?></td>
				</tr>
				<tr>
					<td><b>Last Name</b></td>
					<td><?= $last_name ?></td>
				</tr>
				<tr>
					<td><b>Email Address</b></td>
					<td><?= $email ?></td>
				</tr>
				<tr>
					<td><b>Date Joined</b></td>
					<td><?= date('l jS F Y', $date_created) ?></td>
				</tr>
			</tbody>
		</table>
		<p>
			<?php 
			$attributes['class'] = 'button';

			$icon_code = '<i class="fa fa-user"></i>';
			echo anchor('members/update_account', $icon_code.' Update Your Account Details', $attributes);

			$icon_code = '<i class="fa fa-shield-alt"></i>';
			$attributes['class'] = 'button button-outline';
			$attributes['style'] = 'float: right; position: relative;';
			echo anchor('members/update_password', $icon_code.' Update Your Password', $attributes);
			?>
		</p>
	</div>
</section>