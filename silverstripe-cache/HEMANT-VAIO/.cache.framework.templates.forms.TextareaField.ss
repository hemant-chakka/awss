<?php
$val .= '<textarea ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= '>';

$val .= $scope->locally()->XML_val('Value', null, true);
$val .= '</textarea>';

