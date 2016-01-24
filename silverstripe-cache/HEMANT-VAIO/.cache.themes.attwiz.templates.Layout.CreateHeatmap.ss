<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '
<div id="TrialSignupForm">
	';

$val .= $scope->locally()->XML_val('CreateHeatmapForm', null, true);
$val .= '
</div>

<p style="display: none;"><a class="fancybox2" id="inlineMsg1" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline1">Inline</a></p>
<div style="display: none;">
     <div id="inline1" class="Loading" style="width:400px;height:100px;">
           <div>
			 <h1>Creating Heatmap...</h1>
			 <span class="waiting">Please wait,this transaction may take several minutes.</span>
			 <div><centre><img src="/themes/attwiz/images/spiffygif_white.gif"</center></div>
			</div>
      </div>
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg2" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline2">Inline</a></p>
<div style="display: none;">
     <div id="inline2" style="width:400px;height:100px;">
         <div id="alert-BoxContenedor" class="BoxAlert">
           	<h2>Oops. There was a problem.</h2>
           	You do not have enough heatmap credits to create a heatmap!
         </div>
      </div>
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg3" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline3">Inline</a></p>
<div style="display: none;">
     <div id="inline3" style="width:400px;height:100px;">
         <div id="alert-BoxContenedor" class="BoxAlert">
           	<h2>Oops. There was a problem.</h2>
           	Image should be 500-1600 pixels wide by 500-1200 pixels height!
         </div>
      </div>
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg4" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline4">Inline</a></p>
<div style="display: none;">
     <div id="inline4" style="width:400px;height:100px;">
         <div id="alert-BoxContenedor" class="BoxAlert">
           	<h2>Oops. There was a problem.</h2>
           	Heatmap could not be created due to some reason, please try again.
         </div>
      </div>
</div>';

