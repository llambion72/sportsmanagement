<?php 
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      default_data.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage transifex
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.modal');
?>
<div id="j-main-container">
<table class="table table-striped" id="contentList">
<thead>
<tr>
						
<th class="title nowrap">
<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
</th>
<th class="title nowrap hidden-phone hidden-tablet">
<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_TITLE_NATIVE', 'a.title_native', $listDirn, $listOrder); ?>
</th>
<th width="1%" class="nowrap">
<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_LANG_TAG', 'a.lang_code', $listDirn, $listOrder); ?>
</th>
<th width="8%" class="nowrap hidden-phone">
<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_LANG_IMAGE', 'a.image', $listDirn, $listOrder); ?>
</th>



</tr>
</thead>
	
<?php
foreach ($this->language as $i => $item) :
?>	
<tr class="row<?php echo $i % 2; ?>">	
<td class="hidden-phone hidden-tablet">
<?php echo $item->file; ?>
</td>	
<td class="hidden-phone hidden-tablet">
<?php echo $item->languagetag; ?>
</td>	
<td class="hidden-phone hidden-tablet">
<?php echo $item->completed; ?>
</td>	
	
	
	
	
	
</tr>	
<?php endforeach; ?>	
</table>	
</div>
	
