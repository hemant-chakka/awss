<?php
$val .= '<div class="cms-menu cms-panel cms-panel-layout west" id="cms-menu" data-layout-type="border">
	<div class="cms-logo-header north">
		<div class="cms-logo">
			<a href="';

$val .= $scope->locally()->XML_val('ApplicationLink', null, true);
$val .= '" target="_blank" title="';

$val .= $scope->locally()->XML_val('ApplicationName', null, true);
$val .= ' (Version - ';

$val .= $scope->locally()->XML_val('CMSVersion', null, true);
$val .= ')">
				';

$val .= $scope->locally()->XML_val('ApplicationName', null, true);
$val .= ' ';

if ($scope->locally()->hasValue('CMSVersion', null, true)) { 
$val .= '<abbr class="version">';

$val .= $scope->locally()->XML_val('CMSVersion', null, true);
$val .= '</abbr>';


}
$val .= '
			</a>
			<span>';

if ($scope->locally()->hasValue('SiteConfig', null, true)) { 
$val .= $scope->locally()->obj('SiteConfig', null, true)->XML_val('Title', null, true);

}else { 
$val .= $scope->locally()->XML_val('ApplicationName', null, true);

}
$val .= '</span>
		</div>
	
		<div class="cms-login-status">
			<a href="Security/logout" class="logout-link" title="';

$val .= _t('LeftAndMain_Menu_ss.LOGOUT','Log out');
$val .= '">';

$val .= _t('LeftAndMain_Menu_ss.LOGOUT','Log out');
$val .= '</a>
			';

$scope->locally()->obj('CurrentMember', null, true); $scope->pushScope();
$val .= '
				<span>
					';

$val .= _t('LeftAndMain_Menu_ss.Hello','Hi');
$val .= '
					<a href="';

$val .= $scope->locally()->XML_val('AbsoluteBaseURL', null, true);
$val .= 'admin/myprofile" class="profile-link">
						';

if ($scope->locally()->hasValue('FirstName', null, true)&&$scope->locally()->hasValue('Surname', null, true)) { 
$val .= $scope->locally()->XML_val('FirstName', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('Surname', null, true);

}else if ($scope->locally()->hasValue('FirstName', null, true)) { 
$val .= $scope->locally()->XML_val('FirstName', null, true);

}else { 
$val .= $scope->locally()->XML_val('Email', null, true);

}
$val .= '
					</a>
				</span>
			';


; $scope->popScope(); 
$val .= '
		</div>
	</div>
		
	<div class="cms-panel-content center">
		<ul class="cms-menu-list">
		';

$scope->locally()->obj('MainMenu', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
			<li class="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('FirstLast', null, true);
$val .= ' ';

if ($scope->locally()->XML_val('LinkingMode', null, true)=='link') { 

}else { 
$val .= 'opened';


}
$val .= '" id="Menu-';

$val .= $scope->locally()->XML_val('Code', null, true);
$val .= '" title="';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('ATT', null, true);
$val .= '">
				<a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= '>
					<span class="icon icon-16 icon-';

$val .= $scope->locally()->obj('Code', null, true)->XML_val('LowerCase', null, true);
$val .= '">&nbsp;</span>
					<span class="text">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</span>
				</a>
			</li>
		';


}; $scope->popScope(); 
$val .= '
		</ul>
	</div>
		
	<div class="cms-panel-toggle south">
		<a class="toggle-expand" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#"><span>&raquo;</span></a>
		<a class="toggle-collapse" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#"><span>&laquo;</span></a>
	</div>
</div>
';

