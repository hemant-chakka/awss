<?php
$val .= '<div id="pages-controller-cms-content" class="cms-content center cms-tabset ';

$val .= $scope->locally()->XML_val('BaseCSSClasses', null, true);
$val .= '" data-layout-type="border" data-pjax-fragment="Content" data-ignore-tab-state="true">

	<div class="cms-content-header north">
		<div class="cms-content-header-info">
			';

$val .= SSViewer::execute_template('CMSBreadcrumbs', $scope->getItem(), array(), $scope);

$val .= '
		</div>

		<div class="cms-content-header-tabs">
			<ul class="cms-tabset-nav-primary">
				<li class="content-treeview';

if ($scope->locally()->XML_val('class', null, true)=='CMSPageEditController') { 
$val .= ' ui-tabs-active';


}
$val .= '">
					<a href="';

$val .= $scope->locally()->XML_val('LinkPageEdit', null, true);
$val .= '" class="cms-panel-link" title="Form_EditForm" data-href="';

$val .= $scope->locally()->XML_val('LinkPageEdit', null, true);
$val .= '">
						';

$val .= _t('CMSMain.TabContent','Content');
$val .= '
					</a>
				</li>
				<li class="content-listview';

if ($scope->locally()->XML_val('class', null, true)=='CMSPageSettingsController') { 
$val .= ' ui-tabs-active';


}
$val .= '">
					<a href="';

$val .= $scope->locally()->XML_val('LinkPageSettings', null, true);
$val .= '" class="cms-panel-link" title="Form_EditForm" data-href="';

$val .= $scope->locally()->XML_val('LinkPageSettings', null, true);
$val .= '">
						';

$val .= _t('CMSMain.TabSettings','Settings');
$val .= '
					</a>
				</li>
				<li class="content-listview';

if ($scope->locally()->XML_val('class', null, true)=='CMSPageHistoryController') { 
$val .= ' ui-tabs-active';


}
$val .= '">
					<a href="';

$val .= $scope->locally()->XML_val('LinkPageHistory', null, true);
$val .= '" class="cms-panel-link" title="Form_EditForm" data-href="';

$val .= $scope->locally()->XML_val('LinkPageHistory', null, true);
$val .= '">
						';

$val .= _t('CMSMain.TabHistory','History');
$val .= '
					</a>
				</li>
			</ul>
		</div>
	</div>

	';

$val .= $scope->locally()->XML_val('Tools', null, true);
$val .= '

	';

$val .= $scope->locally()->XML_val('EditForm', null, true);
$val .= '

</div>';

