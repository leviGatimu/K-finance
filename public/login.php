<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | K-Finance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #ffffff;
            overflow-x: hidden;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
        }

        /* =========================================
           LEFT PANEL: Dark Gradient & Typography 
           ========================================= */
        .left-panel {
            width: 45%;
            background: linear-gradient(145deg, #1e293b 0%, #0B132B 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            padding: 40px 60px;
            position: relative;
        }

        /* The Custom K-Finance Logo */
        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: #ffffff;
            display: flex;
            align-items: center;
        }

        .brand-k {
            color: #00E676;
            /* The requested Green K */
            font-size: 2.5rem;
            line-height: 1;
            margin-right: 2px;
        }

        .brand-dot {
            color: #00E676;
            /* The requested Green dot */
            font-size: 2.5rem;
            line-height: 1;
        }

        .hero-content {
            margin-top: auto;
            margin-bottom: auto;
        }

        /* The Colored Block inspired by NGA */
        .hero-icon-block {
            width: 60px;
            height: 60px;
            background-color: #00E676;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 230, 118, 0.3);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-title .highlight {
            color: #00E676;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 85%;
        }

        /* =========================================
           RIGHT PANEL: White, Floating Shapes, Form 
           ========================================= */
        .right-panel {
            width: 55%;
            background-color: #ffffff;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Interactive Floating Shapes Background */
        .shape {
            position: absolute;
            filter: blur(3px);
            opacity: 0.4;
            z-index: 0;
            animation: float 8s ease-in-out infinite;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            /* Light green */
            border-radius: 20px;
            top: 15%;
            right: 15%;
            transform: rotate(45deg);
        }

        .shape-2 {
            width: 60px;
            height: 60px;
            background: #e0e7ff;
            /* Light blue/purple */
            border-radius: 50%;
            bottom: 20%;
            left: 10%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 40px;
            height: 40px;
            background: #fef08a;
            /* Soft yellow */
            top: 40%;
            right: 5%;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation-delay: 4s;
        }

        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            /* Keeps form above the floating shapes */
        }

        .auth-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .auth-header h2 {
            font-weight: 800;
            color: #0f172a;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #64748b;
            font-size: 1rem;
        }

        /* NGA Style Inputs: Gray background, no borders, clean */
        .input-group-custom {
            margin-bottom: 1.5rem;
        }

        .input-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .input-control {
            width: 100%;
            background-color: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            color: #0f172a;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .input-control:focus-within {
            background-color: #ffffff;
            border-color: #00E676;
            box-shadow: 0 4px 15px rgba(0, 230, 118, 0.1);
        }

        .input-control input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            margin-left: 10px;
        }

        .input-control input::placeholder {
            color: #94a3b8;
        }

        /* The Action Button */
        .btn-action {
            width: 100%;
            background-color: #00E676;
            color: #0B132B;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 230, 118, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-action:hover {
            background-color: #00c868;
            transform: translateY(-2px);
        }

        /* Error Shake Animation */
        .shake {
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 992px) {
            .left-panel {
                display: none;
            }

            .right-panel {
                width: 100%;
            }

            .minimal-home-link {
                display: block;
                color: #0f172a;
                top: 20px;
                left: 20px;
                position: absolute;
                font-weight: bold;
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="split-layout">

        <div class="left-panel">
            <div class="brand-logo">
                <span class="brand-k">K</span>-Finance<span class="brand-dot">.</span>
            </div>

            <div class="hero-content">
                <div class="hero-icon-block"></div>
                <h1 class="hero-title">Welcome to the<br><span class="highlight">Future of Finance</span></h1>
                <p class="hero-subtitle">A unified analytical platform designed to streamline mobile money tracking and
                    guide you to financial excellence.</p>
            </div>
        </div>

        <div class="right-panel">

            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>

            <div class="form-wrapper">

                <div class="auth-header">
                    <h2>Sign In</h2>
                    <p>Access your personalized workspace.</p>
                </div>

                <form id="loginForm">

                    <div class="input-group-custom">
                        <div class="input-label">Full Name</div>
                        <div class="input-control" id="nameContainer">
                            <i class="bi bi-person text-muted"></i>
                            <input type="text" id="fullName" placeholder="e.g. John Doe">
                        </div>
                    </div>

                    <div class="input-group-custom">
                        <div class="input-label">MTN Phone Number</div>
                        <div class="input-control" id="phoneContainer">
                            <i class="bi bi-phone text-muted"></i>
                            <input type="tel" id="phoneNumber" placeholder="078 000 000">
                        </div>
                    </div>

                    <button type="submit" class="btn-action" id="submitBtn">
                        Send Secure OTP <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                    <div class="text-center mt-4 text-muted" style="font-size: 0.85rem;">
                        Simulation: Try submitting with an empty phone number to see the shake animation, or type a
                        number to proceed.
                    </div>
                </form>

                <form id="otpForm" style="display: none;">
                    <div
                        class="alert alert-success border-0 bg-success bg-opacity-10 text-success text-center fw-bold mb-4 rounded-3 p-3">
                        <i class="bi bi-check-circle-fill me-2"></i> Code sent successfully.
                    </div>

                    <div class="input-group-custom">
                        <div class="input-label">
                            Enter 6-Digit Code
                            <a href="#" class="text-success text-decoration-none text-lowercase"
                                onclick="location.reload()" style="font-weight: 600;">Resend?</a>
                        </div>
                        <div class="input-control">
                            <i class="bi bi-shield-lock text-muted"></i>
                            <input type="text" id="otpCode" placeholder="••••••" maxlength="6"
                                style="letter-spacing: 8px; font-weight: bold; font-size: 1.2rem;" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-action">
                        Verify Account <i class="bi bi-unlock ms-2"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const phoneInput = document.getElementById('phoneNumber').value;
            const phoneContainer = document.getElementById('phoneContainer');
            const submitBtn = document.getElementById('submitBtn');

            // Validation Failure: SHAKE ANIMATION
            if (phoneInput.trim() === '') {
                phoneContainer.classList.add('shake');

                // Remove class after animation completes so it can be triggered again
                setTimeout(() => {
                    phoneContainer.classList.remove('shake');
                }, 500);
                return;
            }

            // Validation Success: Show OTP Form
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

            setTimeout(() => {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('otpForm').style.display = 'block';
            }, 800); // Simulate network delay
        });
    </script>

</body>

</html>