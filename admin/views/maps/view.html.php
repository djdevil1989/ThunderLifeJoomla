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


require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';
jimport('joomla.application.categories');

jimport( 'joomla.application.component.view');

class opensimcpViewmaps extends JView {

	public function display($tpl = null) {

		JHTML::stylesheet( 'opensim.css', 'administrator/components/com_opensim/assets/' );
		$queueMessage = array();
		$model = $this->getModel('maps');

		$state = $this->get('State');

		$this->sortDirection = $state->get('filter_order_Dir');
		if(!$this->sortDirection) {
			$this->sortDirection = "asc";
			$state->set('filter_order_Dir',"asc");
		}
		$this->sortColumn = $state->get('filter_order');
		if(!$this->sortColumn) {
			$this->sortColumn = "regions.regionName";
			$state->set('filter_order',"regions.regionName");
		}
		
		$test = $this->sortDirection;
		$this->assignRef('dirtest',$test);
		$this->assignRef('state',$state);

		$mapfolder = $model->checkCacheFolder();
		$this->assignRef('mapfolder',$mapfolder);

		if($mapfolder['existing'] == FALSE) {
			$foldercreated = $model->createImageFolder();
			if($foldercreated == FALSE) {
				$queueMessage['warning'][] = JText::_('JOPENSIM_MAPCACHE_UNWRITEABLE');
				$ismapcache = FALSE;
			} else {
				$ismapcache = TRUE;
			}
		} else {
			if($mapfolder['writeable'] == FALSE) {
				$queueMessage['warning'][] = JText::_('JOPENSIM_MAPCACHE_UNWRITEABLE');
				$ismapcache = FALSE;
			} else {
				$ismapcache = TRUE;
			}
		}
		$this->assignRef('ismapcache',$ismapcache);

		$settingsdata = $model->getSettingsData();
		if(!$model->_osgrid_db) {
			$queueMessage['error'][] = JText::sprintf('ERROR_NOSIMDB',JText::_('OPENSIMGRIDDB'));
		}

		$assetinfo	= pathinfo(JPATH_COMPONENT_ADMINISTRATOR);
		$assetpath	= "components/".$assetinfo['basename']."/assets/";
		$asseturl	= "components/".$assetinfo['basename']."/assets/regionimage.php?uuid=";
		$this->assignRef('assetpath',$assetpath);
		$cachepath	= JPATH_SITE.DS.'images'.DS.'jopensim'.DS.'regions'.DS;
		$cacheurl	= JURI::root()."images/jopensim/regions/";

		/*$settingsdata = $settings['settings'];*/
		$this->assignRef('mapserver',$settingsdata['oshost']);
		$this->assignRef('mapport',$settingsdata['osport']);
		$this->assignRef('defaultregion',$settingsdata['defaulthome']);
		$this->assignRef('settingsdata',$settingsdata);

		$task = JRequest::getVar( 'task', '', 'method', 'string');
		$this->assignRef('task',$task);

		if(count($queueMessage) > 0) {
			foreach($queueMessage AS $type => $messages) {
				if(is_array($messages) && count($messages) > 0) {
					foreach($messages AS $message) {
						JFactory::getApplication()->enqueueMessage($message,$type);
					}
				}
			}
		}

		$regionimage	= "<img src='%1\$s%2\$s' width='%4\$d' height='%4\$d' alt='%3\$s' title='%3\$s' />";
		$regionlink		= "<a href='index.php?option=com_opensim&view=maps&task=selectdefault&region=%1\$s'>%2\$s</a>";

		switch($task) {
			case "selectdefault":
				$ueberschrift = JText::_('MAPSDEFAULT');
				$data = JRequest::get('request');
				if($settingsdata['defaulthome'] == $data['region']) {
					$locX = sprintf("%d",$settingsdata['mapstartX']);
					$locY = sprintf("%d",$settingsdata['mapstartY']);
					$locZ = sprintf("%d",$settingsdata['mapstartZ']);
					$this->assignRef('locX',$locX);
					$this->assignRef('locY',$locY);
					$this->assignRef('locZ',$locZ);
					$imgAddLink = "&defaultX=".$locX."&defaultY=".$locY;
				} else {
					$imgAddLink = "";
				}
				$this->assignRef('imgAddLink',$imgAddLink);
				$region = $data['region'];
				$this->assignRef('region',$region);
				$regiondata = $model->getRegionDetails($region);
				$this->assignRef('regiondata',$regiondata);
				$tpl = "selectregion";
			break;
			case "mapview":
				$ueberschrift = JText::_('MAPS');
				$maptable = "";
				$maprange = $model->getLocationRange();
				if($maprange['maxY'] && $maprange['minY'] && $maprange['maxX'] && $maprange['minX']) {
					for($y=$maprange['maxY']; $y >= $maprange['minY']; $y -= 256) {
						$maptable .= "<tr>\n";
						for($x=$maprange['minX']; $x <= $maprange['maxX']; $x += 256) {
							$maptable .= "<td>";
							$region = $model->getRegionAtLocation($x,$y);
							if(is_array($region)) {
								$model->mapCacheRefresh($region['uuid']);
								if($ismapcache == TRUE && is_file($cachepath.$region['uuid'].".jpg")) {
									$mapimage = sprintf($regionimage,$cacheurl.$region['uuid'].".jpg","",$region['regionName'],128);
								} else {
									$params = sprintf("%1\$s&mapserver=%2\$s&mapport=%3\$s&scale=128",$region['maplink'],$region['serverIP'],$region['serverHttpPort']);
									$mapimage = sprintf($regionimage,$asseturl,$params,$region['regionName'],128);
								}
								$maptable .= sprintf($regionlink,$region['uuid'],$mapimage);
							}
							else $maptable .= "&nbsp;";
							$maptable .= "</td>\n";
						}
						$maptable .= "</tr>\n";
					}
					$maptable = "<div class='mapcontainer'><center><table rules='all' class='maptable' cellspacing='0' cellpadding='0'>".$maptable."</table></center></div>\n";
					$maptable = "<h3>".JText::_('SELECT_MAP')."</h3><br />\n".$maptable;
				} else {
					$maptable = JText::_('ERROR_NOREGION')."<br />\n".JText::_('ERRORQUESTION1')."<br />\n".JText::_('ERRORQUESTION2');
				}

				$this->assignRef('maptable',$maptable);
				$tpl = "mapview";
			break;
			case "edit":
				$articles = $model->getArticles();
				$ueberschrift = JText::_('MAPEDIT');
				$data = JRequest::get('request');
				$mapinfo = $model->getMapInfo($data['selectedRegion']);
				$mapdetails = $model->getRegionDetails($data['selectedRegion']);
				$contentTitle = $model->getContentTitleFromId($mapinfo['articleId']);
				$this->assignRef('data',$data);
				$this->assignRef('contentTitle',$contentTitle);
				$this->assignRef('mapinfo',$mapinfo);
				$this->assignRef('articles',$articles);
				if($ismapcache == TRUE && is_file($cachepath.$mapdetails['uuid'].".jpg")) {
					$mapdetails['image'] = sprintf($regionimage,$cacheurl.$mapdetails['uuid'].".jpg","",$mapdetails['regionName'],256);
				} else {
					$params = sprintf("%1\$s&mapserver=%2\$s&mapport=%3\$s&scale=128",$mapdetails['maplink'],$mapdetails['serverIP'],$mapdetails['serverHttpPort']);
					$mapdetails['image'] = sprintf($regionimage,$asseturl,$params,$mapdetails['regionName'],256);
				}
				$this->assignRef('mapdetails',$mapdetails);

				//Get button
//				$linkg = 'index.php?option=com_content&task=element&tmpl=component&object=id';
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

				$tpl = "mapedit";
			break;
			case "mapconfig":
				$tpl = "mapconfig";
			break;
			default:
				$ueberschrift = JText::_('MAPS');
				$filter = JRequest::getVar('search');
				$regions = $model->getMapData($filter,$this->sortColumn,$this->sortDirection);

				$mapquery = $model->mapquery;
				$this->assignRef('mapquery',$mapquery);

				if(is_array($regions) && count($regions) > 0) {
					foreach($regions['regions'] AS $key => $region) {
						$regiondata = $model->getRegionAtLocation($region['locX'],$region['locY']);
	
	
						if($ismapcache == TRUE && is_file($cachepath.$region['uuid'].".jpg")) {
							$mapimage = sprintf($regionimage,$cacheurl.$region['uuid'].".jpg","",$region['regionName'],48);
						} else {
							$params = sprintf("%1\$s&mapserver=%2\$s&mapport=%3\$s&scale=128",$region['maplink'],$region['serverIP'],$region['serverHttpPort']);
							$mapimage = sprintf($regionimage,$asseturl,$params,$region['regionName'],48);
						}
						$regions['regions'][$key]['image'] = sprintf($regionlink,$region['uuid'],$mapimage);
					}
				} else {
					$regions = array();
					JFactory::getApplication()->enqueueMessage(JText::_('JOPENSIM_NOREGIONS'),'warning');
				}
				$this->assignRef('regions',$regions['regions']);
				$this->assignRef('settings',$regions['settings']);

				$filter = JRequest::getVar('search');
		 		$this->assignRef( 'filter', $filter );
		 		$pagination =& $this->get('Pagination');
				$this->assignRef('pagination', $pagination);
			break;
		}

		$this->assignRef( 'ueberschrift', $ueberschrift );
		parent::display($tpl);
		$this->_setToolbar($tpl);
	}

