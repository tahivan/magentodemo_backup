<?php
	class SD5Qsoft_ImgGallery_Helper_ObjectHelper extends Mage_Core_Helper_Abstract
	{
		public function replaceUnicode($str){
           if(!$str) return false;
           $unicode = array(
              'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|À|Á|Ả|Ạ|Ã|Ă|Ằ|Ắ|Ẳ|Ặ|Ẵ|Â|Ầ|Ấ|Ẩ',
              'd'=>'đ|Đ',
              'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|È|É|Ẻ|Ẹ|Ẽ|Ê|Ề|Ế|Ể|Ệ|Ễ',
              'i'=>'í|ì|ỉ|ĩ|ị|Ì|Í|Ỉ|Ị|Ĩ',
              'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ò|Ó|Ỏ|Ọ|Õ|Ô|Ồ|Ố|Ổ|Ộ|Ỗ|Ơ|Ờ|Ớ|Ở|Ợ|Ỡ',
              'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ù|Ú|Ủ|Ụ|Ũ|Ư|Ừ|Ứ|Ử|Ự|Ữ',
              'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Y|Ỳ|Ý|Ỷ|Ỵ|Ỹ',
           );
           foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);

           $str = strtolower(trim($str));
           $str = preg_replace('/[^a-z0-9-]/', '-', $str);
           $str = preg_replace('/-+/', "-", $str);
           return $str;
        }
	}
?>