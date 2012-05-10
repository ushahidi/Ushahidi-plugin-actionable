<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Actionable Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class actionable {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		$this->actionable = "";
		$this->action_taken = "";
		$this->action_summary = "";
		
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Add a Sub-Nav Link
		Event::add('ushahidi_action.nav_admin_reports', array($this, '_report_link'));

		// Only add the events if we are on that controller
		if (Router::$controller == 'reports')
		{
			switch (Router::$method)
			{
				// Hook into the Report Add/Edit Form in Admin
				case 'edit':
					// Hook into the form itself
					Event::add('ushahidi_action.report_form_admin', array($this, '_report_form'));
					// Hook into the report_submit_admin (post_POST) event right before saving
					// Event::add('ushahidi_action.report_submit_admin', array($this, '_report_validate'));
					// Hook into the report_edit (post_SAVE) event
					Event::add('ushahidi_action.report_edit', array($this, '_report_form_submit'));
					break;
				
				// Hook into the Report view (front end)
				case 'view':
					plugin::add_stylesheet('actionable/views/css/actionable');
					Event::add('ushahidi_action.report_meta', array($this, '_report_view'));
					break;
			}
		}
		elseif (Router::$controller == 'feed')
		{
			// Add Actionable Tag to RSS Feed
			Event::add('ushahidi_action.feed_rss_item', array($this, '_feed_rss'));
		}
		elseif (Router::$controller == 'main')
		{
			Event::add('ushahidi_action.map_main_filters', array($this, '_map_main_filters'));
		}
		elseif (Router::$controller == 'json')
		{
			Event::add('ushahidi_filter.fetch_incidents_set_params', array($this, '_fetch_incidents_set_params'));
			Event::add('ushahidi_filter.json_index_features', array($this, '_json_index_features'));
			
			// Never cluster actionable json
			if (Router::$method == 'cluster' AND in_array($_GET['m'], array(101,102,103,104)))
			{
				Router::$method = 'index';
			}
		}
	}
	
	/**
	 * Add Actionable Form input to the Report Submit Form
	 */
	public function _report_form()
	{
		// Load the View
		$form = View::factory('actionable_form');
		// Get the ID of the Incident (Report)
		$id = Event::$data;
		
		if ($id)
		{
			// Do We have an Existing Actionable Item for this Report?
			$action_item = ORM::factory('actionable')
				->where('incident_id', $id)
				->find();
			if ($action_item->loaded)
			{
				$this->actionable = $action_item->actionable;
				$this->action_taken = $action_item->action_taken;
				$this->action_summary = $action_item->action_summary;
			}
		}
		
		$form->actionable = $this->actionable;
		$form->action_taken = $this->action_taken;
		$form->action_summary = $this->action_summary;
		$form->render(TRUE);
	}
	
	/**
	 * Handle Form Submission and Save Data
	 */
	public function _report_form_submit()
	{
		$incident = Event::$data;

		if ($_POST)
		{
			$action_item = ORM::factory('actionable')
				->where('incident_id', $incident->id)
				->find();
			$action_item->incident_id = $incident->id;
			$action_item->actionable = isset($_POST['actionable']) ? 
				$_POST['actionable'] : "";
			$action_item->action_taken = isset($_POST['action_taken']) ?
				$_POST['action_taken'] : "";
			$action_item->action_summary = $_POST['action_summary'];
			$action_item->save();
			
		}
	}
	
	/**
	 * Render the Action Taken Information to the Report
	 * on the front end
	 */
	public function _report_view()
	{
		$incident_id = Event::$data;
		if ($incident_id)
		{
			$actionable = ORM::factory('actionable')
				->where('incident_id', $incident_id)
				->find();
			if ($actionable->loaded)
			{
				if ($actionable->actionable)
				{
					$report = View::factory('actionable_report');
					$report->actionable = $actionable->actionable;
					$report->action_taken = $actionable->action_taken;
					$report->action_summary = $actionable->action_summary;
					$report->render(TRUE);
				}
			}
		}
	}
	
	/*
	 * Add actionable link to reports admin tabs
	 **/
	public function _report_link()
	{
		$this_sub_page = Event::$data;
		echo ($this_sub_page == "actionable") ? "Actionable" : "<a href=\"".url::site()."admin/actionable\">Actionable</a>";
	}
	
	/**
	 * Add the <actionable> tag to the RSS feed
	 */
	public function _feed_rss()
	{
		$incident_id = Event::$data;
		if ($incident_id)
		{
			$action_item = ORM::factory('actionable')
				->where('incident_id', $incident_id)
				->find();
			if ($action_item->loaded)
			{
				if ($action_item->actionable == 1)
				{
					echo "<actionable>YES</actionable>\n";
					echo "<urgent>NO</urgent>\n";
				}
				elseif ($action_item->actionable == 2)
				{
					echo "<actionable>YES</actionable>\n";
					echo "<urgent>YES</urgent>\n";
				}
				else
				{
					echo "<actionable>NO</actionable>\n";
					echo "<urgent>NO</urgent>\n";
				}
				
				if ($action_item->action_taken)
				{
					echo "<actiontaken>YES</actiontaken>\n";
				} else {
					echo "<actiontaken>NO</actiontaken>\n";
				}
			}
			else
			{
				echo "<actionable>NO</actionable>\n";
				echo "<urgent>NO</urgent>\n";
				echo "<actiontaken>NO</actiontaken>\n";
			}
		}
	}
	
	/*
	 * Add actionable filters on main map
	 */
	public function _map_main_filters()
	{
		echo '</div><h3>Actionable</h3><ul>';
		echo '<li><a id="media_101" href="#"><span>All</span></a></li>';
		echo '<li><a id="media_102" href="#"><span>Actionable</span></a></li>';
		echo '<li><a id="media_103" href="#"><span>Urgent</span></a></li>';
		echo '<li><a id="media_104" href="#"><span>Action Taken</span></a></li>';
		echo '</ul><div>';
	}

	/*
	 * Filter incidents for main map based on actionable status
	 */
	public function _fetch_incidents_set_params()
	{
		$params = Event::$data;
		
		// Look for fake media type
		if (isset($_GET['m']) AND in_array($_GET['m'], array(101,102,103,104)) )
		{
			// Remove media type filter based on fake actionable media type
			$sql = 'i.id IN (SELECT DISTINCT incident_id FROM '.Kohana::config('database.default.table_prefix').'media WHERE media_type IN ('.$_GET['m'].'))';
			$key = array_search($sql, $params);
			//var_dump($key);
			if ($key !== FALSE)
			{
				unset($params[$key]);
			}
			
			// Add filter based on actionable status.
			switch ($_GET['m'])
			{
				case '102':
					$params[] = 'i.id IN (SELECT DISTINCT incident_id FROM '.Kohana::config('database.default.table_prefix').'actionable
						WHERE actionable = 1 AND action_taken = 0)';
					break;
				case '103':
					$params[] = 'i.id IN (SELECT DISTINCT incident_id FROM '.Kohana::config('database.default.table_prefix').'actionable
						WHERE actionable = 2 AND action_taken = 0)';
					break;
				case '104':
					$params[] = 'i.id IN (SELECT DISTINCT incident_id FROM '.Kohana::config('database.default.table_prefix').'actionable
						WHERE actionable = 1 AND action_taken = 1)';
					break;
			}
			
		}
		
		Event::$data = $params;
	}
	
	/*
	 * Customise feature display based on actionable status
	 */
	public function _json_index_features()
	{
		$features = Event::$data;
		$results = ORM::Factory('actionable')->find_all()->as_array();
		
		$actionables = array();
		foreach($results as $actionable)
		{
			$actionables[$actionable->incident_id] = $actionable;
		}
		
		foreach($features as $key => $feature)
		{
			$incident_id = $feature['properties']['id'];
			if ($actionables[$incident_id])
			{
				$feature['properties']['actionable'] = $actionables[$incident_id]->status();
				$feature['properties']['strokecolor'] = $actionables[$incident_id]->color();
				$feature['properties']['strokeopacity'] = 0.7;
				$feature['properties']['strokewidth'] = 5;
				$feature['properties']['radius'] = Kohana::config('map.marker_radius')*2.5;
				$feature['properties']['icon'] = '';
				$features[$key] = $feature;
			}
		}
		
		Event::$data = $features;
	}

}

new actionable;