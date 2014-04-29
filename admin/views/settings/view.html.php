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

class opensimcpViewsettings extends JView {

	function display($tpl = null) {
		JHTML::stylesheet('opensim.css','administrator/components/com_opensim/assets/');
		$tpltext = array();
		$tpltext['dbsettings']			= JText::_('DBSETTING');
		$tpltext['remoteadmin']			= JText::_('REMOTEADMIN');
		$tpltext['enableremoteadmin']	= JText::_('REMOTEADMINENABLE');
		$tpltext['password']			= JText::_('PASSWORD');
		$tpltext['opensimserver']		= JText::_('OSSERVER');
		$tpltext['opensimdbserver']		= JText::_('SERVER1');
		$tpltext['estateserver']		= JText::_('SERVER2');
		$tpltext['host']				= JHTML::tooltip(JText::_('HOST_TT'),JText::_('HOST_TTT'),'',JText::_('HOST'));
		$tpltext['remotehost']			= JHTML::tooltip(JText::_('REMOTEHOST_TT'),JText::_('REMOTEHOST_TTT'),'',JText::_('HOST'));
		$tpltext['port']				= JHTML::tooltip(JText::_('PORT_TT'),JText::_('PORT_TTT'),'',JText::_('PORT'));
		$tpltext['dbhost']				= JText::_('DBHOST');
		$tpltext['dbport']				= JText::_('PORT');
		$tpltext['dbname']				= JText::_('DBNAME');
		$tpltext['dbuser']				= JText::_('DBUSER');
		$tpltext['dbpass']				= JText::_('DBPASS');
		$tpltext['userperms']			= JText::_('USERPERMS');
		$tpltext['userpermsfirstname']	= JText::_('USERPERMFIRSTNAME');
		$tpltext['userpermslastname']	= JText::_('USERPERMLASTNAME');
		$tpltext['userpermsemail']		= JText::_('USERPERMEMAIL');
		$tpltext['userpermspwd']		= JText::_('USERPERMPASSWORD');
		$tpltext['lastnamelist']		= JText::_('LASTNAMELIST');
		$tpltext['lastnametypenone']	= JHTML::tooltip(JText::_('LASTNAMETYPE_NONE_TT'),JText::_('LASTNAMETYPE_NONE_TTT'),'',JText::_('LASTNAMETYPE_NONE'));
		$tpltext['lastnametypeallow']	= JHTML::tooltip(JText::_('LASTNAMETYPE_ALLOW_TT'),JText::_('LASTNAMETYPE_ALLOW_TTT'),'',JText::_('LASTNAMETYPE_ALLOW'));
		$tpltext['lastnametypedeny']	= JHTML::tooltip(JText::_('LASTNAMETYPE_DENY_TT'),JText::_('LASTNAMETYPE_DENY_TTT'),'',JText::_('LASTNAMETYPE_DENY'));
		$tpltext['lastnames']			= JHTML::tooltip(JText::_('LASTNAMES_TT'),JText::_('LASTNAMES_TTT'),'',JText::_('LASTNAMES'));

		$tpltext['addons']				= JText::_('ADDONS');
		$tpltext['addons_messages']		= JText::_('ADDONS_MESSAGES');
		$tpltext['addons_profile']		= JText::_('ADDONS_PROFILE');
		$tpltext['addons_groups']		= JText::_('ADDONS_GROUPS');
		$tpltext['addons_search']		= JText::_('ADDONS_SEARCH');
		$tpltext['addons_currency']		= JText::_('JOPENSIM_ADDONS_MONEY');
		$tpltext['addons_inworldauth']	= JText::_('ADDONS_INWORLD_IDENT');
		$tpltext['listmatureevents']	= JHTML::tooltip(JText::_('JOPENSIM_LISTMATUREEVENTS_TT'),JText::_('JOPENSIM_LISTMATUREEVENTS_TTT'),'',JText::_('JOPENSIM_LISTMATUREEVENTS'));
		


		foreach($tpltext AS $key => $val) {
			$varname = $key;
			$varvalue = $val;
			$this->assignRef($varname,$tpltext[$key]);
		}


		unset($val);
		$setting = $this->get('Data');
//		$setting = $settingsdata[0];
		$minutefield = "<input type='text' name='identminutes' id='identminutes' value='".$setting['identminutes']."' class='minute_field' />";
		$minutestring = sprintf(JText::_('ADDOND_FOR_X_MINUTES'),$minutefield);
		$this->assignRef('minutestring',$minutestring);
		$timezone_identifiers = DateTimeZone::listIdentifiers();
		$this->assignRef('timezones',$timezone_identifiers);
		if(!array_key_exists('eventtimedefault',$setting) || !$setting['eventtimedefault']) $setting['eventtimedefault'] = "UTC";
		$this->assignRef('os_setting',$setting);

		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display($tpl);
		$this->_setToolbar($tpl);
	}

	function _setToolbar($tpl) {
		$task = JRequest::getVar( 'task', '', 'method', 'string');
		JToolBarHelper::title( JText::_('JOPENSIM_MENU_SETTINGS'), 'jopensimconfig' );
		JToolBarHelper::save();
		JToolBarHelper::apply('apply_settings');	
		JToolBarHelper::cancel('cancel','JCANCEL');
		JToolBarHelper::help('jOpenSimHelp',FALSE,JText::_('JOPENSIM_HELP_SETTINGS'));
	}
}
?>