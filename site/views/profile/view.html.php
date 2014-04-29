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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');


class opensimViewprofile extends JView {

	function display($tpl = null) {
		JHTML::stylesheet( 'opensim.css', 'components/com_opensim/assets/' );
		$model = $this->getModel('profile');

		$settingsdata = $model->getSettingsData();
		$this->assignRef('settingsdata',$settingsdata);

		$uid	= JRequest::getVar( 'uid', '', 'method', 'string');

		$wantmask		= $model->profile_wantmask();
		$skillsmask		= $model->profile_skillsmask();
		$profiledata	= $model->getUserProfile($uid);
		$this->assignRef('wantmask',$wantmask);
		$this->assignRef('skillsmask',$skillsmask);
		$this->assignRef('profiledata',$profiledata);
		$this->assignRef('pageclass_sfx',$pageclass_sfx);

		$task	= JRequest::getVar( 'task', '', 'method', 'string');
		switch($task) {
			default:
				
			break;
		}

		parent::display($tpl);
	}
}
?>