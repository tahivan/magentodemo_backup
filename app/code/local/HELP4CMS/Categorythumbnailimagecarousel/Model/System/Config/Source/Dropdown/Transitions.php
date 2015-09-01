<?php
/*
 * Edition : Community 
 * Package: HELP4CMS_Categorythumbnailimagecarousel
 * Author: Mudit Kumawat 
 * Email: muditkumawat19@gmail.com / muditkumawat007@gmail.com
*/

class HELP4CMS_Categorythumbnailimagecarousel_Model_System_Config_Source_Dropdown_Transitions
{
    public function toOptionArray()
  {
    return array(
      array('value' =>'fade', 'label' =>'Fade'),
      array('value' =>'horizontal', 'label' => 'Horizontal'),
      array('value' =>'vertical', 'label' =>'Vertical'),
	  array('value' =>'kenburns', 'label' =>'kenburns'),
     
    );
  }
}