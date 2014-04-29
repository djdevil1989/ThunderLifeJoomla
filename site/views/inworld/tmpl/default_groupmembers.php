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
<?php if($this->ossettings['addons'] & 4): ?>
<h2><a href='index.php?option=com_opensim&view=inworld&task=groupdetail&groupid=<?php echo $this->grouplist['groupid']; ?>&tmpl=component'><?php echo $this->grouplist['groupname']." ".JText::_('GROUPMEMBERS'); ?></a></h2>
<?php if($this->grouplist['power']['power_rolemembersvisible'] == 1 || $this->grouplist['power']['isowner'] == 1): ?>
<table class='membertable<?php echo $this->pageclass_sfx; ?>'>
<tr class='membertable_head_row<?php echo $this->pageclass_sfx; ?>'>
	<td class='membertable_head_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('MEMBERNAME'); ?></td>
	<td class='membertable_head_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('MEMBERROLES'); ?></td>
	<td class='membertable_head_cell<?php echo $this->pageclass_sfx; ?>'>&nbsp;</td>
</tr>
<?php if (is_array($this->memberlist)): ?>
<?php foreach($this->memberlist AS $member): ?>
<tr class='membertable_row<?php echo $this->pageclass_sfx; ?>'>
	<td class='membertable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $member['name']; ?></td>
	<td class='membertable_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $member['roles']; ?></td>
	<td class='membertable_cell<?php echo $this->pageclass_sfx; ?>'><?php if($this->power['power_eject'] && !$member['isowner']): ?><a href='index.php?option=com_opensim&view=inworld&task=ejectgroup&groupid=<?php echo $this->grouplist['groupid']; ?>&ejectid=<?php echo $member['AgentID']; ?>' target='_parent' onClick='return confirm("<?php echo addslashes(JText::_('EJECTGROUPSURE')); ?>");'><img src='<?php echo $this->assetpath; ?>images/exit_small.png' width='20' height='20' border='0' alt='<?php echo JText::_('EJECTFROMGROUP'); ?>' title='<?php echo JText::_('EJECTFROMGROUP'); ?>' /></a><?php else: ?><img src='<?php echo $this->assetpath; ?>images/null.gif' width='20' height='20' border='0' alt='' title='' /><?php endif; ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr class='membertable_row<?php echo $this->pageclass_sfx; ?>'>
	<td class='membertable_cell<?php echo $this->pageclass_sfx; ?>' colspan='3'><?php echo JText::_('MEMBERLISTEMPTY'); ?></td>
</tr>
<?php endif; ?>
</table>
<?php endif; ?>
<?php endif; ?>