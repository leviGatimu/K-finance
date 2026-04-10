<?php
session_start();
require_once __DIR__ . '/../src/config/database.php';

// 1. AUTHENTICATION
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$db = Database::connect();

// ==========================================
// BACKEND: AI SYSTEM CONTEXT
// ==========================================
$userName = $_SESSION['user_name'] ?? "Levi";
$currentBalance = "1,245,500";
$topExpenseCat = "Transport";
$recentTransactions = [
    ['desc' => 'Transfer from Malvyn', 'amount' => '+25,000', 'date' => 'Today'],
    ['desc' => 'Joyous Chapati', 'amount' => '-2,500', 'date' => 'Yesterday'],
    ['desc' => 'MTN Data Bundle', 'amount' => '-5,000', 'date' => 'April 8']
];
$txnString = json_encode($recentTransactions);

// The backend dev can use this string later in the API call
$aiContextQuery = "
    You are K-Finance AI, a highly intelligent financial advisor.
    Current User: {$userName}.
    Current Available Balance: {$currentBalance} RWF.
    Highest spending category this month: {$topExpenseCat}.
    Recent transactions: {$txnString}.
    Rules: Be concise, highly professional, and do not make up financial data. 
    Use the provided data to answer user questions about their budget.
";

// ==========================================
// BACKEND: SESSION & MESSAGE HANDLING
// ==========================================

// Handle "New Chat" creation
if (isset($_GET['new_chat'])) {
    $stmt = $db->prepare("INSERT INTO chat_sessions (user_id, title) VALUES (:user_id, 'New Conversation')");
    $stmt->execute([':user_id' => $userId]);
    $newSessionId = $db->lastInsertId();
    header("Location: ai_advisor.php?session_id=" . $newSessionId);
    exit;
}

