<?php
$val .= '<';

$val .= $scope->locally()->XML_val('Tag', null, true);
$val .= ' class="CompositeField ';

$val .= $scope->locally()->XML_val('extraClass', null, true);
$val .= ' ';

if ($scope->locally()->hasValue('ColumnCount', null, true)) { 
$val .= 'multicolumn';


}
$val .= '">
	';

if ($scope->locally()->XML_val('Tag', null, true)=='fieldset'&&$scope->locally()->hasValue('Legend', null, true)) { 
$val .= '
		<legend>';

$val .= $scope->locally()->XML_val('Legend', null, true);
$val .= '</legend>
	';


}
$val .= '
	
	';

$scope->locally()->obj('FieldList', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		';

if ($scope->locally()->hasValue('ColumnCount', null, true)) { 
$val .= '
			<div class="column-';

$val .= $scope->locally()->XML_val('ColumnCount', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= '">
				';

$val .= $scope->locally()->XML_val('Field', null, true);
$val .= '
			</div>
		';


}else { 
$val .= '
			';

$val .= $scope->locally()->XML_val('Field', null, true);
$val .= '
		';


}
$val .= '
	';


}; $scope->popScope(); 
$val .= '
</';

$val .= $scope->locally()->XML_val('Tag', null, true);
$val .= '>
';

