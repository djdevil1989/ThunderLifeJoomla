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
defined('_JEXEC') or die('Restricted access');
?>
<h1><?php echo $this->ueberschrift; ?></h1>
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='groups' />
<input type='hidden' name='task' value='' />
<input type='hidden' name='boxchecked' value='0' />
<?php echo JText::_('GROUPLISTEXPLAIN'); ?><br /><br />
<table class="adminlist" cellspacing="1">
<colgroup>
	<col width='10'>
	<col width='10'>
	<col width='200'>
	<col width='200'>
	<col width='10'>
	<col width='10'>
	<col width='64'>
	<col width='*'>
</colgroup>
<thead>
<tr>
	<th width="5"><?php echo JText::_('Num'); ?></th>
	<th>&nbsp;</th>
	<th><?php echo JText::_('GROUPNAME'); ?></th>
	<th><?php echo JText::_('GROUPFOUNDER'); ?></th>
	<th><?php echo JText::_('GROUPOWNERS'); ?></th>
	<th><?php echo JText::_('GROUPMEMBERS'); ?></th>
	<th><?php echo JText::_('MATURE'); ?></th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php if(count($this->groupList) > 0): ?>
<?php foreach($this->groupList AS $key => $group): ?>
<tr>
	<td><?php echo $key+1; ?></td>
	<td width='10'><input type='checkbox' name='checkGroup[]' id='group_<?php echo $group['GroupID']; ?>' value='<?php echo $group['GroupID']; ?>' onClick='isChecked(this.checked);' /></td>
	<td><nobr><a class='modal' id='groupdetailwindow' href='index.php?option=com_opensim&view=groups&task=charta&groupID=<?php echo $group['GroupID']; ?>&tmpl=component' rel="{handler: 'iframe', size: {x: 400, y: 400}, overlayOpacity: 0.3}"><?php echo $group['Name']; ?></a></nobr></td>
	<td><nobr><?php echo $group['FounderName']; ?></nobr></td>
	<td align='right'><?php echo $group['owners']; ?></td>
	<td align='right'><?php echo $group['members']; ?></td>
	<td align='center'><?php echo ($group['MaturePublish'] == 1) ? JText::_('YES'):JText::_('NO'); ?></td>
	<td><?php echo ($group['owners'] == 0 && $group['members'] > 0) ? "<a class='modal' id='groupdetailwindow' href='index.php?option=com_opensim&view=groups&task=grouprepair&groupID=".$group['GroupID']."&tmpl=component' rel=\"{handler: 'iframe', size: {x: 400, y: 400}, overlayOpacity: 0.3}\"><img src='".$this->assetpath."images/repair_icon.png' width='16' height='16' alt='".JText::_('GROUPREPAIR')."' title='".JText::_('GROUPREPAIR')."' border='0' /></a>":"&nbsp;"; ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
	<td colspan='5'><?php echo JText::_('NOGROUPSFOUND'); ?></th>
</tr>
<?php endif; ?>
</tbody>
</table>
</form>
