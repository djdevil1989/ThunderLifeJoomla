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

class OpenSimCpControlleruser extends OpenSimCpController {
	public function __construct() {
		parent::__construct();
	}

	public function addUser() {
		$this->setRedirect('index.php?option=com_opensim&view=user&task=newuser','');
	}

	public function canceladduser() {
		$this->setRedirect('index.php?option=com_opensim&view=user','');
	}

	public function saveuseredit() {
		$model = $this->getModel('user');
		$opensim =& $model->opensim;
//		$test = JRequest::get('post');
//		debugprint($test,"test");

		$data['PrincipalID']= trim(JRequest::getVar('userid'));
		$data['FirstName']	= trim(JRequest::getVar('firstname'));
		$data['LastName']	= trim(JRequest::getVar('lastname'));
		$data['Email']		= trim(JRequest::getVar('email'));
		$data['UserLevel']	= trim(JRequest::getVar('UserLevel'));
		$data['UserTitle']	= trim(JRequest::getVar('usertitle'));
		$data['UserFlags']	= trim(JRequest::getVar('jopensim_usersetting_flag3'))
							+ trim(JRequest::getVar('jopensim_usersetting_flag4'))
							+ trim(JRequest::getVar('jopensim_usersetting_flag5'))
							+ trim(JRequest::getVar('jopensim_usersetting_flag9'))
							+ trim(JRequest::getVar('jopensim_usersetting_flag10'))
							+ trim(JRequest::getVar('jopensim_usersetting_flag11'))
							+ trim(JRequest::getVar('jopensim_usersetting_flag12'));
//		debugprint($data);
//		exit;
		$pwd1				= trim(JRequest::getVar('pwd1'));
		$pwd2				= trim(JRequest::getVar('pwd2'));

		if($model->checkUserExists($data['FirstName'],$data['LastName'],$data['PrincipalID']) !== FALSE) { // check if user already exists
			$type = "error";
			$message = JText::_('ERROR_USEREXISTS');
			$redirect = "index.php?option=com_opensim&view=user&task=edituser&checkUser[0]=".$data['PrincipalID']."&firstname=".$data['FirstName']."&lastname=".$data['LastName']."&email=".$data['Email']."&UserLevel=".$data['UserLevel'];
		} elseif($pwd1 != $pwd2) {
			$type = "error";
			$message = JText::_('ERROR_PWDMISMATCH');
			$redirect = "index.php?option=com_opensim&view=user&task=edituser&checkUser[0]=".$data['PrincipalID']."&firstname=".$data['FirstName']."&lastname=".$data['LastName']."&email=".$data['Email']."&UserLevel=".$data['UserLevel'];
		} elseif(!$data['FirstName']) {
			$type = "error";
			$message = JText::_('ERROR_NOFIRSTNAME');
			$redirect = "index.php?option=com_opensim&view=user&task=edituser&checkUser[0]=".$data['PrincipalID']."&firstname=".$data['FirstName']."&lastname=".$data['LastName']."&email=".$data['Email']."&UserLevel=".$data['UserLevel'];
		} elseif(!$data['LastName']) {
			$type = "error";
			$message = JText::_('ERROR_NOLASTNAME');
			$redirect = "index.php?option=com_opensim&view=user&task=edituser&checkUser[0]=".$data['PrincipalID']."&firstname=".$data['FirstName']."&lastname=".$data['LastName']."&email=".$data['Email']."&UserLevel=".$data['UserLevel'];
		} elseif(!$data['Email']) {
			$type = "error";
			$message = JText::_('ERROR_NOEMAIL');
			$redirect = "index.php?option=com_opensim&view=user&task=edituser&checkUser[0]=".$data['PrincipalID']."&firstname=".$data['FirstName']."&lastname=".$data['LastName']."&email=".$data['Email']."&UserLevel=".$data['UserLevel'];
		} else {
			$pregmail = "/^.{1,}@.{2,}\..{2,4}\$/";
			preg_match($pregmail, $data['Email'], $treffer); // Emailadresse auf Gültigkeit prüfen
			if($treffer[0] != $data['Email'] || !isset($treffer[0])) { // validate Email format
				$type = "error";
				$message = JText::_('ERROR_INVALIDEMAIL');
				$redirect = "index.php?option=com_opensim&view=user&task=edituser&checkUser[0]=".$data['PrincipalID']."&firstname=".$data['FirstName']."&lastname=".$data['LastName']."&email=".$data['Email'];
			} else {
				if($pwd1) $data['password'] = $pwd1;
				$result = $model->updateuser($data);
				if($result === TRUE) {
					$type = "message";
					$message = JText::_('OK_USERUPDATE');
				} else {
				$type = "error";
				$message = JText::_('ERROR_USERUPDATE');
				}
				$redirect = "index.php?option=com_opensim&view=user";
			}
		}
		$this->setRedirect($redirect,$message,$type);
	}

