<?php
/**
 * SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage controllers
 * @file       teamplayers.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

/**
 * sportsmanagementControllerteamplayers
 *
 * @package
 * @author
 * @copyright diddi
 * @version   2014
 * @access    public
 */
class sportsmanagementControllerteamplayers extends JSMControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array An optional associative array of configuration settings.
	 *
	 * @see   JController
	 * @since 1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->app    = Factory::getApplication();
		$this->jinput = $this->app->input;
		$this->option = $this->jinput->getCmd('option');
		$this->registerTask('unpublish', 'set_season_team_state');
		$this->registerTask('publish', 'set_season_team_state');
		$this->registerTask('trash', 'set_season_team_state');
		$this->registerTask('archive', 'set_season_team_state');
	}


	/**
	 * sportsmanagementControllerteamplayers::set_season_team_state()
	 *
	 * @return void
	 */
	function set_season_team_state()
	{
		$post   = Factory::getApplication()->input->post->getArray(array());
		$ids    = $this->input->get('cid', array(), 'array');
		$tpids  = $this->input->get('tpid', array(), 'array');
		$values = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');

		$model = $this->getModel();
		$model->set_state($ids, $tpids, $value, $post['pid']);

		switch ($value)
		{
		case 0:
		$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_UNPUBLISHED';
		break;
		case 1:
		$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_PUBLISHED';
		break;
		case 2:
		$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_ARCHIVED';
		break;
		case -2:
		$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_TRASHED';
		break;
		}

		$this->setMessage(Text::plural($ntext, count($ids)));

		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list . '&persontype=' . $post['persontype'] . '&project_team_id=' . $post['project_team_id'] . '&team_id=' . $post['team_id'] . '&pid=' . $post['pid']. '&season_team_id=' . $post['season_team_id'], false));
	}

	/**
	 * Proxy for getModel.
	 *
	 * @since 1.6
	 */
	public function getModel($name = 'teamplayer', $prefix = 'sportsmanagementModel', $config = Array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to update checked teamplayers
	 *
	 * @access public
	 * @return boolean    True on success
	 */
	function saveshort()
	{
		$post  = Factory::getApplication()->input->post->getArray(array());
		$model = $this->getModel();
		$model->saveshort();
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list . '&persontype=' . $post['persontype'] . '&project_team_id=' . $post['project_team_id'] . '&team_id=' . $post['team_id'] . '&pid=' . $post['pid']. '&season_team_id=' . $post['season_team_id'], false));
	}

	/**
	 * sportsmanagementControllerteamplayers::assignplayerscountry()
	 *
	 * @return void
	 */
	function assignplayerscountry()
	{
		$post  = Factory::getApplication()->input->post->getArray(array());
		$model = $this->getModel();
		$model->assignplayerscountry(1, $post['project_team_id'], $post['team_id'], $post['pid'], $post['season_id']);
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list . '&persontype=' . $post['persontype'] . '&project_team_id=' . $post['project_team_id'] . '&team_id=' . $post['team_id'] . '&pid=' . $post['pid']. '&season_team_id=' . $post['season_team_id'], false));
	}


}
