<?php
class Quiz {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getQuestions() {
        $stmt = $this->pdo->query("SELECT * FROM quiz_questions ORDER BY order_num ASC");
        return $stmt->fetchAll();
    }
    
    public function getRecommendations($answers) {
        // Calculate user preferences based on answers
        $preferences = $this->calculatePreferences($answers);
        
        // Get matching products
        $stmt = $this->pdo->prepare("
            SELECT p.* 
            FROM products p
            JOIN product_attributes pa ON p.id = pa.product_id
            WHERE 
                (pa.scent_type = ? OR pa.scent_type = ?) AND
                (pa.mood_effect = ? OR pa.mood_effect = ?)
            GROUP BY p.id
            ORDER BY 
                CASE 
                    WHEN pa.scent_type = ? THEN 2
                    WHEN pa.scent_type = ? THEN 1
                END DESC,
                CASE 
                    WHEN pa.mood_effect = ? THEN 2
                    WHEN pa.mood_effect = ? THEN 1
                END DESC
            LIMIT 3
        ");
        
        $stmt->execute([
            $preferences['primary_scent'],
            $preferences['secondary_scent'],
            $preferences['primary_mood'],
            $preferences['secondary_mood'],
            $preferences['primary_scent'],
            $preferences['secondary_scent'],
            $preferences['primary_mood'],
            $preferences['secondary_mood']
        ]);
        
        return $stmt->fetchAll();
    }
    
    public function saveQuizResult($userId, $answers, $recommendations) {
        // Save quiz answers
        $stmt = $this->pdo->prepare("
            INSERT INTO quiz_results 
            (user_id, answers, recommendations, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        
        return $stmt->execute([
            $userId,
            json_encode($answers),
            json_encode($recommendations)
        ]);
    }
    
    private function calculatePreferences($answers) {
        // Initialize preference arrays
        $scentScores = [
            'floral' => 0,
            'woody' => 0,
            'citrus' => 0,
            'oriental' => 0,
            'fresh' => 0
        ];
        
        $moodScores = [
            'calming' => 0,
            'energizing' => 0,
            'focusing' => 0,
            'balancing' => 0
        ];
        
        // Map answers to preferences
        foreach ($answers as $question => $answer) {
            switch ($question) {
                case 'preferred_scents':
                    $scentScores[$answer] += 2;
                    break;
                    
                case 'environment':
                    if ($answer == 'outdoors') {
                        $scentScores['fresh'] += 1;
                        $scentScores['citrus'] += 1;
                    } else {
                        $scentScores['woody'] += 1;
                        $scentScores['oriental'] += 1;
                    }
                    break;
                    
                case 'mood_goal':
                    $moodScores[$answer] += 2;
                    break;
                    
                case 'daily_routine':
                    if ($answer == 'morning') {
                        $moodScores['energizing'] += 1;
                    } else {
                        $moodScores['calming'] += 1;
                    }
                    break;
            }
        }
        
        // Get top preferences
        arsort($scentScores);
        arsort($moodScores);
        
        $scentTypes = array_keys($scentScores);
        $moodEffects = array_keys($moodScores);
        
        return [
            'primary_scent' => $scentTypes[0],
            'secondary_scent' => $scentTypes[1],
            'primary_mood' => $moodEffects[0],
            'secondary_mood' => $moodEffects[1]
        ];
    }
}