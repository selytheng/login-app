<!DOCTYPE html>
<html>
<head>
    <title>Login Success</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        #welcomeMessage {
            color: #333;
            font-size: 24px;
            margin-top: 20px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #userList {
            margin-top: 20px;
            padding: 0 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h1 id="welcomeMessage">Loading...</h1>
    <button onclick="logout()">Logout</button>
    <div id="userList" style="display: none;">
        <!-- User table will be inserted here if role_id = 1 -->
    </div>

    <script>
        async function fetchUserDetails() {
            try {
                const response = await fetch('/api/auth/me', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    if (data.role_id === 2) {
                        document.getElementById('welcomeMessage').textContent = 'Login successfully';
                    } else if (data.role_id === 1) {
                        document.getElementById('welcomeMessage').textContent = 'Login successfully';
                        fetchUsers(); // Fetch users if role_id is 1
                    }
                } else {
                    document.getElementById('welcomeMessage').textContent = 'Failed to load user details.';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('welcomeMessage').textContent = 'Error occurred while fetching user details.';
            }
        }

        async function fetchUsers() {
            try {
                const response = await fetch('/api/auth/allUser', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });

                const data = await response.json();
                let table = '<table><tr><th>ID</th><th>Name</th><th>Email</th></tr>';

                data.forEach(user => {
                    table += `<tr><td>${user.id}</td><td>${user.name}</td><td>${user.email}</td></tr>`;
                });

                table += '</table>';
                document.getElementById('userList').innerHTML = table;
                document.getElementById('userList').style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('welcomeMessage').textContent = 'Error occurred while fetching users.';
            }
        }

        function logout() {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                localStorage.removeItem('access_token');
                window.location.href = '/signin';
            })
            .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', fetchUserDetails);
    </script>
</body>
</html>
