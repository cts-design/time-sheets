<?php

if ($nodes) {
    $data = array();

    foreach ($nodes as $node){
        $data[] = array(
            "text" => $node['Navigation']['title'],
            "id" => $node['Navigation']['id'],
            "cls" => "folder",
            "leaf" => ($node['Navigation']['lft'] + 1 == $node['Navigation']['rght'])
        );
    }

    echo $this->Js->object($data);
}

?>