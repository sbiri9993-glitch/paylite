/**
 * PayLite — assets/js/wallet.js
 * Handles: deposit, transfer, transaction history, sidebar, modals.
 */

/* ============================================================
   SIDEBAR MOBILE TOGGLE
   ============================================================ */

function initSidebar() {
  const menuBtn   = document.getElementById('menuBtn');
  const sidebar   = document.querySelector('.sidebar');
  const overlay   = document.querySelector('.sidebar-overlay');
  if (!menuBtn || !sidebar) return;

  menuBtn.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('active');
  });

  overlay?.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
  });
}

/* ============================================================
   UTILITIES
   ============================================================ */

function fmt(n) {
  return Number(n).toLocaleString('fr-CM', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function showAlert(container, msg, type = 'danger') {
  removeAlert(container);
  const icons = { success: 'fa-circle-check', danger: 'fa-circle-xmark', info: 'fa-circle-info', warning: 'fa-triangle-exclamation' };
  const d = document.createElement('div');
  d.className = `alert alert-${type} wallet-alert`;
  d.innerHTML = `<i class="fa-solid ${icons[type] || icons.danger}"></i> ${msg}`;
  container.prepend(d);
  if (type === 'success') setTimeout(() => d.remove(), 6000);
}

function removeAlert(container) {
  container.querySelector('.wallet-alert')?.remove();
}

function setLoading(btn, text = 'Processing…') {
  btn.disabled = true;
  btn.dataset.orig = btn.innerHTML;
  btn.innerHTML = `<span class="spinner"></span> ${text}`;
}

function clearLoading(btn) {
  btn.disabled = false;
  btn.innerHTML = btn.dataset.orig;
}

/* ============================================================
   MODAL
   ============================================================ */

function openModal(id) {
  document.getElementById(id)?.classList.add('open');
}

function closeModal(id) {
  document.getElementById(id)?.classList.remove('open');
}

// Close modal on overlay click
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.modal-overlay')?.classList.remove('open');
    });
  });
});

/* ============================================================
   DEPOSIT FORM
   ============================================================ */

function initDeposit() {
  const form     = document.getElementById('depositForm');
  if (!form) return;

  const amountEl  = form.querySelector('[name="amount"]');
  const submitBtn = form.querySelector('[type="submit"]');
  const card      = form.closest('.card');

  // Quick amount buttons
  document.querySelectorAll('[data-amount]').forEach(btn => {
    btn.addEventListener('click', () => {
      amountEl.value = btn.dataset.amount;
      amountEl.dispatchEvent(new Event('input'));
    });
  });

  // Live fee preview (deposits have no fee, just show total)
  amountEl.addEventListener('input', () => {
    const v = parseFloat(amountEl.value) || 0;
    const preview = document.getElementById('depositPreview');
    if (preview) {
      preview.textContent = v > 0 ? `You will receive: XAF ${fmt(v)}` : '';
    }
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    removeAlert(card);

    const amount = parseFloat(amountEl.value);

    if (!amount || amount <= 0) {
      showAlert(card, 'Please enter a valid amount greater than zero.'); return;
    }
    if (amount < 500) {
      showAlert(card, 'Minimum deposit amount is XAF 500.'); return;
    }

    // Populate confirmation modal
    document.getElementById('confirmDepositAmt').textContent = `XAF ${fmt(amount)}`;
    openModal('depositConfirmModal');
  });

  // Confirmed deposit
  document.getElementById('confirmDepositBtn')?.addEventListener('click', async () => {
    const amount   = parseFloat(amountEl.value);
    const submitBtn = form.querySelector('[type="submit"]');
    const confirmBtn = document.getElementById('confirmDepositBtn');

    closeModal('depositConfirmModal');
    setLoading(submitBtn, 'Processing…');
    setLoading(confirmBtn, '');

    try {
      const res = await fetch('../wallet/deposit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ amount }),
      });

      const data = await res.json();

      if (data.success) {
        // Update balance display
        const balEl = document.getElementById('walletBalance');
        if (balEl && data.new_balance !== undefined) {
          balEl.textContent = `XAF ${fmt(data.new_balance)}`;
        }
        showAlert(card, data.message || 'Deposit successful!', 'success');
        form.reset();
        const preview = document.getElementById('depositPreview');
        if (preview) preview.textContent = '';

        // Show receipt
        if (data.txn_id) buildReceipt('depositReceipt', {
          type:   'Deposit',
          amount: `XAF ${fmt(amount)}`,
          fee:    'XAF 0',
          total:  `XAF ${fmt(amount)}`,
          ref:    `TXN-${String(data.txn_id).padStart(4, '0')}`,
          date:   new Date().toLocaleString(),
          status: 'Completed',
        });
      } else {
        showAlert(card, data.message || 'Deposit failed. Please try again.');
      }
    } catch {
      showAlert(card, 'Network error. Please try again.');
    }

    clearLoading(submitBtn);
    clearLoading(confirmBtn);
  });
}

/* ============================================================
   TRANSFER FORM
   ============================================================ */

