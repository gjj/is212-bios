<?php

require_once '../includes/common.php';

// $data is an array of each row values
function hasEmptyField($data){
    $columnpos_arr = [];
    for ($i = 0; $i <=count($data); $i++) {
        // Make sure that the key exists, isn't null or an empty string
        if (!isset($data[$i]) || $data[$i] == '') {
            $columnpos_arr[] = $i;
        }
    }

    return $columnpos_arr; // position of columns with missing values 
}
// incomplete
// function commonValidation($file){

//     $counter = 0;
//     $errors= [];
//     while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {        
//         $counter++;    
//         $columnpos_arr = hasEmptyField($data);
//         if (!empty($columnpos_arr)) {
//             foreach($columnpos as $columnpos_arr){

//             }
//              $errors[] = "Empty field is in row: $counter cell $column_error";
//         }


//     }

    // echo $errors;


// }

    function studentValidation($data){
        $userId = $data[0];
        $password = $data[1];
        $name = $data[2];
        $school = $data[3];
        $edollar = $data[4];

        $errors = [];
        $userDAO = new UserDAO();

        $result = True;

        if(strlen($userId)>128){
            $result = False;
            $error = "invalid userid";
            $errors[] = $error; 
        }
        if($userDAO -> retrieveById($userId) != null) {
            $result = False; 
            $error = "duplicate userid";
            $errors[] = $error; 
        }
        if(is_numeric($edollar) == False || $edollar < 0.0 || $edollar != round($edollar,2) ) {
            $result = False;
            $error = "invalid e-dollar";
            $errors[] = $error; 
        }
        if(strlen($password)>128){
            $result = False;
            $error = "invalid password";
            $errors[] = $error; 
        }
        if(strlen($name)>100){
            $result = False;
            $error = "invalid name";
            $errors[] = $error; 
        }

        // if error not null, delete row 
        if($result == False) {
            $sql="DELETE FROM users WHERE user_id = :userId";

            $connMgr = new ConnectionManager();
            $db = $connMgr->getConnection();

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
    
            $query->execute();
            $query->fetch(PDO::FETCH_ASSOC);
        }

        return $errors;   
    }

    
    function courseValidation($data){
        $course = $data[0];
        $school = $data[1];
        $title = $data[2];
        $description = $data[3];
        $examdate = $data[4];
        $examstart = $data[5];
        $examend = $data[6];

        $errors = [];
        $result = True; 
        if(strlen($title)>100){
            $result = False;
            $error = "invalid title";
            $errors[] = $error; 
        }
        $year = "";
        $month = "";
        $day = "";
        for($i=0;$i<strlen($examdate);$i++){
            if($i>=0 && $i<=3){
                $year .= $examdate[$i];
            }
            elseif($i>3 && $i<=5){
                $month .= $examdate[$i];
            }
            else{
                $day .= $examdate[$i];
            }
        }
        if(checkdate(intval($month),intval($day),intval($year))!= True){
            $error = "invalid exam date";
            $errors[] = $error; 
        }
        if(preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $examstart)!= True){
            $error = "invalid exam start";
            $errors[] = $error;
        }
        if(preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $examend)!= True
        || $examstart > $examend){
            $error = "invalid exam end";
            $errors[] = $error;
        }
        if(strlen($description)>1000){
            $error = "invalid description";
            $errors[] = $error;
        }

        
    }
?>