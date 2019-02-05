<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require("base_controller.php");

class connect extends base_controller {

	public function reflex(){

        @session_start();
        $this->load->library('session');
        $ci_session_id = $this->session->userdata('session_id');

		$dataOut = array();
		$dataOut["HEADERS"] = getallheaders();
        $dataOut["HOST"] = actual_host();
        $dataOut["ORIGIN"] = getValidHeaderOrigin(); 
		$dataOut["METHOD"] = @$_SERVER['REQUEST_METHOD'];
		$dataOut["REQUEST"] = $_REQUEST;
		$dataOut["GET"] = $_GET;
		$dataOut["POST"] = $_POST;
		$dataOut["FILES"] = $_FILES;
		$dataOut["IP_ADDRESS"] = getenv("REMOTE_ADDR");
		$dataOut["timestamp"] = time();
		$dataOut["CI_SESSION_ID"] = $ci_session_id;
		$dataOut["PHP_SESSION_ID"] = session_id();
		$dataOut["raw_body"] = file_get_contents("php://input");
		
		resGood($dataOut);
	}


	

	public function test_json(){

		$json_text = safe_post("json_text");
		$dataOut = array();
		$dataOut["json_text"] = $json_text;
		$dataOut["data_array"] = json_decode($json_text,true);
		resGood($dataOut);

	}


	public function json(){

		$dataJson = '{
	"Reserve hotel": "จองโรงแรม",
	"Flights": "จองตั๋ว",
	"Tours": "จองทัวร์",
	"Wallet": "กระเป๋าตังค์",
	"Setting": "การตั้งค่า",
	"Booking History": "ประวัติการจอง",
	"My Wallet": "กระเป๋าตังค์ของฉัน",
	"Logout": "ออกจากระบบ",
	"Wallet": "กระเป๋าตังค์",
	"Language": "เปลี่ยนภาษา",
	"Currency": "เปลี่ยนสกุลเงิน",
	"Country": "ประเทศ",
	"Wallet": "กระเป๋าตังค์",
	"I need a place tonight!": "ค้นหาโรงแรมสำหรับคืนนี้!",
	"Destination": "จุดหมายปลายทาง",
	"Check-In": "เช็คอิน",
	"Check out": "เช็คเอาท์",
	"Check-in Date": "เลือกวันที่เช็คอิน",
	"Check-out Date": "เลือกวันที่เช็คเอาท์",
	"Rooms": "ห้อง",
	"Adult": "ผู้ใหญ่",
	"Children": "เด็ก",
	"Search": "ค้นหาโรงแรม",
	"Check out": "เช็คเอาท์",
	"City, Hotels, Landmark or address": "เมือง,โรงแรม,สถานที่สำคัญ",
	"Recent Searches": "ค้นหาเมื่อเร็วๆนี้",
	"Check out": "เช็คเอาท์",
	"City": "เมือง",
	"Search near by area": "โรงแรมที่มีในพื้นที่",
	"found": "แห่ง",
	"Use current location": "ใช้ตำแหน่งที่ตั้งของคุณในปัจจุบัน",
	"Destination": "เลือกจุดหมายปลายทาง",
	"Check out": "เช็คเอาท์",
	"Sort": "เรียงผล",
	"Filter": "ตัวกรอง",
	"Map": "แผนที่",
	"Check out": "เช็คเอาท์",
	"Excellent": "ดีมาก",
	"Good": "ดี",
	"Fair": "พอใช้",
	"BAHT/NIGHT": "บาท/คืน",
	"Rooms Type": "ประเภทห้อง",
	"Detail": "รายละเอียด",
	"Policy": "นโยบาย",
	"Guests": "เข้าพัก",
	"Sleeps": "จำนวนผู้เข้าพักสูงสุด",
	"Nearby public transportation": "อยู่ใกล้ขนส่งสาธาราณะ",
	"Near Airport": "อยู่ใกล้สนามบิน",
	"Facilites": "สิ่งอำนวยความสะดวก",
	"Fair": "พอใช้",
	"Terms and Conditions": "นโยบายการจองเเละการเข้าพัก",
	"Enter Your Details": "กรอกข้อมูลของท่าน",
	"Name": "ชื่อ",
	"Contact Name": "ชื่อผู้จอง",
	"Last Name": "นามสกุล",
	"Last Name": "นามสกุลผู้จอง",
	"Email": "อีเมล์",
	"Email Address": "อีเมล์ผู้จอง",
	"Phone number": "หมายเลขโทรศัพท์",
	"Cancellation": "การยกเลิกการจอง",
	"Cancel": "ยกเลิกการจอง",
	"Date": "วันที่",
	"Booking fees": "มีค่าธรรมเนียม",
	"From": "ตั้งแต่วันที่",
	"To": "ถึง",
	"Before": "ก่อนวันที่",
	"Free!": "ฟรี!",
	"Price details": "รายละเอียดราคา",
	"BAHT": "บาท",
	"Total": "รวม",
	"Continue": "ดำเนินการต่อ",
	"Reviews": "ความคิดเห็นจากผู้เข้าพัก",
	"January": "มกราคม",
	"February": "กุมภาพันธ์",
	"March": "มีนาคม",
	"April": "เมษายน",
	"May": "พฤษภาคม",
	"June": "มิถุนายน",
	"July": "กรกฎาคม",
	"August": "สิงหาคม",
	"September": "กันยายน",
	"October": "ตุลาคม",
	"November": "พฤษจิกายน",
	"December": "ธันวาคม"
}';

$dataArray = json_decode($dataJson,true);
//resGood($dataArray);
		
	$newDataArray = array();
	$count = 0;
	$json_text = "{";
	foreach ( $dataArray as $key => $value) {
		if($count>0){
			$json_text .= ",";
		}
		$json_text .= '"'.$value.'":"'.$key.'"';
		$count++;
	}
	$json_text .= "}";
	echo $json_text;

	//echo json_encode($newDataArray);


	}


	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */