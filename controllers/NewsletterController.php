<?php
require_once __DIR__ . '/../includes/EmailService.php';

class NewsletterController {
    private $pdo;
    private $emailService;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->emailService = new EmailService();
    }

    public function subscribe($email) {
        try {
            // Check if already subscribed
            $stmt = $this->pdo->prepare("
                SELECT id FROM newsletter_subscribers 
                WHERE email = ?
            ");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return [
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.'
                ];
            }

            // Add to subscribers
            $stmt = $this->pdo->prepare("
                INSERT INTO newsletter_subscribers (email, status)
                VALUES (?, 'active')
            ");
            $stmt->execute([$email]);

            // Send welcome email
            $content = $this->getWelcomeEmailContent();
            $this->emailService->sendNewsletter($email, $content);

            // Log the email
            $this->logEmail(
                null, 
                'newsletter_welcome',
                $email,
                'Welcome to The Scent Newsletter',
                'sent'
            );

            return [
                'success' => true,
                'message' => 'Thank you for subscribing to our newsletter!'
            ];
        } catch (Exception $e) {
            error_log("Newsletter subscription error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while processing your subscription.'
            ];
        }
    }

    public function unsubscribe($email, $token) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE newsletter_subscribers 
                SET status = 'unsubscribed',
                    unsubscribed_at = CURRENT_TIMESTAMP
                WHERE email = ? 
                AND MD5(CONCAT(email, 'unsubscribe-salt')) = ?
            ");
            $stmt->execute([$email, $token]);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'You have been successfully unsubscribed.'
                ];
            }

            return [
                'success' => false,
                'message' => 'Invalid unsubscribe request.'
            ];
        } catch (Exception $e) {
            error_log("Newsletter unsubscribe error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ];
        }
    }

    public function logEmail($userId, $emailType, $recipientEmail, $subject, $status, $errorMessage = null) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO email_log 
                (user_id, email_type, recipient_email, subject, status, error_message)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                $emailType,
                $recipientEmail,
                $subject,
                $status,
                $errorMessage
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Email logging error: " . $e->getMessage());
            return false;
        }
    }

    private function getWelcomeEmailContent() {
        ob_start();
        include __DIR__ . '/../views/emails/newsletter_welcome.php';
        return ob_get_clean();
    }
}