	function _setToolbar($tpl) {
		JToolBarHelper::title( JText::_('OPENSIM_MAPS'), 'maps' );

		$task = JRequest::getVar( 'task', '', 'method', 'string');
		switch($task) {
			case "selectdefault":
				JToolBarHelper::cancel('cancel','JCANCEL');
			break;
			case "mapview":
				JToolBarHelper::cancel('maps','JCANCEL');
			break;
			case "edit":
				JToolBarHelper::save('save_regionsettings');
				JToolBarHelper::apply('apply_regionsettings');	
				JToolBarHelper::cancel('cancel','JCANCEL');
			break;
			case "mapconfig":
				JToolBarHelper::save('save_mapconfig');
				JToolBarHelper::apply('apply_mapconfig');	
				JToolBarHelper::cancel('cancel','JCANCEL');
			break;
			default:
				JToolBarHelper::makeDefault('setDefaultRegion');
				JToolBarHelper::editList();
				if($this->ismapcache) {
					JToolBarHelper::custom("maprefresh","maprefresh","",JText::_('JOPENSIM_REFRESHMAP'),true,false);
				}
				JToolBarHelper::custom("mapconfig","mapconfig","",JText::_('MAPCONFIG'),false,false);
			break;
		}
		/*JToolBarHelper::save();*/
		/*JToolBarHelper::apply();	*/
		/*JToolBarHelper::cancel('cancel',_CLOSE);*/
	}
}

?>