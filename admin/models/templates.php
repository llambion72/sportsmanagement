<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');



/**
 * sportsmanagementModelTemplates
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelTemplates extends JModelList
{
	var $_identifier = "templates";
	var $_project_id = 0;

	/**
	 * sportsmanagementModelTemplates::__construct()
	 * 
	 * @param mixed $config
	 * @return void
	 */
	public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'tmpl.template',
                        'tmpl.title',
                        'tmpl.id',
                        'tmpl.ordering'
                        );
                parent::__construct($config);
                $getDBConnection = sportsmanagementHelper::getDBConnection();
                parent::setDbo($getDBConnection);
        }
        
    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        // Initialise variables.
		$app = JFactory::getApplication('administrator');
        
        //$app->enqueueMessage(JText::_('sportsmanagementModelsmquotes populateState context<br><pre>'.print_r($this->context,true).'</pre>'   ),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('tmpl.template', 'asc');
	}    
	
	/**
	 * sportsmanagementModelTemplates::getListQuery()
	 * 
	 * @return
	 */
	protected function getListQuery()
	{
		$app	= JFactory::getApplication();
		$option = JRequest::getCmd('option');
        $search	= $this->getState('filter.search');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        
        $this->_project_id	= $app->getUserState( "$option.pid", '0' );
        
        $query->select('tmpl.template,tmpl.title,tmpl.id,tmpl.checked_out,u.name AS editor,(0) AS isMaster');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config AS tmpl');
        $query->join('LEFT', '#__users AS u ON u.id = tmpl.checked_out');
        
        $query->where('tmpl.project_id = '.(int) $this->_project_id);
        
        $oldTemplates="frontpage'";
		$oldTemplates .= ",'do_tipsl','tipranking','tipresults','user'";
		$oldTemplates .= ",'tippentry','tippoverall','tippranking','tippresults','tipprules','tippusers'";
		$oldTemplates .= ",'predictionentry','predictionoverall','predictionranking','predictionresults','predictionrules','predictionusers";
        
        $query->where("tmpl.template NOT IN ('".$oldTemplates."')");
        
        if ($search)
		{
        $query->where(' ( LOWER(tmpl.title) LIKE '.$db->Quote('%'.$search.'%') .' OR '.' LOWER(tmpl.template) LIKE '.$db->Quote('%'.$search.'%').' )'  );
        }



$query->order($db->escape($this->getState('list.ordering', 'tmpl.template')).' '.
                $db->escape($this->getState('list.direction', 'ASC')));

if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        { 
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
}

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');

		return $query;
	}

	/**
	 * check that all templates in default location have a corresponding record,except if project has a master template
	 *
	 */
	function checklist($project_id)
	{
	   $app	= JFactory::getApplication();
		$option = JRequest::getCmd('option');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        $starttime = microtime(); 
        
//        $this->_project_id = JRequest::getVar('pid');
//        if ( !$this->_project_id )
//        {
//        $this->_project_id = $app->getUserState( "$option.pid", '0' );
//        }
//                 
//		$project_id = $this->_project_id;
		$defaultpath = JPATH_COMPONENT_SITE.DS.'settings';
        // Get the views for this component.
        $path = JPATH_SITE.'/components/'.$option.'/views';
		$predictionTemplatePrefix = 'prediction';

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($project_id,true).'</pre>'),'Notice');

		if (!$project_id)
        {
            return;
        }

		// get info from project
        $query->select('master_template,extension');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project');
        $query->where('id = '.(int)$project_id);
//		$query='SELECT master_template,extension FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project WHERE id='.(int)$project_id;

		$db->setQuery($query);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        { 
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
}

		$params = $db->loadObject();

		// if it's not a master template,do not create records.
		if ($params->master_template)
        {
            return true;
        }

		// otherwise,compare the records with the files
		// get records
        $query->clear();
        $query->select('template');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config');
        $query->where('project_id = '.(int)$project_id);
//		$query='SELECT template FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config WHERE project_id='.(int) $project_id;

		$db->setQuery($query);
		$records = $db->loadResultArray();
		if (empty($records))
         { 
            $records=array(); 
         }
		
		// add default folder
		$xmldirs[] = $defaultpath.DS.'default';
		
		$extensions = sportsmanagementHelper::getExtensions($this->_project_id);
		foreach ($extensions as $e => $extension) {
			$extensiontpath =  JPATH_COMPONENT_SITE . DS . 'extensions' . DS . $extension;
			if (is_dir($extensiontpath.DS.'settings'.DS.'default'))
			{
				$xmldirs[] = $extensiontpath.DS.'settings'.DS.'default';
			}
		}

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' xmldirs<br><pre>'.print_r($xmldirs,true).'</pre>'),'Notice');

