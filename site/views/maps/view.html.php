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

class opensimViewmaps extends JView {

	function display($tpl = null) {
		JHTML::stylesheet( 'opensim.css', 'components/com_opensim/assets/' );
		$model = $this->getModel('maps');
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

		$ueberschrift = "Maps";
		$this->assignRef('ueberschrift',$ueberschrift);
		$this->assignRef('mapimagepath',$mapimagepath);

		$mapinfo = pathinfo(JPATH_COMPONENT);
		$asseturl = "components/".$mapinfo['basename']."/assets/";
		$this->assignRef('asseturl',$asseturl);
		$cachepath	= JPATH_SITE.DS.'images'.DS.'jopensim'.DS.'regions'.DS;
		$cacheurl	= JURI::root()."images/jopensim/regions/";

		/*$settingsdata =& $this->get('Data');*/
		$settingsdata = $model->getSettingsData();
		$this->assignRef('settingsdata',$settingsdata);
		$this->assignRef('mapserver',$settingsdata['oshost']);
		$this->assignRef('mapport',$settingsdata['osport']);

		$regionimage	= "<img id='mapimage_%4\$s' class='mapimage' name='mapimage_%4\$s' src='%1\$s%2\$s' alt='%3\$s' title='%3\$s' onMouseOver='jopensimToggleRegionTT(\"%4\$s\",\"visible\");' onMouseOut='jopensimToggleRegionTT(\"%4\$s\",\"hidden\");' />";
		$regiontooltip	= "<div class='jopensim_regiontt_outer' onMouseOver='jopensimToggleRegionTT(\"%2\$s\",\"visible\");' onMouseOut='jopensimToggleRegionTT(\"%2\$s\",\"hidden\");'><div id='jopensim_regiontt_%2\$s' class='jopensim_regiontt_inner'><span>%1\$s%3\$s<br /><a href='secondlife://%1\$s'>Teleport</a></span></div></div>";
//		$regionimage = "<img id='mapimage_%4\$s' class='mapimage' name='mapimage_%4\$s' src='%1\$s%2\$s' alt='%3\$s' title='%3\$s' />";

		$task = JRequest::getVar( 'task', '', 'method', 'string');
		switch($task) {
			default:
			case "mapview":
				JHTML::stylesheet('jquery.ui.all.css','components/com_opensim/assets/css/');
				JHTML::script('jquery-1.4.4.js','components/com_opensim/assets/jQuery/');
				JHTML::script('jquery.ui.core.js','components/com_opensim/assets/jQuery/ui/');
				JHTML::script('jquery.ui.widget.js','components/com_opensim/assets/jQuery/ui/');
				JHTML::script('jquery.ui.mouse.js','components/com_opensim/assets/jQuery/ui/');
				JHTML::script('jquery.ui.draggable.js','components/com_opensim/assets/jQuery/ui/');
				JHTML::script('mousewheel.js','components/com_opensim/assets/jQuery/plugin/');
				JHTML::script('mousewheel.js','components/com_opensim/assets/');
				$maptable = array();
				$defaulthome['row'] = 0;
				$defaulthome['col'] = 0;
				$maprange = $model->getLocationRange();
				if(is_array($maprange)) {
					$maptable[] = "<div id='mapcontainer' class='mapcontainer' style='width:".$settingsdata['mapcontainer_width']."px;height:".$settingsdata['mapcontainer_height']."px;'>";
					$maptable[] = "<center>";
					$maptable[] = "<div id='mapzoom' class='mapzoom' style='position:relative;top:0px;left:0px;width:".$allX."px;height:".$allY."px'>";
					$maptable[] = "<div class='maptable'>";

					$mapcounter = 0;
					$allY = 0;
					$scale = 256;
					$cellX = 0;
					$cellY = 0;
//					$regionimage = "<img id='mapimage_%6\$s' name='mapimage_%6\$s' src='/".$assetpath."regionimage.php?uuid=%1\$s&mapserver=%3\$s&mapport=%4\$s&scale=".$scale."&info=%5\$s' border='1' alt='%2\$s' title='%2\$s' />";
					for($y=$maprange['maxY']; $y >= $maprange['minY']; $y -= 256) {
						if($model->getRegionsInRow($y) == 0) continue;
						$allY += $scale;
						$cellY++;
						$cellX = 0;
						$allX = 0;
						$maptable[] = "<div class='maprow'>";
						for($x=$maprange['minX']; $x <= $maprange['maxX']; $x += 256) {
							if($model->getRegionsInColumn($x) == 0) continue;
							$allX += $scale;
							$cellX++;
							$region = $model->getRegionAtLocation($x,$y);
							if(is_array($region)) {
								$model->mapCacheRefresh($region['uuid']);
								if($settingsdata['defaulthome'] == $region['uuid']) {
									$defaulthome['image'] = "mapimage_".$mapcounter;
									$defaulthome['row'] = $cellY;
									$defaulthome['col'] = $cellX;
								}
								$maptable[] = "<div class='mapcell'>";
								if(intval($region['articleId']) > 0) {
									$info = "yes";
									$infoimage = "<a href='/index.php?option=com_content&view=article&id=".$region['articleId']."' class='regionimageinfolink'><img src='/".$asseturl."images/mapinfo.png' alt='' title='' border='0' width='10' height='10' style='position: absolute;' /></a>";
									$infoimage2 = "&nbsp;<a href='/index.php?option=com_content&view=article&id=".$region['articleId']."'><img src='/".$asseturl."images/mapinfo16.png' alt='' title='' border='0' width='16' height='16' align='absmiddle' /></a>";
								} else {
									$info = "no";
									$infoimage = "";
									$infoimage2 = "";
								}

								if($ismapcache == TRUE && is_file($cachepath.$region['uuid'].".jpg")) {
									$mapimage = sprintf($regionimage,$cacheurl.$region['uuid'].".jpg","",$region['regionName'],$mapcounter);
								} else {
									$params = sprintf("%1\$s&mapserver=%2\$s&mapport=%3\$s&scale=128",$region['maplink'],$region['serverIP'],$region['serverHttpPort']);
									$mapimage = sprintf($regionimage,$asseturl,$params,$region['regionName'],$mapcounter);
								}
								$maptooltip = sprintf($regiontooltip,$region['regionName'],$mapcounter,$infoimage2);
								$mapimage .= $maptooltip;

								$maptable[] = $mapimage;
								$maptable[] = "</div>";
							} else {
								$mapimage = sprintf("<img id='mapimage_%1\$d' class='mapimage' name='mapimage_%1\$d' src='%2\$s/%3\$simages/null.gif' />",$mapcounter,JURI::root(true),$asseturl);
								$maptable[] = "<div class='mapcell'>";
								$maptable[] = $mapimage;
								$maptable[] = "</div>";
							}
							$mapcounter++;
						}
						$maptable[] = "</div>";
					}
					$dragX = ($allX - $settingsdata['mapcontainer_width'])/2;
					$dragY = ($allY - $settingsdata['mapcontainer_height'])/2;
					$this->assignRef('cellX',$cellX);
					$this->assignRef('cellY',$cellY);
					$this->assignRef('dragX',$dragX);
					$this->assignRef('dragY',$dragY);
					$this->assignRef('defaulthome',$defaulthome);






					$maptable[] = "</div>";
					$maptable[] = "</div>";
					$maptable[] = "</center>";
					$maptable[] = "</div>";
//					$maptable = "<div id='mapcontainer' class='mapcontainer' style='width:".$settingsdata['mapcontainer_width']."px;height:".$settingsdata['mapcontainer_height']."px;'><center><div id='mapzoom' class='mapzoom' style='position:relative;top:0px;left:0px;width:".$allX."px;height:".$allY."px'><table rules='all' align='center' class='maptable' cellspacing='0' cellpadding='0'>".$maptable."</table></div></center></div>\n";

					$opensim = $model->opensim;
					$rangequery = $opensim->getRegionRangeQuery();
				} else {
					$maptable[] = JText::_('ERROR_MAP');
				}

				$this->assignRef('maptable',$maptable);
				$tpl = "mapview";
			break;
		}

		parent::display($tpl);
	}
}

?>