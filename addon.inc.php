<?php
function quicksearch_install_addon()
	{
		$current_version = "0.11";
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new Misc();
		//Check Current Installed Version
		$sql = 'SELECT addons_version FROM '.$config['table_prefix_no_lang'].'addons WHERE addons_name = \'quicksearch\'';
		$recordSet = $conn->Execute($sql);
		$version = $recordSet->fields[0];
		if ($version == '') 
		{
			// Preform a new install. Create any needed databases etc, and insert version number into addon table.
			$sql = 'INSERT INTO '.$config['table_prefix_no_lang'].'addons (addons_version, addons_name) VALUES (\'' . $current_version . '\',\'quicksearch\')';
			$recordSet = $conn->Execute($sql);		
			return TRUE; 
		}
		elseif ($version != $current_version)
		{
		    //Preform Updates to database based on previous installed version.
			switch($version)
			{
		    	case '0':
					break;
		    } // switch
			return TRUE;
		}
		return FALSE;
	}

function quicksearch_show_admin_icons()
	{
		$admin_link = '';
		return $admin_link;
	} 
 
function quicksearch_load_template()
	{
		$template_array = array('addon_quicksearch_form');
		return $template_array;
	} 


function quicksearch_run_action_user_template()
	{
		switch ($_GET['action']) {
			case 'addon_quicksearch_showpage1':
				$data = quicksearch_display_addon_page();
				break;
			default:
				$data = '';
				break;
		} // End switch ($_GET['action'])
		return $data;
	} 


function quicksearch_run_action_admin_template()
	{
		switch ($_GET['action']) {
			case 'addon_quicksearch_admin':
				$data = quicksearch_display_admin_page();
				break;
			default:
				$data = '';
				break;
		} // End switch ($_GET['action'])
		return $data;
	} 

function quicksearch_run_template_user_fields($tag = '')
	{
		switch ($tag) {
			case 'addon_quicksearch_form';
				$data = quicksearch_form();
				break;
			default:
				$data = '';
				break;
		} // End switch ($_GET['action'])
		return $data;
	} 

// show quick search fpr,
function quicksearch_form()
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php'); 
		$misc = new misc();
		
		$display = "";
		$display .= "<form action='index.php'method='get' name='class_search_form' id='class_search_form'>";
		
		//Select "Purpose" START
		$sql = "SELECT listingsdbelements_field_value FROM " . $config['table_prefix'] . "listingsdbelements WHERE listingsdbelements_field_name = 'purpose'";
		$rs = $conn->Execute ($sql);
		if (!$rs) {
			$misc->log_error($sql);
		}
		$i = 0;
		while (!$rs->EOF) {
			$current_value = $misc->make_db_unsafe($rs->fields['listingsdbelements_field_value']);
			$rs->MoveNext();
			$purpose[$i] = $current_value;
			$i++;
		}
		$purpose = array_unique($purpose);
		sort($purpose);	
		
		$display .= ""; 	//<strong>{lang_quicksearch_purpose}:   </strong>
		
			for($i=0; $i <count($purpose) ; $i++) {
				$display .= "<input class='checkbox' type='radio'";
				if ($i==1) 
				{
					$display .= " checked ";
				}
				$display .= "name='purpose' value='" . $purpose[$i] . "  '>" . $purpose[$i]." ";
	       	}
		$display .= "<br /><br />";
		//Select "Purpose" END
		
		//Select "Property Type" START
		$sql = "SELECT class_id, class_name FROM " . $config['table_prefix'] . "class";
		$rs = $conn->Execute ($sql);
		if (!$rs) {
			$misc->log_error($sql);
		}

		
					$i= 0;
					$display .= "<strong>{lang_quicksearch_property_type}</strong><br />
								<select class='inputbox' name='pclass[]'>";
					$display .= "<option value=''>" . "{lang_quicksearch_all}" . "</option>";
					
					while (!$rs->EOF) {
						$i++;
						$value_classname = $misc->make_db_unsafe($rs->fields['class_name']);
						$value_classid = $misc->make_db_unsafe($rs->fields['class_id']);
						$display .= "<option value='" . $value_classid . "'>" . $value_classname . "</option>";
						
						$rs->MoveNext();
					}
		//Select "Property Type" END		
		
		//Select "Region" START
		$sql = "SELECT listingsdbelements_field_value FROM " . $config['table_prefix'] . "listingsdbelements WHERE listingsdbelements_field_name = 'region'";
		$rs = $conn->Execute ($sql);
		if (!$rs) {
			$misc->log_error($sql);
		}
		$i = 0;
		while (!$rs->EOF) {
			$current_value = $misc->make_db_unsafe($rs->fields['listingsdbelements_field_value']);
			$rs->MoveNext();
			$regions[$i] = $current_value;
			$i++;
		}
		$regions = array_unique($regions);
		sort($regions);	
		
		$display .= "</select><br />
			<strong>{lang_quicksearch_region}</strong> <br />
			<select class='inputbox' name='region'>
       		<option value=''>{lang_quicksearch_all}</option>";
			for($i=0; $i < count($regions); $i++) {
				$display .= "<option value='" . $regions[$i] . "'>" . $regions[$i] . "</option>";
	       	}
		//Select "Region" END
		
		$display .= "</select><br />
		<strong>{lang_quicksearch_price}</strong> <br />
		<input class='inputbox' type='Text' name='price-min' size='7' maxlength='10'>-<input class='inputbox' type='Text' name='price-max' size='7' maxlength='10'> €<br />
		<!-- <strong>Reference ID</strong><br />
		<input class='inputbox' type='Text' name='reference_id[]' size='10' maxlength='10'><br /> -->					
		<input name='action' id='action' value='' type='hidden' />		
		<strong><input id='find' name='view_search_results' value='{lang_search_button}' type='button' onclick='document.class_search_form.action.value=\"searchresults\";document.getElementById(\"class_search_form\").submit();' /></strong>
		<br /><br />
		<a href='index.php?action=search_step_2'>{lang_quicksearch_detailed_search}</a>
		</form>";
		
		


		return $display;
	} 

function quicksearch_display_addon_page()
	{
		$display = 'This is a Addon page';
		return $display;
	} 

function quicksearch_display_admin_page()
	{
		$display = 'This is a Addon page';
		return $display;
	} 

?>