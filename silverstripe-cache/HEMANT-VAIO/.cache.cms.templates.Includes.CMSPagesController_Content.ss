<?php
$val .= '<div id="pages-controller-cms-content" class="cms-content center cms-tabset ';

$val .= $scope->locally()->XML_val('BaseCSSClasses', null, true);
$val .= '" data-layout-type="border" data-pjax-fragment="Content">

	<div class="cms-content-header north">
		<div class="cms-content-header-info">
			';

$val .= SSViewer::execute_template('CMSBreadcrumbs', $scope->getItem(), array(), $scope);

$val .= '
		</div>

		<div class="cms-content-header-tabs">
			<ul class="cms-tabset-nav-primary">
				<li class="content-treeview';

if ($scope->locally()->XML_val('ViewState', null, true)=='tree') { 
$val .= ' ui-tabs-active ss-tabs-force-active';


}
$val .= ' cms-tabset-icon tree">
					<a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#cms-content-treeview" class="cms-panel-link" data-href="';

$val .= $scope->locally()->XML_val('LinkTreeView', null, true);
$val .= '">';

$val .= _t('CMSPagesController.TreeView','Tree View');
$val .= '</a>
				</li>
				<li class="content-listview';

if ($scope->locally()->XML_val('ViewState', null, true)=='list') { 
$val .= ' ui-tabs-active ss-tabs-force-active';


}
$val .= ' cms-tabset-icon list">
					<a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#cms-content-listview" class="cms-panel-link" data-href="';

$val .= $scope->locally()->XML_val('LinkListView', null, true);
$val .= '">';

$val .= _t('CMSPagesController.ListView','List View');
$val .= '</a>
				</li>
				<!--
				<li class="content-galleryview cms-tabset-icon gallery">
					<a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#cms-content-galleryview">';

$val .= _t('CMSPagesController.GalleryView','Gallery View');
$val .= '</a>
				</li>
				-->
			</ul>
		</div>
	</div>

	';

$val .= $scope->locally()->XML_val('Tools', null, true);
$val .= '

	<div class="cms-content-fields center ui-widget-content cms-panel-padded">

		<div class="cms-content-view cms-panel-deferred" id="cms-content-treeview" data-url="';

$val .= $scope->locally()->XML_val('LinkTreeView', null, true);
$val .= '">
			';


$val .= '
		</div>

		<div class="cms-content-view cms-panel-deferred" id="cms-content-listview" data-url="';

$val .= $scope->locally()->XML_val('LinkListView', null, true);
$val .= '" data-deferred-no-cache="true">
			';


$val .= '
		</div>
		<!--
		<div id="cms-content-galleryview">
			<i>Not implemented yet</i>
		</div>
		-->

	</div>

</div>';

