<?php
	use Cake\View\View;

	switch ($store_data->company->company_keyword) {
		case 'jumbo':
			echo $this->element('stock_out_report_pdf_jumbo', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date, 'robot_session_id' => $robot_session->id]);
			break;

		case 'lider':
			echo $this->element('stock_out_report_pdf_lider', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date, 'robot_session_id' => $robot_session->id]);
			break;

		case 'homecenter':
			echo $this->element('stock_out_report_pdf_homecenter', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date, 'robot_session_id' => $robot_session->id]);
			break;
		
		default:
			echo $this->element('stock_out_report_pdf_homecenter', ['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date, 'robot_session_id' => $robot_session->id]);
			break;
	}
?>