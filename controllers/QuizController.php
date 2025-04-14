<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Product.php';

class QuizController extends BaseController {
    private $quizModel;
    
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->quizModel = new Quiz($pdo);
    }
    
    public function showQuiz() {
        try {
            $questions = $this->quizModel->getQuestions();
            require_once __DIR__ . '/../views/quiz.php';
        } catch (Exception $e) {
            error_log("Error loading quiz questions: " . $e->getMessage());
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Failed to load quiz questions'], 500);
            } else {
                $this->setFlashMessage('Failed to load quiz questions. Please try again.', 'error');
                $this->redirect('home');
            }
        }
    }

    public function handleQuizSubmission() {
        try {
            $this->validateCSRF();
            
            // Validate and sanitize answers
            $answers = [];
            foreach ($_POST as $question => $answer) {
                if (strpos($question, 'q_') === 0) {
                    $questionId = substr($question, 2);
                    $answers[$questionId] = $this->validateInput($answer, 'string');
                }
            }
            
            if (empty($answers)) {
                $this->setFlashMessage('Please answer all questions to get your recommendations.', 'error');
                $this->redirect('quiz', ['error' => 'missing_answers']);
            }
            
            $this->beginTransaction();
            
            // Get personalized recommendations
            $recommendations = $this->quizModel->getRecommendations($answers);
            
            // Save quiz results if user is logged in
            $currentUser = $this->getCurrentUser();
            if ($currentUser) {
                $this->quizModel->saveQuizResult($currentUser['id'], $answers, $recommendations);
            }
            
            $this->commit();
            
            $_SESSION['quiz_recommendations'] = $recommendations;
            
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => true,
                    'recommendations' => $recommendations
                ]);
            } else {
                $this->redirect('quiz_results');
            }
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("Error processing quiz submission: " . $e->getMessage());
            
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Failed to process quiz submission'], 500);
            } else {
                $this->setFlashMessage('An error occurred. Please try again.', 'error');
                $this->redirect('quiz');
            }
        }
    }
    
    public function showResults() {
        if (!isset($_SESSION['quiz_recommendations'])) {
            $this->redirect('quiz');
        }
        
        $recommendations = $_SESSION['quiz_recommendations'];
        require_once __DIR__ . '/../views/quiz_results.php';
        
        // Clear recommendations after showing them
        unset($_SESSION['quiz_recommendations']);
    }
    
    public function getAnalytics() {
        try {
            $this->requireAdmin();
            $this->validateAjax();
            
            $statistics = $this->quizModel->getQuizStatistics();
            $preferences = $this->quizModel->getPopularPreferences();
            
            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'statistics' => $statistics,
                    'preferences' => $preferences
                ]
            ]);
            
        } catch (Exception $e) {
            error_log("Error in quiz analytics: " . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to retrieve quiz analytics'
            ], 500);
        }
    }
    
    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    public function getQuizHistory() {
        $this->requireLogin();
        
        try {
            $userId = $this->getUserId();
            $history = $this->quizModel->getUserQuizHistory($userId);
            
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => true,
                    'data' => $history
                ]);
            } else {
                return $history;
            }
            
        } catch (Exception $e) {
            error_log("Error retrieving quiz history: " . $e->getMessage());
            
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Failed to retrieve quiz history'
                ], 500);
            } else {
                throw $e;
            }
        }
    }
}