<?php
$val .= '<div class="cms-content-tools west cms-panel cms-panel-layout collapsed" data-expandOnClick="true" data-layout-type="border" id="cms-content-tools-CMSPagesController">
	<div class="cms-panel-content center">
		<h3 class="cms-panel-header">';

$val .= _t('CMSPagesController_Tools_ss.FILTER','Filter');
$val .= '</h3>
		';

$val .= $scope->locally()->XML_val('SearchForm', null, true);
$val .= '
	</div>
	<div class="cms-panel-content-collapsed">
		<h3 class="cms-panel-header">';

$val .= _t('CMSPagesController_Tools_ss.FILTER','Filter');
$val .= '</h3>
	</div>
</div>';

