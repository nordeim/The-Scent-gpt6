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
        try {
            $this->pdo->beginTransaction();
            
            // Save quiz answers
            $stmt = $this->pdo->prepare("
                INSERT INTO quiz_results 
                (user_id, answers, recommendations, created_at)
                VALUES (?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $userId,
                json_encode($answers),
                json_encode($recommendations)
            ]);
            
            // Update user preferences table for faster lookups
            $this->updateUserPreferences($userId, $answers);
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error saving quiz result: " . $e->getMessage());
            return false;
        }
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

    private function updateUserPreferences($userId, $answers) {
        $preferences = $this->calculatePreferences($answers);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO user_preferences 
            (user_id, scent_type, mood_effect, preference_score, last_updated)
            VALUES (?, ?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE
            preference_score = preference_score + 1,
            last_updated = NOW()
        ");
        
        foreach ($preferences as $pref) {
            $stmt->execute([
                $userId,
                $pref['scent_type'],
                $pref['mood_effect']
            ]);
        }
    }

    public function getUserPreferenceHistory($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                scent_type,
                mood_effect,
                COUNT(*) as frequency,
                MAX(qr.created_at) as last_selected
            FROM quiz_results qr
            JOIN product_attributes pa ON FIND_IN_SET(pa.product_id, qr.recommended_product_ids)
            WHERE qr.user_id = ?
            GROUP BY scent_type, mood_effect
            ORDER BY frequency DESC, last_selected DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getPersonalizedRecommendations($userId, $limit = 5) {
        // Get user's top preferences
        $stmt = $this->pdo->prepare("
            WITH user_preferences AS (
                SELECT 
                    pa.scent_type,
                    pa.mood_effect,
                    COUNT(*) as preference_score
                FROM quiz_results qr
                JOIN product_attributes pa ON FIND_IN_SET(pa.product_id, qr.recommended_product_ids)
                WHERE qr.user_id = ?
                GROUP BY pa.scent_type, pa.mood_effect
            )
            SELECT DISTINCT p.*
            FROM products p
            JOIN product_attributes pa ON p.id = pa.product_id
            LEFT JOIN user_preferences up 
                ON pa.scent_type = up.scent_type 
                AND pa.mood_effect = up.mood_effect
            WHERE p.stock_quantity > 0
            ORDER BY 
                CASE WHEN up.preference_score IS NOT NULL 
                    THEN up.preference_score 
                    ELSE 0 
                END DESC,
                p.is_featured DESC,
                p.created_at DESC
            LIMIT ?
        ");
        
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public function getQuizStatistics() {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total_quizzes,
                COUNT(DISTINCT user_id) as unique_users,
                DATE(created_at) as quiz_date
            FROM quiz_results 
            GROUP BY DATE(created_at)
            ORDER BY quiz_date DESC
        ");
        return $stmt->fetchAll();
    }

    public function getPopularPreferences() {
        $stmt = $this->pdo->query("
            SELECT 
                JSON_EXTRACT(answers, '$.preferred_scents') as scent_preference,
                JSON_EXTRACT(answers, '$.mood_goal') as mood_preference,
                COUNT(*) as preference_count
            FROM quiz_results
            GROUP BY scent_preference, mood_preference
            ORDER BY preference_count DESC
        ");
        return $stmt->fetchAll();
    }

    public function getDetailedAnalytics($timeRange = 'all') {
        $whereClause = '';
        if ($timeRange !== 'all') {
            $whereClause = "WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
        }
        
        // Get basic statistics
        $stats = $this->getBasicStatistics($whereClause, $timeRange);
        
        // Get scent type preferences
        $scentTypes = $this->getScentTypeDistribution($whereClause, $timeRange);
        
        // Get mood effect preferences
        $moodEffects = $this->getMoodEffectDistribution($whereClause, $timeRange);
        
        // Get daily completions
        $dailyCompletions = $this->getDailyCompletions($whereClause, $timeRange);
        
        // Get top recommended products
        $recommendations = $this->getTopRecommendedProducts($whereClause, $timeRange);
        
        return [
            'statistics' => $stats,
            'preferences' => [
                'scent_types' => $scentTypes,
                'mood_effects' => $moodEffects,
                'daily_completions' => $dailyCompletions
            ],
            'recommendations' => $recommendations
        ];
    }
    
    private function getBasicStatistics($whereClause, $timeRange) {
        $sql = "
            SELECT 
                COUNT(*) as total_quizzes,
                COUNT(DISTINCT user_id) as unique_users,
                AVG(completion_time) as avg_completion_time,
                COUNT(CASE WHEN led_to_purchase = 1 THEN 1 END) * 100.0 / COUNT(*) as conversion_rate
            FROM quiz_results
            $whereClause
        ";
        
        $stmt = $timeRange === 'all' 
            ? $this->pdo->query($sql)
            : $this->pdo->prepare($sql);
            
        if ($timeRange !== 'all') {
            $stmt->execute([$timeRange]);
        }
        
        return $stmt->fetch();
    }
    
    private function getScentTypeDistribution($whereClause, $timeRange) {
        $sql = "
            SELECT 
                pa.scent_type as type,
                COUNT(*) as count
            FROM quiz_results qr
            JOIN product_attributes pa ON FIND_IN_SET(pa.product_id, qr.recommended_product_ids)
            $whereClause
            GROUP BY pa.scent_type
            ORDER BY count DESC
        ";
        
        $stmt = $timeRange === 'all'
            ? $this->pdo->query($sql)
            : $this->pdo->prepare($sql);
            
        if ($timeRange !== 'all') {
            $stmt->execute([$timeRange]);
        }
        
        return $stmt->fetchAll();
    }
    
    private function getMoodEffectDistribution($whereClause, $timeRange) {
        $sql = "
            SELECT 
                pa.mood_effect as effect,
                COUNT(*) as count
            FROM quiz_results qr
            JOIN product_attributes pa ON FIND_IN_SET(pa.product_id, qr.recommended_product_ids)
            $whereClause
            GROUP BY pa.mood_effect
            ORDER BY count DESC
        ";
        
        $stmt = $timeRange === 'all'
            ? $this->pdo->query($sql)
            : $this->pdo->prepare($sql);
            
        if ($timeRange !== 'all') {
            $stmt->execute([$timeRange]);
        }
        
        return $stmt->fetchAll();
    }
    
    private function getDailyCompletions($whereClause, $timeRange) {
        $sql = "
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as count
            FROM quiz_results
            $whereClause
            GROUP BY DATE(created_at)
            ORDER BY date DESC
            LIMIT 30
        ";
        
        $stmt = $timeRange === 'all'
            ? $this->pdo->query($sql)
            : $this->pdo->prepare($sql);
            
        if ($timeRange !== 'all') {
            $stmt->execute([$timeRange]);
        }
        
        return $stmt->fetchAll();
    }
    
    private function getTopRecommendedProducts($whereClause, $timeRange) {
        $sql = "
            WITH recommended_counts AS (
                SELECT 
                    p.id,
                    p.name,
                    c.name as category,
                    COUNT(*) as recommendation_count,
                    COUNT(CASE WHEN o.id IS NOT NULL THEN 1 END) * 100.0 / COUNT(*) as conversion_rate
                FROM quiz_results qr
                JOIN products p ON FIND_IN_SET(p.id, qr.recommended_product_ids)
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN orders o ON qr.user_id = o.user_id 
                    AND o.created_at >= qr.created_at
                    AND o.created_at <= DATE_ADD(qr.created_at, INTERVAL 30 DAY)
                $whereClause
                GROUP BY p.id, p.name, c.name
            )
            SELECT *
            FROM recommended_counts
            ORDER BY recommendation_count DESC
            LIMIT 10
        ";
        
        $stmt = $timeRange === 'all'
            ? $this->pdo->query($sql)
            : $this->pdo->prepare($sql);
            
        if ($timeRange !== 'all') {
            $stmt->execute([$timeRange]);
        }
        
        return $stmt->fetchAll();
    }
}