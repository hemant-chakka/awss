<?php
$val .= '<div class=\'cms-sitetree-information\'>
	<p class="meta-info">';

$val .= _t('SiteTree.LASTSAVED','Last saved');
$val .= ' ';

$val .= $scope->locally()->obj('LastEdited', null, true)->XML_val('Ago', array('0'), true);
$val .= '
	';

if ($scope->locally()->hasValue('ExistsOnLive', null, true)) { 
$val .= '
		<br />';

$val .= _t('SiteTree.LASTPUBLISHED','Last published');
$val .= ' ';

$val .= $scope->locally()->obj('Live', null, true)->obj('LastEdited', null, true)->XML_val('Ago', array('0'), true);
$val .= '
	';


}else { 
$val .= '
		<br /><em>';

$val .= _t('SiteTree.NOTPUBLISHED','Not published');
$val .= '</em>
	';


}
$val .= '
	</p>
</div>
';

