<?php
Import::loadClass('BaseView');
class BaseController{
	
	/* This array will hold data we want to 
	*  expose to the templates 
	*  we return to users later
	*/
	protected $_data = array();
    
    
    /**
     * scriptMap js map
     * @var array
     */
    public $_scriptMap;
    
    /**
     * cssMap css map
     * @var array
     */
    public $_cssMap;
    
    /**
     * theme 
     * @var string
     */
    public $_theme;
    
	 /**
	  * _locale
	  *@var string
	  */
    public $_locale;
        
    /**
     * page process info 
     * @var string
     */

	public $_cookie;
	/* This function is called by DooPHP 
	*  before we run an action 
	*/

	public $_message;

	public $_nav_type;

    public function beforeRun( $resource, $action ){
		$this->_theme = 'zh-cn';		
		$this->_locale = 'zh-cn';		
		$this->setLayOut('default');

		Import::loadClass('CookieClass');
		$this->_cookie = new CookieClass();
		$this->_message = new MessageClass();
		$this->_message->msgFile = VIEWS_PATH.'errorMsg.html';
		
		$this->_data['keywords'] = "平台";
		$this->_data['description'] = "最大的时尚网站";

		$this->_nav_type = array('1'=>'models','2'=>'celebrities','3'=>'photographers','4'=>'retouching'); 
	}

    public function view(){
        if($this->_view==NULL){			
            $this->_view = new BaseView();
        }

        return $this->_view;
    }
    
    /**
     * public view set flash message;
     * @msg This is a &lt;div&gt; with the class <strong>.success</strong>. <a href="#">Link</a>.
     *       OR array('1'=>'not null')
     * @type 'success','info','notice','error'
     */
    public function setFlash($msg,$is_redirect=false,$auto_hide=false,$type='error') {
    	
    	if(empty($msg)) return;
    	
    	$message = '';
    	if($auto_hide)
    		$message = '<div id="flash_message" class="'.$type.'">';
    	else
        $message = '<div class="'.$type.'">';
      
      if(is_array($msg)){
      	foreach($msg as $k=>$v){
      		$message.= "<p>".$k.'. '.$v."</p>";
      	}
      }  
      else
      	$message .= $msg;
      	
      $message .= "</div>";
      
      if($is_redirect){
      	if(!isset($_SESSION)) session_start();
      	$_SESSION['flash_message'] = $message;
      }
      else
      	$this->_msg = $message;
    }
    
    /**
     * public view get flash message;
     * @echo string
     */
    public function getControllerName() {
    	return str_ireplace('Controller','',get_class($this));
    }
    

    
	/**
	* A simple tender function to render a provided view
	* @param string $view the actions view file (class should also define the viewsFolder)
	* @param bool $useLayout Should the view be rendered without the use of layout?
	*/
	
	protected function rendercAction($view, $useLayout = true) {  
	  $this->view()->rendercAction($view,$useLayout,$this->_data,$this);
	}
	
	/**
	* A simple tender function to render element a provided view
	* @param string $view the actions view file (class should also define the viewsFolder)
	* @param bool $useLayout Should the view be rendered without the use of layout?
	*/
	protected function rendercElement($view, $is_theme = true) {  
	  $this->view()->rendercElement($view,$is_theme,$this->_data,$this);
	}
	
	protected function setLayOut($name,$is_theme=true){
		$this->view()->setLayOut($name,$is_theme);
	}
	
	
	/**
     * protected route redirect useing beforeRun;
     * @param string $uri
     */
	protected function redirect($uri){
        header("Location: $uri", true, 302);
    }

	public function getCityByRemotIp()
	{
		Doo::loadClass('geoip/GeoIp');
		$geoip = new GeoIp();
		$city_name = $geoip->getCity();
		return $city_name;
	}
	
	public function getMobileAgent() {
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
		if(stristr($user_agent,"iPhone")) {
			return "IPhone";
		}
		else if(stristr($user_agent,"Android")){
			return "Andriod";
		}
		else {
			return "";
		}
	}
	
	public function collateRequest()
	{
		if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
		{
			if(isset($_GET))
				$_GET=$this->stripSlashes($_GET);
			if(isset($_POST))
				$_POST=$this->stripSlashes($_POST);
			if(isset($_REQUEST))
				$_REQUEST=$this->stripSlashes($_REQUEST);
			if(isset($_COOKIE))
				$_COOKIE=$this->stripSlashes($_COOKIE);
		}
	}


	
	public function getIsPostRequest()
	{
		return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'],'POST');
	}

	protected function stripSlashes(&$data)
	{
		return is_array($data)?array_map(array($this,'stripSlashes'),$data):stripslashes($data);
	}

}

