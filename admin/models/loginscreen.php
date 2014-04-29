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

class opensimcpModelLoginscreen extends OpenSimCpModelOpenSim {

	var $_data;
	var $filename = "loginscreen.php";
	var $view = "loginscreen";

	function __construct() {
		parent::__construct();
	}

	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data )) {
			$this->_data = $this->getSettingsData();
		}
		return $this->_data;
	}

	function storeSettings() {
		$data = JRequest::get( 'post' );
		$data = $this->prepareData($data);
		$this->saveConfig($data);
		return true;
	}

	function prepareData($data) {
		$data['loginscreen_boxes'] = 0;
		$data['loginscreen_gridbox'] = 0;
		if(is_array($data['loginscreen_boxes_array'])) {
			foreach($data['loginscreen_boxes_array'] AS $datavalue) {
				$data['loginscreen_boxes'] += $datavalue;
			}
		}
		if(is_array($data['loginscreen_gridbox_array'])) {
			foreach($data['loginscreen_gridbox_array'] AS $datavalue) {
				$data['loginscreen_gridbox'] += $datavalue;
			}
		}
		if(!array_key_exists("hiddenregions",$data)) $data['hiddenregions'] = 0;
		return $data;
	}

	function removeimage() {
		$query = "UPDATE #__opensim_config SET #__opensim_config.configvalue = NULL WHERE #__opensim_config.configname = 'loginscreen_image'";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		return TRUE;
	}
}
?>
