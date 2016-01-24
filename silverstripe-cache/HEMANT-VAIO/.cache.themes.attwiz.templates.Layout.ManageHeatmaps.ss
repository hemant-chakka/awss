<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="manageHeatmaps">
<div style="text-align: right;"></div>
<table style="width: 100%; height: 100px; text-align: right;" border="0">
<tbody style="text-align: left;">
<tr style="text-align: left;">
<td colspan="2" style="text-align: right;">
<table width="100%" border="0" cellpadding="0" cellspacing="3">
  <tbody><tr>
    <td>
		';

if ($scope->locally()->hasValue('getExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)||$scope->locally()->hasValue('getNonExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
			<a href="/create-heatmap/"><img src="/themes/attwiz/images/create_heatmap_button.jpg" alt=""></a>
		';


}else { 
$val .= '
			<img src="/themes/attwiz/images/button-create-heatmap-bw.png" alt="">
		';


}
$val .= '
	</td>
  </tr>
</tbody></table>
</td>
</tr>
';

if ($scope->locally()->hasValue('getExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)||$scope->locally()->hasValue('getNonExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
	&nbsp;
';


}else { 
$val .= '
	<tr style="text-align: left;">
<td colspan="2" style="text-align: center;" height="30px"><div style="text-align: left;" id="existing_users_msg">
<h2>Welcome to the new AttentionWizard website.</h2>
<p class="awbutton"><a href="';

if ($scope->locally()->obj('CurrentMember', null, true)->hasValue('SignUpTrial', null, true)) { 
$val .= '/member-trial-signup';


}else { 
$val .= '/trial-signup';


}
$val .= '"><img title="Try It Now" alt="Try It Now" src="/themes/attwiz/images/sign-up-one-penny-5.jpg"></a></p>
<p><strong>We have  discontinued our Lite service and its free daily low-resolution  heatmaps. </strong>We are pleased to now offer you three subscription options for creating full-featured AttentionWizard heatmaps (previously called Pro Heatmaps).</p>
<ul>
<li>Get 10 heatmaps for 1 dollar by enrolling  in the one month trial process. <a href="/account-settings/tabs/3">Get started here</a>.</li>
</ul>
<ul>
<li>
	';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
		<a href="/purchase-member-non-expiring-heatmaps">Purchase non-expiring heatmaps</a>
	';


}else { 
$val .= '
		<a href="/purchase-non-expiring-heatmaps">Purchase non-expiring heatmaps</a>
	';


}
$val .= '
</li>
</ul>
<p><strong> You will not be able to create any new heatmaps until you complete one of the two steps above</strong></p>
</div></td>
</tr>
';


}
$val .= '

<tr style="text-align: left;">
<td style="text-align: left;" width="90%"></td>
</tr>
<tr style="text-align: left;">
<td colspan="2" style="text-align: left;"></td>
</tr>
</tbody>
</table>
<br>
';

if ($scope->locally()->hasValue('recentHeatmap', null, true)) { 
$val .= '
	';

$scope->locally()->obj('recentHeatmap', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		<table id="download-recent-heatmap">
			<tr><td colspan = 3 class="title">Download Current Heatmap</td></tr>
			<tr>
				<td>';

$val .= $scope->locally()->obj('OriginalImage', null, true)->XML_val('CroppedImage', array('75', '90'), true);
$val .= '</td>
				<td>';

$val .= $scope->locally()->obj('Heatmap', null, true)->XML_val('CroppedImage', array('75', '90'), true);
$val .= '</td>
				<td class="download-button">
					';

if ($scope->locally()->hasValue('WatermarkHeatmapID', null, true)) { 
$val .= '
						<a href="';

$val .= $scope->locally()->XML_val('BaseHref', null, true);
$val .= 'manage-heatmaps/downloadHeatmap/';

$val .= $scope->locally()->XML_val('WatermarkHeatmapID', null, true);
$val .= '"><img src="/themes/attwiz/images/download.jpg"></img></a>
					';


}else { 
$val .= '
						<a href="';

$val .= $scope->locally()->XML_val('BaseHref', null, true);
$val .= 'manage-heatmaps/downloadHeatmap/';

$val .= $scope->locally()->XML_val('HeatmapID', null, true);
$val .= '"><img src="/themes/attwiz/images/download.jpg"></img></a>
					';


}
$val .= '
				</td>
			</tr>
		</table>
	';


}; $scope->popScope(); 
$val .= '
';


}
$val .= '
<h2>Recent Heatmaps:</h2>
</div>
</td>
</tr>
</tbody></table>
';

if ($scope->locally()->hasValue('PaginatedHeatmapsList', null, true)) { 
$val .= '
	<span class="article_separator">&nbsp;</span>
	<table id="heatmap-list">
		';

$scope->locally()->obj('PaginatedHeatmapsList', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
			<tr>
				<td width=70px>';

$val .= $scope->locally()->obj('OriginalImage', null, true)->XML_val('CroppedImage', array('60', '50'), true);
$val .= '</td>
				<td>
					<ul>
						<li><b>';

$val .= $scope->locally()->XML_val('UploadImageName', null, true);
$val .= '</b>, Created on ';

$val .= $scope->locally()->obj('Created', null, true)->XML_val('Nice', null, true);
$val .= '</li>
						<li>
							';

if ($scope->locally()->hasValue('WatermarkHeatmapID', null, true)) { 
$val .= '
								<a href="';

$val .= $scope->locally()->XML_val('BaseHref', null, true);
$val .= 'manage-heatmaps/downloadHeatmap/';

$val .= $scope->locally()->XML_val('WatermarkHeatmapID', null, true);
$val .= '">download</a>
							';


}else { 
$val .= '
								<a href="';

$val .= $scope->locally()->XML_val('BaseHref', null, true);
$val .= 'manage-heatmaps/downloadHeatmap/';

$val .= $scope->locally()->XML_val('HeatmapID', null, true);
$val .= '">download</a>
							';


}
$val .= '
							 | <a class = "delete-heatmap" href="';

$val .= $scope->locally()->XML_val('BaseHref', null, true);
$val .= 'manage-heatmaps/deleteHeatmap/';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '">delete</a>
						</li>
					</ul>
				</td>
			</tr>
    	';


}; $scope->popScope(); 
$val .= '
	</table>
	';

if ($scope->locally()->obj('PaginatedHeatmapsList', null, true)->hasValue('MoreThanOnePage', null, true)) { 
$val .= '
    	';

if ($scope->locally()->obj('PaginatedHeatmapsList', null, true)->hasValue('NotFirstPage', null, true)) { 
$val .= '
        	<a class="prev" href="';

$val .= $scope->locally()->obj('PaginatedHeatmapsList', null, true)->XML_val('PrevLink', null, true);
$val .= '">Prev</a>
    	';


}
$val .= '
	    ';

$scope->locally()->obj('PaginatedHeatmapsList', null, true)->obj('Pages', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
    	    ';

if ($scope->locally()->hasValue('CurrentBool', null, true)) { 
$val .= '
        	    ';

$val .= $scope->locally()->XML_val('PageNum', null, true);
$val .= '
	        ';


}else { 
$val .= '
    	        ';

if ($scope->locally()->hasValue('Link', null, true)) { 
$val .= '
        	        <a href="';

$val .= $scope->locally()->XML_val('Link', null, true);
$val .= '">';

$val .= $scope->locally()->XML_val('PageNum', null, true);
$val .= '</a>
            	';


}else { 
$val .= '
                	...
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

if ($scope->locally()->obj('PaginatedHeatmapsList', null, true)->hasValue('NotLastPage', null, true)) { 
$val .= '
    	    <a class="next" href="';

$val .= $scope->locally()->obj('PaginatedHeatmapsList', null, true)->XML_val('NextLink', null, true);
$val .= '">Next</a>
	    ';


}
$val .= '
	';


}
$val .= '
';


}else { 
$val .= '
	<p>You have not created any heatmaps yet.</p>
';


}
$val .= '













';

