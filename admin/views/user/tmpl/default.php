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
<span class='com_opensim_title'><?php echo $this->ueberschrift; ?></span><?php echo $this->zusatztext; ?><br />
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='user' />
<input type='hidden' name='task' value='updateuser' />
<input type='hidden' name='boxchecked' value='0' />
<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
<table>
<tr>
	<td width="100%">
	<?php echo JText::_('Filter'); ?>:
	<input type="text" name="search" id="search" value="<?php echo $this->filter;?>" class="text_area" onchange="document.adminForm.submit();" />
	<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
	<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
	</td>
</tr>
</table>
<table class="adminlist" cellspacing="1">
<thead>
<tr>
	<th width="5"><?php echo JText::_('Num'); ?></th>
	<th class='title' width='10'>&nbsp;</td>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('FIRST_NAME'), 'UserAccounts.FirstName', $this->sortDirection, $this->sortColumn); ?></th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('LAST_NAME'), 'UserAccounts.LastName', $this->sortDirection, $this->sortColumn); ?></th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('ONLINE'), 'online', $this->sortDirection, $this->sortColumn); ?></th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('EMAIL'), 'UserAccounts.Email', $this->sortDirection, $this->sortColumn); ?></th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('CREATIONDATE'), 'created', $this->sortDirection, $this->sortColumn); ?></th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('LASTLOGIN'), 'last_login', $this->sortDirection, $this->sortColumn); ?></th>
	<th class='title'><?php echo JHTML::_( 'grid.sort', JText::_('LASTLOGOUT'), 'last_logout', $this->sortDirection, $this->sortColumn); ?></th>
</tr>
</thead>
<?php if(isset($this->users) && is_array($this->users) && count($this->users) > 0): ?>
<tbody>
<?php
$i=0;
if(is_array($this->users)) {
	foreach($this->users AS $user) {
		$created		= JHTML::_('date',  $user['created'], JText::_('DATE_FORMAT_LC2') );
		$last_login		= ($user['last_login']) ? JHTML::_('date',  $user['last_login'],JText::_('DATE_FORMAT_LC2')):JText::_('NEVER');
		$last_logout	= ($user['last_logout']) ? JHTML::_('date',  $user['last_logout'],JText::_('DATE_FORMAT_LC2')):JText::_('NEVER');
?>
<tr class="row<?php echo $i % 2; ?>">
	<td><?php echo $this->pagination->getRowOffset($i); ?></td>
	<td width='10'><input type='checkbox' name='checkUser[]' id='checkUser_<?php echo $this->pagination->getRowOffset($i); ?>' value='<?php echo $user['userid']; ?>' onClick='isChecked(this.checked);' /></td>
	<td><label for='checkUser_<?php echo $this->pagination->getRowOffset($i); ?>'><span title='<?php echo $user['userid']; ?>'><?php echo $user['firstname']; ?></span></label></td>
	<td><label for='checkUser_<?php echo $this->pagination->getRowOffset($i); ?>'><span title='<?php echo $user['userid']; ?>'><?php echo $user['lastname']; ?></span></label></td>
	<td align='center' class='jgrid'><?php echo ($user['online'] == 1) ? "<a href='index.php?option=com_opensim&view=user&task=setUserOffline&userid=".$user['userid']."'>":""; ?><span class='state <?php echo ($user['online'] == 1) ? "publish":"unpublish"; ?>'><span class='text'><?php echo ($user['online'] == 1) ? JText::_('USER_ONLINE')." - ".JText::_('SETUSEROFFLINE'):JText::_('USER_OFFLINE'); ?></span</span><?php echo ($user['online'] == 1) ? "</a>":""; ?></td>
	<td><?php echo $user['email']; ?></td>
	<td><?php echo $created; ?></td>
	<td><?php echo $last_login; ?></td>
	<td><?php echo $last_logout; ?></td>
</tr>
<?php
		$i++;
	}
}
?>
</tbody>
<?php endif; ?>
<tfoot>
<tr>
	<td colspan='9'><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
</tfoot>
</table>
</form>
