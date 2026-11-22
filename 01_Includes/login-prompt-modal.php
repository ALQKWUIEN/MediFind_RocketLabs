<?php
// 01_Includes/login-prompt-modal.php
// Reusable login prompt modal for guests (non-blurred overlay).
// Optional: set $loginModalBasePath before including (e.g. '' for root, '../' for subfolders).
$loginModalBasePath = $loginModalBasePath ?? '';
?>
<style>
  .mf-login-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    background: rgba(15, 23, 42, 0.55);
    /* Explicitly no backdrop-filter blur per prototype requirement */
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  .mf-login-overlay.open {
    display: flex;
  }

  .mf-login-modal {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 420px;
    padding: 28px 26px 24px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    position: relative;
    text-align: center;
    font-family: "Plus Jakarta Sans", "Poppins", sans-serif;
    animation: mfModalPop 0.2s ease-out;
  }

  @keyframes mfModalPop {
    from {
      opacity: 0;
      transform: scale(0.95) translateY(6px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  .mf-login-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: none;
    background: #f1f5f4;
    color: #4a635a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
  }

  .mf-login-close:hover {
    background: #e2ebe8;
  }

  .mf-login-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: #e8f7f2;
    color: #1d9e75;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
  }

  .mf-login-icon .material-symbols-outlined {
    font-size: 1.8rem;
  }

  .mf-login-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a2e27;
    margin-bottom: 8px;
  }

  .mf-login-desc {
    font-size: 0.9rem;
    color: #5c756c;
    line-height: 1.55;
    margin-bottom: 22px;
  }

  .mf-login-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .mf-login-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #1d9e75;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.92rem;
    padding: 11px 18px;
    border-radius: 99px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
  }

  .mf-login-btn-primary:hover {
    background: #178360;
    color: #ffffff;
  }

  .mf-login-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f0f6f4;
    color: #1a2e27;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 10px 18px;
    border-radius: 99px;
    text-decoration: none;
    border: 1px solid #d5e6e0;
    transition: background 0.15s;
  }

  .mf-login-btn-secondary:hover {
    background: #e2efea;
    color: #1a2e27;
  }
</style>

<div class="mf-login-overlay" id="mfLoginPromptOverlay" role="dialog" aria-modal="true" aria-labelledby="mfLoginTitle">
  <div class="mf-login-modal">
    <button type="button" class="mf-login-close" id="mfLoginCloseBtn" aria-label="Close">
      <span class="material-symbols-outlined" style="font-size:1.15rem;">close</span>
    </button>
    <div class="mf-login-icon">
      <span class="material-symbols-outlined">directions</span>
    </div>
    <h3 class="mf-login-title" id="mfLoginTitle">Log in to view directions</h3>
    <p class="mf-login-desc" id="mfLoginDesc">
      Sign in to your MediFind account to get turn-by-turn directions to this pharmacy.
    </p>
    <div class="mf-login-actions">
      <a href="<?= htmlspecialchars($loginModalBasePath) ?>03_Authentication/login.php" class="mf-login-btn-primary">
        <span class="material-symbols-outlined" style="font-size:1.1rem;">login</span>
        Log In to Continue
      </a>
      <a href="<?= htmlspecialchars($loginModalBasePath) ?>03_UserSignup - Role.php" class="mf-login-btn-secondary">
        Create a Free Account
      </a>
    </div>
  </div>
</div>

<script>
  (function () {
    const overlay = document.getElementById('mfLoginPromptOverlay');
    const closeBtn = document.getElementById('mfLoginCloseBtn');
    const descEl = document.getElementById('mfLoginDesc');

    window.openLoginPromptModal = function (pharmacyName) {
      if (pharmacyName) {
        descEl.textContent = `Sign in to your MediFind account to get turn-by-turn directions to ${pharmacyName}.`;
      } else {
        descEl.textContent = 'Sign in to your MediFind account to get turn-by-turn directions to this pharmacy.';
      }
      overlay.classList.add('open');
    };

    window.closeLoginPromptModal = function () {
      overlay.classList.remove('open');
    };

    closeBtn.addEventListener('click', window.closeLoginPromptModal);
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) window.closeLoginPromptModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('open')) {
        window.closeLoginPromptModal();
      }
    });
  })();
</script>

