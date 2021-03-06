<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2013 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 *
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$db =& JFactory::getDBO();

$droptable = array();
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_config`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_group`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_groupactive`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_groupinvite`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_groupmembership`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_groupnotice`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_grouprole`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_grouprolemembership`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_inworldident`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_mapinfo`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_moneybalances`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_moneysettings`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_moneytransactions`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_offlinemessages`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_allparcels`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_classifieds`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_events`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_hostsregister`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_objects`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_options`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_parcels`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_parcelsales`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_popularplaces`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_search_regions`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_terminals`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_userclassifieds`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_userlevel`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_usernotes`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_userpicks`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_userprofile`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_userrelation`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_usersettings`;";
$droptable[] = "DROP TABLE IF EXISTS `#__opensim_usertemp`;";

$droptable[] = "DROP TABLE IF EXISTS `#__opensim_settings`;";

foreach($droptable AS $query) {
	$db->setQuery($query);
	$db->query();
}
?>