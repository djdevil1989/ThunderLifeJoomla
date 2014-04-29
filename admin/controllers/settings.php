<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2012 FoTo50 http://www.foto50.com/opensim/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OpenSimCpControllersettings extends OpenSimCpController {
	function __construct() {
		parent::__construct();
		$model = $this->getModel('settings');
	}

	function cancel() {
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp');
	}

	function apply_settings() {
		$model = $this->getModel('settings');
		$data = JRequest::get('post');
		$model->storeSettings();
		$this->setRedirect('index.php?option=com_opensim&view=settings&task=applyok',JText::_('SETTINGSSAVEDOK'));
	}

	function save() {
		$model = $this->getModel('settings');
		$model->storeSettings();
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp&task=saveok',JText::_('SETTINGSSAVEDOK'));
	}
}
?>
