<?php
class filter_string
{
   // tag black list
   var $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', /*'style',*/ 'script', /*'embed', 'object',*/ 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', /*'title',*/ 'base');
   // attr black list
   var $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

	 var $regex = Array(
		'id'=>'/^([0-9]{1,11})$/',									//digit only
		'num'=>'/^([\-]{0,1}[0-9]{1,11})$/',				//digit only
		'key'=>'/^([a-zA-Z0-9_=\+\-]{1,255})$/',
		'tagkey'=>'/^(#{0,1}[a-zA-Z0-9]{1,254})$/',
		'cmd'=>'/^([a-zA-Z][a-zA-Z0-9_]{0,40})$/',	//a-zA-Z0-9_, 3~40
		'acc'=>'/^([a-zA-Z][\w]{3,15})$/',					//less 4 character, 20 character max. start and emd with a-zA-Z, a-zA-Z_ in between.
		'pwd'=>'/^([^\'\"]{3,40})$/',								//any character between 4 and 20 times
		'name'=>'/^([^\'\"]{1,255})$/',							//any character between 2 and 20 times. include chinese or other language code
		'pname'=>'/^([a-zA-Z0-9_]{0,255})$/',
		'content'=>'/^([^\']*)$/',									//length 1073741824
		'filename'=>'/^([^\?\$\,\+\!\=#&@%*\/\'"]+){1,255}$/',
		'query'=>'/^([^\'\"]{1,255})$/',
		'idarray'=>'/^([0-9]+(,[0-9]+)*)$/',					//num seperate between ,
		'timestamp'=>'/^([0-9]{10})$/',							//10 digits
		'jstimestamp'=>'/^([0-9]{13})$/',						//13 digits
		'path'=>'/^([\/\w\._@]+)$/',
		'email'=>'email',
		'url'=>'url',
		'ip'=>'ip',
		'sessionid'=>'/^([a-z0-9]{24})$/',
		'lnettoken'=>'lnettoken',
		'activecode'=>'activecode',
		'ARRAY'=>'ARRAY',
		'date'=>'date',
		'xml'=>'xml',
		'json'=>'json',
		'bool'=>'bool');									//0|1|true|false

	 function test($_val,$type,$canbeempty=true){
			if(is_array($_val)){
				$val=$_val;
			}else{
				$no_html=true;
				if(in_array($type,array('content','xml'))){
					$no_html=false;
				}
				$val = $this->filter($_val,$no_html);
			}
			if($canbeempty && empty($val)) return $val;
			$f=false;
			$arr = explode('|',$type);
			foreach($arr as $t){
				switch($t){
					case 'bool':
						$v = filter_var($val, FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE));
						if(is_null($v)){
							$f1 = false;
						}else{
							$f1 = true;
							$val = $v;
						}
						break;
					case 'email':
						$f1 = filter_var($val,FILTER_VALIDATE_EMAIL);
						break;
					case 'url':
						$val1 = filter_var($val,FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_HIGH);
						$f1 = filter_var($val1,FILTER_VALIDATE_URL)
									|| filter_var('http://'.$val1,FILTER_VALIDATE_URL);
						break;
					case 'ip':
						list($host,$port) = explode(':',$val);
						$f1 = filter_var($host,FILTER_VALIDATE_IP);
						if(!empty($port)){
							$f1 &= is_numeric($port);
						}
						break;
					case 'domain':
						list($host,$port) = explode(':',$val);
						$f1 = filter_var($host,FILTER_FLAG_HOST_REQUIRED);
						if(!empty($port)){
							$f1 &= is_numeric($port);
						}
						break;
					case 'ARRAY':
						$f1 = is_array($val);
						break;
					case 'date';
						$f1 = strtotime($val);
						break;
					case 'xml':
						$val = htmlspecialchars_decode($val);
						$f1 = simplexml_load_string($val);
						break;
					case 'json':
						$v = json_decode($val);
						$f1 = !is_null($v);
						break;
					case 'activecode':
						$f1 = ActiveCodeManager::check($val);
						break;
					case 'lnettoken':
						$v = common::checkToken($val);
						$f1 = !is_null($v);
						break;
					case 'idarray':
						$f1 = true;
						if(is_string($val)){
							$val = explode(',',$val);
						}
						if(is_array($val)){
							foreach($val as $v){
								if(!is_numeric($v)){
									$f1=false;
								}
							}
						}else{
							$pattern = $this->regex['id'];
							preg_match($pattern, $val, $matches);
							if(count($matches)==0){
								$f1=false;
							}
						}
						break;
					default:
						$f1 = true;
					 	$pattern = $this->regex[$t];
					 	if(!is_string($val)){
					 		$ee->Error('406.62');
					 	}
						preg_match($pattern, $val, $matches);
						if(count($matches)==0){
							$f1=false;
						}
						break;
				}
				$f = $f || $f1;
			}
			return $f;
	 }

	 function valid($_val,$type,$canbeempty=true)
	 {
		global $ee;
		if(is_array($_val)){
			$val=$_val;
		}else{
			$no_html=true;
			if($type=='content'){
				$no_html=false;
			}
			$val = $this->filter($_val,$no_html);
		}
		if($canbeempty && empty($val)) return $val;

		$f = $this->test($_val,$type,$canbeempty);	
		if(!$f){
			$ee->add('param',$type);
			$ee->add('value',$val);
			$ee->add('msg',sprintf('value "%s" is valid as %s ,has invalid character',strip_tags($_val),$type));
			$ee->error('406.61');
		}
		return (is_null($val))?'':$val;
	 }

   //basic
   function filter($data ,$no_html = true)
   {
		if($no_html)
			return htmlspecialchars(strip_tags(trim($data)));
		else 
			return htmlspecialchars(trim($data));
   }
    // sql safe
   function sql_safe($data){
		if( ! get_magic_quotes_gpc() ){
			//$data = array_map( "mysql_real_escape_string" , $data );
			$data = array_map( "addslashes", $data );
		}
		return $data;
   }   
   // sql safe string
   function fsprintf()
   {
      $arg_list = func_get_args();
      $str = $arg_list[0];
      array_shift( $arg_list );
   
      @$arg_list = ( ! get_magic_quotes_gpc() ) ? $arg_list : array_map( "mysql_real_escape_string" , $arg_list );
   
      return vsprintf( $str , $arg_list );
   }
   
   /**
     * removexss, del tag
     * @param   string   $val                
     * @param   string   $filter             過濾方式( allow_tag的情況 )
     *                                       mark - 加上<x>
     *                                       del - 刪除<tag>~</tag>的內容
     * @param   mixed    $black_list         黑名單   ['tag'], ['attr']
     * @param   mixed    $white_list         白名單   ['tag'], ['attr']
     * @param   mixed    $exception          例外名單 ['tag'], ['attr']
     */
   function removexss($val , $filter , $black_list=array() , $white_list=array() , $exception=array() )
   {
      list( $ra1 , $ra2 ) = $this->set_filter_list( $black_list , $white_list , $exception );
      /*
      var_dump( $ra1 );
      var_dump( $ra2 );
      exit;
      */
      
      // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
      // this prevents some character re-spacing such as <java\0script>
      // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
      //$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   
      // straight replacements, the user should never need these since they're normal characters
      // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A &#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
      $search = 'abcdefghijklmnopqrstuvwxyz';
      $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $search .= '1234567890!@#$%^&*()';
      $search .= '~`";:?+/={}[]-_|\'\\';
      for ($i = 0; $i < strlen($search); $i++) 
      {
         // ;? matches the ;, which is optional
         // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
   
         // &#x0040 @ search for the hex values
         $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
         // &#00064 @ 0{0,7} matches '0' zero to seven times
         $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
      }
      
      // now the only remaining whitespace attacks are \t, \n, and \r
      $ra = array_merge($ra1, $ra2);
   
      // black list
      if( empty( $white_list ) )
      {
         if( $filter == 'mark' )
            $val = $this->mark_black_list( $val , $ra );
         elseif( $filter == 'del' )
            $val = $this->del_black_list( $val , $ra );
      }
      // white list
      else
      {
         if( $filter == 'mark' )
            $val = $this->mark_white_list( $val , $ra );
         elseif( $filter == 'del' )
            $val = $this->del_white_list( $val , $ra );
      }
      return $val;
   }
   
   
   /**
     * set filter list
     * @param   mixed    $black_list         黑名單   ['tag'], ['attr']
     * @param   mixed    $white_list         白名單   ['tag'], ['attr']
     * @param   mixed    $exception          例外名單 ['tag'], ['attr']
     *
     * @return mixed
     */
   function set_filter_list( $black_list , $white_list , $exception )
   {
      if( ! empty( $black_list['tag'] ) || ! empty( $black_list['attr'] ) )
      {
         if( ! empty( $black_list['tag'] ) )
            $ra1 = $black_list['tag'];
         else
            $ra1 = $this->ra1;
         
         if( ! empty( $black_list['attr'] ) )
            $ra2 = $black_list['attr'];
         else
            $ra2 = $this->ra2;
      }
      elseif( ! empty($white_list) )
      {
         $ra1 = $white_list['tag'];
         $ra2 = $white_list['attr'];
      }
      else
      {
         $ra1 = $this->ra1;
         $ra2 = $this->ra2;
      }
      
      if( ! empty( $exception ) )
      {
         foreach( $exception['tag'] as $skip_tag )
         {
            foreach( $ra1 as $key=>$tag )
            {
               if( preg_match("/".$tag."/i",$skip_tag) )
               {
                  unset( $ra1[$key] );
                  break;
               }
            }
         }
         
         foreach( $exception['attr'] as $skip_attr )
         {
            foreach( $ra2 as $key=>$attr )
            {
               if( preg_match("/".$attr."/i",$skip_attr) )
               {
                  unset( $ra2[$key] );
                  break;
               }
            }
         }
         
      }
      return array( $ra1 , $ra2 );
      
   }
   
   
   /**
     * mark all tag in black list
     * @param   string   $val
     * @param   mixed    $ra
     *
     * @return string
     */
   function mark_black_list( $val , $ra )
   {
      $found = true; // keep replacing as long as the previous round replaced something
      while ($found == true) 
      {
         $val_before = $val;
         for ($i = 0; $i < sizeof($ra); $i++) 
	 {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) 
	    {
               if ($j > 0) 
               {
                  $pattern .= '(';
                  $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                  $pattern .= '|';
                  $pattern .= '|(&#0{0,8}([9|10|13]);)';
                  $pattern .= ')*';
               }
               $pattern .= $ra[$i][$j];
            }
	    $pattern .= '/i';
            
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) 
            {
               // no replacements were made, so exit the loop
               $found = false;
            }
         }
      }
      return $val;
   }
   
   
   /**
     * delete all tag in black list
     * @param   string   $val
     * @param   mixed    $ra
     *
     * @return string
     */
   function del_black_list( $val , $ra )
   {
      for ($i = 0; $i < sizeof($ra); $i++) 
      {
         $pattern = '';
         for ($j = 0; $j < strlen($ra[$i]); $j++) 
	 {
            if ($j > 0) 
            {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         // remove <tag>~</tag>
         // <script>~</script>  抓不到有屬性的tag <script langage=....>
         $match_pattern[1] = '/<\s*'.$pattern.'\s*>(.*?)\/\s*'.$pattern.'\s*>/ims';
         // <script languagexxx>~</script> 會誤抓 <script123 >~</script>
         $match_pattern[2] = '/<\s*'.$pattern.'\s+[^>]*>(.*?)\/\s*'.$pattern.'\s*>/ims';
         // remove <tag />
         $match_pattern[3] = '/<\s*'.$pattern.'([^>]*)\/\s*>/ims';
         // remove <tag>
         $match_pattern[4] = '/<\s*'.$pattern.'[^\w]*>/ims';
         $match_pattern[5] = '/<\s*'.$pattern.'\s+[^>]*>/ims';
         // rewrite "<tag attr="  to   "<tag xxx="
         $match_pattern[6] = '/<([^>]*)('.$pattern.')\s*[=|:]/ims';
         
         $replacement[1] = '';
         $replacement[2] = '';
         $replacement[3] = '';
         $replacement[4] = '';
         $replacement[5] = '';
         $replacement[6] = '<$1xxx=';
               
         for($loop=1; $loop<=6; $loop++)
            $val = preg_replace($match_pattern[$loop], $replacement[$loop], $val);
      }
      return $val;
   }
   
   
   /**
     * mark all tag not in white list
     * @param   string   $val
     * @param   mixed    $ra
     *
     * @return string
     */   
   function mark_white_list( $val , $ra )
   {
      $allow_tag = implode('|', $ra);
      // </tag>
      $match_pattern[1] = "/<[\s]*\/((?!\s*(".$allow_tag."))[^>]*)\s*>/i";
      // <tag> & <tag />
      $match_pattern[2] = "/<[\s]*((?!\s*(".$allow_tag."|\/))[^>]*)\s*>/i";
      $match_pattern[2] = "/<[\s]*((?!\s*(".$allow_tag."|\/))[^\s>]*)[^>]*\s*>/i";
      $replacement[1] = '</x$1x>';
      $replacement[2] = '<x$1x>';
      
      for($loop=1; $loop<=2; $loop++)
         $val = preg_replace($match_pattern[$loop], $replacement[$loop], $val);
      
      // clear attr
      unset($match_pattern);
      unset($replacement);
      // parse tag attr without xxx <img src=123 title="456"> ,
      // get two result
      // $1 = <img, $2 = src=123, $3 = src, $4 = 123 
      // $1 = , $2 = title="456", $3 = title, $4 = "456"
      preg_match_all("/(<\s*[^\/][^\s>]*\s+\w+[^>]+\s*>)/",$val,$match);
      foreach( $match[1] as $tag )
      {
         $match_pattern[1] = "/(<\w+|)\s+((\w+)\s*=\s*([\'\"][^\s]+[\'\"][\w]+))/ims";
         $replacement[1] = ' x';
         $match_pattern[2] = "/(<\w+|)\s+((?!".$allow_tag.")(\w+)\s*=\s*([\'\"].*?[\'\"][\w]*|\w*))/ims";
         $replacement[2] = '$1 xxx=$4';
         for($loop=1; $loop<=2; $loop++)
         {
            $new_tag = preg_replace($match_pattern[$loop], $replacement[$loop], $tag);
            if($tag != $new_tag)
            {
               $val = str_replace( $tag , $new_tag , $val );
               $tag = $new_tag;
            }
         }
      }
      return $val;
   }
   
   /**
     * delete all tag not in white list
     * @param   string   $val
     * @param   mixed    $ra
     *
     * @return string
     */   
   function del_white_list( $val , $ra )
   {
      // </tag>
      $match_pattern[1] = '/<\s+\/([^>]*)>/i';
      // <tag>
      $match_pattern[2] = '/<\s+([^>]*)>/i';
      // <tag />
      $match_pattern[3] = '/<\s+([^>]*)\/[\s]*>/i';
      // 將不標準的tag 修改後才可以用 strip_tags刪掉, ex <  Script > => <script>
      $replacement[1] = '</$1>';
      $replacement[2] = '<$1>';
      $replacement[3] = '<$1 />';
               
      for($loop=1; $loop<=3; $loop++)
         $val = preg_replace($match_pattern[$loop], $replacement[$loop], $val);
      
      $allow_tag = '<'.implode('>,<',$ra).'>';
      $val = strip_tags( $val , $allow_tag );
      // clear attr
      $allow_tag = implode('|', $ra);
      preg_match_all("/(<\s*[^\/][^\s>]*\s+\w+[^>]+\s*>)/",$val,$match);
      foreach( $match[1] as $tag )
      {
         $match_pattern[1] = "/(<\w+|)\s+((\w+)\s*=\s*([\'\"][^\s]+[\'\"][\w]+))/ims";
         $replacement[1] = ' x';
         $match_pattern[2] = "/(<\w+|)\s+((?!".$allow_tag.")(\w+)\s*=\s*([\'\"].*?[\'\"][\w]*|\w*))/ims";
         $replacement[2] = '$1 xxx=$4';
         for($loop=1; $loop<=2; $loop++)
         {
            $new_tag = preg_replace($match_pattern[$loop], $replacement[$loop], $tag);
            if($tag != $new_tag)
            {
               $val = str_replace( $tag , $new_tag , $val );
               $tag = $new_tag;
            }
         }
      }
      return $val;
   }

   
   /**
     * removexss, del tag, balance tag
     * @param   string   $val                
     * @param   string   $tag                allow_tag - 開放tag, 搭配黑名單list或白名單 , 可指定例外名單
     *                                       escape - htmlspecialchars, 搭配黑白名單list , 可指定例外名單 ( 尚未實做 )
     * @param   string   $filter             過濾方式( allow_tag的情況 )
     *                                       mark - 加上<x>
     *                                       del - 刪除<tag>~</tag>的內容 ( 黑名單的del目前暫無辦法清除巢狀tag )
     * @param   mixed    $black_list         黑名單   ['tag'], ['attr']
     * @param   mixed    $white_list         白名單   ['tag'], ['attr']
     * @param   mixed    $exception          例外名單 ['tag'], ['attr']
     */
   function string_filter( $val , $tag = 'escape' , $filter = 'del' , $black_list = array() , $white_list = array() , $exception = array() )
   {
      if( $tag == 'allow_tag' )
         return $this->removexss( $val , $filter , $black_list , $white_list , $exception );
      else
         return $this->html( $val , $black_list , $white_list , $exception );
   }
   
   function html( $val , $black_list , $white_list , $exception )
   {
      return htmlspecialchars( $val );
   }

   static function stringValidate($str,$type,$reg=null)
   {
      switch($type)
      {
        case 'username':
          $matches=array();
          preg_match('/^(\w+)$/',$str,$matches);
          if($matches)
            return true;
          break;

        case 'email':
          $matches=array();
          $reg='/^[^\s]+@[^\s]+\.[^\s]{2,3}$/';
          preg_match($reg,$str,$matches);
          if($matches)
            return true;
          break;
        
        case false:
          $matches=array();
          preg_match($reg,$str,$matches);
          if($matches)
            return true;
          break;
      
        default:
          return false;
        
      }
   }
}

// example fsprintf
// $sql = $filterString->fsprintf("insert into test.test1(f1,f2,start_date,end_date) values('%s','%s',NOW(),NOW())", "it's go good day","\\( ^ o ^ )/" );

//example string_filter
// black list
/*
$filterString->string_filter( 
                           $val , 
                           'allow_tag' , 
                           'mark' , 
                           array(
                                  'tag' => array('script','vbscript','iframe') , 
                                  'attr' => array('onclick','onload')
                                )     
                         );
*/
// black list + exception
/*
$filterString->string_filter( 
                           $val , 
                           'allow_tag' , 
                           'del' , 
                           array(
                                  'tag' => array('script','vbscript','iframe','ixmg','font') , 
                                  'attr' => array('onclick','onload','scipt')
                                ),
                           '' , 
                           array(
                                  'tag'=>array('okokoak','ifRame'),
                                  'attr'=>array('onxclick','onLoaD')
                                )
                         );
*/
// white list
/*
$filterString->string_filter( 
                           $val , 
                           'allow_tag' , 
                           'mark' , 
                           '' , 
                           array(
                                  'tag' => array('div','font','b','span','p') , 
                                  'attr' => array('onclxick')
                                )
                         );
*/
// white list for del all tag
/*
$filterString->string_filter( 
                           $val , 
                           'allow_tag' , 
                           'del' , 
                           '' , 
                           array(
                                  'tag' => array('div','font','b','span','p')
                                  'attr' => array('xxx');
                                )
                         );
*/
