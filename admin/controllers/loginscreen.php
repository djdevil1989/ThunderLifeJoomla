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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OpenSimCpControllerLoginscreen extends OpenSimCpController {
	function __construct() {
		parent::__construct();
		$model = $this->getModel('loginscreen');
	}

	function apply_settings() {
		$model = $this->getModel('loginscreen');
		$data = JRequest::get('post');
		$model->storeSettings();
		$this->setRedirect('index.php?option=com_opensim&view=loginscreen',JText::_('SETTINGSSAVEDOK'));
	}

	function save_settings() {
		$model = $this->getModel('loginscreen');
		$data = JRequest::get('post');
		$model->storeSettings();
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp',JText::_('SETTINGSSAVEDOK'));
	}

	function cancel_settings() {
		$this->setRedirect('index.php?option=com_opensim');
	}

	function removeimage() {
		$model = $this->getModel('loginscreen');
		$model->removeimage();
		$this->setRedirect('index.php?option=com_opensim&view=loginscreen',JText::_('SETTINGSSAVEDOK'));
	}
}
?>
