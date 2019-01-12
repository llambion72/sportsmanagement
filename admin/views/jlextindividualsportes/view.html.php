<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage jlextindividualsportes
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * sportsmanagementViewjlextindividualsportes
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewjlextindividualsportes extends sportsmanagementView
{
	public function init ()
	{
		$app = Factory::getApplication();

		if ($this->getLayout() == 'default')
		{
			$this->_displayDefault($tpl);
			return;
		}

		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$app = Factory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        $model = $this->getModel();
		$uri = Factory::getURI();

		$this->state = $this->get('State'); 
        $this->sortDirection = $this->state->get('list.direction');
        $this->sortColumn = $this->state->get('list.ordering');
        
		$cid = $jinput->request->get('cid', null, ARRAY)

		
        $project_id	= $app->getUserState( "$option.pid", '0' );
		$match_id	= $input->getInt('id', 0);
        $rid		= $input->getInt('rid', 0);
		$projectteam1_id		= $input->getInt('team1', 0);
		$projectteam2_id		= J$input->getInt('team2', 0);
        
        $mdlProject = BaseDatabaseModel::getInstance("Project", "sportsmanagementModel");
	    $projectws = $mdlProject->getProject($project_id);
        $mdlRound = BaseDatabaseModel::getInstance("Round", "sportsmanagementModel");
		$roundws = $mdlRound->getRound($rid);
        
        //$app->enqueueMessage(__FILE__.' '.get_class($this).' '.__FUNCTION__.' projectws<br><pre>'.print_r($projectws, true).'</pre><br>','');
        
        $model->checkGames($projectws, $match_id, $rid, $projectteam1_id, $projectteam2_id);
        
        //$app->enqueueMessage(Text::_(get_class($this).' '.__FUNCTION__.' ' .  ' match_id<br><pre>'.print_r($match_id,true).'</pre>'),'');
        
        $matches = $this->get('Items');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
        
        
        
        $teams[] = HTMLHelper::_('select.option', '0', Text::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_TEAM_PLAYER'));
        if ($projectteams = $model->getPlayer($projectteam1_id, $project_id))
		{
			$teams = array_merge($teams, $projectteams);
		}
			$lists['homeplayer'] = $teams;
			unset($teams);
            
            
            
         $teams[] = HTMLHelper::_('select.option', '0', Text::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_TEAM_PLAYER'));
         if ($projectteams = $model->getPlayer($projectteam2_id, $project_id))
		{
			$teams = array_merge($teams, $projectteams);
		}

			$lists['awayplayer'] = $teams;
			unset($teams);    
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(Text::_(get_class($this).' '.__FUNCTION__.' ' .  ' lists<br><pre>'.print_r($lists,true).'</pre>'),'');
        }
        
        $this->matches	= $matches;
        $this->pagination	= $pagination;
        $this->request_url	= $uri->toString();
        
        $this->ProjectTeams	= $model->getProjectTeams($project_id);
        
        $this->match_id	= $match_id;
        $this->rid	= $rid;
        
        $this->projectteam1_id	= $projectteam1_id;
        $this->projectteam2_id	= $projectteam2_id;
        
        $this->projectws	= $projectws;
        $this->roundws	= $roundws;
        
        if ( $result = $model->getPlayer($projectteam1_id, $project_id) )
        {
        $this->getHomePlayer	= $model->getPlayer($projectteam1_id, $project_id));    
        }
        else
        {
            $tempplayer = new stdClass();
            $tempplayer->value = 0;
            $tempplayer->text = 'TempPlayer';
            $exportplayer[] = $tempplayer;
            $this->getHomePlayer	= $exportplayer;
        }
        
        if ( $result = $model->getPlayer($projectteam2_id, $project_id) )
        {
        $this->getAwayPlayer	= $model->getPlayer($projectteam2_id, $project_id);    
        }
        else
        {
            $tempplayer = new stdClass();
            $tempplayer->value = 0;
            $tempplayer->text = 'TempPlayer';
            $exportplayer[] = $tempplayer;
            $this->getAwayPlayer	= $exportplayer;
        }
        

        $this->lists	= $lists;



		parent::display($tpl);
	}

}
?>