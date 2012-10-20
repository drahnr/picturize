<?php

/*******************************************************
* CLASS TO CROP A PICTURE
* by Rendair
* http://www.talkphp.com/advanced-php-programming/1709-cropping-images-using-php.html
********************************************************/

/********** CONFIGURATION *****************************/

$src= $_GET['src'];			// picture url
$size= "165";				// picture wanted max size

/********** THE FUNCTIONS *****************************/

class cropImage{
	// init variable
	var $imgSrc,$myImage,$cropHeight,$cropWidth,$x,$y,$thumb;

	function setImage($image) {
		// your image
		$this->imgSrc = $image; 
		// getting the image dimensions
		list($width, $height) = getimagesize($this->imgSrc); 
		// create image from the jpeg / png / gif
		$im = getimagesize($this->imgSrc);
		if($im){
			if($im['mime'] == 'image/jpeg'){
				$this->myImage = imagecreatefromjpeg($this->imgSrc) or die("Error: Cannot find image!");
			}elseif($im['mime'] == 'image/png'){
				$this->myImage = imagecreatefrompng($this->imgSrc) or die("Error: Cannot find image!");
			}else{
				$this->myImage = imagecreatefromgif($this->imgSrc) or die("Error: Cannot find image!");
			}
		}
		// find biggest lenght
		if($width > $height) {
			$biggestSide = $height;
		}else{
			$biggestSide = $width;
		}
		// the crop size will be half that of the largest side 
		if($biggestSide > 2000){
			$cropPercent = .65;
		}elseif($biggestSide > 1500){
			$cropPercent = .75;
		}elseif($biggestSide > 1000){
			$cropPercent = .85;
		}elseif($biggestSide > 300){
			$cropPercent = .9;
		}elseif($biggestSide > 200){
			$cropPercent = .95;
		}elseif($biggestSide > 100){
			$cropPercent = 1;
		}else{
			$cropPercent = 1.3;
		}
		$this->cropWidth   = $biggestSide *$cropPercent; 
		$this->cropHeight  = $biggestSide *$cropPercent; 
		// getting the top left coordinate
		$this->x = ($width-$this->cropWidth)/2;
		$this->y = ($height-$this->cropHeight)/2;
	}
	function createThumb($size) {
		// will create a DD x DD thumb
		$thumbSize = $size; 	
		$this->thumb = imagecreatetruecolor($thumbSize, $thumbSize); 
		imagecopyresampled($this->thumb, $this->myImage, 0, 0,$this->x, $this->y, $thumbSize, $thumbSize, $this->cropWidth, $this->cropHeight); 
	}
	function renderImage() {
		// it is a picture
		header('Content-type: image/jpeg');
		imagejpeg($this->thumb);
		imagedestroy($this->thumb);
	}
}
// CREATE THE INSTANCE PICTURE
$image = new cropImage;
$image->setImage($src);
$image->createThumb($size);
$image->renderImage();

?>