// Fetch all sessions for the right sidebar
$stmt = $db->prepare("SELECT * FROM chat_sessions WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $userId]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Determine the active session
$activeSessionId = $_GET['session_id'] ?? null;

if (!$activeSessionId && count($sessions) > 0) {
    $activeSessionId = $sessions[0]['id'];
} elseif (!$activeSessionId) {
    // Create their very first session if they have none
    $stmt = $db->prepare("INSERT INTO chat_sessions (user_id, title) VALUES (:user_id, 'New Conversation')");
    $stmt->execute([':user_id' => $userId]);
    $activeSessionId = $db->lastInsertId();
    header("Location: ai_advisor.php?session_id=" . $activeSessionId);
    exit;
}

// Fetch messages for the active session
$stmt = $db->prepare("SELECT * FROM chat_messages WHERE session_id = :session_id ORDER BY created_at ASC");
$stmt->execute([':session_id' => $activeSessionId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Toggle empty state based on message count
$hasMessages = count($messages) > 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Advisor | K-Finance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* =========================================
           GLOBAL & SIDEBAR LAYOUT
           ========================================= */
        :root {
            --k-navy: #0B132B;
            --k-green: #00E676;
            --k-bg-soft: #f8fafc;
            --k-slate: #64748b;
        }

        body {
            background-color: var(--k-bg-soft);
            overflow: hidden;
            /* Lock body scrolling for chat UI */
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .dashboard-layout {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 280px;
            background-color: var(--k-bg-soft);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
            flex-shrink: 0;
            z-index: 100;
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-k {
            color: var(--k-green);
            font-size: 2.5rem;
            line-height: 1;
            margin-right: 2px;
        }

        .brand-dot {
            color: var(--k-green);
            font-size: 2.5rem;
            line-height: 1;
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

        /* =========================================
           AI CHAT INTERFACE
           ========================================= */
        .chat-wrapper {
            flex-grow: 1;
            display: flex;
            background: #ffffff;
            border-top-left-radius: 24px;
            border-bottom-left-radius: 24px;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }

        .chat-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            background: radial-gradient(circle at top left, #ffffff, #f8fafc);
        }

        /* Empty State */
        .empty-state {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
            padding: 2rem;
        }

        .ai-logo-large {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            color: #059669;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 230, 118, 0.2);
        }

        .empty-state h2 {
            font-weight: 800;
            color: var(--k-navy);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--k-slate);
            margin-bottom: 2rem;
        }

        .suggestion-cards {
            display: flex;
            gap: 15px;
            margin-bottom: 3rem;
            max-width: 700px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .sugg-card {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            font-size: 0.9rem;
            color: #0f172a;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .sugg-card:hover {
            border-color: var(--k-green);
            transform: translateY(-2px);
        }

        /* Chat History Bubbles */
        .chat-history-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding: 2rem 15%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            padding-bottom: 150px;
        }

        .chat-bubble {
            max-width: 80%;
            padding: 1rem 1.5rem;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .bubble-user {
            background: #f1f5f9;
            color: #0f172a;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            font-weight: 500;
        }

        .bubble-ai {
            background: white;
            border: 1px solid #e2e8f0;
            color: #334155;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }

        .ai-identity {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--k-navy);
            font-size: 0.85rem;
        }

        .ai-identity i {
            color: var(--k-green);
            font-size: 1.2rem;
        }

        /* The Input Box */
        .input-area-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem 15%;
            background: linear-gradient(to top, #ffffff 60%, transparent);
            transition: all 0.4s ease;
        }

        .input-area-container.centered {
            position: relative;
            padding: 0;
            width: 100%;
            max-width: 700px;
            background: transparent;
            margin: 0 auto;
        }

        .chat-input-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 0.5rem 0.5rem 0.5rem 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .chat-input-box:focus-within {
            border-color: var(--k-green);
            box-shadow: 0 10px 30px rgba(0, 230, 118, 0.15);
        }

        .chat-input-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 1rem;
            color: #0f172a;
        }

        .chat-input-box input::placeholder {
            color: #94a3b8;
        }

        .btn-send {
            background: var(--k-navy);
            color: white;
            border: none;
            border-radius: 16px;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-send:hover {
            background: var(--k-green);
            color: var(--k-navy);
        }

        /* =========================================
           RIGHT SIDEBAR: PAST CHAT HISTORY
           ========================================= */
        .right-history-panel {
            width: 300px;
            background: #f8fafc;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            flex-shrink: 0;
        }

        .new-chat-btn {
            background: white;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--k-navy);
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            width: 100%;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }

        .new-chat-btn:hover {
            background: #dcfce7;
            border-color: var(--k-green);
            color: #059669;
        }

        .history-list {
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .history-list::-webkit-scrollbar {
            width: 4px;
        }

        .history-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .history-bubble {
            background: white;
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.01);
        }

        .history-bubble:hover {
            border-color: #e2e8f0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        }

        .history-bubble.active {
            border-color: var(--k-green);
            background: #f0fdf4;
        }

        .history-bubble h6 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 5px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-bubble p {
            font-size: 0.75rem;
            color: #64748b;
            margin: 0;
        }

        /* Animations */
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

        .nav-menu {
            opacity: 0;
            animation: fadeSlideRight 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        .chat-main {
            opacity: 0;
            animation: fadeLift 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.2s;
        }

        .right-history-panel {
            opacity: 0;
            animation: fadeLift 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.3s;
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
                <a href="transactions.php" class="nav-item"><i class="bi bi-arrow-left-right"></i> Transactions</a>
                <a href="ai_advisor.php" class="nav-item active"><i class="bi bi-cpu"></i> AI Advisor</a>
                <a href="#" class="nav-item"><i class="bi bi-pie-chart"></i> Analytics</a>
                <a href="#" class="nav-item"><i class="bi bi-gear"></i> Settings</a>

                <a href="login.php" class="nav-item logout-btn"><i class="bi bi-box-arrow-right"></i> Log Out</a>
            </div>
        </aside>

        <div class="chat-wrapper">

            <div class="chat-main" id="chatMainArea">

                <div class="empty-state" id="emptyState" style="display: <?= $hasMessages ? 'none' : 'flex' ?>;">
                    <div class="ai-logo-large"><i class="bi bi-stars"></i></div>
                    <h2>How can I help you, <?= htmlspecialchars($userName) ?>?</h2>
                    <p>Your K-Finance AI is ready. Ask about your budget, transactions, or financial health.</p>

                    <div class="suggestion-cards">
                        <div class="sugg-card" onclick="forceChat('Why is my transport cost so high?')">Why is my
                            transport cost so high?</div>
                        <div class="sugg-card" onclick="forceChat('Summarize my spending this week.')">Summarize my
                            spending this week.</div>
                        <div class="sugg-card" onclick="forceChat('Can I afford a new laptop right now?')">Can I afford
                            a new laptop right now?</div>
                    </div>
                </div>

                <div class="chat-history-scroll" id="activeChatStream"
                    style="display: <?= $hasMessages ? 'flex' : 'none' ?>;">
                    <?php foreach ($messages as $msg): ?>
                        <?php if ($msg['sender'] === 'user'): ?>
                            <div class="chat-bubble bubble-user">
                                <?= htmlspecialchars($msg['message']) ?>
                            </div>
                        <?php else: ?>
                            <div class="chat-bubble bubble-ai">
                                <div class="ai-identity"><i class="bi bi-robot"></i> K-Finance Intelligence</div>
                                <?= htmlspecialchars($msg['message']) ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="input-area-container <?= $hasMessages ? '' : 'centered' ?>" id="inputContainer">
                    <form class="chat-input-box" id="aiChatForm">
                        <input type="text" id="aiInput" placeholder="Message K-Finance AI..." autocomplete="off"
                            required>
                        <button type="submit" class="btn-send"><i class="bi bi-send-fill"></i></button>
                    </form>
                    <div class="text-center mt-3 text-muted" style="font-size: 0.75rem;">AI can make mistakes. Always
                        verify your ledger.</div>
                </div>

            </div>

            <div class="right-history-panel">
                <a href="ai_advisor.php?new_chat=1" class="new-chat-btn">
                    <i class="bi bi-plus-lg"></i> New Conversation
                </a>

                <h6
                    style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 15px; letter-spacing: 1px;">
                    Recent Chats</h6>

                <div class="history-list">
                    <?php foreach ($sessions as $session): ?>
                        <?php
                        $isActive = ($session['id'] == $activeSessionId) ? 'active' : '';
                        $dateStr = date('M j, Y', strtotime($session['created_at']));
                        ?>
                        <a href="ai_advisor.php?session_id=<?= $session['id'] ?>" class="text-decoration-none">
                            <div class="history-bubble <?= $isActive ?>">
                                <h6><?= htmlspecialchars($session['title']) ?></h6>
                                <p><?= $dateStr ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

    <script>
        const CURRENT_SESSION_ID = <?= json_encode($activeSessionId) ?>;
    </script>

    <script>
        const emptyState = document.getElementById('emptyState');
        const activeChatStream = document.getElementById('activeChatStream');
        const inputContainer = document.getElementById('inputContainer');
        const aiChatForm = document.getElementById('aiChatForm');
        const aiInput = document.getElementById('aiInput');

        function forceChat(text) {
            aiInput.value = text;
            aiChatForm.dispatchEvent(new Event('submit'));
        }

        // Scroll to bottom on load if there are messages
        if (activeChatStream.style.display !== 'none') {
            activeChatStream.scrollTop = activeChatStream.scrollHeight;
        }

        aiChatForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const message = aiInput.value.trim();
            if (!message) return;

            // Update UI instantly
            emptyState.style.display = 'none';
            activeChatStream.style.display = 'flex';
            inputContainer.classList.remove('centered');

            const userBubble = document.createElement('div');
            userBubble.className = 'chat-bubble bubble-user';
            userBubble.textContent = message;
            activeChatStream.appendChild(userBubble);

            aiInput.value = '';
            activeChatStream.scrollTop = activeChatStream.scrollHeight;

            const aiBubble = document.createElement('div');
            aiBubble.className = 'chat-bubble bubble-ai';
            aiBubble.innerHTML = `<div class="ai-identity"><i class="bi bi-robot"></i> K-Finance Intelligence</div>
                                  <em class="text-muted">Thinking...</em>`;
            activeChatStream.appendChild(aiBubble);
            activeChatStream.scrollTop = activeChatStream.scrollHeight;

            // Fetch dynamic data from the backend
            try {
                const response = await fetch('api/ai_chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: CURRENT_SESSION_ID,
                        prompt: message
                    })
                });

                const result = await response.json();

                if (result.success) {
                    aiBubble.innerHTML = `<div class="ai-identity"><i class="bi bi-robot"></i> K-Finance Intelligence</div>
                                          ${result.reply}`;
                } else {
                    aiBubble.innerHTML = `<div class="ai-identity"><i class="bi bi-exclamation-triangle text-danger"></i> K-Finance Intelligence</div>
                                          <span class="text-danger">Error: ${result.message}</span>`;
                }
            } catch (error) {
                console.error("Chat Error:", error);
                aiBubble.innerHTML = `<div class="ai-identity"><i class="bi bi-wifi-off text-danger"></i> K-Finance Intelligence</div>
                                      <span class="text-danger">Network error. Could not connect to AI servers.</span>`;
            }

            activeChatStream.scrollTop = activeChatStream.scrollHeight;
        });
    </script>
</body>

</html>