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
<table cellspacing='0' cellpadding='0' width='100%'>
<tr>
	<td><h2><?php echo $this->grouplist['groupname']; ?></h2></td>
	<td align='right'><a href='index.php?option=com_opensim&view=inworld&task=leavegroup&groupid=<?php echo $this->grouplist['groupid']; ?>' target='_parent' onClick='return confirm("<?php echo addslashes(JText::_('LEAVEGROUPSURE')); ?>");'><img src='<?php echo $this->assetpath; ?>images/exit.png' width='30' height='30' border='0' alt='<?php echo JText::_('LEAVEGROUP'); ?>' title='<?php echo JText::_('LEAVEGROUP'); ?>' /></a></td>
</tr>
</table>
<p><?php echo $this->grouplist['charter']; ?></p>
<?php if($this->grouplist['acceptnotices'] == 1 && $this->grouplist['power']['power_receivenotice'] == 1 && $this->grouplist['hasnotices'] > 0): ?>
<p><a href='index.php?option=com_opensim&view=inworld&task=groupnotices&groupid=<?php echo $this->grouplist['groupid']; ?>&tmpl=component'><?php echo JText::_('VIEWNOTICES'); ?></a></p>
<?php endif; ?>
<?php if($this->grouplist['power']['power_rolemembersvisible'] == 1 || $this->grouplist['power']['isowner'] == 1): ?>
<p><a href='index.php?option=com_opensim&view=inworld&task=groupmembers&groupid=<?php echo $this->grouplist['groupid']; ?>&tmpl=component'><?php echo JText::_('VIEWMEMBERS'); ?></a></p>
<?php endif; ?>
<?php endif; ?>