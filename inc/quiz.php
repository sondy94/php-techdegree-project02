<?php

session_start();

//Variable determines whether a score is going to be shown or not.
$show_score = false;

include('questions.php');

//Variable with assignment of random index.
$index = rand(0, count($questions) - 1);

//Variable to hold current question.
$question = $questions[$index];

$answers   = [];
$answers[] = $question['correctAnswer'];
$answers[] = $question['firstIncorrectAnswer'];
$answers[] = $question['secondIncorrectAnswer'];
shuffle($answers);

//Toast message variable.
$toast = null;

/*
    If the server request was of type POST
        Check if the user's answer was equal to the correct answer.
        If it was correct:
            1. Assign a congratulatory string to the toast variable
            2. Increment the session variable that holds the total number correct by one.
        Otherwise:
            1. Assign a bummer message to the toast variable.
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['answer'])) {

        if ($_POST['answer'] == $questions[$_POST['index']]['correctAnswer']) {

            $toast = "Well Done! That's correct.";

            if (isset($_SESSION['totalCorrect'])) {

                $_SESSION['totalCorrect']++;

            }

        } else {

            $toast = "Bummer! That was incorrect.";

        }

    }


}

//Total number of questions to ask.
$totalQuestions = count($questions);


/*
    Checking if a session variable has been set/created to hold the indexes of questions already asked.
    If it has NOT:
        1. Create a session variable to hold used indexes and initialize it to an empty array.
        2. Set the show score variable to false.
*/
if (!isset($_SESSION['used_indexes'])) {

    $_SESSION['used_indexes'] = [];
    $_SESSION['totalCorrect'] = 0;

}

/*
  If the number of used indexes in our session variable is equal to the total number of questions
  to be asked:
        1.  Reset the session variable for used indexes to an empty array
        2.  Set the show score variable to true.

  Else:
    1. Set the show score variable to false
    2. If it's the first question of the round:
        a. Set a session variable that holds the total correct to 0.
        b. Set the toast variable to an empty string.
        c. Assign a random number to a variable to hold an index. Continue doing this
            for as long as the number generated is found in the session variable that holds used indexes.
        d. Add the random number generated to the used indexes session variable.
        e. Set the individual question variable to be a question from the questions array and use the index
            stored in the variable in step c as the index.
        f. Create a variable to hold the number of items in the session variable that holds used indexes
        g. Create a new variable that holds an array. The array should contain the correctAnswer,
            firstIncorrectAnswer, and secondIncorrect answer from the variable in step e.
        h. Shuffle the array from step g.
*/

if (count($_SESSION['used_indexes']) == $totalQuestions) {

    $_SESSION['used_indexes'] = [];
    $show_score = true;

} else {

    $show_score = false;

    if (count($_SESSION['used_indexes']) == 0) {

        $_SESSION['totalCorrect'] = 0;
        $toast = '';

    }

    do {

        $index    = rand(0, count($questions) - 1);

    } while (in_array($index, $_SESSION['used_indexes']));

    $question = $questions[$index];
    array_push($_SESSION['used_indexes'], $index);

    $answers   = [];
    $answers[] = $question['correctAnswer'];
    $answers[] = $question['firstIncorrectAnswer'];
    $answers[] = $question['secondIncorrectAnswer'];
    shuffle($answers);
}

//session_destroy();