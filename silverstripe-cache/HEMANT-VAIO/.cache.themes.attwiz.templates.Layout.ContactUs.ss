<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '
<div id="ContactUsForm">
	Please notice fields marked with ( *) are required.
	';

$val .= $scope->locally()->XML_val('ContactUsForm', null, true);
$val .= '
</div>
<p><a class="fancybox" id="showEmailErrorMessage" href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#inline1">Inline</a></p>
<div style="display: none;background-color:transparent;">
        <div id="inline1" style11="width:200px;height:100px;">
                   Write whatever text you want right here!!
    </div>
</div>';

