<?php
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Product.php';

class QuizController {
    private $quizModel;
    
    public function __construct($pdo) {
        $this->quizModel = new Quiz($pdo);
    }
    
    public function showQuiz() {
        try {
            $questions = $this->quizModel->getQuestions();
            $pageTitle = "Find Your Perfect Scent - The Scent";
            require_once __DIR__ . '/../views/quiz.php';
        } catch (Exception $e) {
            error_log("Error loading quiz questions: " . $e->getMessage(), 3, ERROR_LOG_PATH . 'quiz_errors.log');
            throw $e;
        }
    }

    public function handleQuizSubmission() {
        try {
            // Validate and sanitize answers
            $answers = [];
            foreach ($_POST as $question => $answer) {
                if (strpos($question, 'q_') === 0) {
                    $questionId = substr($question, 2);
                    $answers[$questionId] = htmlspecialchars($answer);
                }
            }
            
            if (empty($answers)) {
                error_log("Quiz submitted with no answers", 3, ERROR_LOG_PATH . 'quiz_errors.log');
                header('Location: index.php?page=quiz&error=missing_answers');
                exit;
            }
            
            // Get personalized recommendations
            $recommendations = $this->quizModel->getRecommendations($answers);
            
            // Save quiz results if user is logged in
            $currentUser = getCurrentUser();
            if ($currentUser) {
                $this->quizModel->saveQuizResult($currentUser['id'], $answers, $recommendations);
            }
            
            $_SESSION['quiz_recommendations'] = $recommendations;
            header('Location: index.php?page=quiz_results');
            exit;
            
        } catch (Exception $e) {
            error_log("Error processing quiz submission: " . $e->getMessage(), 3, ERROR_LOG_PATH . 'quiz_errors.log');
            throw $e;
        }
    }
    
    public function getAnalytics() {
        try {
            if (!isAdmin()) {
                error_log("Unauthorized access attempt to quiz analytics", 3, ERROR_LOG_PATH . 'quiz_errors.log');
                http_response_code(403);
                return;
            }
            
            $statistics = $this->quizModel->getQuizStatistics();
            $preferences = $this->quizModel->getPopularPreferences();
            
            return [
                'statistics' => $statistics,
                'preferences' => $preferences
            ];
        } catch (Exception $e) {
            error_log("Error in quiz analytics: " . $e->getMessage(), 3, ERROR_LOG_PATH . 'quiz_errors.log');
            throw $e;
        }
    }
}