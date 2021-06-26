<?PHP
/************************************************************
http://codex.wiki/post/107878-295/
************************************************************/
class TagShortcutImage{
	var $str='';
	var $char;
	var $info;
	var $tmp_image_name;
	var $img_content;

	function __construct(){
		$this->char=array();
		$this->pos=array();
		$this->info=array('has_zh'=>false,'has_en'=>false,'count'=>array());
	}

	function parse(){
		$info = array();
		$boundary_width = 150;
		$zh_chr_size = 25;
		$zh_chr_width = 30;
		$en_chr_size = 10;
		$en_chr_width = 10;

		$reg_langs = array(LangEnum::zh=>'/[\x{4e00}-\x{9fa5}]/u',
				LangEnum::en=>'/[a-zA-Z]/',
				LangEnum::jp=>'/[\x{0800}-\x{4e00}]/u',
				LangEnum::ko=>'/[\x{3130}-\x{318F}]/u',
				LangEnum::num=>'/[\d]/u',
				LangEnum::fullwidth_num=>'/[\x{FE30}-\x{FFA0}]/u',
				LangEnum::space=>'/[\s]/u');

		$this->char = preg_split('//u',$this->str, -1, PREG_SPLIT_NO_EMPTY);
		
		$line=1;
		$group=0;
		$tmpchr = array();
		$_px=0; $_py=0; //prev char x,y
		$has_zh=false;$has_en=false;
		for($i=0;$i<count($this->char);$i++){
			$info[$i] = array();
			foreach($reg_langs as $key => $reg){
				if(preg_match($reg,$this->char[$i])){
					switch($key){
						case LangEnum::zh:
						case LangEnum::jp:
						case LangEnum::ko:
						case LangEnum::fullwidth_num:
							$has_zh=true;
							$this->info['has_zh']=true;
							$info[$i]['langtype'] = LangEnum::zh;
							$g=1;	//each zh, is individual group
							break;
						case LangEnum::en:
						case LangEnum::num:
							$has_en=true;
							$this->info['has_en']=true;
							$tmpchr[] = array('chr'=>$this->char[$i]);
							if($group==LangEnum::en) $g++;
							else $g=1;
							break;
						case LangEnum::space:
							$info[$i]['langtype'] = LangEnum::space;
							$g=1;
							break;
					}
					if($key==LangEnum::fullwidth_num){
						$info[$i]['lang'] = LangEnum::zh;
					}else{
						$info[$i]['lang'] = $key;
					}
					$this->info['count'][$key]++;
				}
			}
			//get prev pos
			if($i>0){
				$_pl = $info[$i-1]['langtype'];
				$_px = $info[$i-1]['x'];
				$_py = $info[$i-1]['y'];
			}

			//get a phase while stop at a space or a zh char
			if($info[$i]['langtype']!=LangEnum::en && $group==LangEnum::en){
				//if any en char
				if(count($tmpchr)>0){
					$num = count($info); //char length
					//if char not in the boundary(include a space), \r\n
					if($_px+(count($tmpchr)+1)*$en_chr_width > $boundary_width){
						$line++;
						$num=0;
					}
					if($line>2 && $has_zh){
						//has zh char, only can have 2 lines;
					}elseif($line>3){
						//paper can't hold over 3 lines
					}
					//leave a space & write char
					for($m=0;$m<count($tmpchr);$m++){
						$info[$num+$m]['x'] = $tmpchr[$m]['x'] + $en_chr_width;
						$info[$num+$m]['l'] = $line;
						$info[$num+$m]['langtype'] = LangEnum::en;
					}
					//reset
					$tmpchr = array();
				}					
			}
			$group = $info[$i]['langtype'];
			$info[$i]['group'] = $g;

			switch($group){
				case LangEnum::zh:
					if($_px + $zh_chr_width > $boundary_width){
						$info[$i]['x'] = $zh_chr_width;
						$info[$i]['l'] = ++$line;
					}else{
						$info[$i]['x'] = $_px + $zh_chr_width;
						$info[$i]['l'] = $line;
					}
					$info[$i]['size'] = $zh_chr_size;
					
					break;
				case LangEnum::en:
					$j = count($tmpchr)-1;
					$tmpchr[$j]['x'] = $_x + $en_chr_width;
					$tmpchr[$j]['y'] = $_y;
					$tmpchr[$j]['size'] = $en_chr_size;
					break;
				case LangEnum::space:
					if($_pl==LangEnum::zh){
						$info[$i]['x'] = $_px + $zh_chr_width;
						$info[$i]['size'] = $zh_chr_size;
					}else{
						$info[$i]['x'] = $_px + $en_chr_width;
						$info[$i]['size'] = $en_chr_size;
					}
					$info[$i]['y'] = $_py;
					break;
			}

		}
		
		if($has_zh && !$has_en){		//only zh
			$pos = $this->zh_pos_info(count($info));
			for($i=0;$i<count($info);$i++){
				$pos[$i][3] = $info[$i]['lang'];
			}
			$this->pos = $pos;
			return $pos;
		}elseif($has_zh && $has_en){	//zh,en mix
			switch($line){
				case 1:
					$line_height=70;
					break;
				case 2:
					$line_height=35;
					break;
				case 3:
					$line_height=25;
					break;
			}
			//upgrade char size if char num is under 6 by 1 line, 12 by 2 line, and has_zh
			if(($has_zh && $line==1 && count($info)<7) ||
					($has_zh && $line==2 && count($info)<13)){
				$upgrade_en_font_size=true;
			}
			//set pos y, by line_height
			$pos = array();
			for($i=0;$i<count($info);$i++){
				$info[$i]['y'] = $info[$i]['l'] * $line_height;
				if($upgrade_en_font_size && $info[$i]['langtype']==LangEnum::en){
					$info[$i]['size'] = $zh_chr_size;
				}
				$pos[$i][0] = $info[$i]['x'];
				$pos[$i][1] = $info[$i]['y'];
				$pos[$i][2] = $info[$i]['size'];
				$pos[$i][3] = $info[$i]['lang'];
			}
			$pos['date'] = array();
			$this->pos = $pos;
			return $pos;
		}
	}
	
