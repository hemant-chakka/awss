<?php
$val .= '<div class="';

if ($scope->locally()->hasValue('extraClass', null, true)) { 
$val .= $scope->locally()->XML_val('extraClass', null, true);

}else { 
$val .= 'fieldgroup';


}
if ($scope->locally()->hasValue('Zebra', null, true)) { 
$val .= ' fieldgroup-zebra';


}
$val .= '" ';

if ($scope->locally()->hasValue('ID', null, true)) { 
$val .= 'id="';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '"';


}
$val .= '>
	';

$scope->locally()->obj('FieldList', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		<div class="fieldgroup-field ';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('EvenOdd', null, true);
$val .= '">
			';

$val .= $scope->locally()->XML_val('FieldHolder', null, true);
$val .= '
		</div>
	';


}; $scope->popScope(); 
$val .= '
</div>
';

