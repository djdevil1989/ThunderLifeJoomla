<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2012 FoTo50 http://www.foto50.com/opensim/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo $this->ueberschrift; ?></h1>
<h2><?php echo JText::_('EDITREGIONSETTINGSFOR'); ?>: <?php echo $this->mapdetails['regionName']; ?></h2>
<?php echo $this->mapdetails['image']; ?>
<script language="javascript" type="text/javascript">
function jOpenSimSelectArticle(buttonval,buttonText,unknownVal) {
//	alert(buttonval);
	document.getElementById("regionArticle").value = buttonval;
	document.adminForm.submit();
}
</script>
<form action="index.php" method="post" name="adminForm">
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="maps" />
<input type="hidden" name="task" value="save_regionsettings" />
<input type="hidden" name="regionUUID" value="<?php echo $this->mapinfo['regionUUID']; ?>" />
<input type="hidden" name="regionArticle" id="regionArticle" value="<?php echo $this->mapinfo['articleId']; ?>" />
<table>
<tr>
	<td><input type='checkbox' name='mapinvisible' id='mapinvisible' value='1'<?php echo ($this->mapinfo['hidemap'] == 1) ? " checked='checked'":""; ?> /></td>
	<td><label for='mapinvisible'><?php echo JText::_('HIDEMAP'); ?></label></td>
</tr>
<tr>
	<td><input type='checkbox' name='mappublic' id='mappublic' value='1'<?php echo ($this->mapinfo['public'] == 1) ? " checked='checked'":""; ?> /></td>
	<td><label for='mappublic'><?php echo JText::_('PUBLICMAP'); ?></label></td>
</tr>
</table>
<table>
<tr>
	<td><?php echo JText::_('CURRENTARTICLE').": ".$this->contentTitle; ?></td>
	<td>
	<div class="<?php echo $this->selectArticle->name; ?>">
	<a class="<?php echo $this->selectArticle->modalname; ?>" title="<?php echo $this->selectArticle->text; ?>" href="<?php echo $this->selectArticle->link; ?>" rel="<?php echo $this->selectArticle->options; ?>"  ><?php echo $this->selectArticle->text; ?></a>
	</div>
	</td>
<?php if($this->mapinfo['articleId']): ?>
	<td>or</td>
	<td><a href='index.php?option=com_opensim&view=maps&task=removemaparticle&regionUUID=<?php echo $this->mapinfo['regionUUID']; ?>'><?php echo JText::_('REMOVE_ARTICLE'); ?></td>
<?php endif; ?>
</tr>
</table>
</form>
<?php
// debugprint($this->mapdetails,"mapdetails");
?>