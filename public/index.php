<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Finance | Enterprise Financial Intelligence</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <style>
        /* =========================================
           1. CORE THEME & VARIABLES
           =========================================
        */
        :root {
            --k-green: #00E676;
            --k-green-glow: rgba(0, 230, 118, 0.4);
            --k-navy: #0B132B;
            --k-navy-light: #1C2541;
            --k-slate: #64748b;
            --k-white: #ffffff;
            --k-bg-soft: #f8fafc;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--k-navy);
            background-color: var(--k-white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* =========================================
           2. INTERACTIVE BACKGROUND SHAPES
           =========================================
        */
        .bg-shapes-wrapper {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-shape {
            position: absolute;
            opacity: 0.15;
            filter: blur(40px);
            border-radius: 50%;
            animation: moveShapes 20s infinite alternate ease-in-out;
        }

        @keyframes moveShapes {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 50px) scale(1.2); }
        }

        /* =========================================
           3. NAVIGATION
           =========================================
        */
        .navbar-k {
            padding: 1.5rem 0;
            transition: all 0.4s ease;
            background: transparent;
        }

        .navbar-k.scrolled {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .brand-logo {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -1px;
            text-decoration: none;
            color: var(--k-navy);
        }

        .brand-k { color: var(--k-green); font-size: 2rem; }
        .brand-dot { color: var(--k-green); font-size: 2rem; }

        /* =========================================
           4. HERO SECTION
           =========================================
        */
        .hero {
            padding: 180px 0 100px;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: var(--k-bg-soft);
            border: 1px solid #e2e8f0;
            border-radius: 100px;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--k-navy);
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -3px;
            margin-bottom: 2rem;
        }

        .hero-title span {
            color: var(--k-green);
        }

        .hero-p {
            font-size: 1.25rem;
            color: var(--k-slate);
            max-width: 650px;
            margin: 0 auto 3rem;
        }

        /* =========================================
           5. BUTTONS
           =========================================
        */
        .btn-k-primary {
            background: var(--k-green);
            color: var(--k-navy);
            font-weight: 700;
            padding: 16px 40px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            display: inline-block;
        }

        .btn-k-primary:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 20px 40px var(--k-green-glow);
            color: var(--k-navy);
        }

        .btn-k-outline {
            background: transparent;
            color: var(--k-navy);
            font-weight: 700;
            padding: 16px 40px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-k-outline:hover {
            border-color: var(--k-navy);
            background: var(--k-navy);
            color: white;
        }

        /* =========================================
           6. PROCESS SECTION (NGA PORTAL STEPS)
           =========================================
        */
        .section-padding { padding: 120px 0; }

        .step-wrapper {
            position: relative;
            padding: 40px;
            background: var(--k-white);
            border-radius: 30px;
            border: 1px solid #f1f5f9;
            height: 100%;
            transition: all 0.4s ease;
        }

        .step-wrapper:hover {
            border-color: var(--k-green);
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.05);
        }

        .step-icon-box {
            width: 70px; height: 70px;
            background: var(--k-navy);
            color: var(--k-green);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            font-weight: 800;
        }

        .step-connector {
            position: absolute;
            top: 75px; left: 100%;
            width: 100%; height: 2px;
            background: repeating-linear-gradient(to right, #e2e8f0 0, #e2e8f0 10px, transparent 10px, transparent 20px);
            z-index: -1;
        }

        /* =========================================
           7. AI FEATURE HIGHLIGHT
           =========================================
        */
        .ai-card {
            background: var(--k-navy);
            border-radius: 40px;
            padding: 80px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .ai-card::after {
            content: '';
            position: absolute;
            top: -50%; right: -20%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0, 230, 118, 0.15) 0%, transparent 70%);
        }

        .ai-tag {
            background: rgba(255,255,255,0.1);
            color: var(--k-green);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* =========================================
           8. FOOTER & MISC
           =========================================
        */
        footer {
            background: var(--k-bg-soft);
            padding: 80px 0 40px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="bg-shapes-wrapper">
        <div class="floating-shape" style="width: 400px; height: 400px; background: #dcfce7; top: -10%; right: -5%;"></div>
        <div class="floating-shape" style="width: 300px; height: 300px; background: #e0e7ff; bottom: 10%; left: -5%; animation-delay: 2s;"></div>
        <div class="floating-shape" style="width: 250px; height: 250px; background: #fef08a; top: 40%; left: 20%; animation-delay: 4s;"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-k fixed-top" id="mainNav">
        <div class="container">
            <a class="brand-logo" href="#">
                <span class="brand-k">K</span>-Finance<span class="brand-dot">.</span>
            </a>
            <div class="ms-auto d-flex align-items-center">
                <a href="login.php" class="btn-k-primary py-2 px-4 shadow-none" style="font-size: 0.9rem;">Sign In</a>
            </div>
        </div>
    </nav>

    <header class="hero text-center">
        <div class="container">
            <div class="hero-badge" data-aos="fade-down">
                <i class="bi bi-stars me-2 text-success"></i> Empowering Rwandan Fintech
            </div>
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">
                The New Standard of <br><span>Financial Intelligence.</span>
            </h1>
            <p class="hero-p" data-aos="fade-up" data-aos-delay="200">
                Stop managing money blindly. K-Finance bridges the gap between raw transactions and actionable wealth strategies using advanced AI.
            </p>
            <div class="d-flex justify-content-center gap-3" data-aos="fade-up" data-aos-delay="300">
                <a href="login.php" class="btn-k-primary">Launch Dashboard</a>
                <a href="#how-it-works" class="btn-k-outline">How it works</a>
            </div>
        </div>
    </header>

    <section class="section-padding" id="how-it-works">
        <div class="container text-center mb-5">
            <h6 class="text-uppercase fw-800 text-success mb-3 ls-2">Framework</h6>
            <h2 class="display-5 fw-800 mb-4">Your Journey to Financial Clarity</h2>
        </div>
        <div class="container mt-5">
            <div class="row g-5">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-wrapper">
                        <div class="step-icon-box">01</div>
                        <div class="step-connector d-none d-lg-block"></div>
                        <h4 class="fw-800 mb-3">Secure Onboarding</h4>
                        <p class="text-muted mb-0">Identity verification via MTN Mobile Money encrypted One-Time Password. We never store your sensitive keys.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-wrapper">
                        <div class="step-icon-box">02</div>
                        <div class="step-connector d-none d-lg-block"></div>
                        <h4 class="fw-800 mb-3">Automated Extraction</h4>
                        <p class="text-muted mb-0">Our dual-sync engine parses your transaction receipts in real-time, mapping every RWF to a specific spending category.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-wrapper">
                        <div class="step-icon-box">03</div>
                        <h4 class="fw-800 mb-3">Intelligent Advising</h4>
                        <p class="text-muted mb-0">The K-Finance AI analyzes spending velocity and provides personalized warnings before you reach budget limits.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="ai-card" data-aos="zoom-in">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="ai-tag">PROPRIETARY ENGINE</div>
                        <h2 class="display-4 fw-800 mb-4">A Financial Advisor <br>That Never Sleeps.</h2>
                        <p class="fs-5 opacity-75 mb-5">Our integrated Gemini-Flash engine processes your anonymized spending patterns to deliver weekly health checks. It’s like having a CFO in your pocket.</p>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <span>Spending Velocity Alerts</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <span>Goal Achievement Tracking</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 text-center mt-5 mt-lg-0">
                        <div class="position-relative d-inline-block">
                             <div class="spinner-grow text-success" style="width: 200px; height: 200px; opacity: 0.1;" role="status"></div>
                             <i class="bi bi-cpu text-success position-absolute top-50 start-50 translate-middle" style="font-size: 8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h2 class="fw-800 display-6">Frequently <br>Asked Questions</h2>
                    <p class="text-muted mt-3">Everything you need to know about the K-Finance ecosystem.</p>
                </div>
                <div class="col-lg-8">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item bg-transparent mb-3 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How secure is my MTN data?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    We use bank-grade encryption and only store the transaction details necessary for your dashboard. We never have access to your MoMo PIN.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent mb-3 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Does this cost anything?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    The basic dashboard and manual tracking are completely free for students. Premium AI features are currently in Beta.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="display-4 fw-800 mb-5">Take the first step toward <br>Financial Excellence.</h2>
            <a href="login.php" class="btn-k-primary px-5 py-4 fs-5">Get Started Now</a>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a class="brand-logo" href="#">
                        <span class="brand-k">K</span>-Finance<span class="brand-dot">.</span>
                    </a>
                </div>
                <div class="col-md-6 text-md-end mt-4 mt-md-0">
                    <p class="text-muted small mb-0">© 2026 K-Finance Hub. Developed for NGA Coding Academy.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // AOS Init
        AOS.init({ duration: 1000, once: true, offset: 50 });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>