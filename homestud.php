<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online  System</title>
    <style>
        li {
            margin: 1.5vw;
            font-size: 1.5vw !important;
        }

        ul {
            list-style: none;
            width: auto !important;
            font-weight: 2vw !important;
        }

        .navbar {
            font-size: 1.5vw !important;
            background-color: #829ab1;
        }

        .navbar>ul>li:hover {
            color: #042A38;
            text-decoration: underline;
            font-weight: bold;
            cursor: default;
        }

        .navbar>ul>li>a:hover {
            color: #042A38;
            text-decoration: underline;
            font-weight: bold !important;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .prof, #score {
            top: 3vw;
            position: fixed;
            width: 50vw !important;
            margin-left: 25vw !important;
            margin-right: 25vw !important;
            background-color: #fff !important;
            display: none !important;
            border-radius: 10px;
            margin-top: 2vw;
            z-index: 1;
            padding: 1vw;
            padding-left: 2vw;
            color: #042A38;
        }

        @media screen and (max-width: 450px) {
            .navbar {
                display: initial !important;
            }

            .navbar>ul {
                display: initial !important;
                left: 25vw !important;
                text-align: center;
                right: 25vw !important;
            }

            .navbar>ul>li {
                background-color: orange !important;
            }

            section {
                text-align: center;
                margin-top: 0 !important;
                background-color: orange !important;
                width: 100vw;
                margin: 0 !important;
            }

            p {
                color: #042A38 !important;
            }
        }

        table {
            width: 90vw;
            margin-left: 5vw;
            margin-right: 5vw;
            align-content: center;
            border: 1px solid black;
        }

        thead {
            font-weight: 900;
            font-size: 1.5vw;
        }

        td {
            width: auto;
            border: 1px solid black;
            text-align: center;
            height: 4vw;
            font-weight: bold;
        }

        #tq {
            text-decoration: underline;
            border: 3px solid #fff;
            padding: 0.5vw;
            border-radius: 10px;
        }

        #sc {
            width: 100% !important;
            margin: 0%;
            color: #042A38;
        }

        #le {
            margin-bottom: 2vw;
        }
    </style>
</head>
<body style="color: #fff !important; font-weight: bolder; margin: 0 !important; font-family: 'Courier New', Courier, monospace;">
    <div style="background-color: #042A38; height: auto;">
        <div class="navbar" style="display: inline-flex; width: 100%; color: black; position: fixed;">
            <section style="margin: 1.5vw;">ONLINE EXAMINATION SYSTEM</section>
            <ul style="display: inline-flex; padding: 0 !important; margin: 0; float: right; right: 0; position: fixed; width: 50vw;">
                <li onclick="prof()">Profile</li>
                <li onclick="score()">Score</li>
                <li onclick="lo()">Sign Out</li>
            </ul>
        </div><br><br>
        <?php
        session_start();
        require_once 'sql.php';
        $conn = mysqli_connect($host, $user, $ps, $project);
        if (!$conn) {
            echo "<script>alert('Database error retry after some time!')</script>";
        }

        $type1 = $_SESSION["type"];
        $username1 = $_SESSION["username"];
        $sql = "SELECT * FROM " . $type1 . " WHERE mail='{$username1}'";
        $res = mysqli_query($conn, $sql);
        if ($res == true) {
            global $dbmail, $dbpw;
            while ($row = mysqli_fetch_array($res)) {
                $dbmail = $row['mail'];
                $dbname = $row['name'];
                $dbusn = $row['usn'];
                $dbphno = $row['phno'];
                $dbgender = $row['gender'];
                $dbdob = $row['DOB'];
                $dbdept = $row['dept'];
            }
        }
        ?>
        <center>
            <section style="width: 100vw; margin: 0; margin-top: 5vw; font-size: 3vw;">
                Welcome to Online Examination System&nbsp;<?php echo $dbname ?>
            </section>
        </center>
        <section style="color: #fff !important"><br><br><br><br><br>
            <?php 
            $sql = "SELECT * FROM quiz";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                echo "<center><h1 style='font-size: 2vw;'>Take any Quiz</h1></center>";
                echo "<center><table><thead><tr><td>Quiz Title</td><td>Created on</td><td>Created By</td><td>  </td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {                
                    echo "<tr><td>".$row["quizname"]."</td><td>".$row["date_created"]."</td><td>".$row["mail"]."</td><td><a id='tq' href='takeq.php?qid=".$row['quizid']."'>Take Quiz</a></td></tr>"; 
                }
                echo "</table></center>";
            }
            ?>
        </section>
        <section class="prof" id="prof" style="display: none; color: #042A38;">
            <p><b>Type of User&nbsp;:&nbsp;<?php echo $type1 ?></b></p>
            <p><b>NAME&nbsp;:&nbsp;<?php echo $dbname ?></b></p>
            <p><b>EMAIL&nbsp;:&nbsp;<?php echo $dbmail ?></b></p>
            <p><b>USN&nbsp;:&nbsp;<?php echo $dbusn ?></b></p>
            <p><b>DEPARTMENT&nbsp;:&nbsp;<?php echo $dbdept ?></b></p>
            <p><b>PHONE NO&nbsp;:&nbsp;<?php echo $dbphno ?></b></p>
            <p><b>GENDER&nbsp;:&nbsp;<?php echo $dbgender ?></b></p>
            <p><b>DATE OF BIRTH&nbsp;:&nbsp;<?php echo $dbdob ?></b></p>
        </section>
        <section id="score" style="display:none;">
            <?php 
            $sql = "SELECT * FROM score
                    JOIN quiz ON score.quizid = quiz.quizid
                    WHERE score.mail = '{$username1}'";
            $res = mysqli_query($conn, $sql);
            
            if ($res) {
                echo "<h1>Scoreboard</h1>";
                echo "<table id='sc'><thead><tr><td>Quiz Title</td><td>Score Obtained</td><td>Total Score</td><td>Remarks</td><td>Download</td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {
                    $remarks = $row['score'] >= $row['totalscore'] / 2 ? 'Pass' : 'Fail';
                    echo "<tr>
                            <td>{$row['quizname']}</td>
                            <td>{$row['score']}</td>
                            <td>{$row['totalscore']}</td>
                            <td>{$remarks}</td>
                            <td><a href='download_score.php?quizid={$row['quizid']}&mail={$username1}'>Download</a></td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No scores available.</p>";
            }
            ?>
        </section>
    </div>

    <script>
        function prof() {
            document.getElementById('prof').style.display = 'block';
            document.getElementById('score').style.display = 'none';
        }

        function score() {
            document.getElementById('score').style.display = 'block';
            document.getElementById('prof').style.display = 'none';
        }

        function lo() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>

