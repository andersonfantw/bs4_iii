<?php
require_once dirname(__FILE__).'/../init/config.php';

$init = new init('tpl','sysauth','db','ehttp');

$tpl->display('backend/sys_testcase.tpl');
