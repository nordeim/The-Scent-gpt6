<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class EmailService {
    private $mailer;
    private $pdo;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();

        global $pdo;
        $this->pdo = $pdo;
    }

    private function setupMailer() {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USER;
            $this->mailer->Password = SMTP_PASS;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = SMTP_PORT;
            $this->mailer->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $this->mailer->isHTML(true);
        } catch (Exception $e) {
            error_log("Mailer setup error: " . $e->getMessage());
            throw new Exception("Failed to configure email service");
        }
    }

    private function logEmail($userId, $emailType, $recipientEmail, $subject, $status, $errorMessage = null) {
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
        } catch (Exception $e) {
            error_log("Email logging error: " . $e->getMessage());
        }
    }

    public function sendOrderConfirmation($order, $user) {
        if (!isset($user['email']) || !isset($order['id'])) {
            throw new InvalidArgumentException("Invalid order or user data for confirmation email");
        }

        try {
            ob_start();
            require __DIR__ . '/../views/emails/order_confirmation.php';
            $emailContent = ob_get_clean();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user['email'], $user['name']);
            $this->mailer->Subject = 'Order Confirmation #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
            $this->mailer->Body = $emailContent;

            $success = $this->mailer->send();
            $this->logEmail(
                $user['id'],
                'order_confirmation',
                $user['email'],
                'Order Confirmation #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT),
                $success ? 'sent' : 'failed'
            );

            return $success;
        } catch (Exception $e) {
            $this->logEmail(
                $user['id'],
                'order_confirmation',
                $user['email'],
                'Order Confirmation #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT),
                'failed',
                $e->getMessage()
            );
            error_log("Failed to send order confirmation: " . $e->getMessage());
            throw new Exception("Failed to send order confirmation email");
        }
    }

    public function sendPasswordReset($user, $token) {
        if (!isset($user['email']) || !isset($user['name'])) {
            throw new InvalidArgumentException("Invalid user data for password reset email");
        }

        try {
            $resetLink = BASE_URL . "index.php?page=reset-password&token=" . urlencode($token);

            ob_start();
            $name = $user['name'];
            require __DIR__ . '/../views/emails/password_reset.php';
            $emailContent = ob_get_clean();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user['email'], $user['name']);
            $this->mailer->Subject = 'Reset Your Password - The Scent';
            $this->mailer->Body = $emailContent;
            $this->mailer->AltBody = "Reset your password by clicking this link: " . $resetLink;

            $success = $this->mailer->send();
            $this->logEmail(
                $user['id'] ?? null,
                'password_reset',
                $user['email'],
                'Reset Your Password - The Scent',
                $success ? 'sent' : 'failed'
            );

            return $success;
        } catch (Exception $e) {
            $this->logEmail(
                $user['id'] ?? null,
                'password_reset',
                $user['email'],
                'Reset Your Password - The Scent',
                'failed',
                $e->getMessage()
            );
            error_log("Failed to send password reset email: " . $e->getMessage());
            throw new Exception("Failed to send password reset email");
        }
    }

    public function sendPasswordResetEmail($to, $data) {
        try {
            $mail = $this->createMailer();
            $mail->addAddress($to);
            
            $mail->Subject = 'Reset Your Password - The Scent';
            
            // Get email template content
            ob_start();
            extract($data);
            require __DIR__ . '/../views/emails/password_reset.php';
            $body = ob_get_clean();
            
            $mail->Body = $body;
            $mail->AltBody = "Reset your password by clicking this link: " . $data['resetLink'];
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("Failed to send password reset email: " . $e->getMessage());
            return false;
        }
    }

    public function sendNewsletter($email, $content) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);
            $this->mailer->Subject = 'Welcome to The Scent Newsletter';
            $this->mailer->Body = $content;

            $success = $this->mailer->send();
            $this->logEmail(
                null,
                'newsletter',
                $email,
                'Welcome to The Scent Newsletter',
                $success ? 'sent' : 'failed'
            );

            return $success;
        } catch (Exception $e) {
            $this->logEmail(
                null,
                'newsletter',
                $email,
                'Welcome to The Scent Newsletter',
                'failed',
                $e->getMessage()
            );
            return false;
        }
    }

    public function sendShippingUpdate($order, $user, $status) {
        if (!isset($user['email']) || !isset($order['id'])) {
            throw new InvalidArgumentException("Invalid order or user data for shipping update");
        }

        try {
            ob_start();
            require __DIR__ . '/../views/emails/shipping_update.php';
            $emailContent = ob_get_clean();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user['email'], $user['name']);
            $this->mailer->Subject = 'Shipping Update - Order #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
            $this->mailer->Body = $emailContent;

            $success = $this->mailer->send();
            $this->logEmail(
                $user['id'],
                'shipping_update',
                $user['email'],
                'Shipping Update - Order #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT),
                $success ? 'sent' : 'failed'
            );

            return $success;
        } catch (Exception $e) {
            $this->logEmail(
                $user['id'],
                'shipping_update',
                $user['email'],
                'Shipping Update - Order #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT),
                'failed',
                $e->getMessage()
            );
            error_log("Failed to send shipping update: " . $e->getMessage());
            throw new Exception("Failed to send shipping update email");
        }
    }

    private function getOrderConfirmationTemplate($order, $user) {
        ob_start();
        include __DIR__ . '/../views/emails/order_confirmation.php';
        return ob_get_clean();
    }

    private function getPasswordResetTemplate($name, $resetLink) {
        ob_start();
        include __DIR__ . '/../views/emails/password_reset.php';
        return ob_get_clean();
    }

    private function getShippingUpdateTemplate($order, $trackingNumber, $carrier) {
        ob_start();
        include __DIR__ . '/../views/emails/shipping_update.php';
        return ob_get_clean();
    }
}