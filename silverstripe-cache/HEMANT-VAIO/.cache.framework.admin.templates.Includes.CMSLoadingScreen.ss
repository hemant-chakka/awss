<?php
$val .= '<div class="ss-loading-screen">
	<div class="loading-logo">
		<img class="loading-animation" src="';

$val .= $scope->locally()->XML_val('ModulePath', array('frameworkadmin'), true);
$val .= '/images/spinner.gif" alt="';

$val .= _t('CMSLoadingScreen_ss.LOADING','Loading...');
$val .= '" />
		<noscript><p class="nojs-warning"><span class="message notice">';

$val .= _t('CMSLoadingScreen_ss.REQUIREJS','The CMS requires that you have JavaScript enabled.');
$val .= '</span></p></noscript>
	</div>
</div>
';

