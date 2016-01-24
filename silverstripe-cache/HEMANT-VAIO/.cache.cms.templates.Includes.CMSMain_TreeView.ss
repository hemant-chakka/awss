<?php
$val .= '<div class="cms-content-toolbar">
	';

$val .= SSViewer::execute_template('CMSPagesController_ContentToolActions', $scope->getItem(), array(), $scope);

$val .= '
	';

$val .= SSViewer::execute_template('CMSPagesController_ContentToolbar', $scope->getItem(), array(), $scope);

$val .= '
</div>

<div class="ss-dialog cms-page-add-form-dialog cms-dialog-content" id="cms-page-add-form" title="';

$val .= _t('CMSMain.AddNew','Add new page');
$val .= '">
	';

$val .= $scope->locally()->XML_val('AddForm', null, true);
$val .= '
</div>

';

$val .= $scope->locally()->XML_val('ExtraTreeTools', null, true);
$val .= '

<div class="center">
	';

if ($scope->locally()->hasValue('TreeIsFiltered', null, true)) { 
$val .= '
	<div class="cms-tree-filtered cms-notice">
		<strong>';

$val .= _t('CMSMain.TreeFiltered','Filtered tree.');
$val .= '</strong>
		<a href="';

$val .= $scope->locally()->XML_val('LinkPages', null, true);
$val .= '" class="cms-panel-link">
			';

$val .= _t('CMSMain.TreeFilteredClear','Clear filter');
$val .= '
		</a>
	</div>
	';


}
$val .= '

	<div class="cms-tree" data-url-tree="';

$val .= $scope->locally()->XML_val('LinkWithSearch', array($scope->locally()->XML_val('Link', array('getsubtree'), true)), true);
$val .= '" data-url-savetreenode="';

$val .= $scope->locally()->XML_val('Link', array('savetreenode'), true);
$val .= '" data-url-updatetreenodes="';

$val .= $scope->locally()->XML_val('Link', array('updatetreenodes'), true);
$val .= '" data-url-addpage="';

$val .= $scope->locally()->XML_val('LinkPageAdd', array('AddForm/?action_doAdd=1', 'ParentID=%s&amp;PageType=%s'), true);
$val .= '" data-url-editpage="';

$val .= $scope->locally()->XML_val('LinkPageEdit', array('%s'), true);
$val .= '" data-url-duplicate="';

$val .= $scope->locally()->XML_val('Link', array('duplicate/%s'), true);
$val .= '" data-url-duplicatewithchildren="';

$val .= $scope->locally()->XML_val('Link', array('duplicatewithchildren/%s'), true);
$val .= '" data-url-listview="';

$val .= $scope->locally()->XML_val('Link', array('?view=list'), true);
$val .= '" data-hints="';

$val .= $scope->locally()->obj('SiteTreeHints', null, true)->XML_val('XML', null, true);
$val .= '" data-extra-params="SecurityID=';

$val .= $scope->locally()->XML_val('SecurityID', null, true);
$val .= '">
		';

$val .= $scope->locally()->XML_val('SiteTreeAsUL', null, true);
$val .= '
	</div>
</div>
';

