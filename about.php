<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About — MediFind</title>
    <link rel="icon" href="07_Assets/images/logo.png" type="image/png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <link href="07_Assets/css/00_Global CSS/landing-style2.css" rel="stylesheet" />
    <link href="07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet" />
    <link rel="stylesheet" href="07_Assets/node_modules/material-symbols/outlined.css" />

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
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.75;
        }

        /* ── MISSION ── */
        .mission-section {
            padding: 80px 20px;
            background: white;
        }

        .mission-inner {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        @media (max-width: 768px) {
            .mission-inner {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }

        .mission-visual {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mission-blob {
            width: 220px;
            height: 220px;
            border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
            background: #e8f7f2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mission-blob .material-symbols-outlined {
            font-size: 4.5rem;
            color: #1d9e75;
        }

        .mission-text .section-title {
            margin-bottom: 16px;
        }

        .mission-text p {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.88rem;
            color: #6b8f82;
            line-height: 1.75;
            margin-bottom: 12px;
        }

        /* ── VALUES ── */
        .values-section {
            padding: 80px 20px;
            background: #f7fdf9;
        }

        .values-inner {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        @media (max-width: 768px) {
            .values-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .values-grid {
                grid-template-columns: 1fr;
            }
        }

        .value-card {
            background: white;
            border-radius: 20px;
            padding: 28px 20px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
            text-align: left;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .value-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(29, 158, 117, 0.15);
        }

        .value-icon {
            width: 48px;
            height: 48px;
            background: #e8f7f2;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1d9e75;
            margin-bottom: 16px;
        }

        .value-card h4 {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a2e27;
            margin-bottom: 8px;
        }

        .value-card p {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.78rem;
            color: #6b8f82;
            line-height: 1.6;
            margin: 0;
        }

        /* ── TEAM ── */
        .team-section {
            padding: 80px 20px;
            background: white;
        }

        .team-inner {
            max-width: 960px;
            margin: 0 auto;
            text-align: center;
        }

        .team-inner>p {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.88rem;
            color: #6b8f82;
            max-width: 520px;
            margin: 0 auto 48px;
            line-height: 1.7;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
        }

        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .team-grid {
                grid-template-columns: 1fr;
            }
        }

        .team-card {
            text-align: left;
        }

        .card-photo-wrap {
            position: relative;
            width: 100%;
            padding-top: 110%;
            border-radius: 14px;
            overflow: visible;
            /* ← changed from hidden */
            margin-bottom: 14px;
        }

        .card-photo-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 14px;
            z-index: 0;
            /* ← add this */
        }

        /* Individual accent colors — change these freely */
        .team-card:nth-child(1) .card-photo-wrap::before {
            background: #b2ecd8;
        }

        .team-card:nth-child(2) .card-photo-wrap::before {
            background: #ffd6a0;
        }

        .team-card:nth-child(3) .card-photo-wrap::before {
            background: #a8d8f8;
        }

        .team-card:nth-child(4) .card-photo-wrap::before {
            background: #f4b8c8;
        }

        .card-photo-wrap img {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 100%;
            /* ← taller so it overflows upward */
            object-fit: contain;
            object-position: bottom center;
            z-index: 1;
            /* ← sits above the background block */
        }

        .card-name {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 0.97rem;
            font-weight: 700;
            color: #1a2e27;
            margin-bottom: 4px;
        }

        .card-name .first {
            color: #1d9e75;
        }

        .card-role {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            color: #6b8f82;
        }
    </style>

      <!-- Page transition -->
  <?php include '01_Includes/page-transition-hardcode.php' ?>
</head>

<body class="landing-page scrollable">

    <?php include_once '01_Includes/navbar_landing-role.php' ?>

    <!-- PAGE HERO -->
    <div class="page-hero">
        <div class="page-tag">About MediFind</div>
        <h1>Medicine access, <span>made easy</span><br>for everyone in Malaybalay.</h1>
        <p>MediFind was built to solve a simple but urgent problem — people wasting time visiting pharmacy after pharmacy looking for a medicine that may or may not be in stock.</p>
    </div>

    <!-- MISSION -->
    <section class="mission-section">
        <div class="mission-inner">
            <div class="mission-visual">
                <div class="mission-blob">
                    <span class="material-symbols-outlined">medication</span>
                </div>
            </div>
            <div class="mission-text">
                <div class="section-label">Our Mission</div>
                <h2 class="section-title">Connecting patients with the medicines they need — fast.</h2>
                <p>MediFind is a free, community-driven platform that helps residents of Malaybalay, Bukidnon quickly check which local pharmacies carry specific medicines — before they even step outside.</p>
                <p>We believe that no one should have to travel from pharmacy to pharmacy, especially when they or a loved one is unwell. With real-time inventory data and smart search tools, MediFind puts the right information at your fingertips.</p>
            </div>
        </div>
    </section>


    <!-- VALUES -->
    <section class="values-section">
        <div class="values-inner">
            <div class="section-label">What We Stand For</div>
            <h2 class="section-title">Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <span class="material-symbols-outlined">favorite</span>
                    </div>
                    <h4>Community First</h4>
                    <p>MediFind exists to serve the people of Malaybalay. Every feature we build is driven by real community needs.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <span class="material-symbols-outlined">bolt</span>
                    </div>
                    <h4>Speed & Accuracy</h4>
                    <p>We prioritize real-time data so that when you search, you can trust what you see is current and correct.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <span class="material-symbols-outlined">lock</span>
                    </div>
                    <h4>Privacy & Security</h4>
                    <p>Your search history and personal health data are yours. We never sell, share, or misuse your information.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <span class="material-symbols-outlined">diversity_3</span>
                    </div>
                    <h4>Accessibility for All</h4>
                    <p>MediFind is free for all residents. We design for simplicity so anyone — young or old — can use it with ease.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features-section">
        <div class="features-inner">
            <div class="features-text">
                <div class="section-label">Why We Built This</div>
                <h2 class="section-title">A tool built for<br />real people.</h2>
                <p class="features-desc">MediFind is built for the people of Malaybalay — a free, easy-to-use tool that saves you time and stress when finding medicines for yourself or your family.</p>
                <a href="03_Authentication/signup.php" class="btn-get-started">
                    Get Started Free
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div class="feature-content">
                        <h4>Community-Driven</h4>
                        <p>Built with and for the residents of Malaybalay, reflecting real local needs.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="material-symbols-outlined">savings</span>
                    </div>
                    <div class="feature-content">
                        <h4>Always Free</h4>
                        <p>No subscriptions, no ads, no hidden fees. MediFind is free for everyone, always.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="material-symbols-outlined">devices</span>
                    </div>
                    <div class="feature-content">
                        <h4>Works on Any Device</h4>
                        <p>Access MediFind from your phone, tablet, or computer — no app download needed.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <span class="material-symbols-outlined">language</span>
                    </div>
                    <div class="feature-content">
                        <h4>Local by Design</h4>
                        <p>Every detail — pharmacies, medicines, coverage — is focused on Malaybalay and its residents.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="team-inner">
            <div class="section-label">The People Behind It</div>
            <h2 class="section-title">Meet the Team</h2>
            <p>A group of students and developers from Malaybalay passionate about using technology to improve local healthcare access.</p>
            <div class="team-grid">

                <div class="team-card">
                    <div class="card-photo-wrap">
                        <img src="07_Assets/images/the_team/1.png" alt="Dayan Balansag">
                    </div>
                    <div class="card-name"><span class="first">DAYAN</span> BALANSAG</div>
                    <div class="card-role">Project Leader</div>
                </div>

                <div class="team-card">
                    <div class="card-photo-wrap">
                        <img src="07_Assets/images/the_team/2.png" alt="Cedric Cagas">
                    </div>
                    <div class="card-name"><span class="first">JOHN CEDRIC</span> CAGAS</div>
                    <div class="card-role">System Analyst / Document</div>
                </div>

                <div class="team-card">
                    <div class="card-photo-wrap">
                        <img src="07_Assets/images/the_team/3.png" alt="Alquien Capuyan">
                    </div>
                    <div class="card-name"><span class="first">ALQUIEN</span> CAPUYAN</div>
                    <div class="card-role">Developer / Programmer</div>
                </div>

                <div class="team-card">
                    <div class="card-photo-wrap">
                        <img src="07_Assets/images/the_team/4.png" alt="Hazel Carillo">
                    </div>
                    <div class="card-name"><span class="first">HAZEL ANN</span> CARILLO</div>
                    <div class="card-role">UI/UX Designer</div>
                </div>

            </div>
        </div>
    </section>
    <!-- CTA -->
    <section class="cta-section">
        <div class="cta-inner">
            <div class="cta-icon">
                <span class="material-symbols-outlined">search</span>
            </div>
            <h2>Ready to find your medicine?</h2>
            <p>Start searching now — no account needed. Sign up to unlock notifications and save your search history.</p>
            <div class="cta-buttons">
                <a href="03_Authentication/signup.php" class="btn-cta-primary">Create Free Account</a>
                <a href="index.php" class="btn-cta-secondary">Search Medicines</a>
            </div>
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