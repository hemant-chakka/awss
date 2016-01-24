<?php
$val .= '<ul class="menu" id="navMain">
	';

if ($scope->locally()->hasValue('CurrentMember', null, true)) { 
$val .= '
		';

$scope->locally()->obj('Menu', array('1'), true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '	  
			';

if ($scope->locally()->hasValue('ShowInMenus', null, true)&&$scope->locally()->hasValue('CustomerView', null, true)) { 
$val .= '
				';

if ($scope->locally()->XML_val('LinkOrSection', null, true)=='section') { 
$val .= '
					<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '" class="parent active">
						<a  href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" title="';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '"><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a>
						<ul>
							';

$scope->locally()->obj('Children', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
								';

if ($scope->locally()->hasValue('Last', null, true)) { 
$val .= '
									<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '" class="lastChild"><a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '"  title="Go to the ';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= ' page" ><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a></li>
								';


}else { 
$val .= '
									<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '"><a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '"  title="Go to the ';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= ' page" ><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a></li>	
								';


}
$val .= '
							';


}; $scope->popScope(); 
$val .= '
						</ul>
	   			    </li>
				';


}else { 
$val .= '
					<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '" class="parent"><a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '"  title="';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '"><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a></li>
				';


}
$val .= '
			';


}
$val .= '
		';


}; $scope->popScope(); 
$val .= '
	';


}else { 
$val .= '
		';

$scope->locally()->obj('Menu', array('1'), true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '	  
			';

if ($scope->locally()->XML_val('LinkOrSection', null, true)=='section') { 
$val .= '
				<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '" class="parent active">
					<a  href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '" title="';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '"><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a>
						<ul>
							';

$scope->locally()->obj('Children', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
								';

if ($scope->locally()->hasValue('Last', null, true)) { 
$val .= '
									<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '" class="lastChild"><a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '"  title="Go to the ';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= ' page" ><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a></li>
								';


}else { 
$val .= '
									<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '"><a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '"  title="Go to the ';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= ' page" ><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a></li>	
								';


}
$val .= '
							';


}; $scope->popScope(); 
$val .= '
						</ul>
				 </li>
			';


}else { 
$val .= '
				<li id="';

$val .= $scope->locally()->XML_val('LinkingMode', null, true);
$val .= '" class="parent"><a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '"  title="';

$val .= $scope->locally()->obj('Title', null, true)->XML_val('XML', null, true);
$val .= '"><span>';

$val .= $scope->locally()->obj('MenuTitle', null, true)->XML_val('XML', null, true);
$val .= '</span></a></li>
			';


}
$val .= '
		';


}; $scope->popScope(); 
$val .= '
	';


}
$val .= '
</ul>';

