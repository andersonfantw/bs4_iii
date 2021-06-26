<?php
/**
 * smarty 設定
 */
require_once LIBS_PATH.'/Smarty/libs/Smarty.class.php';

$GLOBALS[GLOBAL_IDENTIFIER]["smarty"] = new Smarty;

// smarty設定
$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->template_dir = ROOT_PATH.'/view';
$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->compile_dir = ROOT_PATH . '/templates_c/';
$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->cache_dir = ROOT_PATH . '/cache/';
$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->left_delimiter = '<{';
$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->right_delimiter = '}>';
?>
