<?php
class ControllerPdPin extends Controller {
	public function index() {

		$this->document->setTitle('Pin');
		$this->load->model('sale/customer');
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;

		$ts_history = $this -> model_sale_customer -> get_count_code();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/pin', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		$data['load_pin_date'] = $this -> url -> link('pd/pin/load_pin_date&token='.$this->session->data['token']);
		$data['pin'] =  $this-> model_sale_customer->get_all_code($limit, $start);
		$data['pagination'] = $pagination -> render();
		$data['getaccount'] = $this->url->link('pd/ph/getaccount&token='.$this->session->data['token'], '', 'SSL');
		$data['token'] = $this->session->data['token'];
		$data['load_pin_username'] = $this -> url -> link('pd/pin/load_pin_username&token='.$this->session->data['token']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/pin.tpl', $data));
	}

	public function load_pin_date()
	{
		$date = date('Y-m-d',strtotime($this -> request ->post['date']));
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_pin_date($date);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
				<td><?php echo $value['username'] ?></td>
				<td><?php echo $value['input_address'] ?></td>
		        <td><?php echo $value['pin'] ?></td>
		        <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Waiting</span>" : "<span class='label label-success'>Delivered</span>" ?></td>
		       
				<td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
				
			</tr>
	               
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">No data</td> </tr>
		<?php
		}
	}
	public function load_pin_username()
	{
		$username = $this -> request ->post['username'];
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_pin_username($username);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
				<td><?php echo $value['username'] ?></td>
				<td><?php echo $value['input_address'] ?></td>
		        <td><?php echo $value['pin'] ?></td>
		        <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Waiting</span>" : "<span class='label label-success'>Delivered</span>" ?></td>
		       
				<td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
				
			</tr>
	               
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">No data</td> </tr>
		<?php
		}
	}
	public function pin_tranfer() {

		$this->document->setTitle('Pin');
		$this->load->model('sale/customer');
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;

		$ts_history = $this -> model_sale_customer -> get_history_pin();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/pin/pin_tranfer', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		$data['getaccount'] = $this->url->link('pd/ph/getaccount&token='.$this->session->data['token'], '', 'SSL');
		$data['load_pinhistory_username'] = $this -> url -> link('pd/pin/load_pinhistory_username&token='.$this->session->data['token']);
		$data['load_pinhistory_date'] = $this -> url -> link('pd/pin/load_pinhistory_date&token='.$this->session->data['token']);
		$data['pin'] =  $this-> model_sale_customer->get_all_ping_history($limit, $start);
		$data['pagination'] = $pagination -> render();
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/pin_tranfer.tpl', $data));
	}
	public function load_pinhistory_date(){
		$date = date('Y-m-d',strtotime($this -> request ->post['date']));
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_pinhistory_date($date);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
	            <td><?php echo $value['username'] ?></td>
	            <td><?php echo $value['amount'] ?></td>
	            <td><?php echo date('d/m/Y H:i:s',strtotime($value['date_added'])) ?></td>
	            <td><?php echo $value['type'] ?></td>
	            <td><?php echo $value['system_description'] ?></td>
				
			</tr>
	               
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">No data</td> </tr>
		<?php
		}
	}
	
	public function load_pinhistory_username(){
		$username = $this -> request ->post['username'];
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_pinhistory_username($username);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
	            <td><?php echo $value['username'] ?></td>
	            <td><?php echo $value['amount'] ?></td>
	            <td><?php echo date('d/m/Y H:i:s',strtotime($value['date_added'])) ?></td>
	            <td><?php echo $value['type'] ?></td>
	            <td><?php echo $value['system_description'] ?></td>
				
			</tr>
	               
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">No data</td> </tr>
		<?php
		}
	}

	public function export_pin()
	{
		$date = date('Y-m-d');
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_pin_date($date);
		!count($load_pin_date) > 0 && die('no data!');
		if (count($load_pin_date) > 0)
		{
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');
			require_once dirname(__FILE__) . '/PHPExcel.php';
			
			

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
			->setCellValue('C1', 'Wallet')
			->setCellValue('D1', 'Account')
			->setCellValue('E1', 'Status')
			->setCellValue('F1', 'Date');
	         $objPHPExcel->getActiveSheet()->getStyle('A1:F1')
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
	        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$h=0;
			$n = 2;
			$i=0;
		foreach ($load_pin_date as $customer) {
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$n,$i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$n," ".$customer['username']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$n,$customer['input_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$n," ".$customer['pin']);
			if ($customer['confirmations'] == 3) $status = "Paid";
			if ($customer['confirmations'] != 3) $status = "Unpaid";
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$n," ".$status);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,date('d/m/Y H:i',strtotime($customer['date_created'])));
			$n++;
			}
		

		$objPHPExcel->getActiveSheet()->getStyle('A'.$n.':'.'F'.$n)
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
}
