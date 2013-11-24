<?php
require_once('GdImage.php');

class BaseGdImage extends GdImage{

	/**
     * Save the uploaded images in HTTP File Upload variables
     * 
     * @param string $filename The file field name in $_FILES HTTP File Upload variables
     * @return string The file name of the uploaded image.
     */
    public function uploadImages($filename){
		if(!is_array($filename)){
		 $img = !empty($_FILES[$filename]) ? $_FILES[$filename] : null;
		}else{
		 $img = $filename;
		}
        if($img==Null)return;    			
        if ($this->timeAsName){
            $pic = strrpos($img['name'], '.');
            $ext = substr($img['name'], $pic+1);
            $newName = date('Ymdhis').rand(1,992) . '.' . $ext;
        }
        else{
            $newName = $this->genName($img['name']);
        }  
		$path = $this->genPath('src',$newName);	
		if(empty($path)) return;			
        $imgPath = $path.$newName;		
        if (move_uploaded_file($img['tmp_name'], $this->uploadPath.$imgPath))
            return $imgPath;
    }
    
    /*
    *  issue create thumb image
    *	 @param array $sizes array('src'=>'src','100x100'=>'100x100','200x200'=>'200x200')
    */
    public function issueThumb($file,$sizes){
    		if(!is_array($sizes)) return false;
    		foreach($sizes as $k=>$v){
    			if($v != 'src'){
    				$wh_size = explode('x', $v);
    				if(!empty($wh_size)){
    					$newName = str_replace("src", $k, $file);
    					if($this->makeDir(dirname($this->uploadPath.$newName))){
							$calc_tag = '';
							if($v == '690x406') $calc_tag = 'auto';
    						$sizes[$k] = $this->createSThumb($file, $newName, $wh_size[0], $wh_size[1],$calc_tag);
						}
    					else
    						$sizes[$k] = false;
    				}
    				else
    					$sizes[$k] = false;
    			}
    		}
    		return $sizes;
    }
    
    /**
     * Resize/Generates thumbnail from an existing image file.
     * 
     * @param string $file The image file name.
     * @param string $newName The image file name.
     * @param int $width Width of the thumbnail
     * @param int $height Height of the thumbnail
     * @return bool|string Returns the generated image file name. Return false if failed.
     */
    public function createSThumb($file, $newName,$width=128, $height=128,$calc_tag=''){
        $file = $this->uploadPath . $file;
        $imginfo = $this->getInfo($file);
        //$newName = substr($imginfo['name'], 0, strrpos($imginfo['name'], '.')) . $this->thumbSuffix .'.'. $this->generatedType;

        //create image object based on the image file type, gif, jpeg or png
        $this->createImageObject($img, $imginfo['type'], $file);

        if(!$img) return false;

        $width  = ($width > $imginfo['width']) ? $imginfo['width'] : $width;
        $height = ($height > $imginfo['height']) ? $imginfo['height'] : $height;
        $oriW = $imginfo['width'];
        $oriH = $imginfo['height'];

		if(!empty($calc_tag))
		{
			//maintain ratio
			if($oriW*$width > $oriH*$height)
				$height = round($oriH * $width/$oriW);
			else
				$width = round($oriW * $height/$oriH);
		}

		$width = round($oriW * $height/$oriH);


        //For GD version 2.0.1 only
        if (function_exists('imagecreatetruecolor')){
            $newImg = imagecreatetruecolor($width, $height);
            imagecopyresized($newImg, $img, 0, 0, 0, 0, $width, $height, $imginfo['width'], $imginfo['height']);
        }
        else{
            $newImg = imagecreate($width, $height);
            imagecopyresized($newImg, $img, 0, 0, 0, 0, $width, $height, $imginfo['width'], $imginfo['height']);
        }

        if($this->saveFile){
            //delete if exist
            if(file_exists($this->processPath . $newName))
                unlink($this->processPath . $newName);
            $this->generateImage($newImg, $this->processPath . $newName);
            imagedestroy($newImg);
            imagedestroy($img);
            return $this->processPath . $newName;
        }
        else{
            $this->generateImage($newImg);
            imagedestroy($newImg);
            imagedestroy($img);
        }
        
        return true;
    }
    
    /*
    * make dir recursive
    */
    private function makeDir($path,$permission=0777){
    		if(is_dir($path)) return true;
    		
    		$oldumask = umask(0);
				$res = @mkdir($path, $permission,true);
				umask($oldumask);
				
				return $res;
    }
    
    /*
    *  generate image file name by crc32
    */
    private function genName($name){
    	$c_ip = $_SERVER['REMOTE_ADDR'].$_SERVER['REMOTE_PORT'];
    	$img_name = crc32($name.$c_ip);
			return sprintf("%u", $img_name);
    }
    
    /*
    *  generate image path 
    */
    private function genPath($issue_path,$name){
    	$path = $issue_path.'/';
    	$name = substr($name,0,strrpos($name, '.'));

		/*
		$mod = substr($name,(strlen($name) -8));
    	$path .= ($mod % 8) + 1;   //mod value
		*/

    	$path .= date('Ymd').'/';
    	
    	if(!$this->makeDir($this->uploadPath.$path))
    		return '';
    		
			return $path;
    }
}

?>