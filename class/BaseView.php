<?php
class BaseView{
	
    /**
     * layout 
     * @var string
     */
    public $_layout;
    
    /**
     * layout theme 
     * @var bool
     */
    public $_layout_theme;
    /**
     * view file path 
     * @var string
     */
    public $_vpath;
    
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

    public function __construct(){
    	$this->_vpath = VIEWS_PATH;
    }
    
	 /**
	* A simple tender function to render a provided view
	* @param string $view the actions view file (class should also define the viewsFolder)
	* @param bool $useLayout Should the view be rendered without the use of layout?
	*/
	public function rendercAction($view, $useLayout = true,&$data=NULL, &$controller=NULL) {  
	  ob_start();
	  $this->data = $data;
      $this->controller = $controller;

	  if ($useLayout) {
	     $this->rendercLayout($view);
	  } else {
	  	$c_name = $this->_vpath . 'themed/'.$this->controller->_theme .  "/$view.php";
	    include $c_name;
	  }
	  ob_end_flush();
	}
	
	public function setLayOut($name,$is_themed=true){
		$this->_layout = $name;
		$this->_layout_theme = $is_themed;
	}
	
	/**
	* A simple tender function to render a provided view
	* @param string $view the actions view file 
	*
	*/
	public function rendercLayout($view) {
		if(empty($this->_layout)) return;
		
		if($this->_layout_theme)
			$l_name = $this->_vpath . 'themed/'.$this->controller->_theme . "/layout/".$this->_layout.".php";
		else
			$l_name = $this->_vpath . "layout/".$this->_layout.".php";

		if(!empty($this->controller->_theme))
			$c_name = $this->_vpath . 'themed/'.$this->controller->_theme . "/$view.php";
		else
			$c_name = $this->_vpath . "$view.php";

        $this->data['layout'] = $c_name;
       
        include $l_name;
    }
    /**
	* A simple tender function to layout element a provided view
	* @param string $name the layout element file
	*
	*/
    public function element($name,$is_theme=true){
    	$theme = '';
    	if($is_theme)
    		$theme = 'themed/'.$this->controller->_theme.'/';
    	
    	$e_name = $this->_vpath .$theme. "element/$name.php";
    	include $e_name;
    }
    
	public function rendercElement($view,$is_theme=true,&$data=NULL, &$controller=NULL) {
		ob_start();
	  	$this->data = $data;
      	$this->controller = $controller;
	
    	if($is_theme){
		if(!empty($this->controller->_theme))
			$l_name = $this->_vpath . 'themed/'.$this->controller->_theme."/element/$view.php";
		else
			$l_name = $this->_vpath . "element/$view.php";
		} 
        include $l_name;
        ob_end_flush();
    }
    
	/**
     * public view loadLink();
     * @echo string
     */
    public function loadLink(){
    	$link = '';
    	if(is_array($this->controller->_cssMap)){
			foreach($this->controller->_cssMap as $key=>$value){
					$link .= "<link type='text/css' href='".PUBLIC_URL.$value."' rel='stylesheet'>\n";
			}
    	}
		
    	return $link;
    }
    
    /**
     * public view loadScript();
     * @echo string
     */
    public function loadScript(){
    	$script = '';
    	if(is_array($this->controller->_scriptMap)){
			foreach($this->controller->_scriptMap as $key=>$value){
				$script .= "<script type='text/javascript' src='".PUBLIC_URL.$value."'></script>\n";
			}
    	}
    	return $script;
    }

	/**
     * public view get flash message;
     * @echo string
     */
    public function getFlash($is_out=false) {
      $msg = '';
      if(!empty($this->controller->_msg)){
      	$msg = $this->controller->_msg;
      }
      else{
      	if(!isset($_SESSION)) session_start();
      	
      	if(isset($_SESSION['flash_message'])){
      		$msg = $_SESSION['flash_message'];
      		unset($_SESSION['flash_message']);
      	}
      }
      if($is_out) return $msg;
      
      echo $msg;
    }
}
?>
