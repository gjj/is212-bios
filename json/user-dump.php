***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('r'),
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!isEmpty($errors)) {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
***REMOVED***
    else {
        $request = $_GET['r'];
        $token = $_GET['token'];

        if (verify_token($token)) {

            $requestJson = json_decode($request);

            $jsonError = json_last_error();

            if ($jsonError) {
                $errors = ["Unable to process request parameter: " . $jsonError];
                $result = [
                    "status" => "error",
                    "message" => array_values($errors)
                ];
        ***REMOVED***
            else {
                // Check my JSON request for my compulsory fields.
                $errors = [
                    isMissingOrEmptyJson('userid', $requestJson)
                ];

                $errors = array_filter($errors);

                if (!isEmpty($errors)) {
                    $result = [
                        "status" => "error",
                        "message" => array_values($errors)
                    ];
            ***REMOVED***
                else {
                    $userDAO = new UserDAO();
                    $user = $userDAO->retrieveStudentById($requestJson->userid);
                    
                    if ($user) {
                        $result = [
                            "status" => "success"
                        ];
                        $result = array_merge($result, $user);
                ***REMOVED***
                    else {
                        $errors = ["Invalid user ID."];
                        $result = [
                            "status" => "error",
                            "message" => array_values($errors)
                        ];
                ***REMOVED***
            ***REMOVED***
        ***REMOVED***
    ***REMOVED***
        else {
            $errors = ["Unauthorised access."];
            $result = [
                "status" => "error",
                "message" => array_values($errors)
            ];
    ***REMOVED***
***REMOVED***

    echo json_encode($result, JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);