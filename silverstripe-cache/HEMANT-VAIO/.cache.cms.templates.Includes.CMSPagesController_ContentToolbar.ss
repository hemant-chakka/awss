<?php
$val .= '<div class="cms-content-batchactions">
	<div class="view-mode-batchactions-wrapper">
		<input id="view-mode-batchactions" name="view-mode-batchactions" type="checkbox" />
		<label for="view-mode-batchactions">';

$val .= _t('CMSPagesController_ContentToolbar_ss.MULTISELECT','Multi-selection');
$val .= '</label>
	</div>

	';

$val .= $scope->locally()->XML_val('BatchActionsForm', null, true);
$val .= '
</div>
';

