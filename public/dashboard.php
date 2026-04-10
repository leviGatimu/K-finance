<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | K-Finance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        /* =========================================
           DASHBOARD SPECIFIC LAYOUT
           ========================================= */
        body {
            background-color: var(--k-bg-soft);
            /* f8fafc */
            overflow-x: hidden;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 280px;
            background-color: var(--k-navy);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .nav-menu {
            margin-top: 3rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.2rem;
            color: #0f172a;
            /* Changed from light gray to high-contrast dark navy/black */
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-item i {
            font-size: 1.25rem;
            margin-right: 15px;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(0, 230, 118, 0.15);
            color: #059669 !important;
            /* Used a darker emerald green so it's actually readable */
        }

        .logout-btn {
            margin-top: auto;
            color: #ef4444;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* --- MAIN CONTENT AREA --- */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            /* Offset for fixed sidebar */
            padding: 2rem 3rem;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .user-greeting h3 {
            font-weight: 800;
            color: var(--k-navy);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .user-greeting p {
            color: var(--k-slate);
            margin: 0;
            font-size: 0.95rem;
        }

        /* --- CARDS --- */
        .dash-card {
            background: white;
            border-radius: 24px;
            padding: 1.8rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        /* The Hero Balance Card */
        .balance-card {
            background: linear-gradient(145deg, var(--k-navy-start) 0%, var(--k-navy-end) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .balance-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 230, 118, 0.15) 0%, transparent 70%);
        }

        .balance-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 3.5rem;
            font-weight: 800;
            color: #0f172a;
            /* Changed to dark slate/navy */
            line-height: 1;
            letter-spacing: -2px;
            margin-bottom: 1.5rem;
        }

        .balance-amount span {
            font-size: 1.5rem;
            color: var(--k-green);
            vertical-align: super;
            margin-right: 5px;
        }

        /* AI Insight Card */
        .ai-insight {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.2rem;
            border: 1px solid #e2e8f0;
            display: flex;
            gap: 15px;
        }

        /* Transaction List */
        .transaction-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .tx-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .tx-in {
            background: #dcfce7;
            color: #16a34a;
        }

        .tx-out {
            background: #f1f5f9;
            color: #64748b;
        }

        .tx-details h6 {
            margin: 0;
            font-weight: 700;
            color: var(--k-navy);
        }

        .tx-details p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--k-slate);
        }

        .tx-amount {
            font-weight: 800;
        }

        .tx-amount.positive {
            color: #16a34a;
        }

        .tx-amount.negative {
            color: var(--k-navy);
        }

        /* --- SIDEBAR ANIMATION LOGIC --- */

        /* 1. Add smooth transitions to the default states */
        .sidebar {
            /* Keep your existing sidebar styles, just add this transition line: */
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .main-content {
            /* Keep your existing main-content styles, just add this transition line: */
            transition: margin-left 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* 2. The new 'Collapsed' state classes */
        body.sidebar-closed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-closed .main-content {
            margin-left: 0;
        }

        /* Animate the button icon when clicked */
        #sidebarToggle i {
            transition: transform 0.4s ease;
            display: inline-block;
        }

        body.sidebar-closed #sidebarToggle i {
            transform: rotate(180deg);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-layout">

        <aside class="sidebar">
            <a class="brand-logo text-dark text-decoration-none" href="#">
                <span class="brand-k">K</span>-Finance<span class="brand-dot">.</span>
            </a>

            <div class="nav-menu">
                <a href="#" class="nav-item active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                <a href="#" class="nav-item"><i class="bi bi-arrow-left-right"></i> Transactions</a>
                <a href="#" class="nav-item"><i class="bi bi-cpu"></i> AI Advisor</a>
                <a href="#" class="nav-item"><i class="bi bi-pie-chart"></i> Analytics</a>
                <a href="#" class="nav-item"><i class="bi bi-gear"></i> Settings</a>

                <a href="login.php" class="nav-item logout-btn"><i class="bi bi-box-arrow-right"></i> Log Out</a>
            </div>
        </aside>

        <main class="main-content">

            <header class="top-header">
                <div class="d-flex align-items-center gap-3">
                    <button id="sidebarToggle"
                        class="btn btn-light shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; border-radius: 12px; transition: all 0.3s ease;">
                        <i class="bi bi-layout-sidebar-inset fs-5 text-dark"></i>
                    </button>

                    <div class="user-greeting">
                        <h3>Welcome back, Levi</h3>
                        <p>Here is your financial overview for today.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light rounded-circle shadow-sm" style="width: 45px; height: 45px;">
                        <i class="bi bi-bell fs-5 text-muted"></i>
                    </button>
                    <img src="https://ui-avatars.com/api/?name=Levi&background=00E676&color=0B132B&bold=true"
                        alt="Profile" class="rounded-circle shadow-sm" width="45" height="45">
                </div>
            </header>

            <div class="row g-4 mb-4">

                <div class="col-xl-8 col-lg-7">
                    <div class="dash-card balance-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="balance-label">Total Available Balance</div>
                                <div class="balance-amount"><span>RWF</span>1,245,500</div>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-3 p-2">
                                <i class="bi bi-wallet2 fs-3 text-success"></i>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-2 position-relative z-1">
                            <button class="btn-action py-2 px-4 shadow-none" style="border-radius: 8px;">
                                <i class="bi bi-plus-lg me-2"></i> Add Funds
                            </button>
                            <button class="btn btn-light text-dark py-2 px-4 fw-bold shadow-sm"
                                style="border-radius: 8px;">
                                <i class="bi bi-send me-2"></i> Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="dash-card">
                        <h6 class="fw-800 text-uppercase mb-3"
                            style="color: var(--k-slate); font-size: 0.8rem; letter-spacing: 1px;">AI Intelligence</h6>

                        <div class="ai-insight mb-3">
                            <i class="bi bi-stars text-success fs-4 mt-1"></i>
                            <div>
                                <p class="mb-0 text-dark fw-semibold" style="font-size: 0.9rem;">Transport costs are
                                    down 15% this week.</p>
                                <small class="text-muted">You saved RWF 4,500 compared to last week.</small>
                            </div>
                        </div>

                        <div class="ai-insight">
                            <i class="bi bi-exclamation-triangle text-warning fs-4 mt-1"></i>
                            <div>
                                <p class="mb-0 text-dark fw-semibold" style="font-size: 0.9rem;">Approaching food budget
                                    limit.</p>
                                <small class="text-muted">You have RWF 12,000 left for the weekend.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-lg-7">
                    <div class="dash-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-800 m-0">Recent Transactions</h5>
                            <a href="#" class="text-success text-decoration-none fw-bold"
                                style="font-size: 0.9rem;">View All</a>
                        </div>

                        <div class="transaction-list">
                            <div class="transaction-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tx-icon tx-in"><i class="bi bi-arrow-down-left"></i></div>
                                    <div class="tx-details">
                                        <h6>Received Money</h6>
                                        <p>From Malvyn</p>
                                    </div>
                                </div>
                                <div class="tx-amount positive">+ 25,000 RWF</div>
                            </div>

                            <div class="transaction-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tx-icon tx-out"><i class="bi bi-cup-hot"></i></div>
                                    <div class="tx-details">
                                        <h6>Joyous Chapati</h6>
                                        <p>Food & Dining</p>
                                    </div>
                                </div>
                                <div class="tx-amount negative">- 2,500 RWF</div>
                            </div>

                            <div class="transaction-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tx-icon tx-out"><i class="bi bi-wifi"></i></div>
                                    <div class="tx-details">
                                        <h6>MTN Data Bundle</h6>
                                        <p>Utilities</p>
                                    </div>
                                </div>
                                <div class="tx-amount negative">- 5,000 RWF</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="dash-card d-flex flex-column justify-content-between">
                        <h5 class="fw-800 m-0 mb-2">Weekly Spending</h5>
                        <p class="text-muted small">Visual breakdown of your expenses.</p>

                        <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-light rounded-3 mt-3 border border-dashed"
                            style="min-height: 200px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-bar-chart-fill fs-1 text-success opacity-50"></i>
                                <p class="mt-2 fw-semibold">Chart.js Canvas Area</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
    <script>
        // Sidebar Toggle Logic
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            // This toggles the 'sidebar-closed' class on the whole body
            document.body.classList.toggle('sidebar-closed');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>