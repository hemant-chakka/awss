<?php
$val .= '<div id="';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '" class="dashboard-button-options-btn-group ';

$val .= $scope->locally()->XML_val('Size', null, true);
$val .= '" data-toggle="buttons-radio">
	';

$scope->locally()->obj('Options', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '<a class="';

if ($scope->locally()->hasValue('isChecked', null, true)) { 
$val .= 'active';


}
$val .= ' ';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= ' ';

if ($scope->locally()->hasValue('Middle', null, true)) { 
$val .= 'middle';


}
$val .= '" data-value="';

$val .= $scope->locally()->XML_val('Value', null, true);
$val .= '" data-name="';

$val .= $scope->locally()->XML_val('Name', null, true);
$val .= '">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</a>';


}; $scope->popScope(); 
$val .= '
	<input type="hidden" name="';

$val .= $scope->locally()->XML_val('Name', null, true);
$val .= '" value="';

$val .= $scope->locally()->XML_val('Value', null, true);
$val .= '" />
</div>';

