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

class opensimcpViewloginscreen extends JView {
	function display($tpl = null) {
		$boxsize = 80; // max dimension of image in imageselector
		$previewsize = 300; // max dimension of background image in loginscreen form

		$document			= &JFactory::getDocument();
		JHTML::stylesheet( 'picker.css', "administrator".DS."components".DS."com_opensim".DS."assets".DS );
		$document->addScript(JURI::base(true).DS."components".DS."com_opensim".DS."assets".DS."opensim.js");


		$base = JPATH_ROOT.DS."images";
		$this->assignRef('base',$base);

		$task = JRequest::getVar( 'task', '', 'method', 'string');
		switch($task) {
			case "browseimage":
				$settingsdata =& $this->get('Data');
				$setting = $settingsdata;
				$this->assignRef('os_setting',$setting);

				$folder = JRequest::getVar( 'folder', '', 'method', 'string');
				if($folder == DS) $folder = "";
				$this->assignRef('folder',$folder);

				$relfolder = $folder;

				$currentfolder = $folder;
				$hdl = opendir($base.DS.$currentfolder);

				$foldercontent				= array();
				$foldercontent['folder']	= array();
				$foldercontent['files']		= array();

				if($folder && $folder != DS) {
					$uplink = DS;
				} else {
					$uplink = FALSE;
				}
				$this->assignRef('uplink',$uplink);

				while($res = readdir ($hdl)) {
					if ($res != "." && $res != "..") {
						if(is_dir($base.DS.$currentfolder.DS.$res)) {
							$foldercontent['folder'][] = $currentfolder.DS.$res;
						} else {
							$img = getimagesize($base.DS.$currentfolder.DS.$res);
							if(is_array($img) && $img[2] > 0 && $img[2] < 4) {
								if($img[0] > $boxsize || $img[1] > $boxsize) {
									$dim = $this->imageproportions($img[0],$img[1],$boxsize,$boxsize);
									$width = $dim['x'];
									$height = $dim['y'];
								} else {
									$width = $img[0];
									$height = $img[1];
								}
								if($img[0] > $previewsize || $img[1] > $previewsize) {
									$dim = $this->imageproportions($img[0],$img[1],$previewsize,$previewsize);
									$pw_width = $dim['x'];
									$pw_height = $dim['y'];
								} else {
									$pw_width = $img[0];
									$pw_height = $img[1];
								}
								$htmlfolder = str_replace("\\","/",$currentfolder);
								$foldercontent['files'][$res] = "<img src='".JURI::root(true)."/images".$htmlfolder."/".$res."' width='".$width."' height='".$height."' alt='".$res."' title='".$res."' border='0' style='cursor:pointer;' onClick='window.parent.setWelcomeImage(\"".JURI::root()."images".$htmlfolder."/".$res."\",".$pw_width.",".$pw_height.");' />";
							}
						}
					}
				}
				sort($foldercontent['folder']);
				ksort($foldercontent['files']);
				$this->assignRef('foldercontent',$foldercontent);
				

				$tpl = "imagebrowser";
			break;
			default:
				$ueberschrift = "Viewer Login Screen";
				$this->assignRef( 'ueberschrift', $ueberschrift );
				$setting = $this->get('Data');
//				$setting = $settingsdata[0];
				$this->assignRef('os_setting',$setting);
				$welcomeuri = JURI::root()."index.php?option=com_opensim";
				$this->assignRef('welcomeuri',$welcomeuri);
				if($setting['loginscreen_image']) {
					$img = getimagesize($setting['loginscreen_image']);

					if($img[0] > $previewsize || $img[1] > $previewsize) {
						$dim = $this->imageproportions($img[0],$img[1],$previewsize,$previewsize);
						$pw_width = $dim['x'];
						$pw_height = $dim['y'];
					} else {
						$pw_width = $img[0];
						$pw_height = $img[1];
					}
					
					$loginimage = "<img src='".$setting['loginscreen_image']."' width='".$pw_width."' height='".$pw_height."' alt='".$setting['loginscreen_image']."' title='".$setting['loginscreen_image']."' border='0' />\n";
					$buttontext = JText::_('CHANGE_IMAGE');
					$buttontext2 = " or <a href='index.php?option=com_opensim&view=loginscreen&task=removeimage'>".JText::_('REMOVE_IMAGE')."</a>";
				} else {
					$loginimage = JText::_('NONE');
					$buttontext = JText::_('SELECT_IMAGE');
					$buttontext2 = "";
				}
				$this->assignRef('loginimage',$loginimage);
				$this->assignRef('buttontext2',$buttontext2);

				$linkg = 'index.php?option=com_opensim&view=loginscreen&task=browseimage&tmpl=component&folder=';
				JHTML::_('behavior.modal', 'a.modal-button');
				$selectImage = new JObject();
				$selectImage->set('modal', true);
				$selectImage->set('link', $linkg);
				$selectImage->set('text', $buttontext);
				$selectImage->set('name', 'image');
				$selectImage->set('modalname', 'modal-button');
				$selectImage->set('id', 'imageSelector');
				$selectImage->set('options', "{handler: 'iframe', size: {x: 640, y: 360}}");
				// - - - - - - - - - - - - - - - - 
				$this->assignRef('selectImage', $selectImage);

				JRequest::setVar( 'hidemainmenu', 1 );
			break;
		}
		parent::display($tpl);
		$this->_setToolbar($tpl);
	}

	function imageproportions($src_x,$src_y,$dst_x,$dst_y) {
		$sourceprop = $src_y / $src_x; // check out the proportion
		$destprop = $dst_y/$dst_x;
		if ($sourceprop < $destprop) {
			$img['x'] = $dst_x;
			$img['y'] = $src_y/($src_x/$dst_x);
		} else {
			$img['x'] = $src_x/($src_y/$dst_y);
			$img['y'] = $dst_y;
		}
		return $img;
	}

	function _setToolbar($tpl) {
		JToolBarHelper::title( JText::_('OpenSim Login Screen'), 'oslogin' );

		JToolBarHelper::save('save_settings');
		JToolBarHelper::apply('apply_settings');
		JToolBarHelper::cancel('cancel_settings','JCANCEL');

	}

}

?>