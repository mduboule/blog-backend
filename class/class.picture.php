<?php
 
/*
* File: Picture.php [originaly SimpleImage.php]
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* Modified by Marius Duboule May 2012
*
*/
 
class Picture {
 
	protected $imageType,
			  $image,
			  $name,
			  $description,
			  $dateCreated,
			  $status,
			  $id;
   
	public function __construct(array $data) {
		$this->hydrate($data);
	}
   
	public function hydrate(array $data) {
		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
	}

 	public function setImageType($filename) {
		$image_info = getimagesize($filename);
		
		// L'image ne peut être que .gif .jpg .png
		if ($image_info[2] > 0 and $image_info[2] < 4) {
			$this->imageType = $image_info[2];
			return true;
		}
		else return false;
	}
 
	public function setImage($filename) {
		if($this->imageType == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} 
		else if($this->imageType == IMAGETYPE_GIF) {
			 $this->image = imagecreatefromgif($filename);
		} 
		else if($this->imageType == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}
	}
	
	public function save($filename, $imageType=IMAGETYPE_JPEG, $compression=85, $permissions=null) {
		$path = "img/gallery/" . $filename;
		if($imageType == IMAGETYPE_JPEG) {
			imagejpeg($this->image,$path,$compression);
		}
		else if($imageType == IMAGETYPE_GIF) {
			imagegif($this->image,$path);
		} 
		else if($imageType == IMAGETYPE_PNG) {
			imagepng($this->image,$path);
		}
		if($permissions != null) {
			chmod($path,$permissions);
		}
	}
	
	public function output($imageType=IMAGETYPE_JPEG) {
		if($imageType == IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} 
		else if($imageType == IMAGETYPE_GIF) {
			imagegif($this->image);
		} 
		else if($imageType == IMAGETYPE_PNG) {
			imagepng($this->image);
		}
	}
	
	public function setName($name) {
		$this->name = (is_string($name)) ? $name : null;
	}
	
	public function setDate($date) {
		$this->date = $date;
	}
	
	public function setStatus($status) {
		if ($status == "on" or $status == "off") {
			$this->status = $status;
		}
	}
	
	public function setId($id) {
		$id = (int) $id;
		if ($id > 0) {
			$this->id = $id;
        }
    }
	
	public function setDescription($desc) {
		$this->description = (is_string($desc)) ? $desc : null;
	}
	
	public function setDateCreated($date) {
		$this->dateCreated = $date;
	}
	
	public function getImage() {
		return $this->image;
	}
	
	public function getImageType() {
		return $this->imageType;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getShortName($len) {
		if (strlen($this->name) > $len) {			
  	 		$str = substr($this->name,0,$len) . "..." ;
			return $str;		
		}
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getDateCreated() {
		return $this->dateCreated;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getWidth() {
		return imagesx($this->image);
	}
	
	public function getHeight() {
		return imagesy($this->image);
	}
	
	public function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
 
	public function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}
 
	public function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}
 
	public function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}      
}
?>