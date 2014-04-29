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
<script type="text/javascript">
function setWelcomeImage(imgName,imgWidth,imgHeight) {
	document.getElementById("loginscreen_image").value = imgName;
	var welcomeImgTag = "<img src='"+imgName+"' width='"+imgWidth+"' height='"+imgHeight+"' alt='"+imgName+"' title='"+imgName+"' />";
	document.getElementById("welcomeImageName").innerHTML = welcomeImgTag;
	SqueezeBox.close();
//	document.getElementById("sbox-btn-close").click();
}
</script>
<form action="index.php" method="post" name="adminForm">
<input type="hidden" name="option" value="com_opensim" />
<input type="hidden" name="view" value="loginscreen" />
<input type="hidden" name="task" value="" />
<input type="hidden" id="loginscreen_image" name="loginscreen_image" value="<?php echo $this->os_setting['loginscreen_image']; ?>" />
<table>
<tr>
	<td colspan='2'>
	<b><span class='com_opensim_title'><?php echo $this->ueberschrift; ?></span></b><br />
	URL for OpenSim.ini (Robust.ini): <a href='<?php echo $this->welcomeuri; ?>' target='_blank' title='<?php echo JText::_('PREVIEW'); ?>'><?php echo $this->welcomeuri; ?></a>
	</td>
</tr>
<tr>
	<td valign='top'>
	<table>
	<tr>
		<td colspan='2'><b>Background:</b></td>
	</tr>
	<tr>
		<td><div id='welcomeImageName'><?php echo $this->loginimage; ?></div></td>
		<td>
		<div class="<?php echo $this->selectImage->name; ?>">
		<a class="<?php echo $this->selectImage->modalname; ?>" title="<?php echo $this->selectImage->text; ?>" href="<?php echo $this->selectImage->link; ?>" rel="<?php echo $this->selectImage->options; ?>"  ><?php echo $this->selectImage->text; ?></a><?php echo $this->buttontext2; ?>
		</div>
		</td>
	</tr>
	<tr>
		<td colspan='2'><b>Loginscreen Boxes:</b></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_boxes_1' name='loginscreen_boxes_array[]' value='1' <?php echo ($this->os_setting['loginscreen_boxes'] & 1) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_boxes_1'>Gridstatusbox</label></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_boxes_2' name='loginscreen_boxes_array[]' value='2' <?php echo ($this->os_setting['loginscreen_boxes'] & 2) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_boxes_2'>Messagebox</label></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_boxes_4' name='loginscreen_boxes_array[]' value='4' <?php echo ($this->os_setting['loginscreen_boxes'] & 4) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_boxes_4'>Regionbox</label></td>
	</tr>
	<tr>
		<td colspan='2'><b>Gridstatus Box:</b></td>
	</tr>
	<tr>
		<td>Gridstatus:</td>
		<td>
		<select name='loginscreen_gridstatus'>
			<option value='-1'<?php echo ($this->os_setting['loginscreen_gridstatus'] == -1) ? " selected='selected'":""; ?>>Offline</option>
			<option value='0'<?php echo ($this->os_setting['loginscreen_gridstatus'] == 0) ? " selected='selected'":""; ?>>Auto</option>
			<option value='1'<?php echo ($this->os_setting['loginscreen_gridstatus'] == 1) ? " selected='selected'":""; ?>>Online</option>
		</select>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_gridbox_1' name='loginscreen_gridbox_array[]' value='1' <?php echo ($this->os_setting['loginscreen_gridbox'] & 1) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_gridbox_1'>Show Grid Status</label></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_gridbox_2' name='loginscreen_gridbox_array[]' value='2' <?php echo ($this->os_setting['loginscreen_gridbox'] & 2) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_gridbox_2'>Total Regions</label></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_hiddenregions' name='hiddenregions' value='1' <?php echo ($this->os_setting['hiddenregions'] == 1) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_gridbox_2'>Count hidden regions</label></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_gridbox_4' name='loginscreen_gridbox_array[]' value='4' <?php echo ($this->os_setting['loginscreen_gridbox'] & 4) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_gridbox_4'>Visitors last <input type='text' name='loginscreen_xdays' style='width:3em;text-align:right;' value='<?php echo $this->os_setting['loginscreen_xdays']; ?>' /> Days</label></td>
	</tr>
	<tr>
		<td align='right'><input type='checkbox' id='com_opensim_loginscreen_gridbox_8' name='loginscreen_gridbox_array[]' value='8' <?php echo ($this->os_setting['loginscreen_gridbox'] & 8) ? " checked='checked'":""; ?> /></td>
		<td><label for='com_opensim_loginscreen_gridbox_8'>Online Now</label></td>
	</tr>
	<tr>
		<td colspan='2'><b>Message Box:</b></td>
	</tr>
	<tr>
		<td>Title:</td>
		<td><input type='text' name='loginscreen_msgbox_title' size='30' value="<?php echo str_replace("\"","&quot;",$this->os_setting['loginscreen_msgbox_title']); ?>" /></td>
	</tr>
	<tr>
		<td valign='top'>Message:</td>
		<td><textarea name='loginscreen_msgbox_message' cols='30' rows='5'><?php echo $this->os_setting['loginscreen_msgbox_message']; ?></textarea></td>
	</tr>
	</table>
	</td>
	<td valign='top'>
	<table>
	<tr>
		<td colspan='2'><b>Colors:</b></td>
	</tr>
	<tr>
		<td>Loginscreen Background Color:</td>
		<td>#<input type='text' id='loginscreen_color' name='loginscreen_color' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_color']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_color')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	<tr>
		<td>Box Background Color:</td>
		<td>#<input type='text' id='loginscreen_msgbox_color' name='loginscreen_msgbox_color' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_msgbox_color']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_msgbox_color')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	<tr>
		<td>Text Color:</td>
		<td>#<input type='text' id='loginscreen_text_color' name='loginscreen_text_color' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_text_color']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_text_color')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	<tr>
		<td>Online Color:</td>
		<td>#<input type='text' id='loginscreen_online_color' name='loginscreen_online_color' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_online_color']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_online_color')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	<tr>
		<td>Offline Color:</td>
		<td>#<input type='text' id='loginscreen_offline_color' name='loginscreen_offline_color' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_offline_color']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_offline_color')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	<tr>
		<td>Box Border:</td>
		<td><input type='text' name='loginscreen_msgbox_border' size='30' maxlength='255' value='<?php echo str_replace("\"","&quot;",$this->os_setting['loginscreen_msgbox_border']); ?>' /></td>
	</tr>
	<tr>
		<td>Message Title Background:</td>
		<td>#<input type='text' id='loginscreen_msgbox_title_background' name='loginscreen_msgbox_title_background' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_msgbox_title_background']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_msgbox_title_background')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	<tr>
		<td>Message Title Text:</td>
		<td>#<input type='text' id='loginscreen_msgbox_title_text' name='loginscreen_msgbox_title_text' size='6' maxlength='6' value='<?php echo $this->os_setting['loginscreen_msgbox_title_text']; ?>' /><img src='components/com_opensim/assets/images/colorpicker.png' width='14' height='14'  style="margin-left: 10px;border:#000000 1px solid" onclick="openPicker('loginscreen_msgbox_title_text')" alt='pick color' title='pick color' align='absmiddle' /></td>
	</tr>
	</table>
	</td>
</tr>
</table>
</form>
