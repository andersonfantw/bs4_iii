<?php
require_once LIBS_PATH.'/class.upload/class.upload.php';
class uploadimage extends upload {

  private $file_upload_path =  null;
  public function __construct($file,$path='')
  {
    parent::upload($file,'zh_TW');
    if(!empty($path)){
      $this->file_upload_path = $path;
    }
  }

  private function _create()
  {
    if ($this->uploaded && !empty($this->file_upload_path) ) {
      $this->Process($this->file_upload_path);
      if ($this->processed) {
        return true;
      }else{
        return false;
      }
    }
  }

  public function createimage($filename,$width=NULL,$height=NULL,$option=NULL)
  {
      $this->file_new_name_body = $filename;
      $this->image_resize = true;
      // limit larger side ,keep ratio
      if( !empty($width) && !empty($height) ){
        $this->image_ratio = true;
        $this->image_y = $height;
        $this->image_x = $width;
      }
      // resize to $height , width auto
      else if( empty($width) && !empty($height) ){
        $this->image_ratio_x = true;
        $this->image_y = $height;
      }
      // resize to $width , height auto
      else if( !empty($width) && empty($height) ){
        $this->image_ratio_y = true;
        $this->image_x = $width;
      }
      // no resize
      else{
        $this->image_resize = false;
      }
	
	  if($option=='crop')
		$this->image_ratio_crop = true;
	  
      if ( $this->_create() ) {
        return true;
      }else{
        return false;
      }
  }

}
