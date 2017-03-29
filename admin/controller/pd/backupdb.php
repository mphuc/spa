<?php
class ControllerPdBackupdb extends Controller {
	public function index() {
		
		$this->document->setTitle('Backupdb');
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['backup_hungthinhphat'] = $this->url->link('pd/backupdb/backup_hungthinhphat&token=' . $this->session->data['token']);
		$data['backup_happymoney'] = $this->url->link('pd/backupdb/backup_happymoney&token=' . $this->session->data['token']);
		$data['backup_bitlegend'] = $this->url->link('pd/backupdb/backup_bitlegend&token=' . $this->session->data['token']);
		$data['backup_coinmax'] = $this->url->link('pd/backupdb/backup_coinmax&token=' . $this->session->data['token']);
		$data['backup_inter_wegroup_help'] = $this->url->link('pd/backupdb/backup_inter_wegroup_help&token=' . $this->session->data['token']);
		$data['backup_vn_wegroup_help'] = $this->url->link('pd/backupdb/backup_vn_wegroup_help&token=' . $this->session->data['token']);
		$data['backupdb_all'] = $this->url->link('pd/backupdb/backupdb_all&token='.$this->session->data['token']);
	$this->response->setOutput($this->load->view('pd/backupdb.tpl', $data));
	}

	
	public function check_otp($otp){
		require_once dirname(__FILE__) . '/vendor/autoload.php';
		$authenticator = new PHPGangsta_GoogleAuthenticator();
		$secret = "D6OTWYQ5EZC67KSA";
		$tolerance = "0";
		$checkResult = $authenticator->verifyCode($secret, $otp, $tolerance);    
		if ($checkResult) 
		{
		    return 1;
		    die();
		     
		} else {
		    return 2;
		    die();
		}

	}


	
	
	function backup_db($host,$user,$pass,$name,       $tables=false, $backup_name=false){ 
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
	    ob_get_clean(); 
	    header('Content-Type: application/octet-stream');   
	    header("Content-Transfer-Encoding: Binary"); 
	    header("Content-disposition: attachment; filename=\"".$backup_name."\"");
	    echo $content; exit;
	}    
}