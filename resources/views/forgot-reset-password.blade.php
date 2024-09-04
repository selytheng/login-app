<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #96e6a1, #d4fc79);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 28px;
            color: #28a745;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus, input[type="password"]:focus, input[type="text"]:focus {
            outline: none;
            border-color: #28a745;
        }

        input.error {
            border-color: #dc3545;
        }

        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 30px;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        #responseMessage {
            color: #dc3545;
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-link {
            margin-top: 15px;
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .signup-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                max-width: 320px;
            }

            form {
                padding: 20px;
            }

            input[type="email"], input[type="password"], input[type="text"] {
                font-size: 14px;
            }

            button {
                font-size: 14px;
                padding: 10px;
            }
        }

        /* Spinner Styles */
        .spinner {
            display: none;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #28a745;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 10px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        button.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        .back-link {
            margin-top: 15px;
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        #responseMessage, #responseMessageOtp, #responseMessageReset{
            color: #dc3545;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 id="formTitle">Forgot Password</h1>

        <div id="spinner" class="spinner"></div>

        <!-- Forgot Password Form -->
        <form id="forgotPasswordForm">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <button type="submit">Send OTP</button>
            <div id="responseMessage"></div>
            <a href="/signin" class="back-link">Back</a>
        </form>

        <!-- OTP Verification Form -->
        <form id="verifyOtpForm" style="display: none;">
            <input type="text" id="otp" name="otp" placeholder="OTP" required>
            <button type="submit">Verify OTP</button>
            <div id="responseMessageOtp"></div>
            <a id="backToForgotPassword" class="back-link">Back</a>
        </form>

        <!-- Reset Password Form -->
        <form id="resetPasswordForm" style="display: none;">
            <input type="password" id="password" name="password" placeholder="New Password" required>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
            <div id="responseMessageReset"></div>
        </form>
    </div>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            showSpinner();

            const email = document.getElementById('email').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/api/auth/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ email }),
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('forgotPasswordForm').style.display = 'none';
                    document.getElementById('verifyOtpForm').style.display = 'block';
                    document.getElementById('formTitle').textContent = 'Verify OTP';
                } else {
                    document.getElementById('responseMessage').textContent = data.message;
                }
            } catch (error) {
                console.error('Error:', error);
            }
            hideSpinner();
        });

        document.getElementById('verifyOtpForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            showSpinner();
            const email = document.getElementById('email').value;
            const otp = document.getElementById('otp').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/api/auth/verify-reset-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ email, otp }),
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('verifyOtpForm').style.display = 'none';
                    document.getElementById('resetPasswordForm').style.display = 'block';
                    document.getElementById('formTitle').textContent = 'Reset Password';
                    document.getElementById('otp').classList.remove('error'); // Remove error class if it was there
                } else {
                    document.getElementById('responseMessageOtp').textContent = data.message;
                    document.getElementById('otp').classList.add('error'); // Add error class
                    hideSpinner();
                }
            } catch (error) {
                console.error('Error:', error);
            }
            hideSpinner();
        });

        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            showSpinner();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const responseMessage = document.getElementById('responseMessageReset');

            // Reset error classes
            document.getElementById('password').classList.remove('error');
            document.getElementById('password_confirmation').classList.remove('error');

            //check the password input
            if (password.length < 8 || password_confirmation.length < 8) {
                responseMessage.textContent = 'The password must be at least 8 digits';
                document.getElementById('password').classList.add('error');
                document.getElementById('password_confirmation').classList.add('error');
                hideSpinner();
                return;
            }

            if (password !== password_confirmation) {
                responseMessage.textContent = 'Confirm Password must be the same';
                document.getElementById('password').classList.add('error');
                document.getElementById('password_confirmation').classList.add('error');
                hideSpinner();
                return;
            }

            try {
                const response = await fetch('/api/auth/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ email, password, password_confirmation }),
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = '/signin'; // Redirect to login page
                } else {
                    responseMessage.textContent = data.message;
                    hideSpinner();
                }
            } catch (error) {
                console.error('Error:', error);
            }
            hideSpinner();
        });

        //back to first form
        document.getElementById('backToForgotPassword').addEventListener('click', function() {
            document.getElementById('verifyOtpForm').style.display = 'none';
            document.getElementById('forgotPasswordForm').style.display = 'block';
            document.getElementById('formTitle').textContent = 'Forgot Password';
            document.getElementById('responseMessage').textContent = '';
            document.getElementById('email').value = '';
        });

        // Function to show and hide spinner
        function showSpinner() {
            document.getElementById('spinner').style.display = 'block';
        }

        function hideSpinner() {
            document.getElementById('spinner').style.display = 'none';
        }
    </script>
</body>
</html>
