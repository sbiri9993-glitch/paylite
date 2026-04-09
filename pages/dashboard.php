<?php
session_start();
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$oid  = sessionOid();
$user = $users->findOne(['_id' => $oid]);

// Last 5 transactions (sender OR receiver)
$cursor = $transactions->aggregate([
    ['$match' => [
        '$or' => [
            ['sender_id'   => $oid],
            ['receiver_id' => $oid],
        ],
    ]],
    ['$sort'  => ['created_at' => -1]],
    ['$limit' => 5],
    ['$lookup' => [
        'from'         => 'users',
        'localField'   => 'sender_id',
        'foreignField' => '_id',
        'as'           => 'sender_doc',
    ]],
    ['$lookup' => [
        'from'         => 'users',
        'localField'   => 'receiver_id',
        'foreignField' => '_id',
        'as'           => 'receiver_doc',
    ]],
    ['$addFields' => [
        'sender_name'   => ['$arrayElemAt' => ['$sender_doc.name',   0]],
        'receiver_name' => ['$arrayElemAt' => ['$receiver_doc.name', 0]],
    ]],
]);
$recentTxns = iterator_to_array($cursor);

// Stats summary
$statsCursor = $transactions->aggregate([
    ['$match' => [
        '$or' => [
            ['sender_id'   => $oid],
            ['receiver_id' => $oid],
        ],
    ]],
    ['$group' => [
        '_id'      => null,
        'totalIn'  => ['$sum' => [
            '$cond' => [['$eq' => ['$type', 'deposit']], '$amount', 0],
        ]],
        'totalOut' => ['$sum' => [
            '$cond' => [
                ['$and' => [
                    ['$eq' => ['$type', 'transfer']],
                    ['$eq' => ['$sender_id', $oid]],
                ]],
                '$amount', 0,
            ],
        ]],
        'count'    => ['$sum' => 1],
    ]],
]);
$statsArr = iterator_to_array($statsCursor);
$stats    = $statsArr[0] ?? ['totalIn' => 0, 'totalOut' => 0, 'count' => 0];

function fmtXAF($n) { return 'XAF ' . number_format((float)$n, 0, '.', ','); }
function txnRef($id) { return 'TXN-' . strtoupper(substr((string)$id, -6)); }
function fmtDate($utc) {
    $ts = $utc instanceof MongoDB\BSON\UTCDateTime ? $utc->toDateTime() : new DateTime();
    $ts->setTimezone(new DateTimeZone('Africa/Douala'));
    return $ts->format('M d, Y · H:i');
}

