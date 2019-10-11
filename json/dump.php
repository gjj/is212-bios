***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!$errors) {
        $token = $_GET['token'];

        if (verify_token($token)) {
            $roundDAO = new RoundDAO();
            $currentRound = $roundDAO->getCurrentRound();

            $userDAO = new UserDAO();
            $users = $userDAO->retrieveAllStudents();

            $courseDAO = new CourseDAO();
            $courses = $courseDAO->retrieveAll();

            $sectionDAO = new SectionDAO();
            $sections = $sectionDAO->retrieveAll();
            
            $bidDAO = new BidDAO();
            $prerequisites = $bidDAO->retrieveAllPrerequisites();

            // Only the bid details for the current round should be shown in the bid records.
            // If the current round is round 2, list the last bid made by each user in each section.
            // If there is no active round, the bids (whether successful or unsuccessful)
            // for the most recently concluded round should be shown.
            // The system does not need to maintain a history of bidding results
            // from previous bidding rounds.
            if ($roundDAO->roundIsActive()) {
                $bids = $bidDAO->retrieveAllBids($currentRound['round']);
        ***REMOVED***
            else {
                $bids = $bidDAO->retrieveAllBids($currentRound['round'], true);
        ***REMOVED***
            $bidsSuccessful = $bidDAO->retrieveAllSuccessfulBids(0);
    ***REMOVED***
        else {
            $errors[] = "invalid token";
    ***REMOVED***
***REMOVED***
    
    if (!$errors) {
        $result = [
            "status" => "success",
            "course" => $courses,
            "section" => $sections,
            "student" => $users,
            "prerequisite" => $prerequisites,
            "bids" => $bids,
            "section-student" => $bidsSuccessful
        ];
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "messages" => $errors
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);