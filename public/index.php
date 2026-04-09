<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Finance | Intelligent Money Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        body {
            overflow-x: clip;
            background-color: #ffffff;
        }

        /* NGA-Style Hero */
        .hero-section {
            padding: 160px 0 100px;
            background: linear-gradient(145deg, #f8fafc 0%, #ffffff 100%);
        }

        .hero-tag {
            display: inline-block;
            background: rgba(0, 230, 118, 0.1);
            color: #00c868;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
        }

        /* Step Process Map (Taking piece from NGA) */
        .step-container {
            padding: 100px 0;
        }

        .step-card {
            position: relative;
            padding: 40px;
            background: #ffffff;
            border-radius: 24px;
            transition: all 0.3s ease;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: #0B132B;
            color: #00E676;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            margin-bottom: 20px;
        }

        /* The connecting line between steps */
        .step-line {
            position: absolute;
            top: 65px;
            left: 100%;
            width: 100%;
            height: 2px;
            background: repeating-linear-gradient(to right, #e2e8f0, #e2e8f0 10px, transparent 10px, transparent 20px);
            z-index: -1;
        }

        /* Value Grid */
        .value-card {
            border: 1px solid #f1f5f9;
            padding: 40px;
            border-radius: 20px;
            height: 100%;
            transition: border-color 0.3s;
        }

        .value-card:hover {
            border-color: #00E676;
        }

        .value-icon {
            font-size: 2.5rem;
            color: #00E676;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand brand-logo text-dark" href="#">
                <span class="brand-k">K</span>-Finance<span class="brand-dot">.</span>
            </a>
            <div class="ms-auto">
                <a href="login.php" class="btn btn-action px-4 py-2">Get Started</a>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <div class="hero-tag" data-aos="fade-down">FINTECH EVOLVED</div>
            <h1 class="hero-title mb-4" data-aos="zoom-in">Master your money<br>with <span
                    style="color:#00E676">Intelligence.</span></h1>
            <p class="lead text-muted mx-auto mb-5" style="max-width: 600px;">A unified analytical platform designed to
                automate mobile money tracking and guide you to financial excellence.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="login.php" class="btn btn-action px-5 py-3">Enter Portal</a>
                <a href="#steps" class="btn btn-outline-dark px-5 py-3 rounded-3 fw-bold">How it works</a>
            </div>
        </div>
    </section>

    <section class="step-container" id="steps">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-800 display-6">Simple 3-Step Integration</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <div class="step-line d-none d-md-block"></div>
                        <h4>Secure Link</h4>
                        <p class="text-muted">Enter your MTN number and verify via encrypted OTP. No passwords, no risk.
                        </p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <div class="step-line d-none d-md-block"></div>
                        <h4>Auto-Sync</h4>
                        <p class="text-muted">Our system categorizes your MoMo history into visual streams of income and
                            debt.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h4>AI Analysis</h4>
                        <p class="text-muted">Receive personalized budgeting strategies generated by our financial AI
                            engine.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-right">
                    <div class="value-card bg-white">
                        <i class="bi bi-shield-check value-icon"></i>
                        <h5>Bank-Grade Security</h5>
                        <p class="text-muted">We use industry-standard encryption to ensure your transaction data stays
                            private and protected.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="value-card bg-white">
                        <i class="bi bi-lightning-charge value-icon"></i>
                        <h5>Real-Time Velocity</h5>
                        <p class="text-muted">See your money move as it happens. Charts update instantly with every
                            transaction.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="value-card bg-white">
                        <i class="bi bi-graph-up-arrow value-icon"></i>
                        <h5>Wealth Growth</h5>
                        <p class="text-muted">Identify "Money Leaks" and redirect your funds toward long-term savings
                            goals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 border-top">
        <div class="container text-center">
            <p class="fw-bold">K-Finance<span class="text-success">.</span></p>
            <p class="text-muted small">© 2026 Built for the Coding Academy.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>

</html>