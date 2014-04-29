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

class opensimcpViewaddons extends JView {
	public function display($tpl = null) {
		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );

		$model		= $this->getModel('addons');
		$task		= JRequest::getVar('task');
		switch($task) {
			case "ominfo":
				$infotext = "<span class='jopensim_infotitle'>OpenSim.ini:</span>\n\n[Messaging]\n\n\tOfflineMessageModule = OfflineMessageModule\n\tOfflineMessageURL = ".JURI::root()."components/com_opensim/interface.php\n\tMuteListModule = MuteListModule\n\tMuteListURL = ".JURI::root()."components/com_opensim/interface.php\n\n\t; Optional:\n\tForwardOfflineGroupMessages = true\n";
			break;
			case "pinfo":
				$infotext = "<span class='jopensim_infotitle'>config-include/GridCommon.ini</span> (Grid Mode)\nor\n<span class='jopensim_infotitle'>config-include/StandaloneCommon.ini</span> (Standalone Mode)\n\n[Profile]\n\n\tProfileURL = ".JURI::root()."components/com_opensim/interface.php\n\tModule = \"jOpenSimProfile\"\n\n\t; Optional:\n\tDebug = true\n\n...and copy:\n".JPATH_COMPONENT_ADMINISTRATOR."/opensim_modules/0.7.x/jOpenSim.Profile.dll\nto your opensim/bin folder";
			break;
			case "ginfo":
				$infotext = "<span class='jopensim_infotitle'>OpenSim.ini:</span>\n\n[Groups]\n\n\tEnabled = true\n\tModule = GroupsModule\n\tServicesConnectorModule = XmlRpcGroupsServicesConnector\n\tGroupsServerURI = \"".JURI::root()."components/com_opensim/interface.php\"\n\n\t; These values must match your settings in \"Global Configuration\"!\n\tXmlRpcServiceReadKey = 1234\n\tXmlRpcServiceWriteKey = 4321\n\n\t; Optional:\n\tMessagingEnabled = true\n\tMessagingModule = GroupsMessagingModule\n\tNoticesEnabled = true\n\tDebugEnabled = false\n";
			break;
			case "sinfo":
				$infotext = "<span class='jopensim_infotitle'>OpenSim.ini:</span>\n\n[DataSnapshot]\n\n\tindex_sims = true\n\tdata_services=\"".JURI::root()."components/com_opensim/registersearch.php\"\n\n\t; Optional (if you want to provide events, this is required to collect ALL parcel data):\n\tdata_exposure = all";
				$infotext .="\n\n[Search]\n\n\tSearchURL = ".JURI::root()."components/com_opensim/interface.php\n\n\t; Optional:\n\tDebugMode = true\n\n...and copy:\n".JPATH_COMPONENT_ADMINISTRATOR."/opensim_modules/0.7.x/jOpenSim.Search.dll\nto your opensim/bin folder";
			break;
			case "iaiinfo":
				$infotext = "Copy:\n".JPATH_COMPONENT_ADMINISTRATOR."/lsl-scripts/jOpenSimTerminal.lsl\ninto a prim inside your OpenSimulator and see:\n<a href='http://help.jopensim.com/index.php?keyref=addon_inworldident' target='_blank'>http://help.jopensim.com/index.php?keyref=addon_inworldident</a> for more information.\n";
			break;
			case "minfo":
				$infotext = "<span class='jopensim_infotitle'><i>Demoversion:</i>\n\nOpenSim.ini:</span>\n\n\t[Economy]\n\n\tEconomyModule = jOpenSimMoneyModule\n\tCurrencyURL = \"".JURI::root()."components/com_opensim/currency.php\"\n\n\t; Optional:\n\tDebugMode = \"1\"\n\tPay2myself = true\n\tPayPopup = true\n\tPayPopupMsgSender = \"You paid jO\${0} to {1}\"\n\tPayPopupMsgReceiver = \"You received jO\${0} from {1}\"\n\n";
				$infotext .="\n\n<span class='jopensim_infotitle'>Robust.ini:</span>\n\n\t[GridInfoService]\n\n\t...\n\teconomy = ".JURI::root()."components/com_opensim/\n\n\t; Optional:\n\t[LoginService]\n\tCurrency = \"jO\$\"\n";
				$infotext .="\n\n... and download OpenSim.Joomla.Money.dll from http://www.jopensim.com and copy it to the bin folder of your OpenSim.\n";
				$infotext .="\n\n<span class='jopensim_infotitle'><i>Full Version:</i></span>\n\nAfter purchasing a license for the full version at http://www.jopensim.com you will receive email with further information.\n";
			break;
			default:
				$infotext = "Click on <img src='components/com_opensim/assets/images/info16.png' width='16' height='16' border='0' align='absmiddle' title='addon information' alt='addon information' /> to get more information about how to enable the addon's";
			break;
		}

		$settings	= $model->_settingsData;
		$this->assignRef('infotext',$infotext);
		$this->assignRef('addons',$settings['addons']);

		$this->_setToolbar($tpl);
		parent::display($tpl);
	}

	public function _setToolbar($tpl) {
		JToolBarHelper::title(JText::_('OPENSIM')." ".JText::_('ADDONS'),'osuser');
		switch($tpl) {
			default:
//				JToolBarHelper::deleteList(JText::_('DELETEUSERSURE'),"deleteuser",JText::_('DELETEUSER'));
//				$model = $this->getModel('user');
//				$os_settings = $model->getSettingsData();
//				if(isset($os_settings['remoteadmin_enabled']) && $os_settings['remoteadmin_enabled'] == 1) {
//					JToolBarHelper::custom("sendMessage","opensim","opensim",JText::_(SENDMESSAGE2USER),true,false); <-- TODO
//				}
//				JToolBarHelper::addNew("newuser",JText::_('ADDNEWUSER'));
//				JToolBarHelper::editList("edituser",JText::_('JOPENSIM_EDITUSER'));
//				JToolBarHelper::custom("attachUser","joomla2opensim","opensim",JText::_('ATTACHJOOMLA2OPENSIM'),true,false);
//				JToolBarHelper::custom("repairUserStatus","userrepair","opensim",JText::_('REPAIRUSERSTATUS'),false,false);
//				if($model->moneyEnabled === TRUE) {
//					JToolBarHelper::custom("userMoney","usermoney","opensim",JText::_('JOPENSIMUSERMONEY'),true,false);
//				}
//				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP_USER'));
			break;
		}
	}
}

?>