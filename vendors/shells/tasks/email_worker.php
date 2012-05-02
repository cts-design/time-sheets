<?php 
class EmailWorkerTask extends QueueShell {
	public $uses = array('Queue.Job' );
	public $tubes = array('program_email');
	
	public function execute() {
		while(true) {
			$this->out('Waiting for a job....');	
			$job = $this->Job->reserve(array('tube' => $this->tubes));
			$this->out(var_export($job));
			if(!$job) {
				$this->log('Invalid job found. Not processing.', 'error');	
			}
			else {
				$this->out('Processing job ' . $job['Job']['id']);
				App::import('Core', 'Controller');
				App::import('Component', 'Email');
				$this->Controller =& new Controller();
				$this->Email =& new EmailComponent(null);
				$this->Email->initialize($this->Controller);    
				$this->Email->to = $job['Job']['Email']['to'];
				$this->Email->from = $job['Job']['Email']['from'];
				$this->Email->subject = $job['Job']['Email']['subject'];
				$processed =  $this->Email->send($job['Job']['Email']['body']);
				if($processed) {
					$this->out('Job ' . $job['Job']['id'] . ' processed.');
					if($this->Job->delete()) {
						$this->out('Job ' . $job['Job']['id'] . ' deleted from queue.');
					}
				}
				else {
					$this->out('Unable to process job ' .  $job['Job']['id'] . 'burying.');
					$this->Job->bury(6000);
				}
			}
		}
	}
}
