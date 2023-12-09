<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user submitted an answer
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if an answer is selected
    if (!isset($_POST['answer'])) {
        $feedback = "Please select an answer.";
    } else {
        // Retrieve user's selected answer and question ID from the form submission
        $selectedAnswer = $_POST['answer'];
        $questionId = $_POST['question_id'];

        // Retrieve the correct answer from the database
        $sql = "SELECT correct_option FROM questions WHERE id = $questionId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $correctAnswer = $row['correct_option'];

            // Check if the selected answer is correct
            $isCorrect = ($selectedAnswer == $correctAnswer);

            // Display feedback to the user
            if ($isCorrect) {
                $feedback = "Correct! Well done!";
            } else {
                $feedback = "Incorrect. The correct answer is option $correctAnswer.";
            }
        } else {
            $feedback = "Error retrieving question.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
        }

        .quiz-container {
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .question {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .feedback {
            color: #d9534f;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="quiz-container">
    <h1>Quiz App</h1>

    <?php if (isset($feedback)): ?>
        <p class="feedback"><?php echo $feedback; ?></p>
    <?php endif; ?>

    <?php
    // Retrieve questions from the database
    $sql = "SELECT * FROM questions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $questions = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($questions as $question):
            ?>
            <div class="question">
                <p><?php echo $question['question_text']; ?></p>
                <form action="" method="post">
                    <?php foreach (range(1, 4) as $optionNumber): ?>
                        <label>
                            <input type="radio" name="answer" value="<?php echo $optionNumber; ?>">
                            <?php echo $question['option' . $optionNumber]; ?>
                        </label>
                    <?php endforeach; ?>
                    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                    <button type="submit">Next</button>
                </form>
            </div>
        <?php
        endforeach;
    } else {
        echo "No questions found.";
    }
    ?>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
