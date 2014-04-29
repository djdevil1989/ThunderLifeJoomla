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
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 


JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );

class OpenSimCpController extends JController {
	var $model;
	public $opensim;
	/**
	 * Method to display the view
	 *
	 * @access    public
	 */
	public function __construct() {
		parent::__construct();
		$this->model	= $this->getModel('opensim');
		$test = gettype($this->model->opensim);
		$griddb = $this->model->opensim->connect2osgrid();
		if(strtolower(gettype($griddb)) == "boolean" && $griddb == FALSE) {
			$conn['host'] = $this->model->opensim->osgriddbhost;
			$conn['port'] = ($this->model->opensim->osgriddbport) ? $this->model->opensim->osgriddbport:3306;
			$conn['user'] = $this->model->opensim->osgriddbuser;
			$conn['pass'] = $this->model->opensim->osgriddbpasswd;
			$conn['db'] = $this->model->opensim->osgriddbname;
			if(!$conn['user'] || !$conn['pass']) {
				JError::raiseNotice(100,JText::_('JOPENSIM_ERROR_NOCONFIG'));
			} else {
				JError::raiseWarning(100,JText::sprintf('ERROR_NOSIMDB',JText::_('OPENSIMGRIDDB')));
				if(!function_exists('mysqli_connect')) {
					$connid = @mysql_connect($conn['host'].":".$conn['port'],$conn['user'],$conn['pass']);
					if(!$connid) {
						$conn['msg'] = mysql_error();
					} else {
						$select = mysql_select_db($conn['db'],$connid);
						if(!$select) $conn['msg'] = JText::sprintf('JOPENSIM_ERROR_DBSELECT',$conn['db']);
					}
				} else {
					$connid = @mysqli_connect($conn['host'],$conn['user'],$conn['pass'],null,$conn['port']);
					if(!$connid) {
						$conn['msg'] = mysqli_connect_error();
					} else {
						$select = @mysqli_select_db($connid,$conn['db']);
						if(!$select) $conn['msg'] = JText::sprintf('JOPENSIM_ERROR_DBSELECT',$conn['db']);
					}
				}
	//			$debug = var_export($conn,TRUE);
				JError::raiseWarning(100,$conn['msg']);
			}
		}
//		$debug = var_export($griddb,TRUE);
//		JError::raiseNotice(100,"test: ".$test);
//		JError::raiseNotice(100,"debug: ".gettype($griddb));
	}

	public function display() {
//		$model = $this->getModel('opensim');
		$settings = $this->model->getSettingsData();
		$this->jopensimmenue($settings['addons']);
		$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );
		if($settings['addons'] > 0) {
			if($view == "addons") JSubMenuHelper::addEntry(JText::_('JOPENSIM_ADDONHELP'), 'index.php?option=com_opensim&view=addons',true);
			else JSubMenuHelper::addEntry(JText::_('JOPENSIM_ADDONHELP'), 'index.php?option=com_opensim&view=addons');
		}
//		JFactory::getApplication()->enqueueMessage($view);
		parent::display();
	}

	public function getOpensim() {
		return $this->opensim;
	}

	public function jopensimmenue($addon) {
		$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );
		
		switch($view) {
			case "settings":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings',true);
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "loginscreen":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen',true);
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "maps":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps',true);
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "user":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user',true);
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('ADDONS_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "groups":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups',true);
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "search":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search',true);
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "money":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money',true);
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
			break;
			case "misc":
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc',true);
			break;
			default:
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_OVERVIEW'), 'index.php?option=com_opensim');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SETTINGS'), 'index.php?option=com_opensim&view=settings');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_LOGINSCREEN'), 'index.php?option=com_opensim&view=loginscreen');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MAPS'), 'index.php?option=com_opensim&view=maps');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_USER'), 'index.php?option=com_opensim&view=user');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_GROUPS'), 'index.php?option=com_opensim&view=groups');
				if(($addon &  16) == 16) JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_SEARCH'), 'index.php?option=com_opensim&view=search');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MONEY'), 'index.php?option=com_opensim&view=money');
				JSubMenuHelper::addEntry(JText::_('JOPENSIM_MENU_MISC'), 'index.php?option=com_opensim&view=misc');
		}
	}

	public function payMoneyFromBanker() {
		$data = JRequest::get('post');
		$returnto = JRequest::getString('returnto');
		$redirect = "index.php?option=com_opensim&view=".$returnto;
		if($data['jopensim_money_payment'] == 0) {
			if($returnto == "user") {
				$type = "warning";
				$message = JText::_('JOPENSIM_WARNING_ZEROPAYMENT');
			} else {
				$redirect .= "&task=moneyUser";
				$type = "";
				$message = "";
			}
			$this->setRedirect($redirect,$message,$type);
		} else {
			$parameter['senderID']		= $data['jopensim_money_bankeraccount'];
			$parameter['receiverID']	= $data['jopensim_money_userid'];
			$parameter['amount']		= $data['jopensim_money_payment'];
			$parameter['description']	= $data['jopensim_money_paytext'];

			$model = $this->getModel('money');
			$message = $model->TransferMoney($parameter);
			if(!$model->opensimRelationReverse($data['jopensim_money_userid'])) { // We dont have any user relation yet, raise a warning!
				jimport( 'joomla.error.error' );
				JError::raiseNotice(100,JText::_('JOPENSIM_MONEY_NORELATIONWARNING')." (".$data['jopensim_money_userid'].")");
			}
			if(array_key_exists("success",$message) && $message['success'] == TRUE) {
				$this->setRedirect($redirect,JText::_('JOPENSIM_MONEY_TRANSFER_OK'));
			} else {
				$type		= "warning";
				$message	= JText::_('JOPENSIM_MONEY_TRANSFER_ERROR');
				$this->setRedirect($redirect,$message,$type);
			}
		}
	}
}
