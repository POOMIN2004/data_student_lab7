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
		if(isset($_GET['course_code']) && !empty($_GET['course_code'])){			
			$course_code = $_GET['course_code'];			
			$sql = "SELECT * FROM course WHERE course_code = '$course_code'";		
		}else{
			$sql = "SELECT * FROM course";
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
			$course_code = $result['course_code'];
			$course_name = $result['course_name'];
			$credit = $result['credit'];
			
			$sql = "INSERT INTO course (course_code,course_name,credit) VALUES ('$course_code','$course_name','$credit')";
			
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
			$course_name = $result['course_name'];
			$credit = $result['credit'];
			
			$sql = "UPDATE course SET course_name = '$course_name' , credit = '$credit' WHERE course_code = '$course_code'";

			$result = mysqli_query($link, $sql);
			
			if ($result) {
			   echo json_encode(['status' => 'ok','message' => 'Update Data Complete']);
			} else {
			   echo json_encode(['status' => 'error','message' => 'Error']);	
			}
		
	}
	if($requestMethod == 'DELETE'){
		if(isset($_GET['course_code']) && !empty($_GET['course_code'])){			
			$course_code = $_GET['course_code'];			
			$sql = "DELETE FROM course WHERE course_code = '$course_code'";
			$result = mysqli_query($link, $sql);			
			if ($result) {
			   echo json_encode(['status' => 'ok','message' => 'Delete Data ($course_code) Complete']);
			} else {
			   echo json_encode(['status' => 'error','message' => 'Error']);	
			}
		}	
	}
?>