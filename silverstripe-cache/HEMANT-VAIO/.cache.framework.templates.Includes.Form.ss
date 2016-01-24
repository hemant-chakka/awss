<?php
if ($scope->locally()->hasValue('IncludeFormTag', null, true)) { 
$val .= '
<form ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= '>
';


}
$val .= '
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
	</div>
	';


}
$val .= '
';

if ($scope->locally()->hasValue('IncludeFormTag', null, true)) { 
$val .= '
</form>
';


}
$val .= '
';

