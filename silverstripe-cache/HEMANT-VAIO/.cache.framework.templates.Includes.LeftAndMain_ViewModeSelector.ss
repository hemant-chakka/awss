<?php
$val .= '<span id="';

$val .= $scope->locally()->XML_val('SelectID', null, true);
$val .= '" class="preview-mode-selector preview-selector field dropdown">
	<select title="';

$val .= _t('SilverStripeNavigator.ChangeViewMode','Change view mode');
$val .= '" id="';

$val .= $scope->locally()->XML_val('SelectID', null, true);
$val .= '-select" class="preview-dropdown dropdown nolabel no-change-track" autocomplete="off" name="Action">

		<option data-icon="icon-split" class="icon-split icon-view first" value="split">';

$val .= _t('SilverStripeNavigator.SplitView','Split mode');
$val .= '</option>
		<option data-icon="icon-preview" class="icon-preview icon-view" value="preview">';

$val .= _t('SilverStripeNavigator.PreviewView','Preview mode');
$val .= '</option>
		<option data-icon="icon-edit" class="icon-edit icon-view last" value="content">';

$val .= _t('SilverStripeNavigator.EditView','Edit mode');
$val .= '</option>
		<!-- Dual window not implemented yet -->
		<!--
			<option data-icon="icon-window" class="icon-window icon-view last" value="window">';

$val .= _t('SilverStripeNavigator.DualWindowView','Dual Window');
$val .= '</option>
		-->
	</select>
</span>
';

