<?php
require_once 'sql.php';
$conn = mysqli_connect($host, $user, $ps, $project);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quizid'])) {
    $quizid = $_POST['quizid'];

    // Fetch quiz details and score information
    $sql = "SELECT quiz.quizname, score.score, score.totalscore, score.remark, users.name, users.mail
            FROM score
            JOIN quiz ON score.quizid = quiz.quizid
            JOIN users ON score.mail = users.mail
            WHERE score.quizid = ? AND score.mail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quizid, $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Create a marksheet content
        $marksheetContent = "Quiz Name: " . $row['quizname'] . "\n";
        $marksheetContent .= "Student Name: " . $row['name'] . "\n";
        $marksheetContent .= "Email: " . $row['mail'] . "\n";
        $marksheetContent .= "Score: " . $row['score'] . "\n";
        $marksheetContent .= "Total Score: " . $row['totalscore'] . "\n";
        $marksheetContent .= "Remarks: " . $row['remark'] . "\n";
        
        // Set headers to force download
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="marksheet.txt"');
        
        // Output marksheet content
        echo $marksheetContent;
    } else {
        echo "No data found for the specified quiz.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
