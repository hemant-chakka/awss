<?php
$val .= '<form ';

$val .= $scope->locally()->XML_val('FormAttributes', null, true);
$val .= ' data-layout-type="border">

	<div class="cms-content-fields center">
		';

if ($scope->locally()->hasValue('Message', null, true)) { 
$val .= '
		<p id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->XML_val('MessageType', null, true);
$val .= '">';

$val .= $scope->locally()->XML_val('Message', null, true);
$val .= '</p>
		';


}else { 
$val .= '
		<p id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->XML_val('MessageType', null, true);
$val .= '" style="display: none"></p>
		';


}
$val .= '

		<fieldset>
			';

if ($scope->locally()->hasValue('Legend', null, true)) { 
$val .= '<legend>';

$val .= $scope->locally()->XML_val('Legend', null, true);
$val .= '</legend>';


}
$val .= '
			';

$scope->locally()->obj('Fields', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
				';

$val .= $scope->locally()->XML_val('FieldHolder', null, true);
$val .= '
			';


}; $scope->popScope(); 
$val .= '
			<div class="clear"><!-- --></div>
		</fieldset>
	</div>

	<div class="cms-content-actions cms-content-controls south">
		';

if ($scope->locally()->hasValue('Actions', null, true)) { 
$val .= '
		<div class="Actions">
			';

$scope->locally()->obj('Actions', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
				';

$val .= $scope->locally()->XML_val('Field', null, true);
$val .= '
			';


}; $scope->popScope(); 
$val .= '
				';

if ($scope->locally()->obj('Controller', null, true)->hasValue('LinkPreview', null, true)) { 
$val .= '
			<a href="';

$val .= $scope->locally()->obj('Controller', null, true)->XML_val('LinkPreview', null, true);
$val .= '" target="_cmsPreview" class="cms-preview-toggle-link ss-ui-button" data-icon="preview">
				';

$val .= _t('LeftAndMain.PreviewButton','Preview');
$val .= ' &raquo;
			</a>
			';


}
$val .= '

			';

$val .= SSViewer::execute_template('LeftAndMain_ViewModeSelector', $scope->getItem(), array('SelectID' => 'preview-mode-dropdown-in-content'), $scope);

$val .= '
		</div>
		';


}
$val .= '
	</div>
</form>
';

