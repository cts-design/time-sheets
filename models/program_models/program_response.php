<?php

class ProgramResponse extends AppModel {

    var $name = 'ProgramResponse';

    var $hasMany = array('ProgramResponseDoc', 'ProgramResponseActivity');

    var $belongsTo = array('Program', 'User');

    var $validate = array();

    var $actsAs = array('Containable');

    function getProgramResponse($programId, $userId) {
        $programResponses = $this->find('all', array(
            'conditions' => array(
                'ProgramResponse.user_id' => $userId,
                'ProgramResponse.program_id' => $programId)));
        foreach($programResponses as $programResponse) {
            if($programResponse['ProgramResponse']['status'] === 'complete') {
                $return = $programResponse;
                break;
            }
            if($programResponse['ProgramResponse']['status'] === 'pending_approval') {
                $return = $programResponse;
                break;
            }
            elseif($programResponse['ProgramResponse']['status'] === 'not_approved' &&
                ! $programResponse['ProgramResponse']['allow_new_response']) {
                    $return = $programResponse;
                    break;
            }
            elseif($programResponse['ProgramResponse']['expires_on'] > date('Y-m-d H:i:s') &&
                $programResponse['ProgramResponse']['status'] === 'not_approved') {
                    $return = $programResponse;
                    break;
            }
            elseif($programResponse['ProgramResponse']['status'] === 'incomplete' &&
                $programResponse['ProgramResponse']['expires_on'] > date('Y-m-d H:i:s')) {
                    $return = $programResponse;
                    break;
            }
        }
        if(empty($return)) {
            $return = null;
        }
        return $return;
    }
}
