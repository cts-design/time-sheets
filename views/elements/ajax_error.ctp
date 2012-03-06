<?php

if($url) {
	$data['message'] = $url . '<br /><br />' . $authError;
}
else {
	$data['message'] = $authError;
}

$data['success'] = false;

echo json_encode($data);

?>