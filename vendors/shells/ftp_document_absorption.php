<?php

App::import('Component', 'Notifications');

class FtpDocumentAbsorptionShell extends Shell {

	public $uses = array(
		'AutoLock', 
		'DocumentQueueCategory',
		'FtpDocumentScanner', 
		'QueuedDocument', 
		'BarCodeDefinition', 
		'User'
	);

	public function main() { 
		$this->Notifications = &new NotificationsComponent();

		// If locked, stop running
		$locked = $this->AutoLock->find("first");
		if ($locked["AutoLock"]["status"]) {
			print "Locked\n\n";
			exit ;
		}
		// If not locked, set lock to 1 and begin processing
		$this->AutoLock->id = 1;
		$this->AutoLock->set(array('auto_lock_status' => 1));
		if ($this->AutoLock->save()) {
			$scan_path = Configure::read('Document.scan.path');
			// Read in existing FTP log
			$ftp_log = file(Configure::read('FTP.log.path'));
			$path = APP . substr(Configure::read('Document.storage.path'), 1);
			if ($folder_list = opendir($scan_path)) {
				while (false != ($scan_folder = readdir($folder_list))) {
					if (($scan_folder != '.') && ($scan_folder != '..')) {
						if ($file_list = opendir($scan_path . $scan_folder)) {
							while (false != ($file_name = readdir($file_list))) {
								// We currently only accept PDF documents, which is all we will be looking for and allowing
								if (($file_name != '.') && ($file_name != '..') && (preg_match("/\.pdf/", $file_name))) {
									$ip_address = '';
									// Find IP based on filename
									foreach ($ftp_log as $ftp_log_line) {
										if (preg_match("/$file_name/", $ftp_log_line)) {
											$file_parts = explode(" ", $ftp_log_line);
											// Elements of standard proftpd log are space-delimited. The IP is always in the 7th (6th array) position
											$ip_address = $file_parts[6];
											break;
										}
									}
									if (!$ip_address) {
										// Skip - can't find IP, which means the doc hasn't finished uploading yet
										continue;
									}
									// Query location based on IP address
									if ($location = $this->FtpDocumentScanner->find('first', array('conditions' => array('device_ip' => "$ip_address")))) {
										$my_location = $location["FtpDocumentScanner"]["location_id"];
									} else {
										// ALERT THAT DEVICE IS NOT FOUND WITHIN SYSTEM; MOVE ALONG TO NEXT ITEM
										$this->log('FTP ABSORB: Can\'t find location or device for IP: ' . $ip_address, 'error');
										$this->Notifications->sendAbsorptionEmail('FTP scanner details not found for IP','Could not find device info for ip:'."\n" . $ip_address . "\n");
										continue;
									}
									// Query queue category based on folder name
									if ($location = $queue_category = $this->DocumentQueueCategory->find('first', array('conditions' => array('ftp_path' => "$scan_folder")))) {
										$my_category = $queue_category["DocumentQueueCategory"]["id"];
									} else {
										// ALERT THAT SCAN FOLDER NOT FOUND WITHIN SYSTEM; MOVE ALONG TO NEXT ITEM
										$this->log('FTP ABSORB: Can\'t find queue category for path: ' . $scan_folder, 'error');
										continue;
									}

									// Barcode section
									// Needs to be run on Ubuntu, using ImageMagick/convert, pdftk, and bardecode
									// Make sure path for writing file to filesystem exists; if not, create
									
									if (!file_exists($path . date('Y') . '/')) {
										// If it can't create the year folder, something is wrong. Keep the db locked, E-Mail admin, and exit
										if (!mkdir($path . date('Y'), 0777)) {
											$this->log('FTP ABSORB: Can\'t create year folder at: ' . $path, 'error');
											$this->Notifications->sendAbsorptionEmail('CANNOT CREATE YEAR FOLDER','CANNOT CREATE YEAR FOLDER.\nATLAS FTP doc absorption has exited and will not run until resolved.\n');
											exit;
										}
									}
									$path .= date('Y') . '/';
									if (!file_exists($path . date('m') . '/')) {
										if (!mkdir($path . date('m'), 0777)) {
											// If it can't create month folder, something is wrong. Keep the db locked, E-Mail admin, and exit
											$this->log('FTP ABSORB: Can\'t create month folder at: ' . $path, 'error');
											$this->Notifications->sendAbsorptionEmail('CANNOT CREATE MONTH FOLDER','CANNOT CREATE MONTH FOLDER.\nATLAS FTP doc absorption has exited and will not run until resolved.\n');
											exit;
											continue;
										}
									}
									$path .= date('m') . '/';
									$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
									// Copy the file to destination folder
									// If it can't copy the file, something is wrong. Keep the db locked, E-Mail admin, and exit
									if (!copy($scan_path . $scan_folder . '/' . $file_name, $path . $docName)) {
										$this->log('FTP ABSORB: Can\'t copy file: ' . $file_name . ' to: ' . $path . $docName, 'error');
										$this->Notifications->sendAbsorptionEmail('CANNOT COPY FILE','CANNOT COPY FILE.\nATLAS FTP doc absorption has exited and will not run until resolved.\n');
										exit ;
									} else {
										// If copied successfully but can't unlink, write to error log and skip document
										if (!unlink($scan_path . $scan_folder . '/' . $file_name)) {
											$this->log('FTP ABSORB: Can\'t unlink file: ' . $file_name, 'error');
											continue;
										}
									}
									$this->QueuedDocument->create();
									$this->data = array(
										'filename' => $docName, 
										'entry_method' => 'FTP Scanner', 
										'queue_category_id' => $my_category, 
										'scanned_location_id' => $my_location);
									$barCode = $this->BarCodeDefinition->barDecode($path . $docName);
									if ($barCode) {
										$this->data['bar_code_definition_id'] = $barCode['BarCodeDefinition']['id'];
										$this->data['queue_category_id'] = $barCode['BarCodeDefinition']['document_queue_category_id'];
										$this->data['user_id'] = $barCode['User']['id'];	
										if(preg_match('/esign/i', $barCode['BarCodeDefinition']['name'])) {
											$this->User->id = $barCode['User']['id'];
											$this->User->saveField('signature', 1);
											$this->User->saveField('signature_created', date('Y-m-d H:i:s'));
											$this->User->saveField('signature_modified', date('Y-m-d H:i:s'));
										}
									}
									if (!$this->QueuedDocument->save($this->data)) {
										$this->log('FTP ABSORB: Can\'t write document record to QueuedDocument table in database', 'error');
										continue;
									}
								}
							}
						} else {
							$this->log('FTP ABSORB: Can\'t read files from primary file directory located at: ' . $scan_path . $scan_folder . ', exiting.', 'error');
							exit ;
						}
					}
				}
				closedir($folder_list);
			} else {
				// Can't get a directory list of scan_folders
				$this->log('FTP ABSORB: Can\'t read directory information for scan folders, exiting', 'error');
				exit ;
			}
		} else {
			// Unable to run query to create lock
			$this->log('FTP ABSORB: Can\'t lock the database, exiting.', 'error');
			exit ;
		}
		// Unlock
		$this->AutoLock->id = 1;
		$this->AutoLock->set(array('status' => 0));
		if (!$this->AutoLock->save()) {
			$this->log('FTP ABSORB: Can\'t unlock the database, exiting.', 'error');
		}

	}

}
