<?php
$val .= '<div id="pages-controller-cms-content" class="cms-content center " data-layout-type="border" data-pjax-fragment="Content">
	<div class="cms-content-header north">
		<div class="cms-content-header-info">
			';

$val .= SSViewer::execute_template('CMSBreadcrumbs', $scope->getItem(), array(), $scope);

$val .= '
		</div>	
		<div class="dashboard-top-buttons">
		';

if ($scope->locally()->hasValue('CanAddPanels', null, true)) { 
$val .= '
			<a class="ss-ui-button ss-ui-action-constructive manage-dashboard" href="javascript:void(0);">';

$val .= _t('Dashboard.ADDPANEL','New Panel');
$val .= '</a>
		';


}
$val .= '
		';

if ($scope->locally()->hasValue('IsAdmin', null, true)) { 
$val .= '
			<span class="ss-fancy-dropdown right">
				<a class="ss-ui-button ss-fancy-dropdown-btn" href="javascript:void(0)">';

$val .= _t('Dashboard_Content.ADMINISTRATION','Administration');
$val .= '</a>
				<span class="ss-fancy-dropdown-options">
					<a class="set-as-default dashboard-message-link" href="';

$val .= $scope->locally()->XML_val('Link', array('setdefault'), true);
$val .= '">';

$val .= _t('Dashboard.SETASDEFAULT','Make this the default dashboard');
$val .= '</a>	
					<a class="apply-to-all dashboard-message-link" href="';

$val .= $scope->locally()->XML_val('Link', array('applytoall'), true);
$val .= '">';

$val .= _t('Dashboard.APPLYTOALL','Apply this dashboard to all members');
$val .= '</a>
				</span>
			</span>
		';


}
$val .= '

		</div>
	</div>
	<div class="dashboard dashboard-sortable" data-sort-url="';

$val .= $scope->locally()->XML_val('Link', array('sort'), true);
$val .= '">
		<div id="dashboard-message"></div>		
		<div class="dashboard-panel-list"><!--
		';

$scope->locally()->obj('Panels', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		-->';

$val .= $scope->locally()->XML_val('PanelHolder', null, true);
$val .= '<!--
		';


}; $scope->popScope(); 
$val .= '
	--></div>
		<div class="dashboard-panel-selection dashboard-panel normal" id="dashboard-panel-0">
			<div class="dashboard-panel-inner">
				<div class="dashboard-panel-selection-inner">
					<div class="dashboard-panel-header">
						<div class="dashboard-panel-icon">
							<img src="dashboard/images/dashboard-panel-default.png" width="24" height="24" />
						</div>

						<h3>';

$val .= _t('Dashboard.CHOOSEPANELTYPE','Choose a panel type');
$val .= '</h3>
					</div>
					<div class="dashboard-panel-content">
						';

$scope->locally()->obj('AllPanels', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
							<div class="available-panel ';

$val .= $scope->locally()->XML_val('EvenOdd', null, true);
$val .= '" data-type="';

$val .= $scope->locally()->XML_val('Class', null, true);
$val .= '" data-create-url="';

$val .= $scope->locally()->XML_val('CreateLink', null, true);
$val .= '" ';

if ($scope->locally()->hasValue('ShowConfigure', null, true)) { 
$val .= 'data-configure="true"';


}
$val .= '>
								<div class="available-panel-icon">
									<img src="';

$val .= $scope->locally()->XML_val('Icon', null, true);
$val .= '" />
								</div>
								<div class="available-panel-content">
									<h4>';

$val .= $scope->locally()->XML_val('Label', null, true);
$val .= '</h4>
									<p>';

$val .= $scope->locally()->XML_val('Description', null, true);
$val .= '</p>
								</div>
							</div>
						';


}; $scope->popScope(); 
$val .= '
					</div>
					<div class="dashboard-panel-footer">
						<div class="dashboard-panel-footer-actions">
							<button class="ss-ui-button dashboard-create-cancel">';

$val .= _t('Dashboard.CANCEL','Cancel');
$val .= '</button>
						</div>
					</div>
				</div>
            </div>
		</div>
	</div>

</div>
';

