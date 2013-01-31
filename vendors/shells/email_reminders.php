<?php

App::import('Component', 'Notifications');

class EmailRemindersShell extends Shell {
	public $uses = array(
		'Event',
		'EventCategory'
	);

	public $components = array('Notifications');

	public function main() {
	}

	public function twenty_four_hour() {
		$today = date('Y-m-d H:i:s');
		$tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($today)));
		$tomorrowStartOfDay = date('Y-m-d H:i:s', strtotime("$tomorrow 00:00:00"));
		$tomorrowEndOfDay = date('Y-m-d H:i:s', strtotime("$tomorrow 23:59:59"));

		$this->Event->Behaviors->attach('Containable');
		$this->Event->recursive = -1;

		$events = $this->Event->find('all', array(
			'conditions' => array(
				'Event.scheduled BETWEEN ? AND ?' => array($tomorrowStartOfDay, $tomorrowEndOfDay)
			),
			'contain' => array(
				'EventRegistration' => array(
					'User' => array(
						'fields' => array('firstname', 'lastname', 'email')
					)
				)
			)
		));

		$this->Notifications =& new NotificationsComponent();

		// Loop through each event and eventRegistration
		foreach ($events as $key => $event) {
			if (isset($event['EventRegistration']) && !empty($event['EventRegistration'])) {
				foreach ($event['EventRegistration'] as $eventRegistration) {
					$this->Notifications->sendEventReminderEmail($event, $eventRegistration);
				}
			}
		}
	}

	public function seventy_two_hour() {
		$today = date('Y-m-d H:i:s');
		$seventyTwo = date('Y-m-d', strtotime('+3 day', strtotime($today)));
		$seventyTwoStartOfDay = date('Y-m-d H:i:s', strtotime("$seventyTwo 00:00:00"));
		$seventyTwoEndOfDay = date('Y-m-d H:i:s', strtotime("$seventyTwo 23:59:59"));

		$this->Event->Behaviors->attach('Containable');
		$this->Event->recursive = -1;

		$events = $this->Event->find('all', array(
			'conditions' => array(
				'Event.scheduled BETWEEN ? AND ?' => array($seventyTwoStartOfDay, $seventyTwoEndOfDay)
			),
			'contain' => array(
				'EventRegistration' => array(
					'User' => array(
						'fields' => array('firstname', 'lastname', 'email')
					)
				)
			)
		));

		$this->Notifications =& new NotificationsComponent();

		// Loop through each event and eventRegistration
		foreach ($events as $key => $event) {
			if (isset($event['EventRegistration']) && !empty($event['EventRegistration'])) {
				foreach ($event['EventRegistration'] as $eventRegistration) {
					$this->Notifications->sendEventReminderEmail($event, $eventRegistration);
				}
			}
		}
	}
}
?>
