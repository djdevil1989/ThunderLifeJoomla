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

class OpenSimCpModelSettings extends OpenSimCpModelOpenSim {

	var $_data;
	var $filename = "settings.php";
	var $view = "settings";

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
		if(!$data['remoteadmin_enabled']) $data['remoteadmin_enabled'] = 0;
		$data['userchange'] = intval($data['userchange_firstname'])+intval($data['userchange_lastname'])+intval($data['userchange_email'])+intval($data['userchange_password']);
		$data['addons'] = intval($data['addons_messages'])+intval($data['addons_profile'])+intval($data['addons_groups'])+intval($data['addons_search'])+intval($data['addons_inworldauth'])+intval($data['addons_currency']);

		$this->saveConfig($data);
		return true;
	}
}
?>