// now check for all xml files in these folders
		foreach ($xmldirs as $xmldir)
		{
			/*
            $files = JFolder::files($xmldir, '.xml', false, false, array('predictionentry.xml','predictionflash.xml','predictionoverall.xml','predictionranking.xml','predictionresults.xml','predictionrules.xml','predictionusers.xml'), array());
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' files<br><pre>'.print_r($files,true).'</pre>'),'Notice');
            
            foreach ($files as $file)
		{
            $template = substr($file,0,(strlen($file)-4));
            
            
            
            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' template -> '.$template.''),'Notice');
         
                            $xmlfile = $xmldir.DS.$file;
							$arrStandardSettings = array();
							if(file_exists($xmlfile)) 
                            {
								$strXmlFile = $xmlfile;
                                $form = JForm::getInstance($template, $strXmlFile,array('control'=> ''));
                                $fieldsets = $form->getFieldsets();
								foreach ($fieldsets as $fieldset) 
                                {
									foreach($form->getFieldset($fieldset->name) as $field) 
                                    {
										$arrStandardSettings[$field->name] = $field->value;
									}
								}
                                
							}
							
                            $defaultvalues = json_encode( $arrStandardSettings);
         $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' defaultvalues<br><pre>'.print_r($defaultvalues,true).'</pre>'),'Notice');
         $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getName -> '.$form->getName().''),'Notice');
         $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' form<br><pre>'.print_r($form,true).'</pre>'),'Notice');
         }   
         */
         
            if ($handle=opendir($xmldir))
			{
				/* check that each xml template has a corresponding record in the
				database for this project. If not,create the rows with default values
				from the xml file */
				while ($file=readdir($handle))
				{
					if	(	$file!='.' &&
							$file!='..' &&
							$file!='do_tipsl' &&
							strtolower(substr($file,-3))=='xml' &&
							strtolower(substr($file,0,strlen($predictionTemplatePrefix))) != $predictionTemplatePrefix
						)
					{
						$template = substr($file,0,(strlen($file)-4));
                        
                        // Determine if a metadata file exists for the view.
				        //$metafile = $path.'/'.$template.'/metadata.xml';
                        $metafile = $path.'/'.$template.'/tmpl/default.xml';
                        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' metafile -> '.$metafile.''),'Notice');
                        
                        $attributetitle = '';
                        if (is_file($metafile)) 
                        {
                        $xml = JFactory::getXML($metafile,true);
                        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' xml<br><pre>'.print_r($xml->layout,true).'</pre>'),'Notice');
                        $attributetitle = (string)$xml->layout->attributes()->title;
                        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' xml<br><pre>'.print_r($attributetitle,true).'</pre>'),'Notice');
                            
                        /*    
                        // Attempt to load the xml file.
					   if ($metaxml = simplexml_load_file($metafile)) 
                        {
                        //$app->enqueueMessage(JText::_('PredictionGame template metaxml-> '.'<br /><pre>~' . print_r($metaxml,true) . '~</pre><br />'),'');    
                        // This will save the value of the attribute, and not the objet
                        //$attributetitle = (string)$metaxml->view->attributes()->title;
                        $attributetitle = (string)$metaxml->layout->attributes()->title;
                        $app->enqueueMessage(JText::_('PredictionGame template attribute-> '.'<br /><pre>~' . print_r($attributetitle,true) . '~</pre><br />'),'');
                        if ($menu = $metaxml->xpath('view[1]')) 
                        {
							$menu = $menu[0];
                            //$app->enqueueMessage(JText::_('PredictionGame template menu-> '.'<br /><pre>~' . print_r($menu,true) . '~</pre><br />'),'');
                            }
                        }
                        */
                        }

						if ((empty($records)) || (!in_array($template,$records)))
						{
							$xmlfile = $xmldir.DS.$file;
							$arrStandardSettings = array();
							if(file_exists($xmlfile)) {
								$strXmlFile = $xmlfile;
                                $form = JForm::getInstance($template, $strXmlFile,array('control'=> ''));
                                $fieldsets = $form->getFieldsets();
								foreach ($fieldsets as $fieldset) 
                                {
									foreach($form->getFieldset($fieldset->name) as $field) 
                                    {
										$arrStandardSettings[$field->name] = $field->value;
									}
								}
                                
							}
							
                            $defaultvalues = json_encode( $arrStandardSettings);
                            
							//$query="	INSERT INTO #__".COM_SPORTSMANAGEMENT_TABLE."_template_config (template,title,params,project_id)
//													VALUES ('$template','".$form->getName()."','$defaultvalues','$project_id')";
//							JFactory::getDbo()->setQuery($query);
                            
          $query->clear();
          // Select some fields
        $query->select('id');
		// From the table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config');
        $query->where('template LIKE '.$db->Quote(''.$template.''));
        $query->where('project_id = '.(int)$project_id);
			$db->setQuery($query);
            if ( !JFactory::getDbo()->loadResult() )
            {
                // Create and populate an object.
        $object_template = new stdClass();
        $object_template->template = $template;
        $object_template->title = $attributetitle;
        $object_template->params = $defaultvalues;
        $object_template->project_id = $project_id;
        // Insert the object into the user profile table.
        $result = JFactory::getDbo()->insertObject('#__sportsmanagement_template_config', $object_template);
        
                            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' template -> '.$template.''),'Notice');
                            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getName -> '.$form->getName().''),'Notice');
                            }
                            
                            //$my_text .= '<span style="color:'.$this->storeSuccessColor.'">';
