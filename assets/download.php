<?php
require_once '../../../../wp-config.php';
if(isset($_GET['file']) && !empty($_GET['file'])):
    $id = (int)$_GET['file'];
    download_file($id);
endif;