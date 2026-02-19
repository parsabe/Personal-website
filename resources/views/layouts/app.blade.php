<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .top-bar {
            background-color: #f8f9fa;
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .social-icon {
            margin-right: 15px;
            font-size: 1.2rem;
            text-decoration: none;
        }
        .social-icon.linkedin { color: #0077b5; }
        .social-icon.researchgate { color: #00ccbb; }
        .social-icon.github { color: #333333; }
        .social-icon.email { color: #D44638; }
        
        .navbar-brand { font-weight: bold; }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container d-flex justify-content-end align-items-center">
            <a href="https://www.linkedin.com/in/parsabe" target="_blank" class="social-icon linkedin" title="LinkedIn">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="https://www.researchgate.net/profile/Parsa-Besharat" target="_blank" class="social-icon researchgate" title="ResearchGate">
                <i class="fab fa-researchgate"></i>
            </a>
            <a href="https://github.com/parsabe" target="_blank" class="social-icon github" title="GitHub">
                <i class="fab fa-github"></i>
            </a>
            <a href="mailto:parsa.besharat@student.tu-freiberg.de" class="social-icon email" title="Email">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">MySite</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/chatroom') }}">Chatroom</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/sandika') }}">Sandika</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>