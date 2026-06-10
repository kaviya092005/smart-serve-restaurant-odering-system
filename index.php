<?php
/**
 * Smart Serve Restaurant Ordering System
 * Landing Page
 */
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-bg: #0f0c29;
            --dark-bg-2: #1a1a2e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            overflow-x: hidden;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            animation: slideInLeft 0.8s ease-out;
        }
        
        .hero h1 i {
            animation: pulse 2s infinite;
            display: inline-block;
        }
        
        .hero p {
            font-size: 1.4rem;
            margin-bottom: 40px;
            opacity: 0.95;
            line-height: 1.8;
            animation: slideInRight 0.8s ease-out;
        }
        
        .hero-buttons {
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        
        .hero-buttons .btn {
            margin: 10px;
            padding: 18px 45px;
            font-size: 1.15rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: 2px solid transparent;
        }
        
        .hero-buttons .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        
        .hero-buttons .btn-light {
            background: white;
            color: #667eea;
        }
        
        .hero-buttons .btn-light:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: white;
        }
        
        .hero-buttons .btn-outline-light:hover {
            background: white;
            color: #667eea;
        }
        
        .features {
            padding: 100px 0;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        .features h2 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 60px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .feature-card {
            text-align: center;
            padding: 50px 30px;
            border-radius: 20px;
            transition: all 0.4s ease;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            animation: fadeInUp 0.8s ease-out;
        }
        
        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.3);
        }
        
        .feature-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 40px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.4s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: rotateY(360deg);
        }
        
        .feature-card h4 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2d3436;
        }
        
        .feature-card p {
            color: #636e72;
            line-height: 1.8;
        }
        
        .how-it-works {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .how-it-works::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 30s infinite linear;
        }
        
        .how-it-works h2 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .step {
            text-align: center;
            padding: 40px 20px;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .step-number {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            color: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 800;
            margin: 0 auto 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
        }
        
        .step:hover .step-number {
            transform: scale(1.15) rotate(360deg);
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .step h5 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .step p {
            opacity: 0.9;
            line-height: 1.7;
        }
        
        footer {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        footer i {
            animation: pulse 2s infinite;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero p {
                font-size: 1.1rem;
            }
            .hero-buttons .btn {
                padding: 15px 35px;
                font-size: 1rem;
            }
            .features h2, .how-it-works h2 {
                font-size: 2rem;
            }
            .feature-card {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h1><i class="fas fa-utensils"></i> <?php echo SITE_NAME; ?></h1>
            <p>Experience seamless dining with our QR code-based ordering system.<br>
            Scan, Order, Enjoy - It's that simple!</p>
            <div class="hero-buttons">
                <a href="reservation.php" class="btn btn-light btn-lg">
                    <i class="fas fa-calendar-alt"></i> Book a Table
                </a>
                <a href="admin/login.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-shield"></i> Admin Login
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Smart Serve?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h4>QR Code Ordering</h4>
                        <p>Simply scan the QR code at your table to access our digital menu and place orders instantly.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Real-Time Tracking</h4>
                        <p>Track your order status in real-time from preparation to serving.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h4>Easy Payments</h4>
                        <p>Pay conveniently with cash or online payment options.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h5>Scan QR Code</h5>
                        <p>Scan the unique QR code placed on your table</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step">
                        <div class="step-number">2</div>
                        <h5>Browse Menu</h5>
                        <p>Explore our delicious menu with categories</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step">
                        <div class="step-number">3</div>
                        <h5>Place Order</h5>
                        <p>Add items to cart and place your order</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step">
                        <div class="step-number">4</div>
                        <h5>Enjoy & Pay</h5>
                        <p>Track your order, enjoy your meal, and pay easily</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p><i class="fas fa-utensils"></i> <?php echo SITE_NAME; ?></p>
            <p class="mb-0">&copy; <?php echo date('Y'); ?> All Rights Reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
