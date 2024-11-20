<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            overflow-x: hidden;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .collapsed-body {
            margin-left: 0;
        }

        .navbar {
            width: calc(100% - 250px);
            transition: width 0.3s ease;
        }

        .navbar.collapsed {
            width: 100%;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            /* Changed to start from the top of the page */
            padding: 2rem 1rem;
            background-color: #343a40;
            color: #ffffff;
            width: 250px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 0;
            padding: 2rem 0;
        }

        .sidebar a {
            color: #ddd;
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }

        .main-content {
            padding: 2rem;
            transition: margin-left 0.3s ease;
            width: 100%;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <button id="sidebarToggle" class="btn btn-outline-light me-3"><i class="bi bi-list"></i></button>
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <h2>Sidebar</h2>
        <a href="#">Dashboard</a>
        <a href="#">Profile</a>
        <a href="#">Settings</a>
        <a href="#">Logout</a>
        <a href="#" class="disabled">Disabled</a>
    </div>

    <div class="main-content">
        <h1>Welcome to the Dashboard</h1>
        <p>This is a simple dashboard layout using Bootstrap 5 with an enhanced sidebar.</p>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.navbar').classList.toggle('collapsed');
            document.body.classList.toggle('collapsed-body');
        });
    </script>
</body>

</html>
