<script>
jQuery.noConflict();
form = jQuery('#reg-form');
submit = jQuery('#reg-submit');
table = jQuery('#reg-table');
form.hide();
// handles the click event of the submit button
submit.click(function(){	
	// defines the options and their default values
	// again, this is not the most elegant way to do this
	// but well, this gets the job done nonetheless
	var options = { 
		'role'    : 'subscriber',
		'message' : '',
		'notify' : '',
		'password' : 'no'
		};
	var shortcode = '[register';
	
	for( var index in options) {
		var value = table.find('#reg-' + index).val();
		if ( value !== options[index])
			shortcode += ' ' + index + '="' + value + '"';
	}
	
	shortcode += ']';
	
	// inserts the shortcode into the active editor
	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
	// closes Thickbox
	tb_remove();
});
</script>
<?php require_once('../../../wp-load.php'); ?>
 <div id="reg-form">
	<table id="reg-table" class="form-table">
			<tr>
				<th><label for="reg-role">Role</label></th>
				<td>
				<select name="role" id="reg-role">
					<option value="">Select role ... </option>
					<?php global $wp_roles; print_r($wp_roles); ?>
					<?php foreach($wp_roles->role_names as $k => $v): ?>
					<?php if($k != 'administrator'): ?>
					<option value="<?=$k; ?>"><?=$v; ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select><br/>
				<small>Specify the registration user role.</small>
				</td>
			</tr>
			<tr>
				<th><label for="reg-role">Message</label></th>
				<td>
				<textarea id="reg-message" name="message"></textarea><br/>			
				<small>Confirmation for registered users. </small>
				</td>
			</tr>
			<tr>
				<th><label for="reg-role">Notifications</label></th>
				<td>
				<input id="reg-notify" name="notify" value=""></input>	<br/>			
				<small>Notify these emails.</small>
				</td>
			</tr>
			<tr>
				<th><label for="reg-password">Password</label></th>
				<td>
				<select id="reg-password" name="password">
					<option value="no">No</option>
					<option value="yes">Yes</option>
				</select><br/>
				<small>Select "yes" to allow users to set their password.</small>			
				</td>
			</tr>
	</table>
	<input type="submit" id="reg-submit" class="button-primary" value="Insert Registration Form" name="submit" />
</div>