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

class OpenSimCpControllergroups extends OpenSimCpController {
	function __construct() {
		parent::__construct();
		$model = $this->getModel('groups');
	}

	function cancel() {
		$this->setRedirect('index.php?option=com_opensim&view=opensimcp');
	}

	function assignOwner() {
		$data = JRequest::get('post');
		$model = $this->getModel('groups');
		$model->assignOwner($data['groupID'],$data['OwnerRoleID'],$data['memberID']);
		$this->setRedirect('index.php?option=com_opensim&view=groups',JText::_('OKGROUPNEWOWNER'));
	}

	function deleteGroups() {
		$data = JRequest::get('post');
		/*debugprint($data);*/
		/*exit;*/
		$model = $this->getModel('groups');
		$countgroups = $model->deleteGroups($data['checkGroup']);
		$message = JText::sprintf(GROUPSDELETED,$countgroups);
		$this->setRedirect('index.php?option=com_opensim&view=groups',$message);
	}
}
?>
