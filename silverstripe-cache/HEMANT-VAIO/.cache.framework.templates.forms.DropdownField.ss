<?php
$val .= '<select ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= '>
';

$scope->locally()->obj('Options', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
	<option value="';

$val .= $scope->locally()->obj('Value', null, true)->XML_val('XML', null, true);
$val .= '"';

if ($scope->locally()->hasValue('Selected', null, true)) { 
$val .= ' selected="selected"';


}
if ($scope->locally()->hasValue('Disabled', null, true)) { 
$val .= ' disabled="disabled"';


}
$val .= '>';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '</option>
';


}; $scope->popScope(); 
$val .= '
</select>
';

