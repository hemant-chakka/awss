<?php
$val .= '<div class="cms-actions-row">
	<a class="cms-page-add-button ss-ui-button ss-ui-action-constructive" data-icon="add" href="';

$val .= $scope->locally()->XML_val('LinkPageAdd', null, true);
$val .= '" data-url-addpage="';

$val .= $scope->locally()->XML_val('LinkPageAdd', array('', 'ParentID=%s'), true);
$val .= '">';

$val .= _t('CMSMain.AddNewButton','Add new');
$val .= '</a>
	<button href="';

$val .= $scope->locally()->XML_val('LinkPagesWithSearch', null, true);
$val .= '" class="cms-tree-expand-trigger cms-panel-link ss-button" data-icon="pencil">
		';

$val .= _t('CMSMain.EditTree','Edit Tree');
$val .= '
	</button>
</div>';

