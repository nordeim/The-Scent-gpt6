<?php
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Product.php';

function showQuiz() {
    global $pdo;
    $quizModel = new Quiz($pdo);
    $questions = $quizModel->getQuestions();
    
    $pageTitle = "Find Your Perfect Scent - The Scent";
    require_once __DIR__ . '/../views/quiz.php';
}

function handleQuizSubmission() {
    global $pdo;
    $quizModel = new Quiz($pdo);
    
    // Validate and sanitize answers
    $answers = [];
    foreach ($_POST as $question => $answer) {
        if (strpos($question, 'q_') === 0) {
            $questionId = substr($question, 2);
            $answers[$questionId] = htmlspecialchars($answer);
        }
    }
    
    if (empty($answers)) {
        header('Location: index.php?page=quiz&error=missing_answers');
        exit;
    }
    
    // Get personalized recommendations
    $recommendations = $quizModel->getRecommendations($answers);
    
    // Save quiz results if user is logged in
    $currentUser = getCurrentUser();
    if ($currentUser) {
        $quizModel->saveQuizResult($currentUser['id'], $answers, $recommendations);
    }
    
    // Store recommendations in session for display
    $_SESSION['quiz_recommendations'] = $recommendations;
    
    // Redirect to results page
    header('Location: index.php?page=quiz_results');
    exit;
}

function showQuizResults() {
    // Check if recommendations exist in session
    if (!isset($_SESSION['quiz_recommendations'])) {
        header('Location: index.php?page=quiz');
        exit;
    }
    
    $recommendations = $_SESSION['quiz_recommendations'];
    
    // Clear recommendations from session after display
    unset($_SESSION['quiz_recommendations']);
    
    $pageTitle = "Your Perfect Scent Matches - The Scent";
    require_once __DIR__ . '/../views/quiz_results.php';
}

function getQuizStats() {
    if (!isAdmin()) {
        http_response_code(403);
        return;
    }
    
    global $pdo;
    $quizModel = new Quiz($pdo);
    
    // Get quiz completion statistics
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total_submissions,
            DATE(created_at) as submission_date,
            COUNT(DISTINCT user_id) as unique_users
        FROM quiz_results
        GROUP BY DATE(created_at)
        ORDER BY submission_date DESC
        LIMIT 30
    ");
    
    $stats = $stmt->fetchAll();
    
    $pageTitle = "Quiz Statistics - Admin";
    require_once __DIR__ . '/../views/admin/quiz_stats.php';
}