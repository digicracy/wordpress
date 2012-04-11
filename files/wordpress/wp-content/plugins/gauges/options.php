<div class="wrap">
<h2>Gaug.es Web Analytics</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('gauges'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Gauges Site ID:</th>
<td><input type="text" name="gauges_site_id" value="<?php echo esc_attr(get_option('gauges_site_id')); ?>" size="40" /></td>
</tr>
</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php esc_attr(_e('Save Changes')) ?>" />
</p>

</form>
</div>
