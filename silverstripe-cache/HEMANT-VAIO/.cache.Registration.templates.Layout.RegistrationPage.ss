<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '	
<div class="signup_box">
	<h3 class="h3_heading">Create Your Account</h3>
	<div id="signupinfo">
		';

$val .= $scope->locally()->XML_val('RegistrationForm', null, true);
$val .= '
	</div>
</div>
<p style="display: none;"><a class="fancybox" id="showEmailErrorMessage" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
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

<p style="display: none;"><a class="fancybox" id="showEmailErrorMessage2" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline12">Inline</a></p>
<div style="display: none;">
     <div id="inline12" style="width:400px;height:150px;">
           <div id="alert-BoxContenedor" class="BoxAlert">
                 <h2>Oops. There was a problem.</h2>
                 <br>
                 * Please enter a valid email address
           </div>
      </div>
</div>




<p style="display: none;"><a class="fancybox" id="showPasswordErrorMessage" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
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

<p style="display: none;"><a class="fancybox" id="listErrorMessages" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline3">Inline</a></p>
<div style="display: none;">
     <div id="inline3" style="width:400px;height:100px;">
           <div id="alert-BoxContenedor" class="BoxAlert">
                 <h2>Oops. There was a problem.</h2>
                 <br>
                 <span id="ErrorMessages"></span> 
           </div>
      </div>
</div>';

