/**
 * PayLite — assets/js/auth.js
 * Handles login and register forms dynamically.
 * Targets inputs by name attribute — no IDs required.
 */

/* ============================================================
   UTILITIES
   ============================================================ */
   console.log('auth.js loaded');

function showFieldError(input, msg) {
  clearFieldError(input);
  input.classList.add('is-error');
  const p = document.createElement('p');
  p.className = 'field-error';
  p.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${msg}`;
  input.closest('.field').appendChild(p);
}

function clearFieldError(input) {
  input.classList.remove('is-error');
  const existing = input.closest('.field')?.querySelector('.field-error');
  if (existing) existing.remove();
}

function showBanner(container, msg, type = 'danger') {
  removeBanner(container);
  const d = document.createElement('div');
  d.className = `alert alert-${type} auth-banner`;
  const icon = type === 'success'
    ? 'fa-circle-check'
    : type === 'warning'
    ? 'fa-triangle-exclamation'
    : 'fa-circle-xmark';
  d.innerHTML = `<i class="fa-solid ${icon}"></i> ${msg}`;
  container.prepend(d);
  if (type === 'success') setTimeout(() => d.remove(), 5000);
}

function removeBanner(container) {
  container.querySelector('.auth-banner')?.remove();
}

function setLoading(btn, text = 'Please wait…') {
  btn.disabled = true;
  btn.dataset.orig = btn.innerHTML;
  btn.innerHTML = `<span class="spinner"></span> ${text}`;
}

function clearLoading(btn) {
  btn.disabled = false;
  btn.innerHTML = btn.dataset.orig;
}

function shakeCard(card) {
  card.classList.remove('shake');
  void card.offsetWidth;
  card.classList.add('shake');
  card.addEventListener('animationend', () => card.classList.remove('shake'), { once: true });
}

/* ============================================================
   VALIDATORS
   ============================================================ */

const V = {
  required:     v => v.trim().length > 0,
  email:        v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
  minLen:       (v, n) => v.length >= n,
  hasUpper:     v => /[A-Z]/.test(v),
  hasNumber:    v => /[0-9]/.test(v),
  matches:      (a, b) => a === b,
};

/* ============================================================
   PASSWORD TOGGLE
   ============================================================ */

function addPwToggle(input) {
  if (!input) return;
  const wrap = input.parentElement;
  if (!wrap.classList.contains('input-wrap')) {
    const w = document.createElement('div');
    w.className = 'input-wrap';
    input.parentNode.insertBefore(w, input);
    w.appendChild(input);
  }

  const btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'pw-toggle';
  btn.setAttribute('aria-label', 'Toggle password visibility');
  btn.innerHTML = '<i class="fa-regular fa-eye"></i>';
  input.parentElement.appendChild(btn);
  input.style.paddingRight = '40px';

  btn.addEventListener('click', () => {
    if (input.type === 'password') {
      input.type = 'text';
      btn.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
    } else {
      input.type = 'password';
      btn.innerHTML = '<i class="fa-regular fa-eye"></i>';
    }
  });
}

/* ============================================================
   PASSWORD STRENGTH METER
   ============================================================ */

function addStrengthMeter(input) {
  if (!input) return;
  const bar = document.createElement('div');
  bar.className = 'strength-bar';
  const fill = document.createElement('div');
  fill.className = 'strength-fill';
  bar.appendChild(fill);

  const label = document.createElement('span');
  label.className = 'strength-label';

  const field = input.closest('.field');
  field.appendChild(bar);
  field.appendChild(label);

  input.addEventListener('input', () => {
    const v = input.value;
    let s = 0;
    if (v.length >= 8)           s++;
    if (v.length >= 12)          s++;
    if (/[A-Z]/.test(v))         s++;
    if (/[0-9]/.test(v))         s++;
    if (/[^A-Za-z0-9]/.test(v))  s++;

    const lvls = [
      { w: '0%',   c: 'transparent',       t: '' },
      { w: '25%',  c: 'var(--danger)',      t: 'Weak' },
      { w: '50%',  c: 'var(--amber)',       t: 'Fair' },
      { w: '75%',  c: 'var(--amber)',       t: 'Good' },
      { w: '90%',  c: 'var(--teal)',        t: 'Strong' },
      { w: '100%', c: 'var(--teal-light)',  t: 'Very strong' },
    ];

    const l = lvls[Math.min(s, 5)];
    fill.style.width      = v.length ? l.w : '0%';
    fill.style.background = v.length ? l.c : 'transparent';
    label.textContent     = v.length ? l.t : '';
  });
}

/* ============================================================
   REGISTER FORM
   ============================================================ */

function initRegister() {
  const form = document.getElementById('registerForm');
  if (!form) return;

  const nameEl    = form.querySelector('[name="name"]');
  const emailEl   = form.querySelector('[name="email"]');
  const passEl    = form.querySelector('[name="password"]');
  const confirmEl = form.querySelector('[name="Cpassword"]');
  const submitBtn = form.querySelector('button[type="submit"]');
  const card      = form.closest('.auth-card');

  addPwToggle(passEl);
  addPwToggle(confirmEl);
  addStrengthMeter(passEl);

  // Live clear
  nameEl.addEventListener('input',    () => clearFieldError(nameEl));
  emailEl.addEventListener('input',   () => clearFieldError(emailEl));
  passEl.addEventListener('input',    () => clearFieldError(passEl));
  confirmEl.addEventListener('input', () => {
    clearFieldError(confirmEl);
    if (confirmEl.value && !V.matches(passEl.value, confirmEl.value)) {
      showFieldError(confirmEl, 'Passwords do not match.');
    }
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    removeBanner(card);

    let ok = true;

    if (!V.required(nameEl.value)) {
      showFieldError(nameEl, 'Full name is required.'); ok = false;
    } else if (!V.minLen(nameEl.value.trim(), 2)) {
      showFieldError(nameEl, 'Name must be at least 2 characters.'); ok = false;
    }

    if (!V.required(emailEl.value)) {
      showFieldError(emailEl, 'Email is required.'); ok = false;
    } else if (!V.email(emailEl.value)) {
      showFieldError(emailEl, 'Enter a valid email address.'); ok = false;
    }

    if (!V.required(passEl.value)) {
      showFieldError(passEl, 'Password is required.'); ok = false;
    } else if (!V.minLen(passEl.value, 8)) {
      showFieldError(passEl, 'Password must be at least 8 characters.'); ok = false;
    } else if (!V.hasUpper(passEl.value)) {
      showFieldError(passEl, 'Password needs at least one uppercase letter.'); ok = false;
    } else if (!V.hasNumber(passEl.value)) {
      showFieldError(passEl, 'Password needs at least one number.'); ok = false;
    }

    if (!V.required(confirmEl.value)) {
      showFieldError(confirmEl, 'Please confirm your password.'); ok = false;
    } else if (!V.matches(passEl.value, confirmEl.value)) {
      showFieldError(confirmEl, 'Passwords do not match.'); ok = false;
    }

    if (!ok) { shakeCard(card); return; }

    setLoading(submitBtn, 'Creating account…');

    try {
      const res = await fetch('http://localhost/paylite/auth/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          name:             nameEl.value.trim(),
          email:            emailEl.value.trim(),
          password:         passEl.value,
          confirm_password: confirmEl.value,
        }),
      });
       console.log('Response status:', res.status);
      const data = await res.json();
      console.log('Response data:', data);

      if (data.success) {
        showBanner(card, data.message || 'Account created! Redirecting to login…', 'success');
        form.reset();
        setTimeout(() => { window.location.href = data.redirect || 'login.html'; }, 1500);
      } else {
        showBanner(card, data.message || 'Registration failed. Please try again.');
        shakeCard(card);
        clearLoading(submitBtn);
      }
    } catch {
      showBanner(card, 'Network error. Please check your connection.');
      shakeCard(card);
      clearLoading(submitBtn);
    }
  });
}

/* ============================================================
   LOGIN FORM
   ============================================================ */

function initLogin() {
  const form = document.getElementById('loginForm');
  if (!form) return;

  const emailEl   = form.querySelector('[name="email"]');
  const passEl    = form.querySelector('[name="password"]');
  const submitBtn = form.querySelector('button[type="submit"]');
  const card      = form.closest('.auth-card');

  addPwToggle(passEl);

  emailEl.addEventListener('input', () => clearFieldError(emailEl));
  passEl.addEventListener('input',  () => clearFieldError(passEl));

  form.addEventListener('submit', async e => {
    e.preventDefault();
    removeBanner(card);

    let ok = true;

    if (!V.required(emailEl.value)) {
      showFieldError(emailEl, 'Email is required.'); ok = false;
    } else if (!V.email(emailEl.value)) {
      showFieldError(emailEl, 'Enter a valid email address.'); ok = false;
    }

    if (!V.required(passEl.value)) {
      showFieldError(passEl, 'Password is required.'); ok = false;
    }

    if (!ok) { shakeCard(card); return; }

    setLoading(submitBtn, 'Signing in…');

    try {
      const res = await fetch('http://localhost/paylite/auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          email:    emailEl.value.trim(),
          password: passEl.value,
        }),
      });

      const data = await res.json();

      if (data.success) {
        showBanner(card, 'Login successful! Redirecting…', 'success');
        setTimeout(() => { window.location.href = data.redirect || '../pages/dashboard.php'; }, 800);
      } else {
        showBanner(card, data.message || 'Incorrect email or password.');
        shakeCard(card);
        clearLoading(submitBtn);
      }
    } catch {
      showBanner(card, 'Network error. Please check your connection.');
      shakeCard(card);
      clearLoading(submitBtn);
    }
  });
}

/* ============================================================
   BOOT
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
  initRegister();
  initLogin();
});