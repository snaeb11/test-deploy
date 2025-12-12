<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'CTET-CTSuL Inventory System')</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                line-height: 1.6;
                color: #333333;
                background-color: #f5f5f5;
            }

            .email-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .header {
                background: linear-gradient(to bottom, #D56C6C, #C96262);
                padding: 30px 20px;
                text-align: center;
                color: white;
            }

            .header-logos {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 20px;
                margin-bottom: 20px;
            }

            .header-logo {
                width: 60px;
                height: 60px;
                background-color: white;
                border-radius: 50%;
                padding: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .header-logo img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            .header-title {
                font-size: 24px;
                font-weight: bold;
                margin: 0;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .header-subtitle {
                font-size: 14px;
                margin: 5px 0 0 0;
                opacity: 0.9;
            }

            .content {
                padding: 40px 30px;
            }

            .greeting {
                font-size: 18px;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 20px;
            }

            .message-content {
                font-size: 16px;
                line-height: 1.7;
                color: #444444;
                margin-bottom: 30px;
            }

            .info-box {
                background-color: #f8f9fa;
                border-left: 4px solid #D56C6C;
                padding: 20px;
                margin: 20px 0;
                border-radius: 0 8px 8px 0;
            }

            .info-box p {
                margin: 8px 0;
                font-size: 15px;
            }

            .info-box strong {
                color: #2c3e50;
            }

            .verification-code {
                background: linear-gradient(135deg, #D56C6C, #C96262);
                color: white;
                font-size: 32px;
                font-weight: bold;
                letter-spacing: 8px;
                text-align: center;
                padding: 20px;
                margin: 25px 0;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(213, 108, 108, 0.3);
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .action-button {
                text-align: center;
                margin: 30px 0;
            }

            .action-button a {
                background: linear-gradient(135deg, #D56C6C, #C96262);
                color: white;
                padding: 15px 30px;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                font-size: 16px;
                display: inline-block;
                box-shadow: 0 4px 12px rgba(213, 108, 108, 0.3);
                transition: all 0.3s ease;
            }

            .action-button a:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(213, 108, 108, 0.4);
            }

            .footer {
                background-color: #2c3e50;
                color: white;
                padding: 30px 20px;
                text-align: center;
            }

            .footer-content {
                margin-bottom: 20px;
            }

            .footer-title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .footer-subtitle {
                font-size: 14px;
                opacity: 0.8;
                margin-bottom: 15px;
            }

            .footer-contact {
                font-size: 13px;
                opacity: 0.7;
                line-height: 1.5;
            }

            .footer-divider {
                border: none;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                margin: 20px 0;
            }

            .footer-disclaimer {
                font-size: 12px;
                opacity: 0.6;
                font-style: italic;
            }

            .signature {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #eee;
                color: #666;
                font-size: 14px;
            }

            .signature-title {
                font-weight: 600;
                color: #2c3e50;
            }

            @media only screen and (max-width: 600px) {
                .email-container {
                    margin: 0;
                    border-radius: 0;
                }

                .header-logos {
                    flex-direction: column;
                    gap: 15px;
                }

                .header-logo {
                    width: 50px;
                    height: 50px;
                }

                .content {
                    padding: 30px 20px;
                }

                .verification-code {
                    font-size: 28px;
                    letter-spacing: 6px;
                    padding: 15px;
                }
            }
        </style>
    </head>

    <body>
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <h1 class="header-title">@yield('header-title', 'CTET-CTSuL Inventory System')</h1>
            </div>

            <!-- Content -->
            <div class="content">
                @yield('content')

                <!-- Signature -->
                <div class="signature">
                    <p class="signature-title">Best regards,</p>
                    <p>CTET - CTSuL Office<br>
                        College of Teacher Education and Technology<br>
                        University of Southeastern Philippines</p>
                </div>
            </div>
        </div>
    </body>

</html>
