<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password - The Scent</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Your Password</h1>
        </div>
        
        <div class="content">
            <p>Hello <?= $userName ?>,</p>
            
            <p>We received a request to reset your password for your account at The Scent. 
               If you didn't make this request, you can safely ignore this email.</p>
            
            <p>To reset your password, click the button below:</p>
            
            <p style="text-align: center;">
                <a href="<?= $resetLink ?>" class="button">Reset Password</a>
            </p>
            
            <p>Or copy and paste this link into your browser:</p>
            <p><?= $resetLink ?></p>
            
            <p>This link will expire in 1 hour for security reasons.</p>
            
            <p>Best regards,<br>The Scent Team</p>
        </div>
        
        <div class="footer">
            <p>If you need any assistance, please contact our customer support.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>