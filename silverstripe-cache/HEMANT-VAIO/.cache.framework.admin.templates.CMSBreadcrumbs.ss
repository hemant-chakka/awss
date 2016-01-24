<?php
$val .= '<div class="breadcrumbs-wrapper" data-pjax-fragment="Breadcrumbs">
	<h2 id="page-title-heading">
		';

if ($scope->locally()->hasValue('ToplevelController', null, true)) { 
$val .= '
			<span class="section-icon icon icon-16 icon-';

$val .= $scope->locally()->obj('ToplevelController', null, true)->obj('MenuCurrentItem', null, true)->obj('Code', null, true)->XML_val('LowerCase', null, true);
$val .= '"></span>
		';


}else if ($scope->locally()->hasValue('Controller', null, true)) { 
$val .= '
			<span class="section-icon icon icon-16 icon-';

$val .= $scope->locally()->obj('Controller', null, true)->obj('MenuCurrentItem', null, true)->obj('Code', null, true)->XML_val('LowerCase', null, true);
$val .= '"></span>
		';


}else { 
$val .= '
			<span class="section-icon icon icon-16 icon-';

$val .= $scope->locally()->obj('MenuCurrentItem', null, true)->obj('Code', null, true)->XML_val('LowerCase', null, true);
$val .= '"></span>
		';


}
$val .= '

		';

$scope->locally()->obj('Breadcrumbs', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
			';

if ($scope->locally()->hasValue('Last', null, true)) { 
$val .= '
				<span class="cms-panel-link crumb last">';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '</span>
			';


}else { 
$val .= '
				<a class="cms-panel-link crumb" href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '">';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '</a>
				<span class="sep">/</span>
			';


}
$val .= '
		';


}; $scope->popScope(); 
$val .= '
	</h2>
</div>
';

