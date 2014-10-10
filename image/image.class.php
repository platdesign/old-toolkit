<?PHP

class image {
	var $src;
	var $handler;
	var $info;
	var $binaryString;
	var $baseString;
	var $newImg;
	var $quality;
	var $tmpFile;
	
	
	function __construct($src,$isData=null) {
		if($isData) {
			$this->binaryString = $src;
			$this->src 	= tempnam(NULL,"img_");
			$handler 	= fopen($this->src,"w");
			fwrite($handler,$this->binaryString,strlen($this->binaryString));
			fclose($handler);
			
		} else {
			$this->src = $src;
		}
	    
		$this->getInfo();
	}
	
	function getInfo() {
		if(!isset($this->info)) {
			if(file_exists($this->src)) {
				$this->info = getimagesize($this->src);
				$this->info['size'] = filesize($this->src);
			}
		}
		return($this->info);
	}
	
	function fileExtension() {
		$mime = $this->getMime();
		$return = file::extension($mime);
		return($return);
	}
	
	function getMime() {
		$info = $this->getInfo();
		return($info['mime']);
	}

	function getWidth() {
		$info = $this->getInfo();
		return($info[0]);
	}
	
	function getHeight() {
		$info = $this->getInfo();
		return($info[1]);
	}

	function getRatio() {
		$ratio = $this->getWidth() / $this->getHeight();
		return($ratio);
	}

	function getBase64() {
		if(!$this->binaryString) {
			$this->binaryString = $this->getString();
		}
		if(!$this->baseString) {
			$this->baseString = base64_encode($this->binaryString);
		}
		
		return($this->baseString);
	}

	function getString() {
		if($this->binaryString) {
			$this->binaryString = fread(fopen($this->src, "r"), $this->info['size']);
		}
		
		if($this->newImg) {
			
			ob_start();
			imagejpeg($this->newImg,NULL,$this->quality);
			$this->binaryString = ob_get_contents();
			ob_end_clean();
		}
		
		return($this->binaryString);
	}

	function setQuality($quality=null) {
		if(!$quality) {
			$this->quality = 100;
		} else {
			$this->quality = $quality;
		}
	}

	function resize($width=null,$height=null,$quality=null) {
		$this->setQuality($quality);
		$this->info['mime'] = "image/jpeg";
		$info = $this->getInfo();
		
		switch($info[2]) {
			case "1": $resizeImg = imagecreatefromgif($this->src); break;
			case "2": $resizeImg = imagecreatefromjpeg($this->src);break;
			case "3": $resizeImg = imagecreatefrompng($this->src); break;
			default:  $resizeImg = imagecreatefromjpeg($this->src);
		}
		
		$oWidth 	= $this->getWidth();
		$oHeight 	= $this->getHeight();
		
		if($width AND !$height) {
			$zoom 		= $width / $oWidth;
			$newWidth 	= $width;
			$newHeight 	= $oHeight * $zoom;
		}
		
		if($height AND !$width) {
			$zoom 		= $height / $oHeight;
			$newWidth 	= $oWidth * $zoom;
			$newHeight 	= $height;
		}
		
		if($height AND $width) {
			$newWidth 	= $width;
			$newHeight 	= $height;
		}
		
		if($newHeight > $oHeight) 	{ $newHeight = $oHeight; }
		if($newWidth > $oWidth) 	{ $newWidth = $oWidth; }
		
		if($newWidth == $oWidth AND $newHeight == $oHeight) {
			$this->newImg = $resizeImg;
		} else {
			$newImg = imagecreate($newWidth, $newHeight); 
			$newImg = imagecreatetruecolor($newWidth,$newHeight); 
			imagecopyresampled($newImg,$resizeImg,0,0,0,0,$newWidth,$newHeight,$oWidth,$oHeight);
			$this->newImg = $newImg;
		}
		
	}

	function outputNewImg() {
		imagejpeg($this->newImg,NULL,$this->quality);
	}

	function saveNewImg($uri) {
		imagejpeg($this->newImg,$uri,$this->quality);
	}

	// Nicht vergessen nach getString() und getBase64() !!!
	function close() {
		if($this->tmpFile) {
			unlink($this->tmpFile);
		}
		
		if($this->newImage) {
			imagedestroy($this->newImage);
		}
	}
}

?>