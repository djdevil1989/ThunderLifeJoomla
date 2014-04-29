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

JHTML::_('behavior.tooltip');

?>
<form action="index.php" method="post" name="adminForm">
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="settings" />
<input type="hidden" name="task" value="savesettings" />
<div class='submenue'>
<table cellspacing='10' cellpadding='10' border='0' style='background:#cfcfcf'>
<tr>
	<td valign='top'>
	<table cellpadding='5' cellspacing='1' width='300'>
	<tbody style='background:#ffffff'>
	<tr>
		<td colspan='2'><b><span class='com_opensim_title'><?php echo $this->userperms; ?>:</span></b></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='userchange_firstname' name='userchange_firstname' value='1' <?php echo ($this->os_setting['userchange'] & 1) ? " checked='checked'":""; ?> /></td>
		<td><label for='userchange_firstname'><?php echo $this->userpermsfirstname; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='userchange_lastname' name='userchange_lastname' value='2' <?php echo ($this->os_setting['userchange'] & 2) ? " checked='checked'":""; ?> /></td>
		<td><label for='userchange_lastname'><?php echo $this->userpermslastname; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='userchange_email' name='userchange_email' value='4' <?php echo ($this->os_setting['userchange'] & 4) ? " checked='checked'":""; ?> /></td>
		<td><label for='userchange_email'><?php echo $this->userpermsemail; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='userchange_password' name='userchange_password' value='8' <?php echo ($this->os_setting['userchange'] & 8) ? " checked='checked'":""; ?> /></td>
		<td><label for='userchange_password'><?php echo $this->userpermspwd; ?></label></td>
	</tr>
	</tbody>
	</table>
	<br />
	<table cellpadding='5' cellspacing='1' width='300'>
	<tbody style='background:#ffffff'>
	<tr>
		<td colspan='2'><b><span class='com_opensim_title'><?php echo $this->addons; ?>:</span></b></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='addons_messages' name='addons_messages' value='1' <?php echo ($this->os_setting['addons'] & 1) ? " checked='checked'":""; ?> /></td>
		<td><label for='addons_messages'><?php echo $this->addons_messages; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='addons_profile' name='addons_profile' value='2' <?php echo ($this->os_setting['addons'] & 2) ? " checked='checked'":""; ?> /></td>
		<td><label for='addons_profile'><?php echo $this->addons_profile; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='addons_groups' name='addons_groups' value='4' <?php echo ($this->os_setting['addons'] & 4) ? " checked='checked'":""; ?> /></td>
		<td><label for='addons_groups'><?php echo $this->addons_groups; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='addons_search' name='addons_search' value='16' <?php echo ($this->os_setting['addons'] & 16) ? " checked='checked'":""; ?> /></td>
		<td><label for='addons_search'><?php echo $this->addons_search; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='addons_currency' name='addons_currency' value='32' <?php echo ($this->os_setting['addons'] & 32) ? " checked='checked'":""; ?> /></td>
		<td><label for='addons_currency'><?php echo $this->addons_currency; ?></label></td>
	</tr>
	<tr>
		<td><input type='checkbox' id='addons_inworldauth' name='addons_inworldauth' value='8' <?php echo ($this->os_setting['addons'] & 8) ? " checked='checked'":""; ?> /></td>
		<td><label for='addons_inworldauth'><?php echo $this->addons_inworldauth; ?></label> <input type='text' size='3' name='terminalchannel' id='terminalchannel' value='<?php echo $this->os_setting['terminalchannel']; ?>' class='channel_field' /> <?php echo $this->minutestring; ?></td>
	</tr>
	</tbody>
	</table>
	</td>
	<td width='30'>&nbsp;</td>
	<td valign='top'>
	<table cellpadding='5' cellspacing='1' width='300'>
	<tbody style='background:#ffffff'>
	<tr>
		<td colspan='2'><b><span class='com_opensim_title'><?php echo JText::_('LASTNAMES'); ?>:</span></b></td>
	</tr>
	<tr>
		<td colspan='2'><b><?php echo $this->lastnamelist; ?>:</b></td>
	</tr>
	<tr>
		<td><input type='radio' id='lastnametype_none' name='lastnametype' value='0' <?php echo ($this->os_setting['lastnametype'] == 0) ? " checked='checked'":""; ?> /></td>
		<td><label for='lastnametype_none' class='tooltip'><?php echo $this->lastnametypenone; ?></label></td>
	</tr>
	<tr>
		<td><input type='radio' id='lastnametype_allow' name='lastnametype' value='1' <?php echo ($this->os_setting['lastnametype'] == 1) ? " checked='checked'":""; ?> /></td>
		<td><label for='lastnametype_allow' class='tooltip'><?php echo $this->lastnametypeallow; ?></label></td>
	</tr>
	<tr>
		<td><input type='radio' id='lastnametype_deny' name='lastnametype' value='-1' <?php echo ($this->os_setting['lastnametype'] == -1) ? " checked='checked'":""; ?> /></td>
		<td><label for='lastnametype_deny' class='tooltip'><?php echo $this->lastnametypedeny; ?></label></td>
	</tr>
	<tr>
		<td valign='top'><label for='lastnamelist' class='tooltip'><?php echo $this->lastnames; ?>:</label></td>
		<td><textarea name='lastnamelist' id='lastnamelist' rows='5' cols='20'><?php echo $this->os_setting['lastnamelist']; ?></textarea></td>
	</tr>
	</table>
	<br />
	<table cellpadding='5' cellspacing='1' width='300'>
	<tbody style='background:#ffffff'>
	<tr>
		<td colspan='2'><b><span class='com_opensim_title'><?php echo JText::_('JOPENSIM_EVENTS'); ?>:</span></b></td>
	</tr>
	<tr>
		<td valign='top'><label for='eventtimedefault'><?php echo JText::_('JOPENSIM_EVENTTIME_DEFAULT'); ?>:</label></td>
		<td>
		<select name='eventtimedefault' id='eventtimedefault' >
<?php if(is_array($this->timezones) && count($this->timezones) > 0): ?>
<?php foreach($this->timezones AS $timezone): ?>
			<option value='<?php echo $timezone; ?>' <?php echo ($timezone == $this->os_setting['eventtimedefault']) ? " selected='selected'":""; ?>><?php echo $timezone; ?></option>
<?php endforeach; ?>
<?php else: ?>
			<option value='???'><?php echo JText::_('JOPENSIM_TIMEZONE_ERROR'); ?></option>
<?php endif; ?>
		</select>
		</td>
	</tr>
	<tr>
		<td valign='top'><label for='listmatureevents' class='tooltip'><?php echo $this->listmatureevents; ?>:</label></td>
		<td>
		<select name='listmatureevents' id='listmatureevents'>
			<option value='false'<?php if($this->os_setting['listmatureevents'] == "false") echo " selected='selected'"; ?>><?php echo JText::_('JNO'); ?></option>
			<option value='true'<?php if($this->os_setting['listmatureevents'] == "true") echo " selected='selected'"; ?>><?php echo JText::_('JYES'); ?></option>
		</select>
	</tr>
	</table>
	</td>
</tr>
</table>
</div>
</form>
