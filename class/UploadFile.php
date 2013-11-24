<?
/**
 +------------------------------------------------------------------------------
 * Spring Framework 文件上传
 +------------------------------------------------------------------------------
 * @date    2008-05-24
 * @mobile  13183857698
 * @oicq    78252859
 * @author  VOID(空) <lkf5_303@163.com>
 * @version 2.0
 +------------------------------------------------------------------------------
 */
class UploadFile
{
	public  $msg     = null;    //异常消息
	private $path    = null;    //上传文件路径
	public  $upFile  = null;    //上传到服务器上的文件名
	private $maxSize = null;    //上传文件最大大小
	private $upType  = null;    //上传文件类型

	/**
	 +----------------------------------------------------------
	 * 类的构造子
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __construct()
	{
	}

	/**
	 +----------------------------------------------------------
	 * 类的析构方法(负责资源的清理工作)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __destruct()
	{
		 $this->msg     = null;
		 $this->path    = null;
		 $this->upFile  = null;
		 $this->maxSize = null;
		 $this->upType  = null;
	}

	/**
	 +----------------------------------------------------------
	 * 属性访问器(写)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */
	public function __set($name,$value)
	{
		if(property_exists($this,$name))
		{
			$this->$name = $value;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 检查上传文件信息
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param  string  $name 文件名 
	 +----------------------------------------------------------
	 * @param  integer $size 文件大小
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	private function checkFile($name,$size)
	{
		if($size > $this->maxSize)
		{
			$this->msg = "上传文件 $name 超过规定大小!";
			return false;
		}
		//echo $this->upType."___".$name."____".$this->getFileType($name);
		if(!strstr(strtolower($this->upType),strtolower($this->getFileType($name))))
		{
			$this->msg = "没有上传.".$this->getFileType($name)."文件格式的权限";
			return false;
		}
		return true;
	}

	/**
	 +----------------------------------------------------------
	 * 获取文件扩展名
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $fileName   文件名  
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	private function getFileType($fileName)
	{
		$temp = explode(".",$fileName);
		return $temp[count($temp)-1];
	}

	/**
	 +----------------------------------------------------------
	 * 修改文件名
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $fileName   文件名  
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	private function changeFileName($fileName)
	{
		return date("Ymdhis").rand(11,99).".".$this->getFileType($fileName);
	}

	//获取文件名实体部分
	private function getFileNameBody($fileName)
	{
		$fileType = $this->getFileType($fileName);
		$fileBody = substr($fileName,0,strlen($fileName)-strlen($fileType)-1);
		return $fileBody;
	}

	/**
	 +----------------------------------------------------------
	 * 检查路径，不存在则创建
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	private function checkPath()
	{
		if(!file_exists($this->path))
		{
			$dirs = explode('/', $this->path);
			$total = count($dirs);
			$temp = '';
			for($i=0; $i<$total; $i++)
			{
				$temp .= $dirs[$i].'/';
				if (!is_dir($temp))
				{
					if(!@mkdir($temp)) 
					{
						$this->msg = "不能建立目录 $temp";
						return false;
					}
					@chmod($temp, 0777); // 改变目录权限 为0777
				}
			}
		}
		return true;
	}

	/**
	 +----------------------------------------------------------
	 * 文件上传
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param array   $file    上传文件信息  
	 +----------------------------------------------------------
	 * @param boolen  $change 文件是否更名  
	 +----------------------------------------------------------
	 * @return boolean
	 +----------------------------------------------------------
	 */
	public function upload($file,$change = true)
	{
		$img_array = array('gif','jpg','jpeg','bmp');
		if(!$this->checkPath()) return false;
		if(!$this->checkFile($file["name"],$file["size"])) return false;
		$fileName = ($change) ? $this->changeFileName($file["name"]) : $file["name"];
		$fileType = $this->getFileType($fileName);
		$file2 = $file;

		if(in_array($fileType,$img_array))
		{
			//缩略图处理
			$thumbPath = $this->path."/".$this->getFileNameBody($fileName)."_thumb.".$fileType;
			$res = $this->Resize($file2,$thumbPath,$fileType,151,120);
		}

		if(move_uploaded_file($file["tmp_name"],$this->path."/".$fileName))
		{
			$this->upFile = $fileName;
			return true;
		}
		$this->msg = "网络故障,上传失败";
		return false;
	}

	public function uploadArray($file_array,$change = true)
	{
		$img_array = array('gif','jpg','jpeg','bmp');
		if(!$this->checkPath()) return false;
		$file_names = array();
		foreach($file_array['name'] as $key=>$val){

			if(!isset($file_array["name"][$key]) && !empty($file_array['name'][$key])) continue;
			$fileName = ($change) ? $this->changeFileName($file_array["name"][$key]) : $file_array["name"][$key];
			$fileType = $this->getFileType($fileName);
			$file2['name'] = $file_array["name"][$key];
			$file2['type'] = $file_array["type"][$key];
			$file2['tmp_name'] = $file_array["tmp_name"][$key];
			$file2['error'] = $file_array["error"][$key];
			$file2['size'] = $file_array["size"][$key];

			if(in_array($fileType,$img_array))
			{
				//缩略图处理
				$thumbPath = $this->path.$this->getFileNameBody($fileName)."_thumb.".$fileType;
				$res = $this->Resize($file2,$thumbPath,$fileType,150,150);
			}

			if(move_uploaded_file($file_array["tmp_name"][$key],$this->path."/".$fileName)){
				$file_names[] = $fileName;				
			}
		}
		return $file_names;
	}

   //对图片进行处理；
   /*$files 即上传图片的名字
    *$upThumFile 保存路径
	*$kname 图片扩展名
	*/
    private function Resize($files , $upThumFile , $kname , $width , $height)
    {
        // 取得上传图片
        if ($kname=="gif"){
            if(!$src = imagecreatefromgif($files['tmp_name'])){
                $this->msg = "gif图片获取失败,可能是因为不支持的图片格式！";
                return false;
            }
        }else if($kname == "png"){
            if(!$src = imagecreatefrompng($files['tmp_name'])){
                $this->msg = "png图片获取失败,可能是因为不支持的图片格式！";
                return false;
            }
        }else { 
            if(!$src = imagecreatefromjpeg($files['tmp_name'])){
                $this->msg = "图片获取失败,可能是因为不支持的图片格式！";
                return false;
            }
        }
	

        // 取得來源圖片長寬
        if(!$src_w = imagesx($src)){
            $this->msg = "图片尺寸获取失败！";
            return false;
        }
        if(!$src_h = imagesy($src)){
            $this->msg = "图片尺寸获取失败！";
            return false;
        }

        // 假設尺寸需要限制在$width , $height之间；
        if(($src_w <= $width) && ($src_h <= $height)){
            //按原图片尺寸储存；
            if(!copy($files['tmp_name'],$upThumFile)){
                $this->msg = "缩略图存储失败！";
                return false; 
            }else{
                return true;
            }
        }

        if($src_w > $src_h){
            $thumb_w = $width;
            $thumb_h = intval($src_h / $src_w * $thumb_w);
        }else{
            $thumb_h = $height;
            $thumb_w = intval($src_w / $src_h * $thumb_h);
        }

        // 建立縮圖
        if($kname == "png"){
            if(!$thumb = imagecreate($thumb_w, $thumb_h)){
                $this->msg = "缩略图创建失败！";
                return false;
            }
        }else{
            if(!$thumb = imagecreatetruecolor($thumb_w, $thumb_h)){
                $this->msg = "缩略图创建失败！";
                return false;
            }
        }

        // 開始縮圖
        if(!imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h)){
            $this->msg = "缩图失败！";
            return false;
        }
        
        // 儲存縮圖到指定 thumb 目錄
        if ($kname=="gif"){
            if(!imagegif($thumb, $upThumFile)){
                $this->msg = "gif缩放图片保存失败!";
                return false;
            }
        }else if($kname == "png"){
            if(!imagepng($thumb, $upThumFile)){
                $this->msg = "png缩放图片保存失败!";
                return false;
            }
        }else{
            if(!imagejpeg($thumb, $upThumFile)){
                $this->msg = "缩放图片保存失败!";
                return false;
            }
        }
        return true; 
    }
}
?>