	function zh_pos_info($len){
		if($len==0) return null;
		//set character position by len
		//x,y,size
		$pos = array(0=>array(array(80,70,60),
				'stamp1'=>array(180,70),
				'stamp2'=>array(200,70),
				'date'=>array(85,90,10)
			),//1
			array(0=>array(45,70,50),
				1=>array(105,70,50),
				'stamp1'=>array(180,70),
				'stamp2'=>array(200,70),
				'date'=>array(75,90,10)
			),//2
			array(0=>array(25,70,50),
				1=>array(85,70,50),
				2=>array(145,70,50),
				'stamp1'=>array(160,75),
				'stamp2'=>array(180,75),
				'date'=>array(60,90,10)
			),//3
			array(0=>array(15,55,40),
				1=>array(65,65,40),
				2=>array(115,55,40),
				3=>array(165,65,40),
				'stamp1'=>array(160,75),
				'stamp2'=>array(180,75),
				'date'=>array(60,90,10)
			),//4
			array(0=>array(15,60,30),
			  1=>array(55,60,30),
			  2=>array(95,60,30),
			  3=>array(135,60,30),
			  4=>array(175,60,30),
			  'stamp1'=>array(160,75),
			  'stamp2'=>array(180,75),
			  'date'=>array(60,90,10)
			),//5
			array(0=>array(20,50,25),
			  1=>array(50,50,25),
			  2=>array(80,50,25),
			  3=>array(110,50,25),
			  4=>array(140,50,25),
			  5=>array(170,50,25),
			  'stamp1'=>array(160,65),
			  'stamp2'=>array(180,65),
			  'date'=>array(60,80,10)
			),//6
			array(0=>array(30,60,50),
			  1=>array(100,30,20),
			  2=>array(130,30,20),
			  3=>array(160,30,20),
			  4=>array(110,55,20),
			  5=>array(140,55,20),
			  6=>array(170,55,20),
			  'stamp1'=>array(160,65),
			  'stamp2'=>array(180,65),
			  'date'=>array(60,80,10)
			),//7
			array(0=>array(30,35,25),
			  1=>array(75,35,25),
			  2=>array(120,35,25),
			  3=>array(165,35,25),
			  4=>array(30,65,25),
			  5=>array(75,65,25),
			  6=>array(120,65,25),
			  7=>array(165,65,25),
			  'stamp1'=>array(160,70),
			  'stamp2'=>array(180,75),
			  'date'=>array(70,90,10)
			),//8
			array(0=>array(30,35,25),
			  1=>array(65,35,25),
			  2=>array(100,35,25),
			  3=>array(135,35,25),
			  4=>array(170,35,25),
			  5=>array(50,65,25),
			  6=>array(85,65,25),
			  7=>array(120,65,25),
			  8=>array(155,65,25),
			  'stamp1'=>array(193,63),
			  'stamp2'=>array(180,75),
			  'date'=>array(70,90,10)
			),//9
			 array(0=>array(20,35,25),
			  1=>array(60,35,25),
			  2=>array(100,35,25),
			  3=>array(140,35,25),
			  4=>array(180,35,25),
			  5=>array(20,70,25),
			  6=>array(60,70,25),
			  7=>array(100,70,25),
			  8=>array(140,70,25),
			  9=>array(180,70,25),
			  'stamp1'=>array(160,70),
			  'stamp2'=>array(180,75),
			  'date'=>array(70,90,10)
			),//10
			 array(0=>array(20,35,25),
			  1=>array(50,35,25),
			  2=>array(80,35,25),
			  3=>array(110,35,25),
			  4=>array(140,35,25),
			  5=>array(170,35,25),
			  6=>array(35,70,25),
			  7=>array(65,70,25),
			  8=>array(95,70,25),
			  9=>array(125,70,25),
			  10=>array(155,70,25),
			  'stamp1'=>array(160,70),
			  'stamp2'=>array(180,75),
			  'date'=>array(70,90,10)
			),//11
			 array(0=>array(20,35,25),
			  1=>array(50,35,25),
			  2=>array(80,35,25),
			  3=>array(110,35,25),
			  4=>array(140,35,25),
			  5=>array(170,35,25),
			  6=>array(20,70,25),
			  7=>array(50,70,25),
			  8=>array(80,70,25),
			  9=>array(110,70,25),
			  10=>array(140,70,25),
			  11=>array(170,70,25),
			  'stamp1'=>array(160,70),
			  'stamp2'=>array(180,75),
			  'date'=>array(70,90,10)
			));//12
      return $pos[$len-1];
	}
	function setString($str){
		$this->str=urldecode($str);
		$this->parse();
	}
	function getChar(){
		return $this->char;
	}
	function getInfo(){
		return $this->info;
	}
	function getLunarDate(){
		$lunar = new Lunar();
		$yige_org_date=$lunar->convertSolarToLunar(date("Y"), date("m"), date("d"));
		//$yige_org_date=$lunar->convertSolarToLunar(2012, 5, 26);
		return $lunar->getLunarYearName(date("Y")).$yige_org_date[1].$yige_org_date[2];
	}
	function getDate(){
		return date('Y-m-d');
	}
	function getImageTicket(){
		return $this->_encode($this->tmp_image_name);
	}
	function getImage($ticket=''){
		header("Content-type:  image/gif");
		if(!empty($ticket)){
			if(strlen($ticket)<8) exit;
			$this->tmp_image_name = $this->_decode($ticket);
		}
		if(empty($this->img_content)){
			$gif = imagecreatefromgif($this->tmp_image_name);
			imageGIF($gif);
		}else{
			imageGIF($this->img_content);
		}
	}
	function getHtml($ticket=''){
		if(!empty($ticket)){
			if(strlen($ticket)<8) exit;
			$this->tmp_image_name = $this->_decode($ticket);
		}
		return file_get_contents($this->tmp_image_name);
	}
	function getImageTmpPath($ticket=''){
		if(!empty($ticket)){
			$this->tmp_image_name = $this->_decode($ticket);
			return $this->tmp_image_name;
		}
		if(!empty($this->tmp_image_name)){
			return $this->tmp_image_name;
		}
	}
	function getImageContent($ticket){
		if(!empty($ticket)){
			if(strlen($ticket)<8) exit;
			$this->tmp_image_name = $this->_decode($ticket);
			$gif = imagecreatefromgif($this->tmp_image_name);
			return $gif;
		}
		return null;
	}
	function Drow(){
		if(empty($this->str)) exit;

		$fonts = array(LangEnum::zh=>array('zh/zh001.ttf','zh/zh003.ttf'),
			LangEnum::en=>array('en/BAUHS93.TTF','en/CURLZ___.TTF','en/ITCBLKAD.TTF','en/RAGE.TTF'),
			LangEnum::jp=>array('jp/epgyobld.ttf','jp/epgyosho.ttf','jp/epkyouka.ttf','jp/Sword.ttf'),
			LangEnum::ko=>array());

		$_pos = $this->pos;//$pos[count($this->char)-1];

		$l = array();
		foreach($fonts as $index => $val){
			$n = count($val);
			$n = ($n==0)?0:$n-1;
			$l[$index] = mt_rand(0,$n);
		}

		$width = 230;
		$height = 100;
		$image = imageCreate($width, $height);
		$white = imageColorAllocate($image, 245, 245, 245);
		$black = imageColorAllocate($image, 0, 0, 0);
		foreach($this->char as $index => $chr){
			$font = $fonts[$_pos[$index][3]][$l[$_pos[$index][3]]];
			$_x = $_pos[$index][0];
			$_y = $_pos[$index][1];
			$_s = $_pos[$index][2];
			imagettftext($image, $_s, 0, $_x, $_y, $black, 'font/'.$font, $chr);
		}

		$stamp1= imagecreatefrompng('font/s_mzl.pzymghkf.png');
		$stamp2= imagecreatefrompng('font/s_ttii.png');
		imagecopymerge($image,$stamp1,$_pos['stamp1'][0],$_pos['stamp1'][1],0,0,15,15,80);
		imagecopymerge($image,$stamp2,$_pos['stamp2'][0],$_pos['stamp2'][1],0,0,15,15,90);

		if($this->info['count'][LangEnum::zh]>$this->info['count'][LangEnum::jp]){
			$d = $this->getLunarDate();
		}else{
			$d = $this->getDate();
		}
		imagettftext($image, $_pos['date'][2], 0, $_pos['date'][0], $_pos['date'][1], $black, 'font/'.$font, $d);
		
		$this->tmp_image_name = tempnam(sys_get_temp_dir(), 'tsi');
		imageGIF($image,$this->tmp_image_name);
		$this->img_content=$image;
		
	}
	function Html(){
		$bg = sprintf('bg%02u',mt_rand(1,9));
		$this->tmp_image_name = tempnam(sys_get_temp_dir(), 'tsh');
		$content = sprintf('<div class="shortcut_img %s"><div>%s</div></div>',$bg,$this->str);
		file_put_contents($this->tmp_image_name,$content);
	}
	function _encode($str){
		$str=base64_encode($str);
		$divide_pos = 2;
		$add_len = 5;
		$randstr = common::randString($add_len);
		$l = strlen($str);
		$str1 = substr($str,0,$divide_pos);
		$str2 = substr($str,$divide_pos,$l-1);
		return $str2.$randstr.$str1;
	}
	function _decode($str){
		$divide_pos = 2;
		$add_len = 5;
		$l = strlen($str);
		$str1 = substr($str,$l-$divide_pos,$l-1);
		$str2 = substr($str,0,$l-$divide_pos-$add_len-1);
		return base64_decode($str1.$str2);
	}
}
?>