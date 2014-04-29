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
<script language="javascript" type="text/javascript">
function jOpenSimSelectArticle(buttonval) {
//	alert(buttonval);
	document.getElementById("welcomemessage").value = buttonval;
	document.adminForm.submit();
}
</script>
<form action="index.php" method="post" name="adminForm">
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="misc" />
<input type="hidden" name="task" value="savewelcomemessage" />
<input type="hidden" name="welcomemessage" id="welcomemessage" value="<?php echo $this->welcomemessageID; ?>" />
</form>
<?php if(is_array($this->misclinks)): ?>
<ul>
<?php foreach($this->misclinks AS $misclink): ?>
<li><?php echo $misclink; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<br /><br />
<h3>Welcome Content:</h3>
<table>
<tr>
	<td><?php echo JText::_('CURRENTARTICLE').": ".$this->contentTitle; ?></td>
	<td>
	<div class="<?php echo $this->selectArticle->name; ?>">
	<a class="<?php echo $this->selectArticle->modalname; ?>" title="<?php echo $this->selectArticle->text; ?>" href="<?php echo $this->selectArticle->link; ?>" rel="<?php echo $this->selectArticle->options; ?>"  ><?php echo $this->selectArticle->text; ?></a>
	</div>
	</td>
	<td>or</td>
	<td><a href='index.php?option=com_opensim&view=misc&task=removewelcome'><?php echo JText::_('DELETE_WELCOME'); ?></td>
</tr>
</table>
