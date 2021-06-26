<?PHP
/*
RewriteRule ^hosts\/(\d+)\/(\d+)\/files\/(\d+)\/book.php /plugin/rewriterule/api/api.php?type=ecocat304&f=book&uid=$1&bs=$2&bkey=$3 [L]
RewriteRule ^hosts\/(\d+)\/(\d+)\/files\/(\d+)\/book_swf.php /plugin/rewriterule/api/api.php?type=ecocat304&f=book_swf&uid=$1&bs=$2&bkey=$3 [L]
RewriteRule ^hosts\/(\d+)\/(\d+)\/files\/(\d+)\/html5/index.php /plugin/rewriterule/api/api.php?type=ecocat304&f=index&uid=$1&bs=$2&bkey=$3 [L]
RewriteRule ^hosts\/(\d+)\/(\d+)\/files\/(\d+)\/html5/index.html /plugin/rewriterule/api/api.php?type=ecocat304&f=index&uid=$1&bs=$2&bkey=$3 [L]
RewriteRule ^hosts\/(\d+)\/(\d+)\/files\/(\d+)\/m/index.php /plugin/rewriterule/api/api.php?type=ecocat211&f=index&uid=$1&bs=$2&bkey=$3 [L]

http://ebook.lnet.tw/hosts/10/33/files/2265751525309587/demo.php
*/
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');

$_type = $fs->valid($_GET['type'],'cmd');
$_f = $fs->valid($_GET['f'],'cmd');
$_uid = $fs->valid($_GET['uid'],'id');
$_bsid = $fs->valid($_GET['bsid'],'id');
$_bkey = $fs->valid($_GET['bkey'],'key');

if(!isset($_SESSION['adminid']) && !isset($_SESSION['buid'])){
        echo 'Please open book from bookshelf!';exit;
}
$file = sprintf('%s/plugin/rewriterule/data/%s/%s.html',ROOT_PATH,$_type,$_f);
echo file_get_contents($file);
?>
