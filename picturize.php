<?php
require_once("model.picturize.php");

class Picturize extends Modules {

	# ----- INSTALL/UNINSTALL MODULE ----------------------------------------------
	static function __install() {
		Group::add_permission("manage_picturize", "Manage Picturize");
	}
	static function __uninstall($confirm) {
		Group::remove_permission('manage_picturize');
	}

	# ----- DISPLAY ADMIN NAVBAR ON INFEX AND INNER PAGES -------------------------
	static function manage_nav($navs) {
		if (!Visitor::current()->group->can('manage_picturize'))
			return $navs;
		$navs["manage_pictures"] = array("title" => __("Picturize", "pictures"),
			"selected" => array("add_pictures", "delete_pictures"));
		return $navs;
	}
	static function manage_nav_pages($pages) {
		array_push($pages, "manage_pictures", "add_pictures", "delete_pictures");
		return $pages;
	}

	# ----- MANAGE THE PICTURIZE LIST ---------------------------------------------
	public function admin_manage_pictures($admin) {
		if (!Visitor::current()->group()->can('manage_picturize'))
			show_403(__("Access Denied"), __('You do not have sufficient privileges to manage this pictures.', 'picturize'));
		# display page layout
		$admin->display("manage_pictures",array("picturizeList" => new Paginator(Pictures::getPictures(), 25)));
	}
	public function admin_add_pictures($admin) {
		if (!Visitor::current()->group()->can('manage_picturize'))
			show_403(__("Access Denied"), __('You do not have sufficient privileges to manage this pictures.', 'picturize'));
		# deal with the submission and display message
		if (isset($_POST['add']))
		if (!empty($_POST['name'])) {
			Pictures::addPictures($_POST);
			Flash::notice(__("Pictures added.", "picturizeList"), "/admin/?action=manage_pictures");
		}else
			$fields['picturizeList'] = array("name" => $_POST['name'], $_POST['photo'] => $_FILES['photo']);
		$admin->display("add_pictures", $fields = array());
	}
	public function admin_delete_pictures($admin) {
		if (!Visitor::current()->group()->can("manage_picturize"))
			show_403(__("Access Denied"), __("You do not have sufficient privileges to manage this pictures.", "picturize"));
		# delete picture and display message
		if (!empty($_REQUEST['url'])) {
			Pictures::deletePictures($_REQUEST['url']);
			Flash::notice(__("Pictures deleted.", "picturizeList"), "/admin/?action=manage_pictures");
		}
	}

	# ----- ADD CSS STYLING IN HEAD -----------------------------------------------
	public function admin_head() {
		$config = Config::current();
		$path = $config->chyrp_url."/modules/picturize/lib/";
?>
		<link rel="stylesheet" href="<?php echo $path; ?>style.css" type="text/css" media="screen" />
<?php
	}
}
?>