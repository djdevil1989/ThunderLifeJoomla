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

class OpenSimCpModelSearch extends OpenSimCpModelOpenSim {
	var $_settingsData;
	var $filename = "search.php";
	var $view = "search";
	var $_os_db;

	public function __construct() {
		parent::__construct();
		$this->getSettingsData();
	}

	public function getoptions($reihe = "intern") {
		$db =& JFactory::getDBO();
		$query = "SELECT #__opensim_search_options.* FROM #__opensim_search_options";
		if($reihe == "intern") $query .= " ORDER BY reiheintern";
		else $query .= " ORDER BY reihe";
		$db->setQuery($query);
		$options = $db->loadAssocList();
		if(is_array($options) && count($options) > 0) {
			$retval = array();
			foreach($options AS $key => $option) {
				$retval[$option['searchoption']] = $option;
				$retval[$option['searchoption']]['name'] = JText::_($option['searchoption']);
			}
			return $retval;
		} else {
			return array();
		}
	}

	public function saveOptions($data) {
		$enabled	= $data['searchoptions'];
		$sort		= $data['sortsearchoptions'];
		$db =& JFactory::getDBO();
		// First we disable all
		$query = "UPDATE #__opensim_search_options SET enabled = '0'";
		$db->setQuery($query);
		$db->query();
		// Now enable all from the array
		if(count($enabled) > 0) {
			foreach($enabled AS $enabledoption) {
				$query = sprintf("UPDATE #__opensim_search_options SET enabled = '1' WHERE searchoption = '%s'",$enabledoption);
				$db->setQuery($query);
				$db->query();
			}
		}
		if(count($sort) > 0) {
			foreach($sort AS $reihe => $option) {
				$query = sprintf("UPDATE #__opensim_search_options SET reihe = '%s' WHERE searchoption = '%s'",$reihe,$option);
				$db->setQuery($query);
				$db->query();
			}
		}
	}

}
?>
