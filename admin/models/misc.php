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

class OpenSimCpModelMisc extends OpenSimCpModelOpenSim {
	var $_settingsData;
	var $filename = "misc.php";
	var $view = "misc";
	var $_os_db;

	function __construct() {
		parent::__construct();
		global $mainframe, $option;
		$this->getSettingsData();
	}

	function updateWelcome() {
		$data = JRequest::get('request');
		$db =& JFactory::getDBO();
		$query = sprintf("UPDATE #__opensim_settings SET welcomecontent = '%d'",$data['welcomemessage']);
		$db->setQuery($query);
		$db->query();
	}

	function removeWelcome() {
		$db =& JFactory::getDBO();
		$query = "UPDATE #__opensim_settings SET welcomecontent = NULL";
		$db->setQuery($query);
		$db->query();
	}

	function toggleTerminal($terminalKey) {
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT active FROM #__opensim_terminals WHERE terminalKey = '%s'",$terminalKey);
		$db->setQuery($query);
		$active = $db->loadResult();
		if($active == 1) {
			$query = sprintf("UPDATE #__opensim_terminals SET active = '0' WHERE terminalKey = '%s'",$terminalKey);
		} else {
			$query = sprintf("UPDATE #__opensim_terminals SET active = '1' WHERE terminalKey = '%s'",$terminalKey);
		}
		$db->setQuery($query);
		$db->query();
	}

	function getTerminal($terminalKey) {
		$db =& JFactory::getDBO();
		$query = sprintf("SELECT * FROM #__opensim_terminals WHERE terminalKey = '%s'",$terminalKey);
		$db->setQuery($query);
		$terminal = $db->loadAssoc();
		return $terminal;
	}

	function saveTerminalStatic($terminalKey,$staticValue) {
		$db =& JFactory::getDBO();
		$query = sprintf("UPDATE #__opensim_terminals SET staticLocation = '%d' WHERE terminalKey = '%s'",$staticValue,$terminalKey);
		$db->setQuery($query);
		$db->query();
		return TRUE;
	}

	function saveTerminal($data) {
		$_SESSION['test']['data'] = $data;
		if($data['staticLocation'] == 1) $staticLocation = 0;
		else $staticLocation = 1;
		$db =& JFactory::getDBO();
		$query = sprintf("UPDATE #__opensim_terminals SET
								terminalName = '%s',
								terminalDescription = '%s',
								location_x = '%d',
								location_y = '%d',
								location_z = '%d',
								staticLocation = '%d',
								active = '%d'
							WHERE
								terminalKey = '%s'",
				$data['terminalName'],
				$data['terminalDescription'],
				$data['location_x'],
				$data['location_y'],
				$data['location_z'],
				$staticLocation,
				$data['active'],
				$data['terminalKey']);
		$_SESSION['test']['query'] = $query;
		$db->setQuery($query);
		$db->query();
		return TRUE;
	}

	function removeTerminal($terminalArray) {
		if(!is_array($terminalArray) || count($terminalArray) == 0) return FALSE;
		$db =& JFactory::getDBO();
		foreach($terminalArray AS $terminalKey) {
			$query = sprintf("DELETE FROM #__opensim_terminals WHERE terminalKey = '%s'",$terminalKey);
			$db->setQuery($query);
			$db->query();
		}
		return TRUE;
	}
}
?>
