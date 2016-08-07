<?php
define('TEMPLATE_DIR', __DIR__ . '/tmpl');
//require_once(__DIR__ . '/../../repository/tomgslib/com/tom_gs/www/libraries/include.php');
require_once(__DIR__ . '/libs/object.php');
require_once(__DIR__ . '/libs/text.php');
require_once(__DIR__ . '/libs/html.php');
require_once(__DIR__ . '/libs/phpsetting.php');
require_once(__DIR__ . '/libs/sysinfo.php');

$info = new AdminModelSysInfo();
$info->display(TEMPLATE_DIR . '/index.inc.html');

