<!DOCTYPE html>
<html>
<head>
    <title>Request Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        h1 {
            color: #FF5722;
            font-size: 28px;
        }
        .content {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            line-height: 1.6;
        }
        .button {
            background-color: #FF5722;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 18px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <div class="content">
        <h1>We Regret to Inform You: Your Request Has Been Rejected</h1>
        
        <p>Dear Employee,</p>

        <p>We are sorry to inform you that after reviewing your request, it has been rejected. Unfortunately, we are unable to proceed with your request for the Certificate of Employment at this time.</p>

        <p>Here is a summary of your request:</p>

        <ul>
            <li><strong>Request ID:</strong> {{ $request->id }}</li>
            <li><strong>Request Type:</strong> Certificate of Employment</li>
            <li><strong>Status:</strong> Rejected</li>
            <li><strong>Date Submitted:</strong> {{ $request->created_at->format('F j, Y') }}</li>
        </ul>

        <p>We understand that this may be disappointing, and we apologize for any inconvenience this may cause. If you believe there is a mistake or if you would like to appeal this decision, please don't hesitate to get in touch with us. We are happy to provide further clarification or discuss possible next steps.</p>

        <p>If you would like to review your request details or have any questions, please click the login your account.</p>

        <p>If you have any questions or need assistance, feel free to reply to this email or contact us directly at hrmolaspinas@gmail.com.</p>

        <p>We appreciate your understanding, and we hope to assist you with any future requests.</p>
    </div>

    <div class="footer">
        <p>Best regards,</p>
        <p>Your HR Team</p>
        <p><small>If you believe this email was sent to you by mistake, please contact us immediately.</small></p>
    </div>

</body>
</html>
