<?php
/**
 *
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 *
 * @version    1.0.05
 * @file       edit_matchreport.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @package    sportsmanagement
 * @subpackage match
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
?>      
<fieldset class="adminform">
	<legend><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_F_MR'); ?>
	</legend>
	<table class="admintable">
	<?php

	foreach ($this->form->getFieldset('matchreport') as $field)
	:
	?>
<tr>
<td class="key"><?php echo $field->label; ?></td>
<td><?php echo $field->input; ?></td>
</tr>					
	<?php endforeach; ?>
	</table>
</fieldset>		
