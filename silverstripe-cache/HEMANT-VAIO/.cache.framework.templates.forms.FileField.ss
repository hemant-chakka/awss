<?php
$val .= '<input type="hidden" name="MAX_FILE_SIZE" value="';

$val .= $scope->locally()->XML_val('MaxFileSize', null, true);
$val .= '" />
<input ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= ' />
';