function initTransfer() {
  const form = document.getElementById('transferForm');
  if (!form) return;

  const recipientEl = form.querySelector('[name="recipient_email"]');
  const amountEl    = form.querySelector('[name="amount"]');
  const noteEl      = form.querySelector('[name="note"]');
  const submitBtn   = form.querySelector('[type="submit"]');
  const card        = form.closest('.card');
  const FEE_RATE    = 0.01; // 1%

  // Live fee calculation
  amountEl.addEventListener('input', () => {
    const v    = parseFloat(amountEl.value) || 0;
    const fee  = v * FEE_RATE;
    const total = v + fee;
    const feeEl   = document.getElementById('feeDisplay');
    const totalEl = document.getElementById('totalDisplay');
    if (feeEl)   feeEl.textContent   = `XAF ${fmt(fee)}`;
    if (totalEl) totalEl.textContent = `XAF ${fmt(total)}`;
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    removeAlert(card);

    const amount    = parseFloat(amountEl.value);
    const recipient = recipientEl.value.trim();
    const balance   = parseFloat(document.getElementById('balanceRaw')?.value || '0');

    if (!recipient) {
      showAlert(card, 'Recipient email is required.'); return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(recipient)) {
      showAlert(card, 'Please enter a valid recipient email.'); return;
    }
    if (!amount || amount <= 0) {
      showAlert(card, 'Please enter a valid amount.'); return;
    }

    const fee   = amount * FEE_RATE;
    const total = amount + fee;

    if (total > balance) {
      showAlert(card, 'Insufficient balance to complete this transfer.'); return;
    }

    // Populate modal
    document.getElementById('modalRecipient').textContent = recipient;
    document.getElementById('modalAmount').textContent    = `XAF ${fmt(amount)}`;
    document.getElementById('modalFee').textContent       = `XAF ${fmt(fee)}`;
    document.getElementById('modalTotal').textContent     = `XAF ${fmt(total)}`;
    openModal('transferConfirmModal');
  });

  // Confirmed transfer
  document.getElementById('confirmTransferBtn')?.addEventListener('click', async () => {
    const amount    = parseFloat(amountEl.value);
    const recipient = recipientEl.value.trim();
    const note      = noteEl?.value.trim() || '';
    const confirmBtn = document.getElementById('confirmTransferBtn');

    closeModal('transferConfirmModal');
    setLoading(submitBtn, 'Sending…');
    setLoading(confirmBtn, '');

    try {
      const res = await fetch('../wallet/transfer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ recipient_email: recipient, amount, note }),
      });

      const data = await res.json();

      if (data.success) {
        const balEl = document.getElementById('walletBalance');
        if (balEl && data.new_balance !== undefined) {
          balEl.textContent = `XAF ${fmt(data.new_balance)}`;
          const rawEl = document.getElementById('balanceRaw');
          if (rawEl) rawEl.value = data.new_balance;
        }

        showAlert(card, data.message || 'Transfer successful!', 'success');
        form.reset();
        document.getElementById('feeDisplay')  && (document.getElementById('feeDisplay').textContent = 'XAF 0');
        document.getElementById('totalDisplay') && (document.getElementById('totalDisplay').textContent = 'XAF 0');

        if (data.txn_id) buildReceipt('transferReceipt', {
          type:      'Transfer',
          recipient: recipient,
          amount:    `XAF ${fmt(amount)}`,
          fee:       `XAF ${fmt(amount * FEE_RATE)}`,
          total:     `XAF ${fmt(amount * 1.01)}`,
          ref:       `TXN-${String(data.txn_id).padStart(4, '0')}`,
          date:      new Date().toLocaleString(),
          status:    'Completed',
        });
      } else {
        showAlert(card, data.message || 'Transfer failed. Please try again.');
      }
    } catch {
      showAlert(card, 'Network error. Please try again.');
    }

    clearLoading(submitBtn);
    clearLoading(confirmBtn);
  });
}

/* ============================================================
   RECEIPT BUILDER
   ============================================================ */

function buildReceipt(containerId, data) {
  const el = document.getElementById(containerId);
  if (!el) return;

  const rows = Object.entries(data)
    .filter(([k]) => k !== 'type' && k !== 'status')
    .map(([k, v]) => `
      <div class="receipt-row">
        <span>${k.charAt(0).toUpperCase() + k.slice(1)}</span>
        <strong>${v}</strong>
      </div>`)
    .join('');

  el.innerHTML = `
    <div class="receipt">
      <div class="receipt-header">
        <i class="fa-solid fa-receipt"></i> &nbsp;${data.type} Receipt
      </div>
      ${rows}
      <div class="receipt-footer">
        <i class="fa-solid fa-shield-halved"></i> &nbsp;Transaction secured by PayLite
      </div>
    </div>`;

  el.style.display = 'block';
  el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ============================================================
   TRANSACTION HISTORY — FILTER
   ============================================================ */

function initHistory() {
  const pills = document.querySelectorAll('.filter-pill[data-filter]');
  if (!pills.length) return;

  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');

      const filter = pill.dataset.filter;
      document.querySelectorAll('.tx-item[data-type]').forEach(item => {
        item.style.display = (filter === 'all' || item.dataset.type === filter)
          ? 'flex' : 'none';
      });

      // Show empty state if nothing visible
      const list     = document.getElementById('txList');
      const noItems  = [...list.querySelectorAll('.tx-item[data-type]')].every(i => i.style.display === 'none');
      const emptyEl  = document.getElementById('txEmpty');
      if (emptyEl) emptyEl.style.display = noItems ? 'block' : 'none';
    });
  });
}

/* ============================================================
   BOOT
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initDeposit();
  initTransfer();
  initHistory();
});
