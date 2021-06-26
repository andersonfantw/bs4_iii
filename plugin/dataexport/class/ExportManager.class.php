<?PHP
/**********************************************************************************
referance: http://krasimirtsonev.com/blog/article/php-export-mysql-data-to-xls-file
***********************************************************************************/

require_once(LIBS_PATH.'/PHPExcel/PHPExcel.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Reader/Excel2007.php');
require_once(LIBS_PATH.'/PHPExcel/PHPExcel/Writer/Excel5.php');
class ExportManager{
	var $objPHPExcel;

	function __construct(){}

	public function _downloadHeader($filename) {
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=\"".$filename.".xls\"");
		header("Content-Type: text/plain");
		header("Pragma: no-cache");
		header("Expires: 0");
	}

  private function _cleanData($str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    return $str;
  }

	protected function xlsBOF($filename) {
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=\"".$filename.".xls\"");
		header("Content-Transfer-Encoding: binary");
		header("Pragma: no-cache");
		header("Expires: 0");
		//echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("L-NET");
		$this->objPHPExcel->getProperties()->setLastModifiedBy("L-NET");
		//$this->objPHPExcel->getProperties()->setTitle("Office 2003 XLS");
		//$this->objPHPExcel->getProperties()->setSubject("Office 2003 XLS");
		//$this->objPHPExcel->getProperties()->setDescription("Office 2003 XLS");
		$this->objPHPExcel->setActiveSheetIndex(0);
		$this->objPHPExcel->getActiveSheet()->freezePane('A1');
		$this->objPHPExcel->getActiveSheet()->freezePane('B1');
	}
	protected function xlsEOF() {
		//echo pack("ss", 0x0A, 0x00);
		$objWriter = new PHPExcel_Writer_Excel5($this->objPHPExcel);
		$objWriter->save('php://output');
	}
	protected function xlsWriteNumber($Row, $Col, $Value, $arrStyle=null) {
		//echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
		//echo pack("d", $Value);
		$cell = $this->_getExcelCeilIndex($Col, $Row);
		$this->objPHPExcel->getActiveSheet()->SetCellValue($cell, $Value);
		$this->objPHPExcel->getActiveSheet()->getCell($cell)->setValueExplicit($Value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		if(!empty($arrStyle)){
			$this->objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($arrStyle);
		}
	}
	protected function xlsWriteLabel($Row, $Col, $Value, $arrStyle=null) {
		//$L = strlen($Value);
		//echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		//echo $Value;
		$cell = $this->_getExcelCeilIndex($Col, $Row);
		$this->objPHPExcel->getActiveSheet()->SetCellValue($cell, $Value);
		if(!empty($arrStyle)){
			$this->objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($arrStyle);
		}
	}
	protected function xlsWriteMultiLine($Row, $Col, $Value, $arrStyle=null) {
		//$L = strlen($Value);
		//echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		//echo $Value;
		$cell = $this->_getExcelCeilIndex($Col, $Row);
		$this->objPHPExcel->getActiveSheet()->SetCellValue($cell, $Value);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension($this->_getExcelCeilIndex($Col))->setWidth('50');
		$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
		if(!empty($arrStyle)){
			$this->objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($arrStyle);
		}

	}
	protected function xlsWriteURL($Row, $Col, $Value, $arrStyle=null) {
		//$L = strlen($Value);
		//echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		//echo $Value;
		$cell = $this->_getExcelCeilIndex($Col, $Row);
		$this->objPHPExcel->getActiveSheet()->SetCellValue($cell, $Value);
		$this->objPHPExcel->getActiveSheet()->getCell($cell)->getHyperlink()->setUrl($Value);
		if(!empty($arrStyle)){
			$this->objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($arrStyle);
		}
	}
	protected function xlsWriteDate($Row, $Col, $Value, $arrStyle=null) {
		//$L = strlen($Value);
		//echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		//echo $Value;
		$cell = $this->_getExcelCeilIndex($Col, $Row);
		$this->objPHPExcel->getActiveSheet()->setCellValue($cell, $Value);
		$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		if(!empty($arrStyle)){
			$this->objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($arrStyle);
		}
	}
	protected function xlsWriteTimestamp($Row, $Col, $Value, $arrStyle=null) {
		//$L = strlen($Value);
		//echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		//echo $Value;
		$cell = $this->_getExcelCeilIndex($Col, $Row);
		$this->objPHPExcel->getActiveSheet()->setCellValue($cell, PHPExcel_Shared_Date::PHPToExcel($Value));
		$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		if(!empty($arrStyle)){
			$this->objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($arrStyle);
		}
	}
	
	private function _getExcelCeilIndex($col,$row=0) {
		if($col > 0 )
		{
			$str     = "ZABCDEFGHIJKLMNOPQRSTUVWXY";
			$col_str = "";
			do
			{
				$col_tmp  = $col % 26;
				$col      = $col_tmp == 0 ? intval($col / 26) - 1 : intval($col / 26);
				$col_str  = $str[$col_tmp].$col_str;
			}while( $col );
			if($row>0) return  $col_str.$row;
			else return $col_str;
		}
		return false;
	}
}
?>
