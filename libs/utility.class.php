<?PHP
class {
		public static function arr2define($arr){
			$str = '';
			foreach($arr as $key=>$val){
				$str .= printf("define('%s','%s');\r\n",$key,$val);
			}
			return $str;
		}
}
?>