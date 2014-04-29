<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2010 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class opensimcpViewuser extends JView {
	public function display($tpl = null) {
		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );

		$model = $this->getModel('user');

		$state = $this->get('State');

		$this->sortDirection = $state->get('filter_order_Dir');
		if(!$this->sortDirection) $this->sortDirection = "desc";
		$this->sortColumn = $state->get('filter_order');
		if(!$this->sortColumn) $this->sortColumn = "created";
		
		$test = $this->sortDirection;
		$this->assignRef('dirtest',$test);

		if (JError::isError($model->_osgrid_db) || !$model->_osgrid_db) {
//			if(!$model->_os_db) JFactory::getApplication()->enqueueMessage(JText::sprintf('ERROR_NOSIMDB',JText::_('OPENSIMDB')),"error");
			if(!$model->_osgrid_db) JFactory::getApplication()->enqueueMessage(JText::sprintf('ERROR_NOSIMDB',JText::_('OPENSIMGRIDDB')),"error");
			$ueberschrift = JText::_('USERMANAGEMENT');
			$errormsg = "<br />\n".JText::_('ERROR_NOUSER')."<br />\n".JText::_('ERRORQUESTION1')."<br />\n".JText::_('ERRORQUESTION2')."<br />\n";
			$this->assignRef('errormsg',$errormsg);
			$tpl = "nodb";
		} else {
			$task = JRequest::getVar( 'task', '', 'method', 'string');
			switch($task) {
				case "newuser":
					JRequest::setVar( 'hidemainmenu', 1 );
					$ueberschrift = JText::_('NEWUSER');
					$tpl = "newuser";
	
					$firstname	= JRequest::getVar('firstname');
					$lastname	= JRequest::getVar('lastname');
					$email		= JRequest::getVar('email');
					$this->assignRef('firstname',$firstname);
					$this->assignRef('lastname',$lastname);
					$this->assignRef('email',$email);
				break;
				case "edituser":
					$userid = JRequest::getVar('checkUser');
					$userparams = $model->getUserParams();
					JRequest::setVar('hidemainmenu',1);
					$userdata = $model->getUserData($userid[0]);
					$userlevellist = $model->getUserLevels();
					$this->assignRef('userdata',$userdata);
					$this->assignRef('userlevellist',$userlevellist);
					$this->assignRef('userid',$userdata['uuid']);
					$this->assignRef('firstname',$userdata['firstname']);
					$this->assignRef('lastname',$userdata['lastname']);
					$this->assignRef('email',$userdata['email']);
					$this->assignRef('userlevel',$userdata['userlevel']);
					$this->assignRef('userparams',$userparams);
					$tpl = "edituser";
					$usertitle = JHTML::tooltip(JText::_('JOPENSIM_USERSETTING_TITLE_DESC'),JText::_('JOPENSIM_USERSETTING_TITLE'),'',JText::_('JOPENSIM_USERSETTING_TITLE'));
					$this->assignRef('usertitle',$usertitle);
				break;
				case "attachUser":
					$ueberschrift = JText::_('USERMANAGEMENT');
					$postdata = JRequest::get('post');
					$testvar = var_export($postdata,TRUE);
					$this->assignRef('testvar',$testvar);
					$userid = $postdata['checkUser'][0];
					$this->assignRef('userid',$userid);
					$opensim_userdata = $model->getUserData($postdata['checkUser'][0]);
					$this->assignRef('opensim_userdata',$opensim_userdata);
					$relation = $model->getUserRelation($postdata['checkUser'][0]);
					if($relation[0]) $relationmethod = "update";
					else $relationmethod = "insert";
					$this->assignRef('relationmethod',$relationmethod);
					$this->assignRef('relation',$relation[0]);
					$joomlalist = $model->getJoomlaRelationList($postdata['checkUser'][0]);
					
					$this->assignRef('testrelationen',$testrelationen);
					$this->assignRef('joomlalist',$joomlalist);
					$tpl = "attachuser";
				break;
				case "applyok":
					$zusatztext = " <div class='com_opensim_okmsg'>".JText::_('SETTINGSSAVEDOK')."</div>";
				default:
					$ueberschrift = JText::_('USERMANAGEMENT');
			 		$users = $this->get('Data');
			 		/*debugprint($this);*/
			 		/*exit;*/
			 		if(!is_array($users) || count($users) == 0) {
			 			$users = array();
						JFactory::getApplication()->enqueueMessage(JText::_('JOPENSIM_NOUSER'),'warning');
						$tpl = "nouser";
					}
			 		$this->assignRef( 'users', $users );
			 		$this->assignRef( 'test', $test );

					$filter = JRequest::getVar('search');
			 		$this->assignRef( 'filter', $filter );
			 		$pagination =& $this->get('Pagination');
					$this->assignRef('pagination', $pagination);
					$avatarusers = $model->repopulateavatars();
					$this->assignRef('avatarusers', $avatarusers);
				break;
			}
		}

		$this->assignRef( 'ueberschrift',$ueberschrift);
 		$this->assignRef( 'zusatztext',$zusatztext);

		$this->_setToolbar($tpl);
		parent::display($tpl);
	}

	public function _setToolbar($tpl) {
		JToolBarHelper::title(JText::_('OPENSIM')." ".JText::_('USERMANAGEMENT'),'osuser');
		switch($tpl) {
			case "newuser":
				JToolBarHelper::save('insertuser');
				JToolBarHelper::cancel('canceladduser','JCANCEL');
				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_USER_ADD'));
			break;
			case "attachuser":
				JToolBarHelper::save('applyuserrelation');
				JToolBarHelper::cancel('cancelapplyuserrelation','JCANCEL');
				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_USER_RELATION'));
			break;
			case "edituser":
				JToolBarHelper::save('saveuseredit');
				JToolBarHelper::cancel('canceluseredit','JCANCEL');
				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_USER_EDIT'));
			break;
			default:
				JToolBarHelper::deleteList(JText::_('DELETEUSERSURE'),"deleteuser",JText::_('DELETEUSER'));
				$model = $this->getModel('user');
				$os_settings = $model->getSettingsData();
				if(isset($os_settings['remoteadmin_enabled']) && $os_settings['remoteadmin_enabled'] == 1) {
//					JToolBarHelper::custom("sendMessage","opensim","opensim",JText::_(SENDMESSAGE2USER),true,false); <-- TODO
				}
				JToolBarHelper::addNew("newuser",JText::_('ADDNEWUSER'));
				JToolBarHelper::editList("edituser",JText::_('JOPENSIM_EDITUSER'));
				JToolBarHelper::custom("attachUser","joomla2opensim","opensim",JText::_('ATTACHJOOMLA2OPENSIM'),true,false);
				JToolBarHelper::custom("repairUserStatus","userrepair","opensim",JText::_('REPAIRUSERSTATUS'),false,false);
				if($model->moneyEnabled === TRUE) {
					JToolBarHelper::custom("userMoney","usermoney","opensim",JText::_('JOPENSIMUSERMONEY'),true,false);
				}
				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_USER'));
			break;
		}
	}
}

?>