$parts    = explode(' ', trim($user['name']));
$initials = strtoupper($parts[0][0] . (count($parts) > 1 ? end($parts)[0] : ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — PayLite</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="topbar">
  <span class="topbar-brand"><i class="fa-solid fa-wallet"></i> PayLite</span>
  <button class="topbar-menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
</div>
<div class="sidebar-overlay"></div>

<div class="pl-layout">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="fa-solid fa-wallet"></i></div>
      <span class="brand-name">PayLite</span>
    </div>
    <div class="sidebar-section">Menu</div>
    <a href="dashboard.php"               class="sidebar-link active"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="deposit.html"                class="sidebar-link"><i class="fa-solid fa-arrow-down-to-line"></i> Deposit</a>
    <a href="transfer.html"               class="sidebar-link"><i class="fa-solid fa-paper-plane"></i> Transfer</a>
    <a href="../transactions/history.php" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
    <div class="sidebar-spacer"></div>
    <div class="sidebar-user">
      <div class="user-avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($parts[0]) ?></div>
        <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
      </div>
    </div>
    <a href="../auth/logout.php" class="sidebar-link danger" style="margin-top:6px;">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </aside>

  <main class="main">

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Balance card -->
    <div class="balance-card">
      <div class="balance-label"><i class="fa-solid fa-wallet"></i> Total balance</div>
      <div class="balance-amount" id="walletBalance"><?= fmtXAF($user['balance']) ?></div>
      <div class="balance-meta">
        <span class="balance-user">
          <i class="fa-regular fa-user"></i>
          <?= htmlspecialchars($user['name']) ?> &middot; <?= htmlspecialchars($user['email']) ?>
        </span>
        <span class="balance-status">
          <i class="fa-solid fa-circle" style="font-size:7px;"></i> Active wallet
        </span>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="quick-actions">
      <a href="deposit.html"                class="action-card">
        <div class="action-icon teal"><i class="fa-solid fa-arrow-down-to-line"></i></div>
        <div class="action-label">Deposit</div>
      </a>
      <a href="transfer.html"               class="action-card">
        <div class="action-icon amber"><i class="fa-solid fa-paper-plane"></i></div>
        <div class="action-label">Transfer</div>
      </a>
      <a href="../transactions/history.php" class="action-card">
        <div class="action-icon blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="action-label">History</div>
      </a>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-arrow-down" style="color:var(--teal)"></i> Total deposited</div>
        <div class="stat-value" style="color:var(--teal-light)"><?= fmtXAF($stats['totalIn']) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-arrow-up" style="color:var(--danger)"></i> Total sent</div>
        <div class="stat-value" style="color:var(--danger)"><?= fmtXAF($stats['totalOut']) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-receipt" style="color:var(--amber)"></i> Transactions</div>
        <div class="stat-value"><?= (int)$stats['count'] ?></div>
      </div>
    </div>

    <!-- Recent transactions -->
    <div class="section-title">
      <i class="fa-solid fa-clock-rotate-left"></i> Recent transactions
      <a href="../transactions/history.php">View all <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="tx-list">
      <?php if (empty($recentTxns)): ?>
        <div class="tx-empty">
          <i class="fa-regular fa-folder-open"></i>
          No transactions yet. Make your first deposit!
        </div>
      <?php else: ?>
        <?php foreach ($recentTxns as $tx):
          $isDeposit = $tx['type'] === 'deposit';
          $isCredit  = !$isDeposit && ((string)$tx['receiver_id'] === $_SESSION['user_id']);
          $iconClass = $isDeposit ? 'deposit' : ($isCredit ? 'credit' : 'debit');
          $amtClass  = ($isDeposit || $isCredit) ? 'credit' : 'debit';
          $sign      = ($isDeposit || $isCredit) ? '+' : '−';
          $icon      = $isDeposit ? 'fa-arrow-down-to-line' : ($isCredit ? 'fa-arrow-down' : 'fa-paper-plane');

          if ($isDeposit) {
            $label = 'Deposit';
            $badge = '<span class="badge badge-warning"><i class="fa-solid fa-arrow-down-to-line"></i> Deposit</span>';
          } elseif ($isCredit) {
            $label = 'Received from ' . htmlspecialchars($tx['sender_name'] ?? 'Unknown');
            $badge = '<span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> Received</span>';
          } else {
            $label = 'Sent to ' . htmlspecialchars($tx['receiver_name'] ?? 'Unknown');
            $badge = '<span class="badge badge-danger"><i class="fa-solid fa-paper-plane"></i> Sent</span>';
          }
        ?>
        <div class="tx-item">
          <div class="tx-icon-wrap <?= $iconClass ?>"><i class="fa-solid <?= $icon ?>"></i></div>
          <div class="tx-meta">
            <div class="tx-name"><?= $label ?> <?= $badge ?></div>
            <div class="tx-date"><i class="fa-regular fa-clock"></i> <?= fmtDate($tx['created_at']) ?></div>
          </div>
          <div class="tx-right">
            <div class="tx-amount <?= $amtClass ?>"><?= $sign ?> <?= fmtXAF($tx['amount']) ?></div>
            <div class="tx-ref"><?= txnRef($tx['_id']) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </main>
</div>
<script src="../assets/js/wallet.js"></script>
</body>
</html>