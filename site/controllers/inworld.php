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

class OpenSimControllerinworld extends OpenSimController {
	function __construct() {
		parent::__construct();
		$model = $this->getModel('inworld');
	}

	function create() {
		$this->insertuser();
	}

	function insertuser() {
		$model = $this->getModel('inworld');
		$opensim = $model->opensim;

		$firstname	= trim(JRequest::getVar('firstname'));
		$lastname	= trim(JRequest::getVar('lastname'));
		$email		= trim(JRequest::getVar('email'));
		$pwd1		= trim(JRequest::getVar('pwd1'));
		$pwd2		= trim(JRequest::getVar('pwd2'));
		/*echo $email;*/
		/*exit;*/

		if($model->checkUserExists($firstname,$lastname)) { // check if user already exists
			$type = "error";
			$message = JText::_('ERROR_USEREXISTS');
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$model->checkLastnameAllowed($lastname)) { // control allowed last names
			$type = "error";
			$message = JText::_('ERROR_LASTNAMENOTALLOWED')." (".$lastname.")";
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif($pwd1 != $pwd2) {
			$type = "error";
			$message = JText::_('ERROR_PWDMISMATCH');
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$pwd1) {
			$type = "error";
			$message = JText::_('ERROR_NOPWD');
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$firstname) {
			$type = "error";
			$message = JText::_('ERROR_NOFIRSTNAME');
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$lastname) {
			$type = "error";
			$message = JText::_('ERROR_NOLASTNAME');
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$email) {
			$type = "error";
			$message = JText::_('ERROR_NOEMAIL');
			$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} else {
			$pregmail = "/^.{1,}@.{2,}\..{2,4}\$/";
			preg_match($pregmail, $email, $treffer); // Emailadresse auf Gültigkeit prüfen
			if($treffer[0] != $email || !isset($treffer[0])) { // validate Email format
				$type = "error";
				$message = JText::_('ERROR_INVALIDEMAIL');
				$redirect = "index.php?option=com_opensim&view=inworld&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
			} else {
				$newuser['firstname']	= $firstname;
				$newuser['lastname']	= $lastname;
				$newuser['email']		= $email;
				$newuser['password']	= $pwd1;
				$newuser['uuid'] = $model->getUUID();
				$result = $model->insertuser($newuser);
				$model->setUserRelation($newuser['uuid']);
				$type = "message";
				$message = JText::_(OK_NEWUSER);
				$settings = $model->getSettingsData();
				if($settings['welcomecontent']) {
					$redirect = "index.php?option=com_content&view=article&id=".$settings['welcomecontent'];
				} else {
					$redirect = "index.php?option=com_opensim&view=inworld&task=welcome";
				}
			}
		}
		$this->setRedirect($redirect,$message,$type);
	}

	function update() {
		$data	= JRequest::get('post');
		$task	= $task = JRequest::getVar('task','','method','string');
		$itemid	= JRequest::getVar('Itemid');
		$model = $this->getModel('inworld');
		$opensim = $model->opensim;
		switch($task) {
			default:
				$newtask = "default";
		}

		$response = $model->updateuser($data);
		if(is_array($response) && $response[0] == "error") { // something went wrong :(
			array_shift($response); // dont need the first one
			$errormessages = implode(", ",$response);
			$type = "Error";
			$message = JText::_('ERROR_USERUPDATE').": ".$errormessages;
		} elseif(is_array($response) && $response[0] == "ok") { // everything ok :)
			$type = "Message";
			$message = JText::_('OK_USERUPDATE').": ".$response[1];
		} else { // that should never happen ... :|
			$debug = var_export($response,TRUE);
			$type = "Notice";
			$message = JText::_('NOTICE_USERUPDATE').": ".$debug;
		}
		$redirect = "index.php?option=com_opensim&view=inworld&task=".$newtask."&Itemid=".$itemid;
		$this->setRedirect($redirect,$message,$type);
	}

	function updateprofile() {
		$data	= JRequest::get('post');
		$task	= JRequest::getVar('task','','method','string');
		$itemid	= JRequest::getVar('Itemid');
		$opensim = new opensim();
		$model = $this->getModel('inworld');
		switch($task) {
			default:
				$newtask = "profile";
		}
		$model->updateprofile($data);
		$message = JText::_('OK_PROFILEUPDATE');
		$type = "Message";
		$redirect = "index.php?option=com_opensim&view=inworld&task=".$newtask."&Itemid=".$itemid;
		$this->setRedirect($redirect,$message,$type);
	}

	function leavegroup() {
		$groupid = JRequest::getVar('groupid');
		$itemid	= JRequest::getVar('Itemid');
		$opensim = new opensim();
		$model = $this->getModel('inworld');
		$groupleft = $model->leaveGroup($groupid);

		if($groupleft === TRUE) {
			$message = JText::_('OK_LEAVEGROUP');
			$type = "Message";
		} else {
			$message = JText::_('ERROR_LEAVEGROUP');
			$type = "Error";
		}
		$redirect = "index.php?option=com_opensim&view=inworld&task=groups&Itemid=".$itemid;
		$this->setRedirect($redirect,$message,$type);
	}

	function ejectgroup() {
		$groupid = JRequest::getVar('groupid');
		$ejectid = JRequest::getVar('ejectid');
		$itemid	= JRequest::getVar('Itemid');
		$model = $this->getModel('inworld');
		$groupleft = $model->ejectFromGroup($groupid,$ejectid);

		if($groupleft === TRUE) {
			$message = JText::_('OK_EJECTGROUP');
			$type = "Message";
		} else {
			$message = JText::_('ERROR_EJECTGROUP');
			$type = "Error";
		}
		$redirect = "index.php?option=com_opensim&view=inworld&task=groups&Itemid=".$itemid;
		$this->setRedirect($redirect,$message,$type);
	}

	function createIdent() {
		$itemid	= JRequest::getVar('Itemid');
		$model = $this->getModel('inworld');
		$model->opensimSetInworldIdent();

		$redirect = "index.php?option=com_opensim&view=inworld&Itemid=".$itemid;
		$this->setRedirect($redirect,$message,$type);
	}

	function cancelIdent() {
		$itemid	= JRequest::getVar('Itemid');
		$model = $this->getModel('inworld');
		$model->opensimCancelInworldIdent();

		$redirect = "index.php?option=com_opensim&view=inworld&Itemid=".$itemid;
		$this->setRedirect($redirect,$message,$type);
	}
}
?>
