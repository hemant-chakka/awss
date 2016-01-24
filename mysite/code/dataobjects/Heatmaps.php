<?php
 
class Heatmaps extends DataObject {
    private static $db = array(
        "HeatmapType" => 'Int',
    	"UploadImageName" => 'Varchar(255)',
    	"Deleted" => 'Boolean'
    );
    
    private static $has_one = array(
    	"Member" => "Member",
    	"OriginalImage" => "Image",
    	"Heatmap" => "Image",
    	"WatermarkHeatmap" => "Image"
    );
    
    private static $summary_fields = array(
		    'Member.Name',
    		'Member.Email',
    		'UploadImageName'
    );
    
    private static $searchable_fields = array(
   		'Member.FirstName',
   		'Member.Surname',
   		'Member.Email',
   		'UploadImageName',
   		'Heatmap.Name',
    	'Deleted'
   );
   
    private static $field_labels = array(
   		'Member.Name' => 'Member Name',
   		'Member.Email' => 'Member Email',
    	'Member.Surname' => 'Member Last Name',
   		'UploadImageName' => 'Original Image Name'
   );

}