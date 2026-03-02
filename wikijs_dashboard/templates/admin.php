<?php
/** @var array $_ */

script('wikijs_dashboard', 'admin-settings');
?>

<div id="wikijs_dashboard_settings" class="section">
	<h2><?php p($l->t('Wiki.js Dashboard')); ?></h2>
	<p class="settings-hint"><?php p($l->t('Configure the Wiki.js API endpoint and display options for the Dashboard widget.')); ?></p>

	<input type="hidden" id="requesttoken" value="<?php p($_['requesttoken'] ?? ''); ?>">

	<p>
		<label for="wikijs_url"><?php p($l->t('Wiki.js API URL')); ?></label><br>
		<input id="wikijs_url" type="text" class="text" style="width: 500px" value="<?php p($_['wikijs_url'] ?? ''); ?>" placeholder="http://192.168.222.61:3000">
	</p>

	<p>
		<label for="wikijs_token"><?php p($l->t('API token')); ?></label><br>
		<input id="wikijs_token" type="password" class="text" style="width: 500px" value="<?php p($_['wikijs_token'] ?? ''); ?>">
		<br>
		<label style="display:inline-flex;align-items:center;gap:6px;margin-top:6px;"><input id="wikijs_token_show" type="checkbox"> <?php p($l->t('Show token')); ?></label>
		<br><em><?php p($l->t('Stored in Nextcloud app config.')); ?></em>
	</p>

	<p>
		<label for="wikijs_public_url"><?php p($l->t('Public Wiki URL')); ?></label><br>
		<input id="wikijs_public_url" type="text" class="text" style="width: 500px" value="<?php p($_['wikijs_public_url'] ?? ''); ?>" placeholder="https://office.npsumava.cz">
	</p>

	<p>
		<label for="wikijs_locale"><?php p($l->t('Locale')); ?></label><br>
		<input id="wikijs_locale" type="text" class="text" style="width: 120px" value="<?php p($_['wikijs_locale'] ?? 'cs'); ?>" placeholder="cs">
	</p>

	<p>
		<label for="limit"><?php p($l->t('Item limit')); ?></label><br>
		<input id="limit" type="number" min="1" max="50" class="text" style="width: 120px" value="<?php p($_['limit'] ?? '7'); ?>">
	</p>

	<p>
		<button id="wikijs_dashboard_save" class="button primary"><?php p($l->t('Save')); ?></button>
	</p>
</div>
