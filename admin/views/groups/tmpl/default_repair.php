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
<h1><?php echo JText::_('GROUPREPAIR'); ?>:</h1>
<form action="index.php" method="post" name="adminForm" target='_parent'>
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='groups' />
<input type='hidden' name='task' value='assignOwner' />
<input type='hidden' name='groupID' value='<?php echo $this->groupDetails['GroupID']; ?>' />
<input type='hidden' name='OwnerRoleID' value='<?php echo $this->groupDetails['OwnerRoleID']; ?>' />
<select name='memberID' id='memberID'>
<?php if(!is_array($this->groupMembers) || count($this->groupMembers) == 0): ?>
	<option value='0'><?php echo JText::_('GROUPNOMEMBERS'); ?></option>
<?php else: ?>
<?php foreach($this->groupMembers AS $member): ?>
	<option value='<?php echo $member['AgentID']; ?>'><?php echo $member['MemberName']; ?></option>
<?php endforeach; ?>
<?php endif; ?>
</select>
<input type='submit' value='<?php echo JText::_('GROUPNEWOWNER'); ?>' />
</form>