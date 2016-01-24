<?php
$val .= '<div id="dashboard-panel-';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '" class="dashboard-panel ';

$val .= $scope->locally()->XML_val('ClassName', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('Size', null, true);
$val .= '" data-refresh-url="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" >
	<div class="dashboard-panel-inner">
		<div class="dashboard-panel-header">
			';

if ($scope->locally()->hasValue('PrimaryActions', null, true)) { 
$val .= '
				<div class="dashboard-panel-header-actions">
					';

$scope->locally()->obj('PrimaryActions', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
						';

$val .= $scope->locally()->XML_val('Action', null, true);
$val .= '
					';


}; $scope->popScope(); 
$val .= '
				</div>
			';


}
$val .= '

			<div class="dashboard-panel-icon">
				<img src="';

$val .= $scope->locally()->XML_val('Icon', null, true);
$val .= '" width="24" height="24" />
			</div>

			<h3>';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</h3>
		</div>

		<div class="dashboard-panel-content">
			';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '
		</div>

		<div class="dashboard-panel-footer">
			';

if ($scope->locally()->hasValue('SecondaryActions', null, true)) { 
$val .= '
			<div class="dashboard-panel-footer-actions">
				';

$scope->locally()->obj('SecondaryActions', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
					';

$val .= $scope->locally()->XML_val('Action', null, true);
$val .= '
				';


}; $scope->popScope(); 
$val .= '
			</div>
			';


}
$val .= '
			<div class="dashboard-panel-toolbar">
				';

if ($scope->locally()->obj('Dashboard', null, true)->hasValue('CanConfigurePanels', null, true)) { 
$val .= '
				<a class="btn-dashboard-panel-configure" href="';

$val .= $scope->locally()->XML_val('ConfigLink', null, true);
$val .= '"><img src="dashboard/images/configure.png" /></a>
				';


}
$val .= '
				';

if ($scope->locally()->obj('Dashboard', null, true)->hasValue('CanDeletePanels', null, true)) { 
$val .= '
				<a class="btn-dashboard-panel-delete" href="';

$val .= $scope->locally()->XML_val('DeleteLink', null, true);
$val .= '"><img src="dashboard/images/trash.png" /></a>
				';


}
$val .= '
			</div>
		</div>



		<div class="dashboard-panel-configure">
			<form ';

$val .= $scope->locally()->obj('Form', null, true)->XML_val('FormAttributes', null, true);
$val .= '>
				<div class="dashboard-panel-configure-fields">
				';

$scope->locally()->obj('Form', null, true)->obj('Fields', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
					';

$val .= $scope->locally()->XML_val('FieldHolder', null, true);
$val .= '
				';


}; $scope->popScope(); 
$val .= '
				</div>
				<div class="dashboard-panel-configure-actions">
					';

$scope->locally()->obj('Form', null, true)->obj('Actions', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
					';

$val .= $scope->locally()->XML_val('Field', null, true);
$val .= '
					';


}; $scope->popScope(); 
$val .= '
				</div>
			</form>
		</div>
	</div>
</div>';

