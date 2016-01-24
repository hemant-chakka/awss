<?php
$val .= '<!DOCTYPE html>
<html>
	<head>
	';

$val .= SSViewer::get_base_tag($val);
$val .= '
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=720, maximum-scale=1.0" />
	<title>';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</title>
</head>
<body class="loading cms" lang="';

$val .= $scope->locally()->obj('Locale', null, true)->XML_val('RFC1766', null, true);
$val .= '" data-frameworkpath="';

$val .= $scope->locally()->XML_val('ModulePath', array('framework'), true);
$val .= '">
	';

$val .= SSViewer::execute_template('CMSLoadingScreen', $scope->getItem(), array(), $scope);

$val .= '
	
	<div class="cms-container center" data-layout-type="custom">
		';

$val .= $scope->locally()->XML_val('Menu', null, true);
$val .= '
		';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '

		<div class="cms-preview east" data-layout-type="border">
			<div class="preview-note"><span><!-- --></span>';

$val .= _t('CMSPageHistoryController_versions_ss.PREVIEW','Website preview');
$val .= '</div>
			<div class="preview-scroll center">
				<div class="preview-device-outer">
					<div class="preview-device-inner">
						<iframe src="about:blank" class="center" name="cms-preview-iframe"></iframe>
					</div>
				</div>
			</div>
			<div class="cms-content-controls cms-preview-controls south"></div>
		</div>
	</div>
	
	';

$val .= $scope->locally()->XML_val('EditorToolbar', null, true);
$val .= '
</body>
</html>
';

