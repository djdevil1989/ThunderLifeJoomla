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
 
class opensimcpViewopensimcp extends JView {
	public function display($tpl = null) {
		JHTML::_('behavior.modal');
		$task	= JRequest::getVar( 'task','','method','string');
		$model	= $this->getModel('opensimcp');
		switch($task) {
			case "editcss":
				if (!JFactory::getUser()->authorise('core.admin', 'com_opensim')) {
					$tpl = "noaccess";
					$this->setToolbar("csserror");
				} else {
					$tpl = "editcss";
					$cssfile = $model->frontendCSS();
					$this->assignRef('cssfile',$cssfile);
					if (JFile::exists($cssfile))  {
						if(is_writable($cssfile)) {
							$cssmsg = "<span style='color:#009900'>".JText::_('JOPENSIM_CSSWRITEABLE')."</span>";
							$this->setToolbar($tpl);
						} else {
							$cssmsg = "<span style='color:#cc0000'>".JText::sprintf('JOPENSIM_CSSNOTWRITEABLE_DESC',$cssfile)."</span>";
							JError::raiseNotice( 100,JText::_('JOPENSIM_CSSNOTWRITEABLE'));
							$this->setToolbar("csserror");
						}
						$csscontent = file_get_contents($cssfile);
						$this->assignRef('cssmsg',$cssmsg);
						$this->assignRef('csscontent',$csscontent);
					} else {
						JError::raiseWarning( 100, JText::_('JOPENSIM_CSSNOTFOUND'));
					}
				}
			break;
			default:
				$version = opensim::osversion();
				$recentversion = opensim::checkversion();
				$settings	= $model->_settingsData;
				$this->assignRef('addons',$settings['addons']);
				JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );
				$params 	= JComponentHelper::getParams('com_opensim');
				$message = JText::_('PLS_CHOOSE_OPTION');
				$this->assignRef('version',$version);
				$this->assignRef('message',$message);
				$this->assignRef('recentversion', $recentversion);
				$button['quickicon']	= $this->renderPlainButton('quickicon_jopensim.php',JText::_('GRIDSTATUS'));
				$button['settings']		= $this->renderButton('index.php?option=com_opensim&view=settings','icon-48-jopensimconfig.png',JText::_('JOPENSIM_MENU_SETTINGS'));
				$button['loginscreen']	= $this->renderButton('index.php?option=com_opensim&view=loginscreen','icon-48-os-login.png',JText::_('JOPENSIM_MENU_LOGINSCREEN'));
				$button['maps']			= $this->renderButton('index.php?option=com_opensim&view=maps','icon-48-os-maps.png',JText::_('JOPENSIM_MENU_MAPS'));
				$button['user']			= $this->renderButton('index.php?option=com_opensim&view=user','icon-48-os-user.png',JText::_('JOPENSIM_MENU_USER'));
				$button['groups']		= $this->renderButton('index.php?option=com_opensim&view=groups','icon-48-os-group.png',JText::_('JOPENSIM_MENU_GROUPS'));
				if(($settings['addons'] &  16) == 16) {
					$button['search']		= $this->renderButton('index.php?option=com_opensim&view=search','icon-48-os-search.png',JText::_('JOPENSIM_MENU_SEARCH'));
				}
				$button['money']		= $this->renderButton('index.php?option=com_opensim&view=money','icon-48-money.png',JText::_('JOPENSIM_MENU_MONEY'));
				$button['misc']			= $this->renderButton('index.php?option=com_opensim&view=misc','icon-48-os-misc.png',JText::_('JOPENSIM_MENU_MISC'));
				$this->assignRef('moneyenabled', $moneyenabled);
				$this->assignRef('adminbuttons', $button);
				$this->setToolbar($tpl);
			break;
		}

		parent::display($tpl);
	}

	public function setToolbar($tpl) {
		switch($tpl) {
			case "editcss":
				JToolBarHelper::apply('applycss');
				JToolBarHelper::save('savecss');
				JToolBarHelper::cancel();
			break;
			case "csserror":
				JToolBarHelper::cancel();
			break;
			default:
				JToolBarHelper::title(JText::_('OPENSIM_CONTROL_PANEL'),'opensim');
				if (JFactory::getUser()->authorise('core.admin', 'com_opensim')) {
					JToolBarHelper::preferences('com_opensim','700','950',JText::_('GLOBAL_SETTINGS'));
					JToolBarHelper::custom('editcss', 'css.png', 'css_f2.png', 'JOPENSIM_EDITCSS',FALSE);
				}
				JToolBarHelper::help("", false, JText::_('JOPENSIM_HELP'));
			break;
		}
	}

	public function renderButton($link,$image,$text) {
		$params = array('title'=>$text, 'border'=>'0');
		$button  = "<div class='icon-wrapper'>";
		$button .= "<div class='icon'>";
		$button .= sprintf("<a href='%s' class='os_mainscreen'>",$link);
		$button .= JHTML::_('image', 'administrator/components/com_opensim/assets/images/'.$image,$text,$params);
		$button .= sprintf("<span>%s</span></a>",$text);
		$button .= "</div></div>\n";
		return $button;
	}
	public function renderPlainButton($image,$text) {
		$params = array('title'=>$text, 'border'=>'0');
		$button  = "<div class='icon-wrapper'>";
		$button .= "<div class='icon'><a>";
		$button .= JHTML::_('image', 'administrator/components/com_opensim/assets/'.$image,$text,$params);
		$button .= sprintf("<span>%s</span></a>",$text);
		$button .= "</div></div>\n";
		return $button;
	}
}
?>