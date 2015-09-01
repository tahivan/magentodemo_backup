<?php

/**
 * Copyright [2014] [Dexxtz]
 *
 * @package   Dexxtz_Contactus
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

class Dexxtz_Contactus_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getTemplate()
	{
		$active = Mage::getStoreConfig('dexxtz_contactus/general/active');
		
		if ($active == 1) {
			return 'dexxtz_contactus/general/template';
		} else {
			return 'contacts/email/email_template';
		}
	}
	
	private function getDepartments()
	{
		$array = unserialize(Mage::getStoreConfig('dexxtz_contactus/general/department'));
		$dpts = array();
		
		foreach ($array as $key => $value) {
			$dpts[] = $value['department'];
		}
		
		return $dpts;
	}
	
	public function getDepartmentsOptions()
	{
		$items = $this->getDepartments();
		$opts = array();
		
		foreach ($items as $opt) {
			$opts[] = '<option value="' . $opt . '">' . $opt . '</option>';	
		}
		
		return $opts;
	}
	
	public function getTelephone()
	{
		$telephone = Mage::getStoreConfig('dexxtz_contactus/general/show_telephone');
		
		return $telephone;
	}
	
	public function getFax()
	{
		$fax = Mage::getStoreConfig('dexxtz_contactus/general/show_fax');
		
		return $fax;
	}
	
	public function addMask($maxLength, $storeCode)
	{
		$mask = Mage::getStoreConfig('dexxtz_contactus/general/show_mask');
		
		if ($mask == 1 && $storeCode == 'pt_BR') {
			return 'onkeypress="maskDexxtz(this,maskTelephone)" maxlength="' . $maxLength . '"';
		}		
	}
	
	public function getOS() { 
	    $user_agent  = $_SERVER['HTTP_USER_AGENT'];
	    $os_platform =   "Unknown OS Platform";
	    $os_array    =   array('/windows nt 6.3/i'     =>  'Windows 8.1',
	                           '/windows nt 6.2/i'     =>  'Windows 8',
	                           '/windows nt 6.1/i'     =>  'Windows 7',
	                           '/windows nt 6.0/i'     =>  'Windows Vista',
	                           '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
	                           '/windows nt 5.1/i'     =>  'Windows XP',
	                           '/windows xp/i'         =>  'Windows XP',
	                           '/windows nt 5.0/i'     =>  'Windows 2000',
	                           '/windows me/i'         =>  'Windows ME',
	                           '/win98/i'              =>  'Windows 98',
	                           '/win95/i'              =>  'Windows 95',
	                           '/win16/i'              =>  'Windows 3.11',
	                           '/macintosh|mac os x/i' =>  'Mac OS X',
	                           '/mac_powerpc/i'        =>  'Mac OS 9',
	                           '/linux/i'              =>  'Linux',
	                           '/ubuntu/i'             =>  'Ubuntu',
	                           '/iphone/i'             =>  'iPhone',
	                           '/ipod/i'               =>  'iPod',
	                           '/ipad/i'               =>  'iPad',
	                           '/android/i'            =>  'Android',
	                           '/blackberry/i'         =>  'BlackBerry',
	                           '/webos/i'              =>  'Mobile'
	                        );	
	    foreach ($os_array as $regex => $value) { 
	        if (preg_match($regex, $user_agent)) {
	            $os_platform =  $value;
	        }
	    }
//        echo "<pre>"; var_dump($os_platform);
	    return $os_platform;
	}
	
	public function getBrowser(){
	    $user_agent = $_SERVER['HTTP_USER_AGENT'];
	    $browsers = array(
	                        'Chrome' => array('Google Chrome','Chrome/(.*)\s'),
	                        'MSIE' => array('Internet Explorer','MSIE\s([0-9\.]*)'),
	                        'Firefox' => array('Firefox', 'Firefox/([0-9\.]*)'),
	                        'Safari' => array('Safari', 'Version/([0-9\.]*)'),
	                        'Opera' => array('Opera', 'Version/([0-9\.]*)')
	                        );
	                         
	    $browser_details = array();
	     
	        foreach ($browsers as $browser => $browser_info){
	            if (preg_match('@'.$browser.'@i', $user_agent)){
	                $browser_details['name'] = $browser_info[0];
	                    preg_match('@'.$browser_info[1].'@i', $user_agent, $version);
	                $browser_details['version'] = $version[1];
	                    break;
	            } else {
	                $browser_details['name'] = 'Unknown';
	                $browser_details['version'] = 'Unknown';
	            }
	        }	     
	    return $browser_details['name'] . ' - ' . $browser_details['version'];
	}
	
	public function getGeolocation() {
	    $ipInfo = json_decode(file_get_contents("http://ipinfo.io/json"), true);
		$city   = ($ipInfo['city']) ? $ipInfo['city'] . ' - ' : '';
		$region = ($ipInfo['region']) ? $ipInfo['region'] . ' - ' : '';
		$contry = ($ipInfo['country']) ? $ipInfo['country'] : '';
	    return ($city . $region . $contry);
	}
	
	public function strposa($haystack, $needles = array(), $offset = 0) {
        $chr = array();
        foreach($needles as $needle) {
                $res = strpos($haystack, $needle, $offset);
                if ($res !== false) $chr[$needle] = $res;
        }
        if(empty($chr)) return false;
        return min($chr);
	}
}	
?> 