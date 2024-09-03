<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
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

        @media (max-width: 480px) {
            .container {
                max-width: 320px;
            }

            form {
                padding: 20px;
            }

            input[type="text"], input[type="email"], input[type="password"] {
                font-size: 14px;
            }

            button {
                font-size: 14px;
                padding: 10px;
            }
        }

        /* Popup Styles */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
            border-radius: 30px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px; 
            text-align: center;
            border-radius: 10px;
            animation: slide-down 0.5s ease;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 30px;
            margin-top: 10px;
        }

        .modal-button:hover {
            background-color: #218838;
        }
        
        .back-link {
            margin-top: 15px;
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @keyframes slide-down {
            from {
                transform: translateY(-20%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal.show {
            display: block;
            opacity: 1;
        }

        #otpForm {
            display: none;
        }

        #otp-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 25px;
            height: 50px;
            font-size: 24px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease;
        }

        .otp-input:focus {
            outline: none;
            border-color: #28a745;
        }

        .otp-input.error {
            border-color: #dc3545;
        }
        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: fadeInOut 3s ease-in-out;
        }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            10%, 90% { opacity: 1; }
        }
        .resendotp-link {
            margin-top: 15px;
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .resendotp-link:hover {
            text-decoration: underline;
        }

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
    </style>
</head>
<body>
    <div class="container">
        <h1 id="header1">Register</h1>

        <div id="spinner" class="spinner"></div>

        <form id="registerForm">
            @csrf
            <input type="text" id="name" name="name" placeholder="Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="c_password" name="c_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <a href="/signin" class="back-link">Back</a>
        </form>

        <form id="otpForm">
            @csrf
            <div id="otp-container">
                <input type="text" id="otp1" maxlength="1" class="otp-input" required>
                <input type="text" id="otp2" maxlength="1" class="otp-input" required>
                <input type="text" id="otp3" maxlength="1" class="otp-input" required>
                <input type="text" id="otp4" maxlength="1" class="otp-input" required>
                <input type="text" id="otp5" maxlength="1" class="otp-input" required>
                <input type="text" id="otp6" maxlength="1" class="otp-input" required>
            </div>
            <input type="hidden" id="otp" name="otp">
            <button type="submit">Verify OTP</button>
            <div class="resendotp-link" id="resendOtpButton">Resend OTP</div>
        </form>

        <div id="responseMessage"></div>
    </div>

    <!-- The Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage">Registration successful!</p>
            <button class="modal-button" onclick="redirectToSignIn()">Back to sign-in page</button>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');
        const otpForm = document.getElementById('otpForm');
        const responseMessage = document.getElementById('responseMessage');
        const modal = document.getElementById("successModal");
        const modalMessage = document.getElementById("modalMessage");
        const span = document.getElementsByClassName("close")[0];
        const spinner = document.getElementById('spinner');
        let userEmail = '';

        //register
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            showSpinner();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const c_password = document.getElementById('c_password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Reset error styles
            document.getElementById('password').classList.remove('error');
            document.getElementById('c_password').classList.remove('error');
            document.getElementById('email').classList.remove('error');
            responseMessage.textContent = '';

            //check the password input
            if (password.length < 8 || c_password.length < 8) {
                responseMessage.textContent = 'The password must be at least 8 digits';
                document.getElementById('password').classList.add('error');
                document.getElementById('c_password').classList.add('error');
                return;
            }

            if (password !== c_password) {
                responseMessage.textContent = 'Confirm Password must be the same';
                document.getElementById('password').classList.add('error');
                document.getElementById('c_password').classList.add('error');
                return;
            }

            //register input
            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ name, email, password, c_password }),
                });

                const data = await response.json();

                if (response.ok) {
                    showPopup(data.message);
                    registerForm.style.display = 'none';
                    otpForm.style.display = 'block';
                    userEmail = email;
                } else {
                    if (data.errors && data.errors.email) {
                        document.getElementById('email').classList.add('error');
                        responseMessage.textContent = 'Email had been taken! try new one.';
                    }
                }
            } catch (error) {
                responseMessage.textContent = 'An error occurred. Please try again.';
            }
            hideSpinner();
        });

        //combined all otp1-opt6 together
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpField = document.getElementById('otp');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                let otp = '';
                otpInputs.forEach(otpInput => {
                    otp += otpInput.value;
                });
                otpField.value = otp;
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        //otp verify
        otpForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            showSpinner();

            const otp = otpField.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/api/auth/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ otp, email: userEmail }),
                });

                const data = await response.json();

                if (response.ok) {
                    showPopup('OTP verified successfully!');
                    modal.style.display = 'block';
                    registerForm.reset();
                    otpForm.reset();
                    otpForm.style.display = 'none';
                    document.getElementById('responseMessage').style.display = 'none';
                    document.getElementById('header1').style.display = 'none';
                    document.getElementById('successModal').classList.add('show');
                } else {
                    responseMessage.textContent = 'OTP verification failed. Please try again.';
                    highlightOtpError();
                }
            } catch (error) {
                responseMessage.textContent = 'An error occurred. Please try again.';
            }
            hideSpinner();
        });

        //resend otp
        const resendOtpButton = document.getElementById('resendOtpButton');

        resendOtpButton.addEventListener('click', async function() {
            showSpinner();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/api/auth/resend-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ email: userEmail }),
                });

                const data = await response.json();

                if (response.ok) {
                    showPopup(data.message);
                } else {
                    responseMessage.textContent = data.message;
                }
            } catch (error) {
                responseMessage.textContent = 'An error occurred. Please try again.';
            }
            hideSpinner();
        });

        //popup massage
        function showPopup(message) {
            const popup = document.createElement('div');
            popup.className = 'popup';
            popup.textContent = message;
            document.body.appendChild(popup);

            setTimeout(() => {
                popup.remove();
            }, 3000);
        }

        // Function to show and hide spinner
        function showSpinner() {
            spinner.style.display = 'block';
        }

        function hideSpinner() {
            spinner.style.display = 'none';
        }

        span.onclick = function() {
            modal.style.display = "none";
            redirectToSignIn();
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                redirectToSignIn();
            }
        }

        function redirectToSignIn() {
            window.location.href = '/signin';
        }

        // Function to add error class to all OTP inputs
        function highlightOtpError() {
            otpInputs.forEach(input => {
                input.classList.add('error');
            });
        }
    </script>
</body>
</html>
