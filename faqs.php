<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="/07_Assets/images/logo.png" type="image/png" />
  <title>FAQs — MediFind</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <link href="07_Assets/css/00_Global CSS/landing-style2.css" rel="stylesheet" />
  <link href="07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet" />
  <link rel="stylesheet" href="07_Assets/node_modules/material-symbols/outlined.css" />
  <link rel="icon" href="07_Assets/images/logo.png" type="image/png" />

  <style>
    /* ── PAGE HERO ── */
    .page-hero {
      text-align: center;
      padding: 80px 20px 50px;
    }

    .page-tag {
      display: inline-block;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #1d9e75;
      background: #e8f7f2;
      padding: 4px 14px;
      border-radius: 100px;
      margin-bottom: 16px;
    }

    .page-hero h1 {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: clamp(2rem, 5vw, 3rem);
      font-weight: 900;
      color: #1a2e27;
      line-height: 1.15;
      margin-bottom: 14px;
    }

    .page-hero h1 span {
      color: #1d9e75;
    }

    .page-hero p {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.92rem;
      color: #6b8f82;
      max-width: 520px;
      margin: 0 auto 28px;
      line-height: 1.75;
    }

    /* ── FAQ SEARCH BAR — reuses search-wrapper style ── */
    .faq-search-wrapper {
      width: 520px;
      max-width: 100%;
      height: 50px;
      display: flex;
      align-items: center;
      background: white;
      border: 1.5px solid #8db8aa;
      border-radius: 15px;
      padding: 10px 20px;
      gap: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
      margin: 0 auto;
    }

    .faq-search-wrapper .material-symbols-outlined {
      color: #87a199;
      font-size: 1.2rem;
    }

    .faq-search-wrapper input {
      border: none;
      outline: none;
      flex: 1;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.9rem;
      color: #555;
      background: transparent;
    }

    .faq-search-wrapper input::placeholder {
      color: #87a199;
    }

    @media (max-width: 768px) {
      .faq-search-wrapper { width: 85vw; }
    }

    /* ── CATEGORY TABS ── */
    .faq-categories {
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-wrap: wrap;
      padding: 28px 20px 0;
    }

    .faq-cat-btn {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.78rem;
      font-weight: 500;
      color: #6b8f82;
      background: #f7fdf9;
      border: 1.5px solid #c8e8dd;
      border-radius: 50px;
      padding: 6px 16px;
      cursor: pointer;
      transition: all 0.18s;
    }

    .faq-cat-btn:hover,
    .faq-cat-btn.active {
      background: #1d9e75;
      color: white;
      border-color: #1d9e75;
    }

    /* ── FAQ BODY ── */
    .faq-section {
      max-width: 820px;
      margin: 0 auto;
      padding: 40px 20px 80px;
    }

    .faq-group {
      margin-bottom: 36px;
    }

    .faq-group-title {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #1d9e75;
      margin-bottom: 12px;
      padding-left: 4px;
    }

    /* ── FAQ ACCORDION ITEMS ── */
    .faq-item {
      background: white;
      border-radius: 16px;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
      margin-bottom: 10px;
      overflow: hidden;
      transition: box-shadow 0.2s;
    }

    .faq-item:hover {
      box-shadow: 0 6px 24px rgba(29, 158, 117, 0.12);
    }

    .faq-item.open {
      box-shadow: 0 8px 28px rgba(29, 158, 117, 0.15);
    }

    .faq-question {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 18px 20px;
      cursor: pointer;
      user-select: none;
    }

    .faq-question .q-text {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      color: #1a2e27;
      flex: 1;
    }

    .faq-toggle-icon {
      width: 32px;
      height: 32px;
      min-width: 32px;
      border-radius: 50%;
      background: #e8f7f2;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.18s;
    }

    .faq-toggle-icon .material-symbols-outlined {
      font-size: 1.1rem;
      color: #1d9e75;
      transition: transform 0.22s;
    }

    .faq-item.open .faq-toggle-icon {
      background: #1d9e75;
    }

    .faq-item.open .faq-toggle-icon .material-symbols-outlined {
      transform: rotate(45deg);
      color: white;
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.32s ease, padding 0.22s ease;
      padding: 0 20px;
    }

    .faq-item.open .faq-answer {
      max-height: 500px;
      padding: 0 20px 18px;
    }

    .faq-answer p {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.85rem;
      color: #6b8f82;
      line-height: 1.75;
      margin: 0;
      border-top: 1px solid #f0f0f0;
      padding-top: 14px;
    }

    .faq-answer a {
      color: #1d9e75;
      text-decoration: underline;
    }

    /* ── NO RESULTS ── */
    .faq-no-results {
      text-align: center;
      padding: 48px 20px;
      display: none;
    }

    .faq-no-results .material-symbols-outlined {
      font-size: 3rem;
      color: #c8e8dd;
      display: block;
      margin-bottom: 12px;
    }

    .faq-no-results p {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.88rem;
      color: #6b8f82;
    }

    /* ── CONTACT STRIP — reuses stats-section bg ── */
    .contact-strip {
      background: #1d9e75;
      padding: 60px 20px;
      text-align: center;
    }

    .contact-strip h2 {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: clamp(1.3rem, 3vw, 1.8rem);
      font-weight: 800;
      color: white;
      margin-bottom: 10px;
    }

    .contact-strip p {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.88rem;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 28px;
    }

    .contact-strip-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-strip-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: white;
      color: #1d9e75;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.88rem;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.2s;
    }

    .btn-strip-primary:hover {
      background: #f0faf6;
      color: #178a63;
    }

    .btn-strip-secondary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: transparent;
      color: white;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 0.88rem;
      font-weight: 500;
      padding: 12px 24px;
      border-radius: 50px;
      border: 1.5px solid rgba(255, 255, 255, 0.6);
      text-decoration: none;
      transition: all 0.2s;
    }

    .btn-strip-secondary:hover {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      border-color: white;
    }

    /* ── HIDDEN STATES ── */
    .faq-item.hidden { display: none; }
    .faq-group.hidden { display: none; }
  </style>

    <!-- Page transition -->
  <?php include '01_Includes/page-transition-hardcode.php' ?>