//                            $my_text .= JText::sprintf('Created new template data for empty Projectdata: %1$s %2$s',"</span><strong>$template</strong>","<br><strong>".$defaultvalues."</strong>" );
//			                $my_text .= '<br />';
                            
							////echo error,allows to check if there is a mistake in the template file
//							if (!sportsmanagementModeldatabasetool::runJoomlaQuery())
//							{
//								$this->setError(JFactory::getDbo()->getErrorMsg());
//								return false;
//							}
							array_push($records,$template);
						}
					}
				}
                
                //$this->_success_text['Importing general template data:'] = $my_text;
                                
				closedir($handle);
			}
		}		
        






        
	}

	/**
	 * sportsmanagementModelTemplates::getMasterTemplatesList()
	 * 
	 * @return
	 */
	function getMasterTemplatesList()
	{
		$app	= JFactory::getApplication();
		$option = JRequest::getCmd('option');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        $starttime = microtime(); 
        
        // get current project settings
        $query->select('template');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config');
        $query->where('project_id = '.(int)$this->_project_id);
//		$query='SELECT template FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config WHERE project_id='.(int)$this->_project_id;
		$db->setQuery($query);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        { 
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
}

		$current = $db->loadResultArray();
        
        $query->clear();
        
        $starttime = microtime(); 

		if ($this->_getALL)
		{
//			$query='SELECT t.*,(1) AS isMaster ';
            $query->select('t.*,(1) AS isMaster');
		}
		else
		{
//			$query='SELECT t.id as value, t.title as text, t.template as template ';
            $query->select('t.id as value, t.title as text, t.template as template');
		}
//		$query .= '	FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config as t
//					INNER JOIN #__'.COM_SPORTSMANAGEMENT_TABLE.'_project as pm ON pm.id=t.project_id
//					INNER JOIN #__'.COM_SPORTSMANAGEMENT_TABLE.'_project as p ON p.master_template=pm.id ';
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_template_config as t');
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_project as pm ON pm.id = t.project_id');            
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_project as p ON p.master_template = pm.id');
//		$where = array();
//		$where[]=' p.id='.(int)$this->_project_id;
        $query->where('p.id = '.(int)$this->_project_id);

		$oldTemplates="frontpage'";
		$oldTemplates .= ",'do_tipsl','tipranking','tipresults','user'";
		$oldTemplates .= ",'tippentry','tippoverall','tippranking','tippresults','tipprules','tippusers'";
		$oldTemplates .= ",'predictionentry','predictionoverall','predictionranking','predictionresults','predictionrules','predictionusers";
//		$where[]=" t.template NOT IN ('".$oldTemplates."')";
        $query->where(" t.template NOT IN ('".$oldTemplates."')");

		if (count($current))
		{
//			$where[]=" t.template NOT IN ('".implode("','",$current)."')";
            $query->where(" t.template NOT IN ('".implode("','",$current)."')");
		}
		//$query .= " WHERE ".implode(' AND ',$where);
//		$query .= " ORDER BY t.title ";
        $query->order('t.title');
		// Build in JText of template title here and sort it afterwards
		$db->setQuery($query);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        { 
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
}

		$current = $db->loadObjectList();
		return (count($current)) ? $current : array();
	}

	/**
	 * sportsmanagementModelTemplates::getMasterName()
	 * 
	 * @return
	 */
	function getMasterName()
	{
	   $app	= JFactory::getApplication();
		$option = JRequest::getCmd('option');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        $starttime = microtime(); 
        
        $query->select('master.name');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project as master');
        $query->join('INNER', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_project as p ON p.master_template = master.id');
        $query->where('p.id = '.(int)$this->_project_id);
        
//		$query='	SELECT master.name
//					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project as master
//					INNER JOIN #__'.COM_SPORTSMANAGEMENT_TABLE.'_project as p ON p.master_template=master.id
//					WHERE p.id='.(int) $this->_project_id;
		$db->setQuery($query);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        { 
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
}

		return ($db->loadResult());
	}

}
?>