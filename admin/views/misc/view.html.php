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
 
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
class opensimcpViewmisc extends JView {
	public function display($tpl = null) {
		JHTML::_('behavior.modal');
		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );
		$model = $this->getModel('misc');
		$settings = $model->getSettingsData();
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		$assetinfo = pathinfo(JPATH_COMPONENT_ADMINISTRATOR);
		$assetpath = "components".DS.$assetinfo['basename'].DS."assets".DS;
		$this->assignRef('assetpath',$assetpath);

		switch($task) {
			case "addregion":
				$remotehost = $settings['remotehost'];
				$this->assignRef('remotehost', $remotehost);
				$tpl = "addregion";
			break;
			case "sendmessage":
				$tpl = "sendmessage";
			break;
			case "terminals":
				$terminalList = $model->getTerminalList(1);
		 		$pagination =& $this->get('Pagination');
				$this->assignRef('pagination', $pagination);
				$this->assignRef('terminalList', $terminalList);
				$tpl = "terminals";
			break;
			case "terminaledit":
				/*$terminalArray = JRequest::get('checkTerminal');*/
				$postdata = JRequest::get('post');
				$terminalArray = $postdata['checkTerminal'];
				$terminalKey = $terminalArray[0];
				$terminalData = $model->getTerminal($terminalKey);
				$this->assignRef('terminal', $terminalData);
				$tpl = "terminaledit";
			break;
			case "pingTerminal":
				$terminalKey = JRequest::getVar('terminalKey');
				$terminalData = $model->getTerminal($terminalKey);
				$pingString = $terminalData['terminalUrl']."?ping=jOpenSim";
				$pingAnswer = @file_get_contents($pingString,FALSE,null,0,13);
				if($pingAnswer == "") $pingAnswer = JText::_('NOPINGANSWER');
				elseif($pingAnswer != "ok, I am here") $pingAnswer = JText::_('UNKNOWNPINGANSWER').": ".$pingAnswer."...";
				$this->assignRef('terminal', $terminalData);
				$this->assignRef('pingAnswer', $pingAnswer);
				$tpl = "terminalping";
			break;
			default:
				$debug = "<pre>\n".var_export($settings,TRUE)."</pre>\n";
				$this->assignRef('debug', $debug);
				if($settings['enableremoteadmin'] == "1") {
					$misclinks['addregion'] = "<a href='index.php?option=com_opensim&view=misc&task=addregion'>".JText::_('JOPENSIM_ADDREGION')."</a>";
					$misclinks['sendmessage'] = "<a href='index.php?option=com_opensim&view=misc&task=sendmessage'>".JText::_('SENDGLOBALMESSAGE')."</a>";
				} else {
					$misclinks['addregion'] = JText::_('JOPENSIM_ADDREGION')." (".JText::_('DISABLED_NOREMOTEADMIN').")";
					$misclinks['sendmessage'] = JText::_('SENDGLOBALMESSAGE')." (".JText::_('DISABLED_NOREMOTEADMIN').")";
				}
				if($settings['addons'] & 8) {
					$misclinks['terminals'] = "<a href='index.php?option=com_opensim&view=misc&task=terminals'>".JText::_('MANAGETERMINALS')."</a>";
				} else {
					$misclinks['terminals'] = JText::_('MANAGETERMINALS')." (".JText::_('DISABLED_MANAGETERMINALS').")";
				}
			break;
		}
		$this->assignRef( 'misclinks', $misclinks );

//		$this->assignRef( 'ueberschrift', $ueberschrift );
// 		$this->assignRef( 'zusatztext', $zusatztext );

		if($settings['welcomecontent']) { // get the welcom content id
			$contentTitle = $model->getContentTitleFromId($settings['welcomecontent']);
		} else {
			$contentTitle = JText::_('NOCONTENT');
		}
		$this->assignRef('contentTitle', $contentTitle);
		$this->assignRef('welcomemessageID', sprintf($settings['welcomecontent']));

		//Get button
//		$linkg = 'index.php?option=com_content&task=element&tmpl=component&object=id';
		$linkg = 'index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=jOpenSimSelectArticle';
		JHTML::_('behavior.modal', 'a.modal-button');
		$selectArticle = new JObject();
		$selectArticle->set('modal', true);
		$selectArticle->set('link', $linkg);
		$selectArticle->set('text', JText::_('SELECT_ARTICLE'));
		$selectArticle->set('name', 'image');
		$selectArticle->set('modalname', 'modal-button');
		$selectArticle->set('options', "{handler: 'iframe', size: {x: 640, y: 360}}");
		// - - - - - - - - - - - - - - - - 
		$this->assignRef('selectArticle', $selectArticle);



		$this->_setToolbar($tpl);
		parent::display($tpl);
	}

	public function _setToolbar($tpl) {
		JToolBarHelper::title(JText::_('JOPENSIM_MENU_MISC'),'osmisc');
		$task = JRequest::getVar( 'task', '', 'method', 'string');
		$model = $this->getModel('misc');

		switch($tpl) {
			case "addregion":
				JToolBarHelper::save('createregionsend');
				JToolBarHelper::cancel('canceladdregion','JCANCEL');
			break;
			case "sendmessage":
				JToolBarHelper::publish('sendoutmessage');
				JToolBarHelper::cancel('cancelmessage','JCANCEL');
			break;
			case "terminals":
				JToolBarHelper::deleteList(JText::_('DELETETERMINALSURE'),"deleteTerminal",JText::_('DELETETERMINAL'),true,false);
				JToolBarHelper::editList('terminaledit');
				JToolBarHelper::cancel('cancelTerminal','JCANCEL');
			break;
			case "terminaledit":
				JToolBarHelper::save('saveTerminal');
				JToolBarHelper::cancel('terminals');
			break;
			default:
				$os_settings = $model->getSettingsData();
				if(isset($os_settings['remoteadmin_enabled']) && $os_settings['remoteadmin_enabled'] == 1) {
					JToolBarHelper::custom("sendmessage","osmisc","opensim",JText::_('SENDMESSAGE2USER'),false,false);
				}
			break;
		}
	}
}

?>