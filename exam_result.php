<?php
	header("Access-Control-Allow-Origin: *");	
	header("Content-Type: application/json; charset=UTF-8");	
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");	
	header("Access-Control-Max-Age: 3600");	
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	$link = mysqli_connect('localhost', 'root', '', 'cake');
	mysqli_set_charset($link, 'utf8');	
	$requestMethod = $_SERVER["REQUEST_METHOD"];	
	if($requestMethod == 'GET'){					
		$course_code = $_GET['course_code'];	
		if(isset($_GET['student_code']) && !empty($_GET['student_code'])){			
			$student_code = $_GET['student_code'];		
			$sql = "SELECT * FROM exam_result WHERE student_code = '$student_code' AND course_code = '$course_code'";		
		}else{
			$sql = "SELECT * FROM exam_result where course_code = '$course_code'";
		}		
		$result = mysqli_query($link, $sql);		
		$arr = array();
				while ($row = mysqli_fetch_assoc($result)) {
			 
			 $arr[] = $row;
		}		
		echo json_encode($arr);
	}
	$data = file_get_contents("php://input");
	$result = json_decode($data,true);
	if($requestMethod == 'POST'){		
		if(!empty($result)){			
			$student_code = $result['student_code'];
			$course_code = $result['course_code'];
			$point = $result['point'];

			$sql = "INSERT INTO exam_result (student_code,course_code,point) VALUES ('$student_code','$course_code','$point')";
			
			$result = mysqli_query($link, $sql);
			
			if ($result) {
			   echo json_encode(['status' => 'ok','message' => 'Insert Data Complete']);
			} else {
			   echo json_encode(['status' => 'error','message' => 'Error']);	
			}
		}
			
	}
	if($requestMethod == 'PUT'){
		$course_code = $result['course_code'];
		$student_code = $result['student_code'];
		$point = $result['point'];
		
		$sql = "UPDATE exam_result SET point = '$point' WHERE student_code = '$student_code' AND course_code='$course_code'";

		$result = mysqli_query($link, $sql);
		
		if ($result) {
			echo json_encode(['status' => 'ok','message' => 'Update Data Complete']);
		} else {
			echo json_encode(['status' => 'error','message' => 'Error']);	
		}
	
	}
	if($requestMethod == 'DELETE'){
		$student_code = $_GET['student_code'];
		if(isset($_GET['student_code'])){					
			$sql = "DELETE FROM exam_result WHERE student_code = '$student_code'";
			$result = mysqli_query($link, $sql);			
			if ($result) {
			   echo json_encode(['status' => 'ok','message' => 'Delete Data ($student_code) Complete']);
			} else {
			   echo json_encode(['status' => 'error','message' => 'Error']);	
			}
		}	
	}
?>