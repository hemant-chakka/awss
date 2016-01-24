<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '
<div id="PrepaidSignupForm">
	';

$val .= $scope->locally()->XML_val('PrepaidSignupForm', null, true);
$val .= '
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg1" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline1">Inline</a></p>
<div style="display: none;">
     <div id="inline1" style="width:400px;height:150px;">
           <div id="alert-BoxContenedor" class="BoxAlert">
                 <h2>Oops. There was a problem.</h2>
                 <br>
                 <b>There is already</b> an AttentionWizard account associated with  
                 <b>the</b> email address <b><span id="EmailAddress"></span></b>. 
                 <b>Please use the</b> Customer Login to access your account, 
                 <b>or enter another email address</b>.
           </div>
      </div>
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg2" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline2">Inline</a></p>
<div style="display: none;">
     <div id="inline2" style="width:400px;height:100px;">
           <div id="alert-BoxContenedor" class="BoxAlert">
                 <h2>Oops. There was a problem.</h2>
                 <br>
                 * Password and Confirm Password fields do not match, 
                 <b>please re-enter them</b>
           </div>
      </div>
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg3" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline3">Inline</a></p>
<div style="display: none;">
     <div id="inline3" style="width:400px;height:100px;">
           <div id="alert-BoxContenedor" class="BoxAlert">
			  <h2>We\'re Sorry.</h2><br>
				This credit card has previously been used for the one cent trial. 
				Only one trial may be purchased per credit card.
		   </div>
      </div>
</div>

<p style="display: none;"><a class="fancybox2" id="inlineMsg4" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline4">Inline</a></p>
<div style="display: none;">
     <div id="inline4" class="Loading" style="width:400px;height:100px;">
           <div>
			 <h1>Loading...</h1>
			 <span class="waiting">Please wait,this transaction may take several minutes.</span>
			 <div><centre><img src="/themes/attwiz/images/spiffygif_white.gif"</center></div>
			</div>
      </div>
</div>

<p style="display: none;"><a class="fancybox" id="inlineMsg5" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline5">Inline</a></p>
<div style="display: none;">
     <div id="inline5" style="width:400px;height:100px;">
         <div id="alert-BoxContenedor" class="BoxAlert">
           	<h2>Oops. There was a problem.</h2>
           	Sorry, credit card number that you have entered is not valid. 
           	Please enter it again.
         </div>
      </div>
</div>
';

