<?php
class ManageHeatmaps extends Page {

}
class ManageHeatmaps_Controller extends Page_Controller {
	private static $allowed_actions = array('downloadHeatmap','deleteHeatmap');
	private static $url_handlers = array(
        'downloadHeatmap/$ID' => 'downloadHeatmap',
		'deleteHeatmap/$ID' => 'deleteHeatmap',
    );
	function init(){
		parent::init();
		Requirements::javascript('mysite/js/manage-heatmaps.js');
	}
    
    //Paginate the list of heatmaps created by the user
	public function PaginatedHeatmapsList() {
    	$member = Member::currentUser();
		$paginatedList = new PaginatedList(Heatmaps::get()->filter(array('MemberID' => $member->ID,'Deleted' => 0))->sort('Created', 'DESC'), $this->request);
		if(isset($_GET['start'])){
			if($paginatedList->CurrentPage() > $paginatedList->TotalPages())
				$this->redirect($paginatedList->LastLink());
		}
		return $paginatedList;
	}
	//Download the heatmap image
	public function downloadHeatmap($request){
		$imageId = $request->param('ID');
		$image = File::get()->byID($imageId);
		return SS_HTTPRequest::send_file(file_get_contents(Director::absoluteBaseURL().$image->Filename), $image->Name);
	}
	//Delete heatmap
	public function deleteHeatmap($request){
		$Id = $request->param('ID');
		$heatmaps = Heatmaps::get()->byID($Id);
		$originalImage = File::get()->byID($heatmaps->OriginalImageID);
		$heatmap = File::get()->byID($heatmaps->HeatmapID);
		$watermarkHeatmap = File::get()->byID($heatmaps->WatermarkHeatmapID);
		$originalImage->delete();
		$heatmap->delete();
		if($watermarkHeatmap)
			$watermarkHeatmap->delete();
		$heatmaps->Deleted = 1;
		$heatmaps->write();
		return $this->redirectBack();
	}
	//Get recent heatmap
	public function recentHeatmap(){
		$member = Member::currentUser();
		$heatmap = Heatmaps::get()->filter(array('MemberID' => $member->ID))->sort('Created', 'DESC')->first();
		if($heatmap){
			$created = strtotime($heatmap->Created);
			$diffHours = (time() - $created)/(60*60);
			if($diffHours <= 1)
				return $heatmap;
		}
		return false;
	}
	
	
}