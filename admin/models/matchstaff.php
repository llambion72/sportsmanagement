<?php
/**
* 
 * SportsManagement ein Programm zur Verwaltung für Sportarten
 *
 * @version    1.0.05
 * @file       matchstaff.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @package    sportsmanagement
 * @subpackage models
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table; 
use Joomla\CMS\MVC\Model\AdminModel;
 

/**
 * sportsmanagementModelmatchstaff
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version   2013
 * @access    public
 */
class sportsmanagementModelmatchstaff extends AdminModel
{
    /**
     * Method override to check if you can edit an existing record.
     *
     * @param array  $data An array of input data.
     * @param string $key  The name of the key for the primary key.
     *
     * @return boolean
     * @since  1.6
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Check specific edit permission then general edit permission.
        return Factory::getUser()->authorise('core.edit', 'com_sportsmanagement.message.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
    }
    
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param  type    The table type to instantiate
     * @param  string    A prefix for the table class name. Optional.
     * @param  array    Configuration array for model. Optional.
     * @return JTable    A database object
     * @since  1.6
     */
    public function getTable($type = 'matchstaff', $prefix = 'sportsmanagementTable', $config = array()) 
    {
        $config['dbo'] = sportsmanagementHelper::getDBConnection(); 
        return Table::getInstance($type, $prefix, $config);
    }
    
    /**
     * Method to get the record form.
     *
     * @param  array   $data     Data for the form.
     * @param  boolean $loadData True if the form is to load its own data (default case), false if not.
     * @return mixed    A JForm object on success, false on failure
     * @since  1.6
     */
    public function getForm($data = array(), $loadData = true) 
    {
        // Get the form.
        $form = $this->loadForm('com_sportsmanagement.matchstaff', 'matchstaff', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }
    
    /**
     * Method to get the script that have to be included on the form
     *
     * @return string    Script files
     */
    public function getScript() 
    {
        return 'administrator/components/com_sportsmanagement/models/forms/sportsmanagement.js';
    }
    
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return mixed    The data for the form.
     * @since  1.6
     */
    protected function loadFormData() 
    {
        // Check the session for previously entered form data.
        $data = Factory::getApplication()->getUserState('com_sportsmanagement.edit.matchstaff.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
    
    /**
     * Method to save item order
     *
     * @access public
     * @return boolean    True on success
     * @since  1.5
     */
    function saveorder($pks = null, $order = null)
    {
        $row =& $this->getTable();
        
        // update ordering values
        for ($i=0; $i < count($pks); $i++)
        {
            $row->load((int) $pks[$i]);
            if ($row->ordering != $order[$i]) {
                $row->ordering=$order[$i];
                if (!$row->store()) {
                    sportsmanagementModeldatabasetool::writeErrorLog(get_class($this), __FUNCTION__, __FILE__, $this->_db->getErrorMsg(), __LINE__);
                    return false;
                }
            }
        }
        return true;
    }
    
}
