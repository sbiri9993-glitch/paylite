<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PayLite — Your Mini Digital Wallet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:         #0d1117;
      --surface:    #161b22;
      --surface2:   #1c2330;
      --border:     rgba(255,255,255,0.07);
      --border2:    rgba(255,255,255,0.13);
      --teal:       #1D9E75;
      --teal-light: #5DCAA5;
      --teal-dim:   rgba(29,158,117,0.12);
      --text:       #e6edf3;
      --muted:      #7d8590;
      --danger:     #E24B4A;
      --amber:      #EF9F27;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Outfit', sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* ---- grid texture ---- */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(29,158,117,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(29,158,117,0.03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
      z-index: 0;
    }

    /* ---- glow orbs ---- */
    .orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      pointer-events: none;
      z-index: 0;
    }
    .orb-1 { width: 500px; height: 500px; background: rgba(29,158,117,0.08); top: -100px; right: -100px; }
    .orb-2 { width: 400px; height: 400px; background: rgba(239,159,39,0.05); bottom: 100px; left: -150px; }

    /* ---- NAV ---- */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 48px;
      background: rgba(13,17,23,0.8);
      backdrop-filter: blur(12px);
      border-bottom: 0.5px solid var(--border);
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 20px;
      font-weight: 800;
      color: var(--teal-light);
      letter-spacing: -0.5px;
      text-decoration: none;
    }

    .nav-brand .brand-icon {
      width: 34px; height: 34px;
      background: var(--teal-dim);
      border: 0.5px solid rgba(93,202,165,0.2);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
    }

    .nav-links { display: flex; align-items: center; gap: 8px; }

    .nav-link {
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      color: var(--muted);
      transition: all 0.15s;
    }
    .nav-link:hover { color: var(--text); background: var(--surface2); }

    .nav-cta {
      padding: 8px 20px;
      background: var(--teal);
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #fff;
      text-decoration: none;
      transition: background 0.15s;
    }
    .nav-cta:hover { background: var(--teal-light); }

    /* ---- HERO ---- */
    .hero {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 120px 24px 80px;
    }

    .hero-inner { max-width: 760px; }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--teal-dim);
      border: 0.5px solid rgba(93,202,165,0.2);
      border-radius: 20px;
      padding: 6px 16px;
      font-size: 12px;
      font-weight: 600;
      color: var(--teal-light);
      margin-bottom: 28px;
      animation: fadeUp 0.6s ease both;
    }

    .hero-badge i { font-size: 10px; }

    .hero-title {
      font-size: clamp(42px, 7vw, 76px);
      font-weight: 900;
      letter-spacing: -3px;
      line-height: 1.05;
      margin-bottom: 24px;
      animation: fadeUp 0.6s ease 0.1s both;
    }

    .hero-title .accent {
      color: var(--teal-light);
      position: relative;
    }

    .hero-title .accent::after {
      content: '';
      position: absolute;
      bottom: 4px;
      left: 0; right: 0;
      height: 3px;
      background: var(--teal);
      border-radius: 2px;
      opacity: 0.5;
    }

    .hero-sub {
      font-size: 18px;
      color: var(--muted);
      max-width: 520px;
      margin: 0 auto 40px;
      font-weight: 400;
      animation: fadeUp 0.6s ease 0.2s both;
    }

    .hero-actions {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
      animation: fadeUp 0.6s ease 0.3s both;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--teal);
      color: #fff;
      padding: 13px 28px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.15s;
      letter-spacing: 0.2px;
    }
    .btn-primary:hover { background: var(--teal-light); transform: translateY(-1px); }

    .btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: transparent;
      color: var(--text);
      padding: 13px 28px;
      border-radius: 10px;
      border: 0.5px solid var(--border2);
      font-size: 15px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.15s;
    }
    .btn-ghost:hover { background: var(--surface2); }

    /* ---- WALLET CARD PREVIEW ---- */
    .hero-card {
      position: relative;
      z-index: 1;
      max-width: 420px;
      margin: 64px auto 0;
      animation: fadeUp 0.6s ease 0.4s both;
    }

    .wallet-preview {
      background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 55%, #3DCCA0 100%);
      border-radius: 20px;
      padding: 28px;
      position: relative;
      overflow: hidden;
    }

    .wallet-preview::before {
      content: '';
      position: absolute;
      right: -40px; top: -40px;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(255,255,255,0.06);
    }

    .wallet-preview::after {
      content: '';
      position: absolute;
      right: 30px; bottom: -40px;
      width: 120px; height: 120px;
      border-radius: 50%;
      background: rgba(255,255,255,0.04);
    }

    .wp-label { font-size: 11px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.8px; position: relative; }
    .wp-amount { font-family: 'Space Mono', monospace; font-size: 36px; font-weight: 700; color: #fff; letter-spacing: -1px; margin: 8px 0 4px; position: relative; }
    .wp-user { font-size: 12px; color: rgba(255,255,255,0.65); position: relative; }
    .wp-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(255,255,255,0.15);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      color: rgba(255,255,255,0.85);
      margin-top: 12px;
      position: relative;
    }

    .wp-actions {
      display: flex;
      gap: 10px;
      margin-top: 20px;
      position: relative;
    }

    .wp-action {
      flex: 1;
      background: rgba(255,255,255,0.15);
      border-radius: 10px;
      padding: 10px;
      text-align: center;
      backdrop-filter: blur(4px);
    }

    .wp-action i { font-size: 14px; color: #fff; display: block; margin-bottom: 4px; }
    .wp-action span { font-size: 11px; color: rgba(255,255,255,0.8); font-weight: 500; }

    /* ---- FEATURES ---- */
    .section {
      position: relative;
      z-index: 1;
      padding: 100px 24px;
      max-width: 1100px;
      margin: 0 auto;
    }

    .section-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--teal);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-label::before {
      content: '';
      width: 20px;
      height: 1px;
      background: var(--teal);
    }

    .section-title {
      font-size: clamp(28px, 4vw, 42px);
      font-weight: 800;
      letter-spacing: -1px;
      margin-bottom: 16px;
    }

    .section-sub {
      font-size: 16px;
      color: var(--muted);
      max-width: 500px;
      margin-bottom: 56px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 16px;
    }

    .feature-card {
      background: var(--surface);
      border: 0.5px solid var(--border2);
      border-radius: 16px;
      padding: 28px;
      transition: all 0.2s;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--teal), transparent);
      opacity: 0;
      transition: opacity 0.2s;
    }

    .feature-card:hover { border-color: rgba(93,202,165,0.25); transform: translateY(-3px); }
    .feature-card:hover::before { opacity: 1; }

    .feature-icon {
      width: 46px; height: 46px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      margin-bottom: 18px;
    }

    .feature-icon.teal  { background: var(--teal-dim);              color: var(--teal-light); border: 0.5px solid rgba(93,202,165,0.2); }
    .feature-icon.amber { background: rgba(239,159,39,0.12);         color: var(--amber);      border: 0.5px solid rgba(239,159,39,0.2); }
    .feature-icon.blue  { background: rgba(55,138,221,0.1);          color: #85B7EB;           border: 0.5px solid rgba(55,138,221,0.15); }
    .feature-icon.pink  { background: rgba(212,83,126,0.1);          color: #ED93B1;           border: 0.5px solid rgba(212,83,126,0.15); }
    .feature-icon.green { background: rgba(99,153,34,0.1);           color: #97C459;           border: 0.5px solid rgba(99,153,34,0.15); }
    .feature-icon.red   { background: rgba(226,75,74,0.1);           color: #F09595;           border: 0.5px solid rgba(226,75,74,0.15); }

    .feature-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
    .feature-desc  { font-size: 13px; color: var(--muted); line-height: 1.7; }

    /* ---- STATS ---- */
    .stats-section {
      position: relative;
      z-index: 1;
      background: var(--surface);
      border-top: 0.5px solid var(--border);
      border-bottom: 0.5px solid var(--border);
      padding: 60px 24px;
    }

    .stats-inner {
      max-width: 900px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
      text-align: center;
    }

    .stat-num {
      font-family: 'Space Mono', monospace;
      font-size: 42px;
      font-weight: 700;
      color: var(--teal-light);
      letter-spacing: -2px;
    }

    .stat-label { font-size: 13px; color: var(--muted); margin-top: 6px; }

    /* ---- HOW IT WORKS ---- */
    .steps {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
      counter-reset: steps;
    }

    .step {
      position: relative;
      padding: 28px;
      background: var(--surface);
      border: 0.5px solid var(--border);
      border-radius: 16px;
    }

    .step-num {
      font-family: 'Space Mono', monospace;
      font-size: 11px;
      font-weight: 700;
      color: var(--teal);
      background: var(--teal-dim);
      border: 0.5px solid rgba(93,202,165,0.2);
      border-radius: 6px;
      padding: 3px 8px;
      display: inline-block;
      margin-bottom: 16px;
    }

    .step-title { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
    .step-desc  { font-size: 13px; color: var(--muted); line-height: 1.7; }

    /* ---- CTA SECTION ---- */
    .cta-section {
      position: relative;
      z-index: 1;
      text-align: center;
      padding: 100px 24px;
    }

    .cta-box {
      max-width: 600px;
      margin: 0 auto;
      background: var(--surface);
      border: 0.5px solid var(--border2);
      border-radius: 24px;
      padding: 56px 40px;
      position: relative;
      overflow: hidden;
    }

    .cta-box::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--teal), transparent);
    }

    .cta-title { font-size: 36px; font-weight: 800; letter-spacing: -1px; margin-bottom: 14px; }
    .cta-sub   { font-size: 15px; color: var(--muted); margin-bottom: 32px; }

    /* ---- FOOTER ---- */
    footer {
      position: relative;
      z-index: 1;
      border-top: 0.5px solid var(--border);
      padding: 32px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }

    .footer-brand {
      font-size: 16px;
      font-weight: 800;
      color: var(--teal-light);
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .footer-copy { font-size: 12px; color: var(--muted); }

    /* ---- ANIMATIONS ---- */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50%       { transform: translateY(-10px); }
    }

    .hero-card { animation: fadeUp 0.6s ease 0.4s both, float 4s ease-in-out 1s infinite; }

    /* ---- RESPONSIVE ---- */
    @media (max-width: 768px) {
      nav { padding: 14px 20px; }
      .nav-links .nav-link { display: none; }
      .stats-inner { grid-template-columns: 1fr; gap: 24px; }
      .section { padding: 60px 20px; }
      footer { padding: 24px 20px; flex-direction: column; text-align: center; }
    }
  </style>
</head>
<body>

  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>

  <!-- NAV -->
  <nav>
    <a href="index.html" class="nav-brand">
      <div class="brand-icon"><i class="fa-solid fa-wallet"></i></div>
      PayLite
    </a>
    <div class="nav-links">
      <a href="#features" class="nav-link">Features</a>
      <a href="#how" class="nav-link">How it works</a>
      <a href="pages/login.html" class="nav-link">Sign in</a>
      <a href="pages/register.html" class="nav-cta">
        <i class="fa-solid fa-user-plus"></i> Get started
      </a>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-inner">
      <div class="hero-badge">
        <i class="fa-solid fa-circle" style="font-size:7px;"></i>
        Simple · Fast · Secure
      </div>

      <h1 class="hero-title">
        Your money,<br>
        <span class="accent">under control</span>
      </h1>

      <p class="hero-sub">
        PayLite is a mini digital wallet that lets you deposit, transfer and track your money — all in one clean dashboard.
      </p>

      <div class="hero-actions">
        <a href="pages/register.html" class="btn-primary">
          <i class="fa-solid fa-wallet"></i> Create free account
        </a>
        <a href="pages/login.html" class="btn-ghost">
          <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in
        </a>
      </div>

      <!-- Wallet card preview -->
      <div class="hero-card">
        <div class="wallet-preview">
          <div class="wp-label"><i class="fa-solid fa-wallet" style="margin-right:5px;"></i> Total balance</div>
          <div class="wp-amount">XAF 125,400</div>
          <div class="wp-user"><i class="fa-regular fa-user" style="margin-right:5px;"></i> Jean Dupont · jean@paylite.com</div>
          <div class="wp-badge"><i class="fa-solid fa-circle" style="font-size:7px;"></i> Active wallet</div>
          <div class="wp-actions">
            <div class="wp-action">
              <i class="fa-solid fa-arrow-down-to-line"></i>
              <span>Deposit</span>
            </div>
            <div class="wp-action">
              <i class="fa-solid fa-paper-plane"></i>
              <span>Transfer</span>
            </div>
            <div class="wp-action">
              <i class="fa-solid fa-clock-rotate-left"></i>
              <span>History</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <div class="stats-section">
    <div class="stats-inner">
      <div>
        <div class="stat-num">100%</div>
        <div class="stat-label">Secure transactions</div>
      </div>
      <div>
        <div class="stat-num">0s</div>
        <div class="stat-label">Instant deposits</div>
      </div>
      <div>
        <div class="stat-num">1%</div>
        <div class="stat-label">Low transfer fee</div>
      </div>
    </div>
  </div>

  <!-- FEATURES -->
  <section class="section" id="features">
    <div class="section-label">Features</div>
    <h2 class="section-title">Everything you need in one wallet</h2>
    <p class="section-sub">Built for simplicity — no hidden fees, no complicated setup.</p>

    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon teal"><i class="fa-solid fa-arrow-down-to-line"></i></div>
        <div class="feature-title">Instant deposits</div>
        <div class="feature-desc">Add funds to your wallet instantly. No waiting, no delays — your balance updates in real time.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon amber"><i class="fa-solid fa-paper-plane"></i></div>
        <div class="feature-title">Fast transfers</div>
        <div class="feature-desc">Send money to any PayLite user by email. Only a 1% fee — transparent and fair.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="feature-title">Transaction history</div>
        <div class="feature-desc">Every transaction logged with a unique reference. Filter by deposits, sent or received.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon pink"><i class="fa-solid fa-receipt"></i></div>
        <div class="feature-title">Transaction receipts</div>
        <div class="feature-desc">Get a detailed receipt after every transaction showing amount, fee, reference and timestamp.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon green"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="feature-title">Secure by design</div>
        <div class="feature-desc">Passwords hashed with BCRYPT, sessions protected, SQL injection blocked via prepared statements.</div>
      </div>
      <div class="feature-card">
        <div class="feature-icon red"><i class="fa-solid fa-circle-check"></i></div>
        <div class="feature-title">Confirmation modals</div>
        <div class="feature-desc">Every transaction requires confirmation before processing — no accidental transfers.</div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="section" id="how">
    <div class="section-label">How it works</div>
    <h2 class="section-title">Up and running in minutes</h2>
    <p class="section-sub">No bank account needed. Just sign up and start transacting.</p>

    <div class="steps">
      <div class="step">
        <div class="step-num">01</div>
        <div class="step-title">Create your account</div>
        <div class="step-desc">Register with your name, email and a secure password. Your wallet is created instantly with zero balance.</div>
      </div>
      <div class="step">
        <div class="step-num">02</div>
        <div class="step-title">Deposit funds</div>
        <div class="step-desc">Add money to your wallet using the deposit feature. Choose a quick amount or enter a custom value.</div>
      </div>
      <div class="step">
        <div class="step-num">03</div>
        <div class="step-title">Send money</div>
        <div class="step-desc">Transfer to any PayLite user by their email address. Review the fee and confirm before sending.</div>
      </div>
      <div class="step">
        <div class="step-num">04</div>
        <div class="step-title">Track everything</div>
        <div class="step-desc">View your full transaction history, filter by type and check your running balance anytime.</div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="cta-box">
      <h2 class="cta-title">Ready to get started?</h2>
      <p class="cta-sub">Join PayLite today and take control of your digital wallet.</p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="pages/register.html" class="btn-primary">
          <i class="fa-solid fa-user-plus"></i> Create free account
        </a>
        <a href="pages/login.html" class="btn-ghost">
          <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in
        </a>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-brand">
      <i class="fa-solid fa-wallet"></i> PayLite
    </div>
    <div class="footer-copy">
      &copy; 2026 PayLite. Built with PHP &amp; MongoDB Atlas.
    </div>
  </footer>

</body>
</html>