<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2013 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='opensimcp' />
<input type='hidden' name='task' value='' />
</form>
<h1><?php echo $this->message; ?></h1>
<table width='100%'>
<tr>
	<td valign='top' width='99%'>
	<div class="adminform">
	<div id="cpanel">
<?php
if(is_array($this->adminbuttons) && count($this->adminbuttons) > 0) {
	foreach($this->adminbuttons AS $button) echo $button;
}
?>
	</div>
	</div>
	</td>
	<td valign='top'>
	<div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px;width:500px;min-height:220px">
	<div style="float:right;margin:10px;">
	<?php echo JHTML::_('image.site','jOpenSim.png','/components/com_opensim/assets/images/',NULL,NULL,'jOpenSim',array('title'=>'jOpenSim')); ?>
	</div>

	<h3><?php echo JText::_('Version');?></h3>
	<p><?php echo $this->version ;?> (running on <?php
if(1 << 32 == 1) echo "32Bit";
elseif(1 << 64 == 1) echo "64Bit";
else echo "unknown";
	?> System)</p>
	<p><?php echo $this->recentversion; ?></p>
	<h3><?php echo JText::_('Copyright');?></h3>
	<p>&copy; 2010<?php echo (date("Y") > 2010) ? " - ".date("Y"):""; ?> FoTo50<br />
	<a href="http://www.jopensim.com/" target="_blank">http://www.jopensim.com</a></p>

	<h3><?php echo JText::_('License');?></h3>
	<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>
	</div>
	</td>
</tr>
</table>
