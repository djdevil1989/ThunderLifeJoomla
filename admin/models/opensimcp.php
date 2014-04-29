<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2013 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die();
/*jimport('joomla.application.component.model');*/
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'opensim.php');

class OpenSimCpModelOpenSimCp extends OpenSimCpModelOpenSim {
	var $_settingsData;
	var $filename	= "opensimcp.php";
	var $view		= "opensimcp";
	var $_os_db;

	public function __construct() {
		parent::__construct();
		$this->getSettingsData();
	}

	public function frontendCSS() {
		return JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."opensim.css";
	}

	public function saveCSS() {
		$cssfile = $this->frontendCSS();
		if(!is_writable($cssfile)) {
			$retval['type']		= "error";
			$retval['message']	= JText::_('JOPENSIM_CSSSAVE_ERROR');
		} else {
			$csscontent = trim(JRequest::getVar('csscontent'));
			file_put_contents($cssfile, $csscontent);
			/*return $csscontent;*/
			$retval['type']		= "message";
			$retval['message']	= JText::_('JOPENSIM_CSSSAVE_OK');
		}
		return $retval;
	}
}
?>
