<?php
session_start();
require_once __DIR__ . '/../src/config/database.php';

// Kick out unauthorized users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$db = Database::connect();

// Use THEIR column name: transaction_date
$stmt = $db->prepare("SELECT * FROM transactions WHERE user_id = :user_id ORDER BY transaction_date DESC");
$stmt->execute([':user_id' => $userId]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | K-Finance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        /* =========================================
           GLOBAL & SIDEBAR LAYOUT (Reused)
           ========================================= */
        body {
            background-color: var(--k-bg-soft);
            overflow-x: hidden;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

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
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
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
        }

        .logout-btn {
            margin-top: auto;
            color: #ef4444;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 2rem 3rem;
            transition: margin-left 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* Toggle State */
        body.sidebar-closed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-closed .main-content {
            margin-left: 0;
        }

        #sidebarToggle i {
            transition: transform 0.4s ease;
            display: inline-block;
        }

        body.sidebar-closed #sidebarToggle i {
            transform: rotate(180deg);
        }

        /* =========================================
           TRANSACTIONS PAGE SPECIFIC
           ========================================= */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .page-title h3 {
            font-weight: 800;
            color: var(--k-navy);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .page-title p {
            color: var(--k-slate);
            margin: 0;
            font-size: 0.95rem;
        }

        /* Summary Cards */
        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .summary-data h6 {
            color: var(--k-slate);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
            font-weight: 700;
        }

        .summary-data h4 {
            color: #0f172a;
            margin: 0;
            font-weight: 800;
            letter-spacing: -1px;
        }

        /* Filter Section */
        .filter-bar {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid #f1f5f9;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .custom-search {
            background: #f8fafc;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            width: 300px;
            color: #0f172a;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .custom-select {
            background: #f8fafc;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #475569;
            font-weight: 600;
            cursor: pointer;
        }

        /* Ledger Table */
        .ledger-container {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .table-custom {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .table-custom th {
            background: #f8fafc;
            color: var(--k-slate);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-custom td {
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #0f172a;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        .tx-desc-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tx-desc-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* Dynamic Badges */
        .badge-category {
            background: #f1f5f9;
            color: #475569;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .badge-status-completed {
            background: #dcfce7;
            color: #16a34a;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .badge-status-pending {
            background: #fef08a;
            color: #ca8a04;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .amount-in {
            color: #16a34a !important;
            font-weight: 800;
        }

        .amount-out {
            color: #ef4444 !important;
            font-weight: 800;
        }

        /* Pagination */
        .pagination-container {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
        }

        .page-btn {
            background: white;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .page-btn:hover {
            background: #f1f5f9;
        }

        /* =========================================
           LOAD-IN ANIMATIONS
           ========================================= */
        @keyframes fadeLift {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeSlideRight {
            0% {
                opacity: 0;
                transform: translateX(-30px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .brand-logo,
        .nav-menu {
            opacity: 0;
            animation: fadeSlideRight 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        .nav-menu {
            animation-delay: 0.1s;
        }

        .top-header {
            opacity: 0;
            animation: fadeLift 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.1s;
        }

        .col-md-4:nth-child(1) {
            opacity: 0;
            animation: fadeLift 0.5s ease-out forwards;
            animation-delay: 0.2s;
        }

        .col-md-4:nth-child(2) {
            opacity: 0;
            animation: fadeLift 0.5s ease-out forwards;
            animation-delay: 0.3s;
        }

        .col-md-4:nth-child(3) {
            opacity: 0;
            animation: fadeLift 0.5s ease-out forwards;
            animation-delay: 0.4s;
        }

        .filter-bar {
            opacity: 0;
            animation: fadeLift 0.5s ease-out forwards;
            animation-delay: 0.5s;
        }

        .ledger-container {
            opacity: 0;
            animation: fadeLift 0.5s ease-out forwards;
            animation-delay: 0.6s;
        }

        /* Staggered Table Rows */
        .table-custom tbody tr {
            opacity: 0;
            animation: fadeLift 0.4s ease-out forwards;
        }

        .table-custom tbody tr:nth-child(1) {
            animation-delay: 0.65s;
        }

        .table-custom tbody tr:nth-child(2) {
            animation-delay: 0.70s;
        }

        .table-custom tbody tr:nth-child(3) {
            animation-delay: 0.75s;
        }

        .table-custom tbody tr:nth-child(4) {
            animation-delay: 0.80s;
        }

        .table-custom tbody tr:nth-child(5) {
            animation-delay: 0.85s;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .filter-bar {
                flex-direction: column;
            }

            .custom-search {
                width: 100%;
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
                <a href="dashboard.php" class="nav-item"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                <a href="transactions.php" class="nav-item active"><i class="bi bi-arrow-left-right"></i>
                    Transactions</a>
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
                        style="width: 45px; height: 45px; border-radius: 12px;">
                        <i class="bi bi-layout-sidebar-inset fs-5 text-dark"></i>
                    </button>

                    <div class="page-title">
                        <h3>Transaction History</h3>
                        <p>Complete ledger of your MTN MoMo activity.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Levi&background=00E676&color=0B132B&bold=true"
                        alt="Profile" class="rounded-circle shadow-sm" width="45" height="45">
                </div>
            </header>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-icon" style="background: #dcfce7; color: #16a34a;"><i
                                class="bi bi-arrow-down-left"></i></div>
                        <div class="summary-data">
                            <h6>Income (This Month)</h6>
                            <h4 class="amount-in">+ 185,000 RWF</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-icon" style="background: #f1f5f9; color: #64748b;"><i
                                class="bi bi-arrow-up-right"></i></div>
                        <div class="summary-data">
                            <h6>Spent (This Month)</h6>
                            <h4 class="amount-out">- 42,500 RWF</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-icon" style="background: #e0e7ff; color: #4f46e5;"><i
                                class="bi bi-safe"></i></div>
                        <div class="summary-data">
                            <h6>Total Saved</h6>
                            <h4>142,500 RWF</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" class="custom-search" placeholder="Search transactions, names...">
                </div>
                <select class="custom-select">
                    <option>All Categories</option>
                    <option>Food & Dining</option>
                    <option>Transport</option>
                    <option>Utilities</option>
                    <option>Transfers</option>
                </select>
                <select class="custom-select">
                    <option>This Month</option>
                    <option>Last 30 Days</option>
                    <option>This Year</option>
                </select>
                <button class="btn btn-dark ms-auto px-4" style="border-radius: 10px;"><i
                        class="bi bi-cloud-download me-2"></i>Export CSV</button>
            </div>

            <div class="ledger-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 30%">Transaction Details</th>
                            <th style="width: 20%">Date & Time</th>
                            <th style="width: 15%">Category</th>
                            <th style="width: 15%">Status</th>
                            <th style="width: 20%; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No transactions found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $tx): ?>

                                <?php
                                // 1. Map to THEIR existing columns
                                // Checking if it's income (assuming they use words like 'income', 'credit', or 'deposit')
                                $typeStr = strtolower(trim($tx['transaction_type'] ?? ''));
                                $isIncome = in_array($typeStr, ['income', 'credit', 'deposit']);

                                // 2. Formatting UI variables
                                $amountClass = $isIncome ? 'amount-in' : 'amount-out';
                                $sign = $isIncome ? '+' : '-';
                                $formattedAmount = number_format($tx['amount']) . ' RWF';

                                // 3. Dynamic Icons based on income/expense
                                $iconBg = $isIncome ? '#dcfce7' : '#f1f5f9';
                                $iconColor = $isIncome ? '#16a34a' : '#64748b';
                                $iconClass = $isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right';

                                // 4. Date Formatting
                                $dateFormatted = date('M j, h:i A', strtotime($tx['transaction_date']));

                                // 5. Fallbacks for UI layout using their columns
                                $desc = !empty($tx['description']) ? $tx['description'] : 'System Transaction';
                                $subDesc = !empty($tx['data_source']) ? $tx['data_source'] : 'Mobile Money';
                                $cat = !empty($tx['data_source']) ? $tx['data_source'] : 'General';
                                ?>

                                <tr>
                                    <td>
                                        <div class="tx-desc-wrapper">
                                            <div class="tx-desc-icon"
                                                style="background: <?= $iconBg ?>; color: <?= $iconColor ?>;">
                                                <i class="bi <?= $iconClass ?>"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 800; color: #0f172a;"><?= htmlspecialchars($desc) ?>
                                                </div>
                                                <div style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                                                    <?= htmlspecialchars($subDesc) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="color: #0f172a;"><?= $dateFormatted ?></div>
                                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 500;">ID:
                                            TXN-<?= str_pad($tx['id'], 6, '0', STR_PAD_LEFT) ?></div>
                                    </td>
                                    <td><span class="badge-category"><?= htmlspecialchars($cat) ?></span></td>
                                    <td>
                                        <span class="badge-status-completed">Completed</span>
                                    </td>
                                    <td style="text-align: right;" class="<?= $amountClass ?>">
                                        <?= $sign ?>         <?= $formattedAmount ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="pagination-container">
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">Showing 1 to 5 of 124
                        entries</span>
                    <div class="d-flex gap-2">
                        <button class="page-btn text-muted" disabled>Previous</button>
                        <button class="page-btn bg-dark text-white border-dark">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">Next</button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Sidebar Toggle Logic
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.body.classList.toggle('sidebar-closed');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>