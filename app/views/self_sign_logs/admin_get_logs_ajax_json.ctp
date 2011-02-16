<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>

<?php

if (isset($selfSignLogs)) {
    echo $this->Js->object($selfSignLogs);
}
else {
    $this->Js->object('');
}
?>