</head>

<body class="landing-page scrollable">

  <?php include_once '01_Includes/navbar_landing-role.php' ?>

  <!-- PAGE HERO -->
  <div class="page-hero">
    <div class="page-tag">Help Center</div>
    <h1>Frequently Asked <span>Questions</span></h1>
    <p>Everything you need to know about MediFind. Can't find your answer? Reach out to us anytime.</p>
    <div class="faq-search-wrapper">
      <span class="material-symbols-outlined">search</span>
      <input type="text" id="faqSearch" placeholder="Search questions..." />
    </div>
  </div>

  <!-- CATEGORY TABS -->
  <div class="faq-categories">
    <button class="faq-cat-btn active" data-cat="all">All</button>
    <button class="faq-cat-btn" data-cat="general">General</button>
    <button class="faq-cat-btn" data-cat="search">Searching Medicines</button>
    <button class="faq-cat-btn" data-cat="account">Account & Privacy</button>
    <button class="faq-cat-btn" data-cat="pharmacy">Pharmacies</button>
    <button class="faq-cat-btn" data-cat="scanrx">Scan RX</button>
  </div>

  <!-- FAQ ACCORDION -->
  <div class="faq-section">

    <!-- GENERAL -->
    <div class="faq-group" data-group="general">
      <div class="faq-group-title">General</div>

      <div class="faq-item" data-cat="general">
        <div class="faq-question">
          <span class="q-text">What is MediFind?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>MediFind is a free, web-based platform that helps residents of Malaybalay, Bukidnon quickly check which local pharmacies have specific medicines available in stock — before making the trip.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="general">
        <div class="faq-question">
          <span class="q-text">Is MediFind free to use?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Yes, MediFind is completely free for all users. You can search for medicine availability without even creating an account. Signing up is optional but unlocks additional features like notifications and search history.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="general">
        <div class="faq-question">
          <span class="q-text">What areas does MediFind cover?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Currently, MediFind covers pharmacies within Malaybalay City, Bukidnon. We plan to expand coverage to other municipalities in Bukidnon in the future.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="general">
        <div class="faq-question">
          <span class="q-text">How is MediFind different from just calling a pharmacy?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>With MediFind, you can check multiple pharmacies at once in seconds — no need to call each one individually. Our platform also supports smart search by generic name, brand name, or even partial spelling, so you always get the most relevant results.</p>
        </div>
      </div>
    </div>

    <!-- SEARCHING MEDICINES -->
    <div class="faq-group" data-group="search">
      <div class="faq-group-title">Searching Medicines</div>

      <div class="faq-item" data-cat="search">
        <div class="faq-question">
          <span class="q-text">How do I search for a medicine?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Simply type the medicine name in the search bar on the homepage. You can use the brand name, generic name, or even a partial spelling. MediFind's AI-powered search will match and display relevant results along with which pharmacies carry it.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="search">
        <div class="faq-question">
          <span class="q-text">Can I search by generic name?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Yes! MediFind supports searching by generic names (e.g., "amoxicillin"), brand names (e.g., "Amoxil"), and even partial or misspelled names. Our AI search is designed to understand and suggest the most relevant matches.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="search">
        <div class="faq-question">
          <span class="q-text">How accurate and current is the stock information?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Stock information is updated directly by partner pharmacies. Availability is as accurate as the data provided by each pharmacy. We encourage pharmacies to update their inventory regularly, but we recommend calling ahead to confirm for critical medicines.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="search">
        <div class="faq-question">
          <span class="q-text">What if a medicine I'm looking for isn't listed?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>If a medicine isn't found, you can sign up for a notification alert. Once you create an account and save the medicine to your watchlist, we'll notify you when it becomes available at a nearby pharmacy.</p>
        </div>
      </div>
    </div>

    <!-- ACCOUNT & PRIVACY -->
    <div class="faq-group" data-group="account">
      <div class="faq-group-title">Account &amp; Privacy</div>

      <div class="faq-item" data-cat="account">
        <div class="faq-question">
          <span class="q-text">Do I need an account to use MediFind?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>No account is required to search for medicines or browse nearby pharmacies. Creating a free account unlocks additional features like saving searches, receiving availability notifications, and viewing your history.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="account">
        <div class="faq-question">
          <span class="q-text">Is my search history private?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Yes. Your search history is private and tied only to your account. We never sell or share your personal data with third parties. You can delete your search history at any time from your account settings.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="account">
        <div class="faq-question">
          <span class="q-text">How do I delete my account?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>You can delete your account at any time from your account settings page. All your personal data and search history will be permanently removed within 30 days. If you need help, contact us at <a href="mailto:support@medifind.ph">support@medifind.ph</a>.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="account">
        <div class="faq-question">
          <span class="q-text">How do I reset my password?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>On the login page, click "Forgot Password" and enter your registered email address. You'll receive a password reset link within a few minutes. Check your spam folder if you don't see it in your inbox.</p>
        </div>
      </div>
    </div>

    <!-- PHARMACIES -->
    <div class="faq-group" data-group="pharmacy">
      <div class="faq-group-title">Pharmacies</div>

      <div class="faq-item" data-cat="pharmacy">
        <div class="faq-question">
          <span class="q-text">How do pharmacies update their inventory?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Partner pharmacies log in to their dedicated pharmacy dashboard to update their medicine inventory. They can add, edit, or remove items and mark medicines as in stock, low stock, or out of stock.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="pharmacy">
        <div class="faq-question">
          <span class="q-text">How can my pharmacy join MediFind?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Pharmacies in Malaybalay can register by contacting us at <a href="mailto:pharmacies@medifind.ph">pharmacies@medifind.ph</a> or by clicking "Register a Pharmacy" on the homepage. The registration process is free and typically takes 1–2 business days for verification.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="pharmacy">
        <div class="faq-question">
          <span class="q-text">How do I get directions to a pharmacy?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>On any pharmacy card, click the "Directions" button. MediFind will open a map with turn-by-turn directions from your current location to the pharmacy.</p>
        </div>
      </div>
    </div>

    <!-- SCAN RX -->
    <div class="faq-group" data-group="scanrx">
      <div class="faq-group-title">Scan RX</div>

      <div class="faq-item" data-cat="scanrx">
        <div class="faq-question">
          <span class="q-text">What is Scan RX?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Scan RX is a feature that lets you photograph your prescription. MediFind's AI will read the prescription, identify all medicines listed, and automatically search for their availability at nearby pharmacies — all at once.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="scanrx">
        <div class="faq-question">
          <span class="q-text">Is my prescription image stored or saved?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>Prescription images are processed in real time and are not permanently stored on our servers after the search is completed. We take your medical privacy seriously and do not retain images beyond the active session.</p>
        </div>
      </div>

      <div class="faq-item" data-cat="scanrx">
        <div class="faq-question">
          <span class="q-text">What if Scan RX can't read my prescription?</span>
          <div class="faq-toggle-icon"><span class="material-symbols-outlined">add</span></div>
        </div>
        <div class="faq-answer">
          <p>If the scan fails, you can manually type in each medicine name in the search bar. Make sure your prescription image is well-lit and in focus for the best results. Handwritten prescriptions may have lower accuracy than printed ones.</p>
        </div>
      </div>
    </div>

    <!-- NO RESULTS -->
    <div class="faq-no-results" id="noResults">
      <span class="material-symbols-outlined">search_off</span>
      <p>No questions match your search. Try different keywords or <a href="mailto:support@medifind.ph">contact us</a> directly.</p>
    </div>

  </div>

  <!-- CONTACT STRIP -->
  <section class="contact-strip">
    <h2>Still have questions?</h2>
    <p>Our support team is happy to help. Reach out anytime.</p>
    <div class="contact-strip-actions">
      <a href="mailto:support@medifind.ph" class="btn-strip-primary">
        <span class="material-symbols-outlined" style="font-size:1.1rem;">mail</span>
        Email Support
      </a>
      <a href="#" class="btn-strip-secondary">
        <span class="material-symbols-outlined" style="font-size:1.1rem;">smart_toy</span>
        Ask the AI Bot
      </a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="footer-content">
      <p class="footer-brand">MediFind</p>
      <p class="footer-text">Malaybalay Medicine Availability Checker</p>
      <p class="footer-copy">© 2026 MediFind. All rights reserved.</p>
    </div>
  </footer>

  <script>
    // ── Accordion ──
    document.querySelectorAll('.faq-question').forEach(btn => {
      btn.addEventListener('click', () => {
        const item = btn.closest('.faq-item');
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
      });
    });

    // ── Category filter ──
    document.querySelectorAll('.faq-cat-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.cat;
        document.querySelectorAll('.faq-item').forEach(item => {
          const match = cat === 'all' || item.dataset.cat === cat;
          item.classList.toggle('hidden', !match);
          if (!match) item.classList.remove('open');
        });
        syncGroups();
        checkNoResults();
      });
    });

    // ── Live search ──
    document.getElementById('faqSearch').addEventListener('input', function () {
      const q = this.value.toLowerCase().trim();
      document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
      document.querySelector('[data-cat="all"]').classList.add('active');
      document.querySelectorAll('.faq-item').forEach(item => {
        const text = item.querySelector('.q-text').textContent.toLowerCase()
          + ' ' + item.querySelector('.faq-answer p').textContent.toLowerCase();
        const match = !q || text.includes(q);
        item.classList.toggle('hidden', !match);
        if (!match) item.classList.remove('open');
      });
      syncGroups();
      checkNoResults();
    });

    function syncGroups() {
      document.querySelectorAll('.faq-group').forEach(group => {
        const visible = group.querySelectorAll('.faq-item:not(.hidden)').length;
        group.classList.toggle('hidden', visible === 0);
      });
    }

    function checkNoResults() {
      const any = document.querySelectorAll('.faq-item:not(.hidden)').length > 0;
      document.getElementById('noResults').style.display = any ? 'none' : 'block';
    }
  </script>

  <script>
  window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 10) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
</script>

</body>
</html>