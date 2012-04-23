<?php

class ProgramResponse extends AppModel {

    var $name = 'ProgramResponse';

    var $hasMany = array('ProgramResponseDoc', 'ProgramResponseActivity');

    var $belongsTo = array('Program', 'User');

    var $validate = array();

    var $actsAs = array('Containable');

    function getProgramResponse($programId, $userId) {
        $programResponse = $this->find('first', array(
            'conditions' => array(
                'ProgramResponse.user_id' => $userId,
                'ProgramResponse.program_id' => $programId)));
        if($programResponse['ProgramResponse']['expires_on'] <= date('Y-m-d H:i:s') &&
            $programResponse['ProgramResponse']['status'] === 'incomplete') {
                $expiredResponse = $this->expireResponse($programResponse['ProgramResponse']['id']);
                if($expiredResponse) {
                    $programResponse = $expiredResponse;
                }
        }
        return $programResponse;
    }

    function expireResponse($responseId) {
        $this->id = $responseId;
        $this->saveField('status', 'expired');
        $response = $this->findById($responseId);
        if($response['ProgramResponse']['status'] === 'expired') {
            return $response;
        }
        else {
            return false;
        }

    }
}
