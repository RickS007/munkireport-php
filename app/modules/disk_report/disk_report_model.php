<?php
class Disk_report_model extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'diskreport'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['TotalSize'] = 0; $this->rt['TotalSize'] = 'BIGINT';
		$this->rs['FreeSpace'] = 0; $this->rt['FreeSpace'] = 'BIGINT';
		$this->rs['Percentage'] = 0;
		$this->rs['SMARTStatus'] = '';
		$this->rs['SolidState'] = 0;
		   
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
			$this->retrieve_one('serial_number=?', $serial);
		
		$this->serial = $serial;
		  
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author abn290
	 **/
	function process($plist)
	{		
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();

		// Reset values
		$this->SolidState = 0;
		$this->TotalSize = 0;
		$this->FreeSpace = 0;
		$this->Percentage = 0;
		$this->SMARTStatus = '';

		// Calculate percentage
		if(isset($mylist['TotalSize']) && isset($mylist['FreeSpace']))
		{
			$mylist['Percentage'] = round(($mylist['TotalSize'] - $mylist['FreeSpace']) /
				max($mylist['TotalSize'], 1) * 100);
		}

		$this->merge($mylist);

		// Set type on SolidState (booleans don't translate well)
		$this->SolidState = (int) $this->SolidState;

		$this->save();
	}

	
}