	public function insertuser() {
		$model = $this->getModel('user');
		$opensim =& $model->opensim;

		$firstname	= trim(JRequest::getVar('firstname'));
		$lastname	= trim(JRequest::getVar('lastname'));
		$email		= trim(JRequest::getVar('email'));
		$pwd1		= trim(JRequest::getVar('pwd1'));
		$pwd2		= trim(JRequest::getVar('pwd2'));

		if($model->checkUserExists($firstname,$lastname)) { // check if user already exists
			$type = "error";
			$message = JText::_('ERROR_USEREXISTS');
			$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif($pwd1 != $pwd2) {
			$type = "error";
			$message = JText::_('ERROR_PWDMISMATCH');
			$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$pwd1) {
			$type = "error";
			$message = JText::_('ERROR_NOPWD');
			$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$firstname) {
			$type = "error";
			$message = JText::_('ERROR_NOFIRSTNAME');
			$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$lastname) {
			$type = "error";
			$message = JText::_('ERROR_NOLASTNAME');
			$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} elseif(!$email) {
			$type = "error";
			$message = JText::_('ERROR_NOEMAIL');
			$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
		} else {
			$pregmail = "/^.{1,}@.{2,}\..{2,4}\$/";
			preg_match($pregmail, $email, $treffer); // Emailadresse auf Gültigkeit prüfen
			if($treffer[0] != $email || !isset($treffer[0])) { // validate Email format
				$type = "error";
				$message = JText::_('ERROR_INVALIDEMAIL');
				$redirect = "index.php?option=com_opensim&view=user&task=newuser&firstname=".$firstname."&lastname=".$lastname."&email=".$email;
			} else {
				$newuser['firstname']	= $firstname;
				$newuser['lastname']	= $lastname;
				$newuser['email']		= $email;
				$newuser['password']	= $pwd1;
				$newuser['uuid'] = $model->getUUID();
				$result = $model->insertuser($newuser);
				$type = "message";
				$message = JText::_('OK_NEWUSER');
				$redirect = "index.php?option=com_opensim&view=user";
			}
		}
		$this->setRedirect($redirect,$message,$type);
	}

	public function deleteuser() {
		$data = JRequest::get('post');
		$model = $this->getModel('user');
		if(is_array($data['checkUser'])) {
			$counter = 0;
			foreach($data['checkUser'] AS $userid) {
				$deleted = $model->deleteUser($userid);
				if($deleted == TRUE) $counter++;
			}
			if($counter == count($data['checkUser'])) {
				$type = "message";
				$message = JText::_('OK_DELETEUSER');
			} else {
				$type = "notice";
				$message = JText::sprintf('OK_DELETEXUSER',$counter);
			}
			$redirect = "index.php?option=com_opensim&view=user";
		} else {
			$type = "error";
			$message = JText::_('ERROR_DELETEUSER');
			$redirect = "index.php?option=com_opensim&view=user";
		}
		$this->setRedirect($redirect,$message,$type);
	}

	public function applyuserrelation() {
		$model = $this->getModel('user');
		$data = JRequest::get('post');
		if($data['joomlauser'] == 0) {
			$message = JText::_('OK_RELATIONNOCHANGE');
		}elseif($data['joomlauser'] == -1) {
			$deleted = $model->userrelation($data['userid'],999,"delete");
			$message = JText::_('OK_RELATIONDELETED');
		} else {
			switch($data['relationmethod']) {
				case "insert":
					$inserted = $model->userrelation($data['userid'],$data['joomlauser'],"insert");
					if($inserted) $message = JText::_('OK_RELATIONINSERTED');
					else $message = JText::_('OK_RELATIONNOCHANGE');
				break;
				case "update":
					$updated = $model->userrelation($data['userid'],$data['joomlauser'],"update");
					if($updated) $message = JText::_('OK_RELATIONUPDATED');
					else $message = JText::_('OK_RELATIONNOCHANGE');
				break;
			}
		}
		$type = "message";
		$redirect = "index.php?option=com_opensim&view=user";
		$this->setRedirect($redirect,$message,$type);
	}

	public function setUserOffline() {
		$userid = JRequest::getVar('userid');
		$model = $this->getModel('user');
		$model->setUserOffline($userid);
		$this->setRedirect("index.php?option=com_opensim&view=user",JText::_('OK_USETSETOFFLINE'));
	}

	public function repairUserStatus() {
		$model = $this->getModel('user');
		$model->repairUserStatus();
		$this->setRedirect("index.php?option=com_opensim&view=user",JText::_('OK_REPAIRUSERSTATUS'));
	}

	public function userMoney() {
		$model = $this->getModel('user');
		if($model->moneyEnabled === TRUE) {
			$data = JRequest::get('post');
			$uuid = $data['checkUser'][0];
			$redirect = "index.php?option=com_opensim&view=money&task=userMoney&uuid=".$uuid."&test=".$data['checkUser'][0];
			$this->setRedirect($redirect);
		} else {
			$type = "error";
			$message = JText::_('JOPENSIM_ERROR_MONEYDISABLED');
			$redirect = "index.php?option=com_opensim&view=user";
			$this->setRedirect($redirect,$message,$type);
		}
	}
}
?>
