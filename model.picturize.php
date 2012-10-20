<?php
class Pictures extends Model {
	
	# ----- GET PICTURE -----------------------------------------------------------
	static function getPictures() {
		# basic variable
		$extensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		$libcrop = "../modules/picturize/lib/cropimage.php?src=";
		$folderUrl = Config::current()->chyrp_url.Config::current()->uploads_path;
		$folderServ = MAIN_DIR.Config::current()->uploads_path;
		$picturizeList = array();
		$i=1;
		# loop to do the listing of pictures
		$Ressource = opendir($folderServ);
		while($fichier = readdir($Ressource)){
			$berk = array('.', '..');
			$test_fichier = $folderServ.$fichier;
			if(!in_array($fichier, $berk) && !is_dir($test_fichier)){
				$ext = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
				if(in_array($ext, $extensions)){
					$picturizeList[$i]['id'] = $i;
					$picturizeList[$i]['url'] = $folderUrl.$fichier;
					$picturizeList[$i]['cropurl'] = $libcrop.$folderUrl.$fichier;
					$i++;
				}
			}
		}
		return $picturizeList;
	}
	
	# ----- ADD A NEW PICTURE -----------------------------------------------------
	static function addPictures($post = array()) {
		if (isset($post['name'])) {
			# clean filename with the name in the input
			$name = sanitize($post['name']);
			$extension = explode(".", $_FILES['photo']['name']);
			$_FILES['photo']['name'] = $name.".".$extension[1];
			# move file to folder
			if (isset($_FILES['photo']) and $_FILES['photo']['error'] == 0)
				$filename = upload($_FILES['photo'], array('jpg', 'jpeg', 'gif', 'png', 'bmp'));
			else
				error(__("Error"), __("Couldn't upload the picture."));
		}
	}
	
	# ----- DELETE A PICTURE ------------------------------------------------------
	static function deletePictures($url, $confirm = FALSE) {
		$url = basename($url);
		$url = MAIN_DIR.Config::current()->uploads_path.$url;
		unlink($url);
	}
}
?>