<?php
$val .= '<div class="cms-navigator">

	';

$val .= SSViewer::execute_template('LeftAndMain_ViewModeSelector', $scope->getItem(), array('SelectID' => 'preview-mode-dropdown-in-preview'), $scope);

$val .= '
	
    <span id="preview-size-dropdown" class="preview-size-selector preview-selector field dropdown">
		<select title="';

$val .= _t('SilverStripeNavigator.ViewDeviceWidth','Select a preview width');
$val .= '" id="preview-size-dropdown-select" class="preview-dropdown dropdown nolabel" autocomplete="off" name="Action">
			<option data-icon="icon-auto" data-description="';

$val .= _t('SilverStripeNavigator.Responsive','Responsive');
$val .= '" class="icon-auto icon-view first" value="auto">
				';

$val .= _t('SilverStripeNavigator.Auto','Auto');
$val .= '
			</option>
			<option data-icon="icon-desktop" data-description="1024px ';

$val .= _t('SilverStripeNavigator.Width','width');
$val .= '" class="icon-desktop icon-view" value="desktop">
				';

$val .= _t('SilverStripeNavigator.Desktop','Desktop');
$val .= '
			</option>
			<option data-icon="icon-tablet" data-description="800px ';

$val .= _t('SilverStripeNavigator.Width','width');
$val .= '" class="icon-tablet icon-view" value="tablet">
				';

$val .= _t('SilverStripeNavigator.Tablet','Tablet');
$val .= '
			</option>
			<option data-icon="icon-mobile" data-description="400px ';

$val .= _t('SilverStripeNavigator.Width','width');
$val .= '" class="icon-mobile icon-view last" value="mobile">
				';

$val .= _t('SilverStripeNavigator.Mobile','Mobile');
$val .= '
			</option>
		</select>
	</span>

	';

if ($scope->locally()->hasValue('Items', null, true)) { 
$val .= '
		';

if ($scope->locally()->obj('Items', null, true)->XML_val('Count', null, true)<'5') { 
$val .= '
			<fieldset id="preview-states" class="cms-preview-states switch-states size_';

$val .= $scope->locally()->obj('Items', null, true)->XML_val('Count', null, true);
$val .= '"> 			
				<div class="switch">
					';

$scope->locally()->obj('Items', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '					
						<input id="';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '" data-name="';

$val .= $scope->locally()->XML_val('Name', null, true);
$val .= '" class="state-name ';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= '" data-link="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" name="view" type="radio" ';

if ($scope->locally()->hasValue('isActive', null, true)) { 
$val .= 'checked';


}
$val .= '>
						<label for="';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '"';

if ($scope->locally()->hasValue('isActive', null, true)) { 
$val .= ' class="active"';


}
$val .= '><span>';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</span></label>
					';


}; $scope->popScope(); 
$val .= '
					<span class="slide-button"></span>
				</div>
			</fieldset>
		';


}else { 
$val .= '
			<span id="preview-state-dropdown" class="cms-preview-states field dropdown">
				<select title="';

$val .= _t('SilverStripeNavigator.PreviewState','Preview State');
$val .= '" id="preview-states" class="preview-state dropdown nolabel" autocomplete="off" name="preview-state">
					';

$scope->locally()->obj('Items', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '	
					<option name="';

$val .= $scope->locally()->XML_val('Name', null, true);
$val .= '" data-name="';

$val .= $scope->locally()->XML_val('Name', null, true);
$val .= '" data-link="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" class="state-name ';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= '" value="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" ';

if ($scope->locally()->hasValue('isActive', null, true)) { 
$val .= 'selected';


}
$val .= '>
						';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '
					</option>
					';


}; $scope->popScope(); 
$val .= '	
				</select>
			</span>
		';


}
$val .= '
	';


}
$val .= '
</div>
';

