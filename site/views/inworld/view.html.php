<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
jimport( 'joomla.html.toolbar.button.standard' );
jimport( 'joomla.html.parameter' );

class opensimViewinworld extends JView {
	public function display($tpl = null) {
		$this->assignRef('tpl_var',$tpl);
		$this->assignRef('template_var',$this->template);
		JHTML::stylesheet( 'opensim.css', 'components/com_opensim/assets/' );
		JHTML::_('behavior.modal');

		$itemid = JRequest::getVar('Itemid');
		$menu = &JSite::getMenu();
		$active = $menu->getActive($itemid);
		$pageclass = "";
		if (is_object($active)) {
			$params = new JParameter( $active->params );
			$pageclass_sfx = $params->get( 'pageclass_sfx' );
		}
		$this->assignRef('pageclass_sfx',$pageclass_sfx);
		$this->assignRef('Itemid',$itemid);

		$model = $this->getModel('inworld');
		$ossettings = $model->getSettingsData();
		$task = JRequest::getVar('task','','method','string');
		$user =& JFactory::getUser();
		if($user->guest) {
			$tpl = "needlogin";
		} elseif(!$model->_osgrid_db) {
			$tpl = "error";
		} else {
			$this->assignRef('ossettings',$ossettings);
			$created = $model->opensimIsCreated();
			if(!$created) {
				$model->cleanIdents(); // delete Idents
				$inworldIdent = $model->opensimGetInworldIdent();
				if($inworldIdent) {
					$tpl = "identdisplay";
					$this->assignRef('identstring',$inworldIdent);
					$command = JText::_('TERMINALCOMMANDTEXT');
					if($ossettings['terminalchannel'] > 0) $typechannel = "/".$ossettings['terminalchannel']." ";
					else $typechannel = "";
					$identcommand = $typechannel."identify ".$inworldIdent;
					$this->assignRef('identcommand',$identcommand);
					$terminalList = $model->getTerminalList();
					$this->assignRef('terminalList',$terminalList);
				} else {
					$tpl = "create";

					// how is the last name controled?
					switch($ossettings['lastnametype']) {
						case 1: // only allow from the list
							$lastnamefield = "<select name='lastname' id='lastname'>\n";
							$lastnames = explode("\n",$ossettings['lastnamelist']);
							if(count($lastnames) > 0) {
								foreach($lastnames AS $lastname) {
									$lastnamefield .= "\t<option value='".trim($lastname)."'>".trim($lastname)."</option>\n";
								}
							}
							$lastnamefield .= "</select>\n";
						break;
						case 0: // no control at all
						case -1: // allow everything but not from the list (the validation has to be done later)
							$lastnamefield = "<input type='text' name='lastname' id='lastname' />";
						break;
					}
					$this->assignRef('lastnamefield',$lastnamefield);
				}
			} else {
				$topbar = $this->generateTopbar($task,$ossettings,$pageclass_sfx);
				$this->assignRef('topbar',$topbar);
				$osdata = $model->getUserData($created);
				if(!$osdata['im2email']) $osdata['im2email'] = 0;
				$this->assignRef('osdata',$osdata);
				$assetinfo = pathinfo(JPATH_COMPONENT);
				$assetpath = "components".DS.$assetinfo['basename'].DS."assets".DS;
				$this->assignRef('assetpath',$assetpath);
				switch($task) {
					case "welcome":
						$tpl = "welcome";
						$welcometitle = JText::_('WELCOME_DEFAULTTITLE');
						$welcometext = JText::_('WELCOME_DEFAULTTEXT');
						$this->assignRef('welcomedefault',$welcomedefault);
					break;
					case "messages":
						$addonsettings = $model->addonSettings();
						$messagelist = $model->messagelist($osdata['uuid']);
						$this->assignRef('messages',$messagelist);
						$this->assignRef('addonsettings',$addonsettings);
						$tpl = "messages";
					break;
					case "messagedetail":
						JHTML::stylesheet( 'opensim_modal.css', 'components/com_opensim/assets/' );
						$imSessionID = JRequest::getVar('imSessionID','','','string');
						$fromAgentID = JRequest::getVar('fromAgentID','','','string');
						$messagedetails = $model->messagedetail($imSessionID,$fromAgentID);
						$fromAgentName = $messagedetails['fromAgentName'];
						unset($messagedetails['fromAgentName']);
						$this->assignRef('messagedetails',$messagedetails);
						$this->assignRef('fromAgentName',$fromAgentName);
						$tpl = "messagedetail";
					break;
					case "profile":
						$wantmask		= $model->profile_wantmask();
						$skillsmask		= $model->profile_skillsmask();
						$profiledata	= $model->getprofile($osdata['uuid']);
						$this->assignRef('wantmask',$wantmask);
						$this->assignRef('skillsmask',$skillsmask);
						$this->assignRef('profiledata',$profiledata);
						$tpl = "profile";
					break;
					case "groups":
						$grouplist = $model->groupmemberships($osdata['uuid']);
						$this->assignRef('grouplist',$grouplist);
						$tpl = "groups";
					break;
					case "groupdetail":
						JHTML::stylesheet( 'opensim_modal.css', 'components/com_opensim/assets/' );
						$groupid = JRequest::getVar('groupid','','','string');
						$grouplist = $model->groupmemberships($osdata['uuid'],$groupid);
						$this->assignRef('grouplist',$grouplist[0]);
						$this->assignRef('grouplist1',$grouplist);
						$tpl = "groupdetail";
					break;
					case "groupnotices":
						JHTML::stylesheet( 'opensim_modal.css', 'components/com_opensim/assets/' );
						$groupid = JRequest::getVar('groupid','','','string');
						$grouplist = $model->groupmemberships($osdata['uuid'],$groupid);
						if($grouplist[0]['acceptnotices'] == 1 && $grouplist[0]['power']['power_receivenotice'] == 1 && $grouplist[0]['hasnotices'] > 0) $noticelist = $model->getnotices($groupid);
						else $noticelist = array();
						$this->assignRef('grouplist',$grouplist[0]);
						$this->assignRef('noticelist',$noticelist);
						$tpl = "groupnotices";
					break;
					case "groupmembers":
						JHTML::stylesheet( 'opensim_modal.css', 'components/com_opensim/assets/' );
						$groupid = JRequest::getVar('groupid','','','string');
//						$grouplist = $model->groupmemberships($osdata['uuid'],$groupid);
						$memberlist = $model->memberlist($groupid);
						$grouplist = $model->groupmemberships($osdata['uuid'],$groupid);
						$power = $model->group_power($osdata['uuid'],$groupid);
						$this->assignRef('memberlist',$memberlist);
						$this->assignRef('power',$power);
						$this->assignRef('grouplist',$grouplist[0]);
						$tpl = "groupmembers";
					break;
					case "money":
						$balance		= $model->getBalance($osdata['uuid']);
						$currencyname	= $model->getCurrencyName();
						$jinput			= JFactory::getApplication()->input;
						$range			= $jinput->get('range', '30', 'INTEGER');
						$itemid			= $jinput->get('Itemid');
						$transactions	= $model->transactionlist($osdata['uuid'],$range);
						$this->assignRef('balance',$balance);
						$this->assignRef('currencyname',$currencyname);
						$this->assignRef('range',$range);
						$this->assignRef('itemid',$itemid);
						$this->assignRef('transactions',$transactions);
						// check if jOpenSimMoney is installed
						$jopensimpaypalfolder = JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_jopensimpaypal";
						if(is_dir($jopensimpaypalfolder)) {
							$paypallink = "<a href='".JRoute::_("index.php?option=com_jopensimpaypal&view=paypal&Itemid=".$itemid."&returnto=jopensim")."'>".JText::_('JOPENSIM_MONEY_BUYPAYPAL')."</a>";
							$params = &JComponentHelper::getParams('com_jopensimpaypal');
							$payout = $params->get('currencyratesell');
							$minbuy = $params->get('minbuy');
							if($minbuy > 0) $minbalance = $payout * $minbuy;
							else $minbalance = $payout;
							$jopensimpaypal = TRUE;
						} else {
							$paypallink = "";
							$payout = 0;
							$minbalance = -1;
							$jopensimpaypal = FALSE;
						}
						if($jopensimpaypal === TRUE && $payout > 0 && $balance >= $minbalance) {
							$payoutlink = "<a href='".JRoute::_("index.php?option=com_jopensimpaypal&view=payout&Itemid=".$itemid."&returnto=jopensim")."'>".JText::_('JOPENSIM_MONEY_PAYOUTREQUEST')."</a>";
						} else {
							$payoutlink = "";
						}
						$this->assignRef('paypallink',$paypallink);
						$this->assignRef('jopensimmoney',$jopensimmoney);
						$this->assignRef('payout',$payout);
						$this->assignRef('payoutlink',$payoutlink);
						$tpl = "money";
					break;
					default:
						// how is the last name controled?
						switch($ossettings['lastnametype']) {
							case 1: // only allow from the list
								$lastnamefield = "<select name='lastname' id='lastname' class='".$pageclass_sfx."'>\n";
								$lastnames = explode("\n",$ossettings['lastnamelist']);
								$lastnames[] = $osdata['lastname']; // ensure that the own lastname is also in the list
								foreach($lastnames AS $key => $lastname) $lastnames[$key] = trim($lastname); // just to ensure different linebreaks between Win and Linux
								$lastnames = array_unique($lastnames); // no duplicate values ( ... own lastname ... )
								if(count($lastnames) > 0) {
									foreach($lastnames AS $lastname) {
										$lastnamefield .= "\t<option value='".$lastname."'";
										if($lastname == $osdata['lastname']) $lastnamefield .= " selected='selected'";
										$lastnamefield .= ">".$lastname."</option>\n";
									}
								}
								$lastnamefield .= "</select>\n";
							break;
							case 0: // no control at all
							case -1: // allow everything but not from the list (the check has to be done later)
								$lastnamefield = "<input type='text' name='lastname' id='lastname' value='".$osdata['lastname']."' class='".$pageclass_sfx."' />";
							break;
						}
						

						$timezone_identifiers = DateTimeZone::listIdentifiers();
						$this->assignRef('timezones',$timezone_identifiers);
						if(!array_key_exists('eventtimedefault',$ossettings) || !$ossettings['eventtimedefault']) $ossettings['eventtimedefault'] = "UTC";
						if(!array_key_exists('timezone',$osdata) || !$osdata['timezone']) $osdata['timezone'] = $ossettings['eventtimedefault'];


						$this->assignRef('lastnamefield',$lastnamefield);
						$tpl = "display";
					break;
				}
			}
		}
		$this->assignRef('userid',$created);
		$this->assignRef('user',$user);
//		$this->assignRef('debug',$debug);
 
		parent::display($tpl);
	}

