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
// No direct access

defined('_JEXEC') or die('Restricted access'); ?>
<?php
$columns = 6;
$rows = 0;
$counter = 0;
?>
<?php if((is_array($this->foldercontent['folder']) && count($this->foldercontent['folder']) > 0) || (is_array($this->foldercontent['files']) && count($this->foldercontent['files']) > 0) || $this->uplink): ?>
<table class='imagebrowsertable'>
<tr>
<?php
if($this->uplink) {
?>
	<td><a href='index.php?option=com_opensim&view=loginscreen&task=browseimage&tmpl=component&folder=<?php echo $this->uplink; ?>'><img src='components/com_opensim/assets/images/folderup_32.png'><br />../</a></td>
<?php
	$counter++;
	if($counter == $columns) {
		echo "</tr>\n";
		$counter = 0;
		$rows++;
	}
}
?>
<?php
foreach($this->foldercontent['folder'] AS $folder) {
	if($counter == 0) echo "<tr>\n";
?>
	<td><a href='index.php?option=com_opensim&view=loginscreen&task=browseimage&tmpl=component&folder=<?php echo $folder; ?>'><img src='components/com_opensim/assets/images/folder.png'><br /><?php echo $folder; ?></a></td>
<?php
	$counter++;
	if($counter == $columns) {
		echo "</tr>\n";
		$counter = 0;
		$rows++;
	}
}

foreach($this->foldercontent['files'] AS $name => $imgtag) {
	if($counter == 0) echo "<tr>\n";
?>
	<td><?php echo $imgtag; ?><br /><?php echo $name; ?></td>
<?php
	$counter++;
	if($counter == $columns) {
		echo "</tr>\n";
		$counter = 0;
		$rows++;
	}
}
if($counter > 0 && $counter < $columns && $rows > 0) {
	for($i = $counter; $i < $columns; $i++) echo "\t<td>&nbsp;</td>\n";
}
if($counter > 0) echo "</tr>\n";
?>
</table>
<?php endif; ?>
<?php
// debugprint($this->uplink,"uplink");
// debugprint($this->foldercontent['files'],"files");
// debugprint($this->foldercontent['folder'],"folder");
?>