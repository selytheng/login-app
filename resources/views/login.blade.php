<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #d4fc79, #96e6a1);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        form {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 360px;
            margin: 0 auto;
        }

        input[type="email"], input[type="password"] {
            width: calc(100% - 2rem);
            padding: 0.8rem;
            margin: 0.8rem 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 0.8rem;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            margin-top: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        #responseMessage {
            color: #dc3545;
            margin-top: 1rem;
        }

        @media (max-width: 480px) {
            form {
                padding: 1.5rem;
                max-width: 280px;
            }

            input[type="email"], input[type="password"] {
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
                padding: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <form id="loginForm">
            @csrf
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div id="responseMessage"></div>
        <div id="userList" style="display: none;">
            <!-- User table will be inserted here if role_id = 1 -->
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ email, password }),
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('access_token', data.access_token);
                    window.location.href = '/loginsucsess';
                } else {
                    document.getElementById('responseMessage').textContent = data.message;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html>
