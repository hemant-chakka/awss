<?php
$val .= '<div class="preview-holder">
	<a class="preview" href="';

$val .= $scope->locally()->XML_val('URL', null, true);
$val .= '" target="_blank">
		';

$val .= $scope->locally()->XML_val('URL', null, true);
$val .= '
	</a>
	<button class="ss-ui-button ss-ui-button-small edit">
		';

$val .= _t('URLSegmentField.Edit','Edit');
$val .= '
	</button>
</div>
<div class="edit-holder">
	<input ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= ' />
	<button class="update ss-ui-button-small">
		';

$val .= _t('URLSegmentField.OK','OK');
$val .= '
	</button>
	<button class="cancel ss-ui-button-small ss-ui-action-minor">
		';

$val .= _t('URLSegmentField.Cancel','Cancel');
$val .= '
	</button>
	';

if ($scope->locally()->hasValue('HelpText', null, true)) { 
$val .= '<p class="help">';

$val .= $scope->locally()->XML_val('HelpText', null, true);
$val .= '</p>';


}
$val .= '
</div>';

