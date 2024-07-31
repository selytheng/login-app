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

        .action-buttons button {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 3px;
            cursor: pointer;
        }

        .action-buttons button.delete {
            background-color: #dc3545;
        }

        .action-buttons button:hover {
            opacity: 0.9;
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
                    document.getElementById('welcomeMessage').textContent = 'Login successfully';

                    if (data.role_id === 1) {
                        fetchUsers(); // Fetch users if role_id is 1
                    } else {
                        document.getElementById('userList').style.display = 'none';
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
                let table = '<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>';

                data.forEach(user => {
                    table += `<tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td class="action-buttons">
                            <button onclick="editUser(${user.id})">Edit</button>
                            <button class="delete" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    </tr>`;
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

        function editUser(userId) {
            // Redirect to an edit page or open a modal to edit user details
            window.location.href = `/editUser/${userId}`;
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/api/auth/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert('User deleted successfully.');
                    fetchUsers(); // Refresh the user list
                })
                .catch(error => console.error('Error:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', fetchUserDetails);
    </script>
</body>
</html>
