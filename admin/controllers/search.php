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

class OpenSimCpControllersearch extends OpenSimCpController {
	public function __construct() {
		parent::__construct();
		$model = $this->getModel('settings');
	}

	public function cancel() {
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp');
	}

	public function applysearch() {
		$data = JRequest::get('post');
		$model = $this->getModel('search');
		$model->saveOptions($data);
		$this->setRedirect('index.php?option=com_opensim&view=search&task=applyok',JText::_('SETTINGSSAVEDOK'));
	}

	public function savesearch() {
		$data = JRequest::get('post');
		$model = $this->getModel('search');
		$model->saveOptions($data);
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp&task=saveok',JText::_('SETTINGSSAVEDOK'));
	}
}
?>
