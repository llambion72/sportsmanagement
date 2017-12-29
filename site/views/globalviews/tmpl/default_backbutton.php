<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      default_backbutton.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 */ 

defined( '_JEXEC' ) or die( 'Restricted access' );

if ( isset( $this->overallconfig['show_back_button'] ) )
{
	?>
	<br />
	<?php
	if ( $this->overallconfig['show_back_button'] == '1' )
	{
		$alignStr = 'left';
	}
	else
	{
		$alignStr = 'right';
	}
	if ( $this->overallconfig['show_back_button'] != '0' )
	{
	    	
	//echo $alignStr;
	?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:<?php echo $alignStr; ?>; ">
    
		<div class="back_button">
			<a href='javascript:history.go(-1)'>
				<?php
				echo JText::_( 'COM_SPORTSMANAGEMENT_BACKBUTTON_BACK' );
				?>
			</a>
		</div>
	</div>
	<?php
	}
}
?>