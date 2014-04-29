<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die();

/*jimport('joomla.application.component.model');*/
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'opensim.php');

class opensimModelProfile extends OpenSimCpModelOpenSim {

	var $_data;
	var $_data_ext;
	var $_regiondata = null;
	var $_settingsData;
	var $filename = "welcome.php";
	var $view = "welcome";
	var $_os_db;
	var $_osgrid_db;
	var $_db;

	public function __construct() {
		parent::__construct();
		$this->getSettingsData();
		$this->_os_db = $this->getOpenSimDB();
		$this->_osgrid_db = $this->getOpenSimGridDB();
	}

	public function getUserProfile($uid) {
		$profiledata	= $this->getprofile($uid);
		$namequery = $this->opensim->getUserNameQuery($uid);
		$this->_osgrid_db->setQuery($namequery);
		$name = $this->_osgrid_db->loadAssoc();
		$profiledata['firstname']	= $name['firstname'];
		$profiledata['lastname']	= $name['lastname'];
		$profiledata['name']		= $name['firstname']." ".$name['lastname'];
		return $profiledata;
	}

}
?>
