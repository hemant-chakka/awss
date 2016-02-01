<?php
class FaqHolder extends Page {

 public function getFaqs(){
 	return Faq::get()->sort('SortOrder');
 }

}
class FaqHolder_Controller extends Page_Controller {

	public function init() {
		parent::init();
		//Requirements::themedCSS('jquery-ui-1.11.2');
		Requirements::themedCSS('jquery-ui');
		Requirements::themedCSS('jquery-ui.theme');
		Requirements::themedCSS('jquery-ui.structure');
		Requirements::javascript('mysite/js/jquery-1.9.1.js');
		Requirements::javascript('mysite/js/jquery-ui.js');
		Requirements::javascript('mysite/js/faqs.js');
	}
}