<?php
	use Cake\View\View;

	switch ($store_data->company->company_keyword) {
		case 'jumbo':
			echo $this->element('price_difference_report_pdf_jumbo', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date]);
			break;

		case 'lider':
			echo $this->element('price_difference_report_pdf_lider', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date]);
			break;

		case 'homecenter':
			echo $this->element('price_difference_report_pdf_homecenter', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date]);
			break;
		
		default:
			echo $this->element('price_difference_report_pdf_homecenter', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date]);
			break;
	}
	
?>