	public function generateTopbar($task,$ossettings,$pageclass_sfx) {
		$itemid = JRequest::getVar('Itemid');
		if(!$task) $task = "default";
		$settings = JText::_('SETTINGS');
		$messages = JText::_('MESSAGES');
		$profile   = JText::_('PROFILE');
		$groups   = JText::_('GROUPS');
		$money   = JText::_('JOPENSIM_MONEY');
		$topbar = "<div><div class='contentsubheading_table".$pageclass_sfx."'>";
		$topbar .= "<div class='contentsubheading".$pageclass_sfx."'>";
		$topbar .= ($task == "default")  ? $settings:"<a href='".JRoute::_('index.php?option=com_opensim&view=inworld&task=default&Itemid='.$itemid)."' class='".$pageclass_sfx."'>".$settings."</a>";
		$topbar .= "</div>";
		if($ossettings['addons'] & 1){
			$topbar .= "<div class='contentsubheading".$pageclass_sfx."'>";
			$topbar .= ($task == "messages") ? $messages:"<a href='".JRoute::_('index.php?option=com_opensim&view=inworld&task=messages&Itemid='.$itemid)."' class='".$pageclass_sfx."'>".$messages."</a>";
			$topbar .= "</div>";
		}
		if($ossettings['addons'] & 2){
			$topbar .= "<div class='contentsubheading".$pageclass_sfx."'>";
			$topbar .= ($task == "profile")   ? $profile:"<a href='index.php?option=com_opensim&view=inworld&task=profile&Itemid=".$itemid."' class='".$pageclass_sfx."'>".$profile."</a>";
			$topbar .= "</div>";
		}
		if($ossettings['addons'] & 4){
			$topbar .= "<div class='contentsubheading".$pageclass_sfx."'>";
			$topbar .= ($task == "groups")   ? $groups:"<a href='index.php?option=com_opensim&view=inworld&task=groups&Itemid=".$itemid."' class='".$pageclass_sfx."'>".$groups."</a>";
			$topbar .= "</div>";
		}
		if($ossettings['addons'] & 32){
			$topbar .= "<div class='contentsubheading".$pageclass_sfx."'>";
			$topbar .= ($task == "money")   ? $money:"<a href='index.php?option=com_opensim&view=inworld&task=money&Itemid=".$itemid."' class='".$pageclass_sfx."'>".$money."</a>";
			$topbar .= "</div>";
		}
		/*$topbar .= "<td class='contentsubheading".$pageclass_sfx."'>".$ossettings['addons']."</td>";*/
		$topbar .= "</div></div>";
		return $topbar;
	}
}

