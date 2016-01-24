<?php
$val .= '<div ';

$val .= $scope->locally()->XML_val('getAttributesHTML', array('class'), true);
$val .= ' class="ss-tabset ';

$val .= $scope->locally()->XML_val('extraClass', null, true);
$val .= '">
	<ul>
	';

$scope->locally()->obj('Tabs', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		<li class="';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('MiddleString', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('extraClass', null, true);
$val .= '"><a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#';

$val .= $scope->locally()->XML_val('id', null, true);
$val .= '" id="tab-';

$val .= $scope->locally()->XML_val('id', null, true);
$val .= '">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</a></li>
	';


}; $scope->popScope(); 
$val .= '
	</ul>

	';

$scope->locally()->obj('Tabs', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		';

if ($scope->locally()->hasValue('Tabs', null, true)) { 
$val .= '
			';

$val .= $scope->locally()->XML_val('FieldHolder', null, true);
$val .= '
		';


}else { 
$val .= '
			<div ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= '>
				';

$scope->locally()->obj('Fields', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
					';

$val .= $scope->locally()->XML_val('FieldHolder', null, true);
$val .= '
				';


}; $scope->popScope(); 
$val .= '
			</div>
		';


}
$val .= '
	';


}; $scope->popScope(); 
$val .= '
</div>
';

