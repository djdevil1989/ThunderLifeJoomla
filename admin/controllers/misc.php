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

class OpenSimCpControllermisc extends OpenSimCpController {
	public function __construct() {
		parent::__construct();
		$model = $this->getModel('misc');
	}

	public function sendoutmessage() {
		$data = JRequest::get('post');
		$message = $data['message'];
		$model = $this->getModel('misc');
		$settings = $model->getSettingsData();
		$opensim = $model->opensim;
		$opensim->RemoteAdmin($settings['remotehost'],$settings['remoteport'],$settings['remotepasswd']);
		$command = "admin_broadcast";
		$params = array('message' => $message);
		$returnvalue = $opensim->SendCommand($command,$params);
		$debug = var_export($returnvalue,TRUE);
		if(!is_array($returnvalue)) {
			JFactory::getApplication()->enqueueMessage(JText::_('REMOTEADMIN_NORESPONSE'),"error");
		} elseif(array_key_exists("error",$returnvalue) && $returnvalue['error']) {
			$message = JText::_('REMOTEADMIN_ERROR').": ".$returnvalue['error'];
			JFactory::getApplication()->enqueueMessage($message,"error");
		} else {
			$messages = array();
			$message = JText::_('MESSAGESENT')." (".JText::_('REMOTEADMIN_RESPONDED').": ";
			foreach($returnvalue AS $key => $val) {
				$messages[] = $key."=".$val;
			}
			$message .= implode(",",$messages).")";
			JFactory::getApplication()->enqueueMessage($message,"message");
		}
		/*JFactory::getApplication()->enqueueMessage("<pre>".$debug."</pre>","error");*/
		$this->setRedirect('index.php?option=com_opensim&view=misc');
	}

	public function createregionsend() {
		$data				= JRequest::get('post');
		$region_name		= $data['region_name'];
		$listen_ip			= $data['listen_ip'];
		$listen_port		= $data['listen_port'];
		$external_address	= $data['external_address'];
		$region_x			= intval($data['region_x']);
		$region_y			= intval($data['region_y']);
		$public				= $data['public'];
		$voice_enabled		= $data['voice'];
		$estate_name		= $data['estate_name'];
		$persist			= 'true';

		$model				= $this->getModel('misc');
		$settings			= $model->getSettingsData();
		$opensim			= $model->opensim;

		$opensim->RemoteAdmin($settings['remotehost'],$settings['remoteport'],$settings['remotepasswd']);
		$command		= "admin_create_region";
		$params			= array('region_name' => $region_name,
							'listen_ip' => $listen_ip,
							'listen_port' => $listen_port,
							'external_address' => $external_address,
							'region_x' => $region_x,
							'region_y' => $region_y,
							'public' => $public,
							'enable_voice' => $voice,
							'persist' => $persist,
							'region_file' => 'jOpenSim.ini',
							'estate_name' => $estate_name);
		$returnvalue	= $opensim->SendCommand($command,$params);
		if(!is_array($returnvalue)) {
			JFactory::getApplication()->enqueueMessage(JText::_('REMOTEADMIN_NORESPONSE'),"error");
		} elseif(array_key_exists("error",$returnvalue) && $returnvalue['error']) {
			$message	= JText::_('REMOTEADMIN_ERROR').": ".$returnvalue['error'];
			JFactory::getApplication()->enqueueMessage($message,"error");
		} else {
			$messages	= array();
			$message	= JText::_('MESSAGESENT')." (".JText::_('REMOTEADMIN_RESPONDED').": ";
			foreach($returnvalue AS $key => $val) {
				$messages[] = $key."=".$val;
			}
			$message .= implode(",",$messages).")";
			JFactory::getApplication()->enqueueMessage($message,"message");
		}
		$this->setRedirect('index.php?option=com_opensim&view=misc');
	}
	public function savewelcomemessage() {
		$model = $this->getModel('misc');
		$model->updateWelcome();
		$this->setRedirect('index.php?option=com_opensim&view=misc',JText::_('WELCOMEUPDATED'));
	}

	public function removewelcome() {
		$model = $this->getModel('misc');
		$model->removeWelcome();
		$this->setRedirect('index.php?option=com_opensim&view=misc',JText::_('WELCOMEUPDATED'));
	}

	public function toggleTerminal() {
		$terminalKey = JRequest::getVar('terminalKey');
		$model = $this->getModel('misc');
		$model->toggleTerminal($terminalKey);
		$this->setRedirect('index.php?option=com_opensim&view=misc&task=terminals');
	}

	public function saveTerminalStatic() {
		/*$postdata = JRequest::get('post');*/
		/*debugprint($postdata,"postdata");*/
		/*exit;*/
		$terminalKey = JRequest::getVar('terminalKey');
		$staticValue = JRequest::getVar('staticValue');
		$model = $this->getModel('misc');
		$model->saveTerminalStatic($terminalKey,$staticValue);
		$this->setRedirect('index.php?option=com_opensim&view=misc&task=terminals',JText::_('AUTOUPDATED'));
	}

	public function saveTerminal() {
		$postdata = JRequest::get('post');
		$model = $this->getModel('misc');
		$model->saveTerminal($postdata);
		$this->setRedirect('index.php?option=com_opensim&view=misc&task=terminals',JText::_('TERMINALUPDATED'));
	}

	public function deleteTerminal() {
		$terminalArray = JRequest::getVar('checkTerminal');
		$model = $this->getModel('misc');
		$model->removeTerminal($terminalArray);
		$this->setRedirect('index.php?option=com_opensim&view=misc&task=terminals',JText::_('TERMINALREMOVED'));
	}
}
?>
