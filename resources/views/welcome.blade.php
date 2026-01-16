<!-- resources/views/demo.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dodo Notifications</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/js/echo.js')
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-3">Dodo Notifications Demo</h3>

                    <div class="mb-3">
                        <label class="form-label">API Token</label>
                        <input id="token" class="form-control" placeholder="Paste Bearer token here">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">User ID</label>
                        <input id="userId" class="form-control" placeholder="Authenticated user ID">
                    </div>

                    <button class="btn btn-primary" onclick="connect()">
                        Connect
                    </button>
                </div>
            </div>

            <!-- Notifications -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Notifications</span>

                    <button type="button"
                        class="btn btn-sm btn-outline-secondary"
                        onclick="markAllAsRead()"
                    >
                        Mark all as read
                    </button>
                </div>

                <ul id="notifications" class="list-group list-group-flush">
                    <!-- Notifications injected here -->
                </ul>
            </div>

        </div>
    </div>

</div>

<script>
    window.connect = function () {
        const token = document.getElementById('token').value;
        const userId = document.getElementById('userId').value;
         if (!token || !userId) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing details',
                text: 'Please provide both API token and User ID.',
            });
            return;
        }
        initEcho(token, userId);
         Swal.fire({
            icon: 'success',
            title: 'Connected',
            text: 'Real-time notifications enabled.',
            timer: 1500,
            showConfirmButton: false
        });
    };
</script>

</body>
</html>
