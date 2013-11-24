<?php
/**
 * Sns Gateway
 *
 * @author hesen2006cn@126.com
 * create_time : 2011-06-23
 */
 
 class SnsWidget{
 	//app key
 	private $_akeys = null;
 	//app secret
 	private $_skeys = null;
 	
 	public function __construct($akeys,$skeys){
 		$this->_akeys = $akeys;
 		$this->_skeys = $skeys;
 	}
 	// probe login to website
 	public function probeLogin($website){
 		
 	}
}
