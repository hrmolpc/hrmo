<!DOCTYPE html>
<html>
<head>
    <title>Request Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        h1 {
            color: #4CAF50;
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
            background-color: #4CAF50;
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
        <h1>Congratulations! Your Request Has Been Approved</h1>
        
        <p>Dear Employee,</p>

        <p>We are thrilled to inform you that your request has been successfully approved. Our team has reviewed the details, and we are happy to proceed with the next steps.</p>

        <p>Here’s a quick overview of the approved request:</p>

        <ul>
            <li><strong>Request ID:</strong> {{ $request->id }}</li>
            <li><strong>Request Type:</strong> Certificate of Employment</li>
            <li><strong>Status:</strong> Approved</li>
            <li><strong>Date Submitted:</strong> {{ $request->created_at->format('F j, Y') }}</li>
        </ul>

        <p>You can expect to receive your Requested Documents in the Application. If you need to make any changes or have any further questions, please don’t hesitate to get in touch with us.</p>

        <p>Thank you again for your patience and for trusting us with your request. We strive to provide you with the best service possible, and we are here to assist you every step of the way.</p>

        <p>If you would like to view your request details, or if you need to take any further action, please click the button below:</p>

        <p>If you have any questions or need assistance, feel free to reply to this email or contact us directly at hrmolaspinas@gmail.com.</p>

        <p>We appreciate your trust in our services, and we look forward to serving you again in the future.</p>
    </div>

    <div class="footer">
        <p>Best regards,</p>
        <p>Your HR Team</p>
        <p><small>If you believe this email was sent to you by mistake, please contact us immediately.</small></p>
    </div>

</body>
</html>
