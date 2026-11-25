<?php
// 01_Includes/medicine-map-component.php
// Single shared Medicine Map component (used by index.php and 04_User/02.3_MedicineMap.php).
// Edit this file to update the map design across all pages that display the map.

$mapBasePath    = $mapBasePath ?? '';
$mapMode        = $mapMode ?? 'overlay'; // 'overlay' | 'embedded'
$mapInitialQuery = $mapInitialQuery ?? '';
$mapBackHref    = $mapBackHref ?? '';
$mapIsLoggedIn  = !empty($_SESSION['user_id']);

$mfMedsPath   = __DIR__ . '/../database/medicines_db.json';
$mfPharmsPath = __DIR__ . '/../database/pharmacies_db.json';
$mfMedsList   = file_exists($mfMedsPath) ? (json_decode(file_get_contents($mfMedsPath), true)['medicines'] ?? []) : [];
$mfPharmsList = file_exists($mfPharmsPath) ? (json_decode(file_get_contents($mfPharmsPath), true)['pharmacies'] ?? []) : [];
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  .guest-map-view {
    position: fixed;
    inset: 0;
    z-index: 1500;
    background: #f4f7f6;
    display: flex;
    flex-direction: column;
    opacity: 0;
    visibility: hidden;
    transform: translateY(16px);
    transition: opacity 0.35s ease, transform 0.35s ease, visibility 0.35s ease;
    font-family: "Plus Jakarta Sans", "Poppins", sans-serif;
  }
  .guest-map-view.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
  .guest-map-view.embedded-mode {
    position: relative;
    inset: auto;
    z-index: 1;
    width: 100%;
    height: 100%;
    opacity: 1;
    visibility: visible;
    transform: none;
  }
  .guest-map-topbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: #ffffff;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    z-index: 10;
  }
  .guest-map-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1px solid #dce9e3;
    border-radius: 99px;
    padding: 7px 14px 7px 10px;
    font-size: 0.86rem;
    font-weight: 600;
    color: #3f444d;
    cursor: pointer;
    text-decoration: none;
    flex-shrink: 0;
  }
  .guest-map-back:hover {
    background: #eef9f4;
    border-color: #5dcaa5;
    color: #0f6e56;
  }
  .guest-map-searchbar {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f0f4f2;
    border: 1.5px solid #e2e8e6;
    border-radius: 99px;
    padding: 6px 14px;
  }
  .guest-map-searchbar:focus-within {
    border-color: #1d9e75;
  }
  .guest-map-searchbar input {
    border: none;
    background: transparent;
    outline: none;
    width: 100%;
    font-size: 0.88rem;
    color: #1a2e27;
  }
  .guest-map-searchbar input:focus-visible {
    outline: none !important;
  }
  .guest-map-stage {
    position: relative;
    flex: 1;
    overflow: hidden;
  }
  #guestLeafletMap {
    position: absolute;
    inset: 0;
    z-index: 1;
  }
  #guestLeafletMap .leaflet-tile-container img {
    width: 258px !important;
    height: 258px !important;
    outline: 1px solid transparent;
  }
  .mf-route-eta-badge {
    background: #facc15;
    color: #1a2e27;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 0.78rem;
    font-weight: 800;
    padding: 5px 11px;
    border-radius: 10px;
    box-shadow: 0 4px 14px rgba(202, 138, 4, 0.38);
    border: 2px solid #ffffff;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  @keyframes mfRouteFlow {
    from { stroke-dashoffset: 48; }
    to { stroke-dashoffset: 0; }
  }
  .mf-route-flow-line {
    animation: mfRouteFlow 0.9s linear infinite !important;
  }
  @keyframes mfUserPulse {
    0% { transform: scale(0.85); opacity: 0.8; }
    70% { transform: scale(1.65); opacity: 0; }
    100% { transform: scale(1.65); opacity: 0; }
  }
  @keyframes mfPinBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
  }
  .mf-pin-bounce {
    animation: mfPinBounce 1.35s ease-in-out infinite;
    will-change: transform;
  }
  .mf-user-beacon {
    position: relative;
    width: 28px;
    height: 28px;
  }
  .mf-user-beacon-ring {
    position: absolute;
    inset: -10px;
    border-radius: 50%;
    background: rgba(250, 204, 21, 0.45);
    animation: mfUserPulse 1.8s ease-out infinite;
  }
  .mf-user-beacon-dot {
    position: relative;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #facc15;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 14px rgba(202, 138, 4, 0.55);
  }
  .guest-locate-btn {
    position: absolute;
    top: 14px;
    left: 56px;
    z-index: 480;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #d5e2dd;
    color: #1a2e27;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
    cursor: pointer;
  }
  .guest-locate-btn:hover {
    background: #eef9f4;
    color: #1d9e75;
  }
  .guest-route-panel {
    display: block;
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 500;
    width: 320px;
    max-width: calc(100vw - 28px);
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 12px 32px rgba(29, 158, 117, 0.18);
    padding: 13px 15px;
    border: 1.5px solid #1d9e75;
    max-height: 240px;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(-8px) scale(0.98);
    transition: opacity 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                border-radius 0.28s ease,
                padding 0.28s ease,
                visibility 0.28s;
  }
  .guest-route-panel.open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0) scale(1);
    overflow-y: auto;
  }
  .guest-route-panel.minimized {
    overflow: hidden;
  }
  .guest-route-body {
    max-height: 200px;
    opacity: 1;
    overflow: hidden;
    transition: max-height 0.34s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.24s ease;
  }
  .guest-route-panel.minimized .guest-route-body {
    max-height: 0;
    opacity: 0;
  }
  #guestRouteMinBtn .material-symbols-outlined {
    transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .guest-route-panel.minimized #guestRouteMinBtn .material-symbols-outlined {
    transform: rotate(180deg);
  }
  .guest-route-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 6px;
    transition: margin-bottom 0.28s ease;
  }
  .guest-route-panel.minimized .guest-route-header {
    margin-bottom: 0;
  }
  .guest-route-panel h6 {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1a2e27;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
  }
  .guest-route-icon-btn {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    border: none;
    background: #f1f5f9;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .guest-route-icon-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
  }
  .guest-route-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e8f7f2;
    color: #0f6e56;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 99px;
    margin-bottom: 8px;
  }
  .guest-route-steps {
    margin: 0;
    padding-left: 16px;
    font-size: 0.76rem;
    color: #4a635a;
    line-height: 1.45;
  }
  .guest-route-steps li {
    margin-bottom: 3px;
  }
  .guest-map-sheet {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 550;
    background: #ffffff;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 -6px 28px rgba(0, 0, 0, 0.12);
    padding: 12px 16px 16px;
    opacity: 0;
    transform: translateY(100%);
    pointer-events: none;
    transition: opacity 0.45s cubic-bezier(0.22, 1, 0.36, 1), transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
  }
  .guest-map-sheet.revealed {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
  }
  .guest-sheet-toggle-pill {
    display: inline-flex;
    position: absolute;
    bottom: 20px;
    right: 16px;
    z-index: 545;
    background: #1d9e75;
    color: #ffffff;
    border: none;
    border-radius: 99px;
    padding: 10px 18px;
    font-size: 0.84rem;
    font-weight: 600;
    box-shadow: 0 6px 20px rgba(29, 158, 117, 0.32);
    align-items: center;
    gap: 6px;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(12px) scale(0.94);
    transition: opacity 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                visibility 0.28s;
  }
  .guest-sheet-toggle-pill.visible {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0) scale(1);
  }
  .guest-sheet-hide-btn {
    width: 30px;
    height: 30px;
    border-radius: 99px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
  }
  .guest-sheet-hide-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
  }
  @media (min-width: 769px) {
    /* Landing page (overlay mode): left-side floating panel */
    .guest-map-view:not(.embedded-mode) .guest-map-sheet {
      top: 14px;
      left: 14px;
      bottom: 14px;
      right: auto;
      width: 440px;
      max-width: calc(100vw - 28px);
      border-radius: 18px;
      box-shadow: 0 10px 32px rgba(15, 23, 42, 0.14);
      border: 1px solid #e2e8f0;
      padding: 16px;
      overflow-y: auto;
      scrollbar-width: thin;
      transform: translateX(calc(-100% - 24px));
    }
    .guest-map-view:not(.embedded-mode) .guest-map-sheet.revealed {
      transform: translateX(0);
    }
    .guest-map-view:not(.embedded-mode) .guest-sheet-handle {
      display: none;
    }
    .guest-map-view:not(.embedded-mode) .guest-cards-row {
      grid-template-columns: 1fr !important;
      overflow-x: visible !important;
      gap: 12px;
    }
    .guest-map-view:not(.embedded-mode) #guestLeafletMap .leaflet-top.leaflet-left {
      left: auto;
      right: 14px;
    }
    .guest-map-view:not(.embedded-mode) .guest-locate-btn {
      left: auto;
      right: 56px;
      top: 14px;
    }
    .guest-map-view:not(.embedded-mode) .guest-route-panel {
      top: 96px;
      right: 14px;
      left: auto;
    }
    .guest-map-view:not(.embedded-mode) .guest-sheet-toggle-pill {
      top: 14px;
      left: 14px;
      right: auto;
      bottom: auto;
    }
    /* Logged-in embedded mode: compact horizontal bottom tray so map stays the hero */
    .guest-map-view.embedded-mode .guest-map-sheet {
      padding: 10px 16px 12px;
    }
    .guest-map-view.embedded-mode .guest-sheet-handle {
      margin-bottom: 6px;
    }
    .guest-map-view.embedded-mode .guest-sheet-header {
      margin-bottom: 6px;
    }
    .guest-map-view.embedded-mode .guest-chip-row {
      margin-bottom: 8px;
    }
    .guest-map-view.embedded-mode .guest-cards-row {
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 10px;
    }
    .guest-map-view.embedded-mode .guest-pharm-card {
      flex-direction: row;
      align-items: stretch;
      padding: 10px;
      gap: 10px;
      border-radius: 14px;
    }
    .guest-map-view.embedded-mode .guest-card-img-wrap {
      width: 54px;
      height: 54px;
      border-radius: 10px;
      flex-shrink: 0;
      align-self: flex-start;
    }
    .guest-map-view.embedded-mode .guest-card-body {
      padding: 0;
      gap: 5px;
      min-width: 0;
    }
    .guest-map-view.embedded-mode .guest-med-box {
      padding: 4px 8px;
      gap: 8px;
      margin-top: 0;
    }
    .guest-map-view.embedded-mode .guest-med-box img {
      width: 26px;
      height: 26px;
    }
    .guest-map-view.embedded-mode .guest-card-dir-btn {
      min-height: 31px;
      font-size: 0.75rem;
    }
  }
  .guest-sheet-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 10px;
  }
  .guest-med-badge {
    display: inline-block;
    background: #e8f7f2;
    color: #0f6e56;
    font-weight: 700;
    font-size: 0.82rem;
    padding: 3px 10px;
    border-radius: 99px;
  }
  .guest-chip-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 10px;
  }
  .guest-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 99px;
    font-size: 0.78rem;
    font-weight: 600;
    background: #f0f4f2;
    color: #4a635a;
    cursor: pointer;
    border: 1px solid transparent;
  }
  .guest-chip.active {
    background: #1d9e75;
    color: #ffffff;
  }
  .guest-cards-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(240px, 1fr));
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
  }
  .guest-pharm-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
  }
  .guest-pharm-card.selected {
    border-color: #1d9e75;
    box-shadow: 0 4px 14px rgba(29, 158, 117, 0.14);
  }
  .guest-card-dir-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 36px;
    border-radius: 99px;
    font-weight: 600;
    font-size: 0.78rem;
    cursor: pointer;
    transition: background 0.18s, color 0.18s, border-color 0.18s;
    background: #eef9f4;
    color: #0f6e56;
    border: 1.5px solid #1d9e75;
  }
  .guest-card-dir-btn:hover {
    background: #dcf3ea;
  }
  .guest-pharm-card.selected .guest-card-dir-btn {
    background: #ff5b27;
    color: #ffffff;
    border-color: #ff5b27;
  }
  .guest-pharm-card.selected .guest-card-dir-btn:hover {
    background: #e64c1b;
    border-color: #e64c1b;
  }
  .guest-map-view :focus-visible {
    outline: 2px solid #1d9e75;
    outline-offset: 2px;
  }
  @media (prefers-reduced-motion: reduce) {
    .guest-map-view,
    .guest-pharm-card,
    .guest-card-dir-btn {
      transition: none !important;
    }
  }
  .guest-card-img-wrap {
    width: 100%;
    height: 88px;
    overflow: hidden;
    background: #f0f4f2;
  }
  .guest-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .guest-card-body {
    padding: 10px 12px 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
  }
  .guest-med-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f6faf8;
    border: 1px solid #e3ece8;
    border-radius: 10px;
    padding: 7px 9px;
    margin-top: 2px;
  }
  .guest-med-box img {
    width: 36px;
    height: 36px;
    object-fit: contain;
    background: #ffffff;
    border-radius: 8px;
    padding: 2px;
    border: 1px solid #e6efec;
    flex-shrink: 0;
  }
  .guest-sheet-handle {
    width: 38px;
    height: 4px;
    border-radius: 99px;
    background: #d5e2dd;
    margin: 0 auto 10px;
  }
  #guestMapView .leaflet-popup-content-wrapper {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.22);
    padding: 0;
    overflow: hidden;
    border: none;
  }
  #guestMapView .leaflet-popup-tip {
    background: #ffffff;
  }
  #guestMapView .leaflet-popup-content {
    margin: 0;
    width: auto !important;
  }
  #guestMapView .leaflet-popup-close-button {
    width: 24px !important;
    height: 24px !important;
    line-height: 24px !important;
    border-radius: 50% !important;
    background: rgba(15, 23, 42, 0.72) !important;
    color: #ffffff !important;
    top: 8px !important;
    right: 8px !important;
    z-index: 10 !important;
    font-size: 16px !important;
    text-align: center !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
  }
  .mf-popup-inner {
    font-family: "Plus Jakarta Sans", sans-serif;
    min-width: 228px;
    max-width: 262px;
    background: #ffffff;
    color: #1f2937;
    padding: 0;
    border-radius: 16px;
    overflow: hidden;
  }
  .mf-popup-hero {
    position: relative;
    height: 98px;
    overflow: hidden;
  }
  .mf-popup-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .mf-popup-hero-grad {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.32) 55%, rgba(15, 23, 42, 0.05) 100%);
    pointer-events: none;
  }
  .mf-popup-titlebar {
    position: absolute;
    left: 12px;
    right: 12px;
    bottom: 8px;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.88rem;
    line-height: 1.25;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.45);
    z-index: 2;
  }
  .mf-popup-body {
    background: #ffffff;
    padding: 9px 12px 11px;
  }
  .mf-popup-addr {
    font-size: 0.74rem;
    color: #64748b;
    line-height: 1.35;
    margin-bottom: 4px;
  }

  /* 2-Column Split Card (Left: Medicine Image, Right: Medicine Info) */
  .mf-med-split-wrap {
    display: grid !important;
    grid-template-columns: 0.9fr 1.1fr !important;
    gap: 12px !important;
    overflow: visible !important;
    padding-bottom: 0 !important;
  }
  .mf-carousel-col {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    min-height: 98px;
    background: #ffffff;
    border: 1px solid #e5ece9;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .mf-carousel-col img {
    width: 100%;
    height: 100%;
    min-height: 98px;
    max-height: 118px;
    object-fit: contain;
    padding: 8px;
    display: block;
  }
  .mf-carousel-grad {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.14) 0%, transparent 45%);
    pointer-events: none;
  }
  .mf-med-info-col {
    border: 1px solid #e5ece9;
    border-radius: 14px;
    padding: 10px 12px;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 6px;
    min-width: 0;
  }
  .mf-med-info-head {
    display: flex;
    align-items: center;
    gap: 9px;
  }
  .mf-med-info-thumb {
    width: 40px;
    height: 40px;
    object-fit: contain;
    background: #f8fafc;
    border: 1px solid #e5ece9;
    border-radius: 10px;
    padding: 3px;
    flex-shrink: 0;
  }
  @media (max-width: 575.98px) {
    .mf-med-split-wrap {
      grid-template-columns: 0.72fr 1.28fr !important;
      gap: 9px !important;
    }
    .mf-carousel-col,
    .mf-carousel-col img {
      min-height: 88px;
      max-height: 104px;
    }
    .mf-med-info-col {
      padding: 8px 10px;
    }
  }
  .mf-popup-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 9px;
    border-radius: 99px;
    font-size: 0.72rem;
    font-weight: 600;
    background: rgba(74, 222, 128, 0.16);
    color: #86efac;
  }
  .mf-popup-pill.unavail {
    background: rgba(248, 113, 113, 0.18);
    color: #fca5a5;
  }
  @media (max-width: 768px) {
    .guest-route-panel {
      top: 10px;
      right: 10px;
      left: 10px;
      width: auto;
      max-height: 180px;
      padding: 8px 12px;
      border-radius: 16px;
    }
    .guest-route-panel.minimized {
      border-radius: 99px;
      padding: 6px 10px 6px 12px;
    }
    .guest-route-panel h6 {
      font-size: 0.78rem;
    }
    .guest-map-sheet {
      padding: 8px 14px 12px;
    }
    .guest-sheet-handle {
      margin-bottom: 6px;
    }
    .guest-sheet-header {
      margin-bottom: 6px;
    }
    .guest-chip-row {
      margin-bottom: 8px;
      gap: 6px;
    }
    .guest-cards-row {
      grid-template-columns: repeat(3, 228px);
      gap: 10px;
    }
    .guest-card-img-wrap {
      height: 68px;
    }
    .guest-card-body {
      padding: 8px 10px 10px;
      gap: 5px;
    }
    .guest-med-box {
      padding: 4px 7px;
      gap: 7px;
      margin-top: 0;
    }
    .guest-med-box img {
      width: 24px;
      height: 24px;
    }
    .guest-card-dir-btn {
      min-height: 32px;
      font-size: 0.75rem;
    }
  }
</style>

<div class="guest-map-view <?= $mapMode === 'embedded' ? 'embedded-mode active' : '' ?>" id="guestMapView" aria-hidden="<?= $mapMode === 'embedded' ? 'false' : 'true' ?>">
  <div class="guest-map-topbar">
    <?php if ($mapMode === 'embedded'): ?>
      <a href="<?= htmlspecialchars($mapBackHref ?: '02_ScanRX.php') ?>" class="guest-map-back">
        <span class="material-symbols-outlined" style="font-size:1.1rem;">arrow_back</span>
        Back
      </a>
    <?php else: ?>
      <button type="button" class="guest-map-back" id="guestMapBackBtn">
        <span class="material-symbols-outlined" style="font-size:1.1rem;">arrow_back</span>
        Back
      </button>
    <?php endif; ?>

    <div class="guest-map-searchbar">
      <span id="guestMapSearchSubmit" class="material-symbols-outlined" style="color:#87a199;font-size:1.1rem;cursor:pointer;" title="Search">search</span>
      <input type="text" id="guestMapSearchInput" value="<?= htmlspecialchars($mapInitialQuery) ?>" placeholder="Search medicine..." aria-label="Search medicine" />
      <button type="button" id="guestMapClearBtn" title="Clear search" aria-label="Clear search" style="display:none;border:none;background:transparent;color:#64748b;padding:2px;cursor:pointer;line-height:1;">
        <span class="material-symbols-outlined" style="font-size:1.05rem;">close</span>
      </button>
    </div>

    <?php if (!$mapIsLoggedIn): ?>
      <a href="<?= htmlspecialchars($mapBasePath) ?>03_Authentication/login.php" class="guest-map-back" style="background:#1d9e75;color:#fff;border-color:#1d9e75;">
        <span class="material-symbols-outlined" style="font-size:1.1rem;">login</span>
        Log In
      </a>
    <?php endif; ?>
  </div>

  <div class="guest-map-stage">
    <div id="guestLeafletMap"></div>

    <button type="button" class="guest-locate-btn" id="guestLocateMeBtn" title="Locate Me" aria-label="Locate Me">
      <span class="material-symbols-outlined" style="font-size:1.2rem;">my_location</span>
    </button>

    <div class="guest-route-panel" id="guestRoutePanel" role="region" aria-label="Turn-by-turn directions">
      <div class="guest-route-header">
        <h6>
          <span class="material-symbols-outlined" style="color:#1d9e75;font-size:1.1rem;">directions</span>
          <span id="guestRouteTitle" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Guided Route</span>
        </h6>
        <div class="d-flex align-items-center gap-1 flex-shrink-0">
          <button type="button" class="guest-route-icon-btn d-md-none" id="guestRouteMinBtn" title="Minimize directions" aria-label="Minimize directions">
            <span class="material-symbols-outlined" style="font-size:1rem;">expand_more</span>
          </button>
          <button type="button" class="guest-route-icon-btn" id="guestRouteCloseBtn" title="Close directions" aria-label="Close directions">
            <span class="material-symbols-outlined" style="font-size:1rem;">close</span>
          </button>
        </div>
      </div>
      <div class="guest-route-body">
        <div class="guest-route-pill" id="guestRouteMeta">Locating your position...</div>
        <ol class="guest-route-steps" id="guestRouteSteps"></ol>
      </div>
    </div>

    <button type="button" class="guest-sheet-toggle-pill" id="guestShowSheetBtn">
      <span class="material-symbols-outlined" style="font-size:1.05rem;">medication</span>
      Show Medicine
    </button>

    <div class="guest-map-sheet" id="guestMapSheet">
      <div class="guest-sheet-handle" id="guestSheetHandle" title="Hide pharmacies"></div>
      <div class="guest-sheet-header">
        <div style="flex:1;min-width:0;">
          <span id="guestResultCount" style="font-weight:600;color:#1a2e27;font-size:0.92rem;">Participating pharmacies</span>
          <span class="guest-med-badge" id="guestMedBadge">Paracetamol</span>
        </div>
        <button type="button" class="guest-sheet-hide-btn" id="guestHideSheetBtn" title="Hide pharmacy cards" aria-label="Hide pharmacy cards">
          <span class="material-symbols-outlined" style="font-size:1.05rem;">close</span>
        </button>
        <div id="guestMedSubtext" class="w-100" style="font-size:0.78rem;color:#6b8f82;">Malaybalay City, Bukidnon</div>
      </div>

      <div id="guestRxMedPills" style="display:none;gap:6px;flex-wrap:nowrap;overflow-x:auto;padding:2px 0 8px;scrollbar-width:none;"></div>

      <div class="guest-chip-row">
        <div class="guest-chip active" data-filter="nearest">Nearest</div>
        <div class="guest-chip" data-filter="available">Available Only</div>
        <div class="guest-chip" data-filter="open_now">Open Now</div>
      </div>

      <div class="guest-cards-row" id="guestCardsRow"></div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  (function () {
    const MAP_BASE_PATH = <?= json_encode($mapBasePath) ?>;
    const MAP_MODE = <?= json_encode($mapMode) ?>;
    const MAP_INITIAL_QUERY = <?= json_encode($mapInitialQuery) ?>;
    const MAP_IS_LOGGED_IN = <?= json_encode($mapIsLoggedIn) ?>;
    const MAP_MEDICINES = <?= json_encode($mfMedsList, JSON_UNESCAPED_SLASHES) ?>;
    const MAP_PHARMACIES = <?= json_encode($mfPharmsList, JSON_UNESCAPED_SLASHES) ?>;

    const guestMapView = document.getElementById('guestMapView');
    const guestMapBackBtn = document.getElementById('guestMapBackBtn');
    const guestMapSearchInput = document.getElementById('guestMapSearchInput');
    const guestMapClearBtn = document.getElementById('guestMapClearBtn');
    const guestMapSearchSubmit = document.getElementById('guestMapSearchSubmit');
    const guestLocateMeBtn = document.getElementById('guestLocateMeBtn');
    const guestRoutePanel = document.getElementById('guestRoutePanel');
    const guestRouteCloseBtn = document.getElementById('guestRouteCloseBtn');
    const guestRouteMinBtn = document.getElementById('guestRouteMinBtn');
    const guestCardsRow = document.getElementById('guestCardsRow');
    const guestMedBadge = document.getElementById('guestMedBadge');
    const guestResultCount = document.getElementById('guestResultCount');
    const guestMedSubtext = document.getElementById('guestMedSubtext');
    const guestRouteTitle = document.getElementById('guestRouteTitle');
    const guestRouteMeta = document.getElementById('guestRouteMeta');
    const guestRouteSteps = document.getElementById('guestRouteSteps');
    const guestMapSheet = document.getElementById('guestMapSheet');
    const guestShowSheetBtn = document.getElementById('guestShowSheetBtn');
    const guestHideSheetBtn = document.getElementById('guestHideSheetBtn');
    const guestSheetHandle = document.getElementById('guestSheetHandle');

    let guestMap = null;
    let userCoords = { lat: 8.1555, lng: 125.1275, isExact: false };
    let userMarker = null;
    let pharmacyMarkers = {};
    let routeLayer = null;
    let routeCasingLayer = null;
    let routeFlowLayer = null;
    let routeProgressDot = null;
    let routeProgressFrame = null;
    let routeEtaMarker = null;
    let routeStartMarker = null;
    let routeDestHighlight = null;
    let routeAnimFrame = null;
    let currentQuery = MAP_INITIAL_QUERY || 'Paracetamol';
    let activeFilter = 'nearest';

    function setSheetVisible(visible) {
      if (visible) {
        guestMapSheet.classList.add('revealed');
        if (guestShowSheetBtn) guestShowSheetBtn.classList.remove('visible');
      } else {
        guestMapSheet.classList.remove('revealed');
        if (guestShowSheetBtn) guestShowSheetBtn.classList.add('visible');
      }
    }

    function fitMapToRoute(coords) {
      if (!guestMap || !coords || !coords.length) return;
      const isDesktop = window.innerWidth >= 769;
      const isLeftPanel = isDesktop && MAP_MODE !== 'embedded';
      const sheetShown = guestMapSheet.classList.contains('revealed');
      const routeShown = guestRoutePanel && guestRoutePanel.classList.contains('open');
      const leftPad = isLeftPanel && sheetShown ? (guestMapSheet.offsetWidth || 440) + 32 : 36;
      const rightPad = isDesktop ? (routeShown ? 350 : 64) : 28;
      const topPad = isDesktop ? 120 : (routeShown ? 110 : 90);
      const bottomPad = sheetShown && !isLeftPanel ? (guestMapSheet.offsetHeight || 290) + 18 : 44;
      guestMap.flyToBounds(L.latLngBounds(coords), {
        paddingTopLeft: [leftPad, topPad],
        paddingBottomRight: [rightPad, bottomPad],
        maxZoom: 18,
        duration: 0.75,
        easeLinearity: 0.25
      });
    }

    function clearRouteLayers() {
      if (routeAnimFrame) { cancelAnimationFrame(routeAnimFrame); routeAnimFrame = null; }
      if (routeProgressFrame) { cancelAnimationFrame(routeProgressFrame); routeProgressFrame = null; }
      if (routeProgressDot && guestMap) { guestMap.removeLayer(routeProgressDot); routeProgressDot = null; }
      if (routeLayer && guestMap) { guestMap.removeLayer(routeLayer); routeLayer = null; }
      if (routeCasingLayer && guestMap) { guestMap.removeLayer(routeCasingLayer); routeCasingLayer = null; }
      if (routeFlowLayer && guestMap) { guestMap.removeLayer(routeFlowLayer); routeFlowLayer = null; }
      if (routeEtaMarker && guestMap) { guestMap.removeLayer(routeEtaMarker); routeEtaMarker = null; }
      if (routeStartMarker && guestMap) { guestMap.removeLayer(routeStartMarker); routeStartMarker = null; }
      if (routeDestHighlight && guestMap) { guestMap.removeLayer(routeDestHighlight); routeDestHighlight = null; }
    }

    function calcKm(lat1, lng1, lat2, lng2) {
      const toRad = d => d * Math.PI / 180;
      const dLat = toRad(lat2 - lat1);
      const dLng = toRad(lng2 - lng1);
      const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
      return 6371 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    function makePinIcon(color) {
      return L.divIcon({
        className: '',
        html: `<div class="mf-pin-bounce"><div style="background:${color};width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,0.32);display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="transform:rotate(45deg);color:#fff;font-size:15px;">local_pharmacy</span></div></div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -34]
      });
    }

    function makeUserIcon() {
      return L.divIcon({
        className: '',
        html: `<div class="mf-user-beacon"><div class="mf-user-beacon-ring"></div><div class="mf-user-beacon-dot"></div></div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 14]
      });
    }

    function ensureMapInitialized() {
      if (guestMap) return;
      guestMap = L.map('guestLeafletMap', { maxZoom: 21 }).setView([userCoords.lat, userCoords.lng], 16);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxNativeZoom: 19,
        maxZoom: 21
      }).addTo(guestMap);
      userMarker = L.marker([userCoords.lat, userCoords.lng], { icon: makeUserIcon(), zIndexOffset: 1000 })
        .addTo(guestMap)
        .bindPopup(userCoords.isExact ? 'Your Current Location' : 'Malaybalay City Center (Approximate)');
    }

    function requestUserLocation(onDone) {
      if (!('geolocation' in navigator)) {
        if (onDone) onDone();
        return;
      }
      navigator.geolocation.getCurrentPosition(
        pos => {
          userCoords = { lat: pos.coords.latitude, lng: pos.coords.longitude, isExact: true };
          if (userMarker && guestMap) {
            userMarker.setLatLng([userCoords.lat, userCoords.lng]).setPopupContent('Your Current Location');
          }
          if (onDone) onDone();
        },
        () => { if (onDone) onDone(); },
        { enableHighAccuracy: true, timeout: 6000 }
      );
    }

    function densifyCoords(rawCoords, minPoints = 40) {
      if (!rawCoords || rawCoords.length < 2) return rawCoords || [];
      if (rawCoords.length >= minPoints) return rawCoords;
      const out = [];
      const segs = rawCoords.length - 1;
      const perSeg = Math.max(2, Math.ceil(minPoints / segs));
      for (let i = 0; i < segs; i++) {
        const a = rawCoords[i];
        const b = rawCoords[i + 1];
        for (let j = 0; j < perSeg; j++) {
          const t = j / perSeg;
          out.push([a[0] + (b[0] - a[0]) * t, a[1] + (b[1] - a[1]) * t]);
        }
      }
      out.push(rawCoords[rawCoords.length - 1]);
      return out;
    }

    function animateRoute(rawCoords, etaLabel, pharmacy, onComplete) {
      clearRouteLayers();
      const coords = densifyCoords(rawCoords, 40);
      if (!coords || coords.length < 2) {
        if (onComplete) onComplete();
        return;
      }

      routeStartMarker = L.circleMarker(coords[0], {
        radius: 8,
        color: '#ca8a04',
        weight: 3,
        fillColor: '#ffffff',
        fillOpacity: 1
      }).addTo(guestMap);

      routeCasingLayer = L.polyline([coords[0], coords[1]], { color: '#ca8a04', weight: 14, opacity: 0.95, lineJoin: 'round', lineCap: 'round' }).addTo(guestMap);
      routeLayer = L.polyline([coords[0], coords[1]], { color: '#facc15', weight: 9, opacity: 1, lineJoin: 'round', lineCap: 'round' }).addTo(guestMap);
      routeFlowLayer = L.polyline([coords[0], coords[1]], { color: '#92400e', weight: 4, opacity: 0.75, dashArray: '10, 14', className: 'mf-route-flow-line', lineJoin: 'round', lineCap: 'round' }).addTo(guestMap);
      routeCasingLayer.bringToFront();
      routeLayer.bringToFront();
      routeFlowLayer.bringToFront();

      fitMapToRoute(coords);

      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const startProgressRunner = () => {
        if (reduceMotion || coords.length < 2) return;
        routeProgressDot = L.circleMarker(coords[0], {
          radius: 7,
          color: '#ffffff',
          weight: 3,
          fillColor: '#ff5b27',
          fillOpacity: 1
        }).addTo(guestMap);
        routeProgressDot.bringToFront();
        const cycleMs = 1800;
        let t0 = performance.now();
        function runLoop(now) {
          if (!routeProgressDot || !guestMap) return;
          const p = ((now - t0) % cycleMs) / cycleMs;
          const idx = Math.min(coords.length - 1, Math.floor(p * (coords.length - 1)));
          routeProgressDot.setLatLng(coords[idx]);
          routeProgressFrame = requestAnimationFrame(runLoop);
        }
        routeProgressFrame = requestAnimationFrame(runLoop);
      };

      const finishRoute = () => {
        routeCasingLayer.setLatLngs(coords);
        routeLayer.setLatLngs(coords);
        routeFlowLayer.setLatLngs(coords);
        const dest = coords[coords.length - 1];
        routeDestHighlight = L.circleMarker(dest, {
          radius: 20,
          color: '#ca8a04',
          weight: 3,
          fillColor: '#facc15',
          fillOpacity: 0.3
        }).addTo(guestMap);

        const midPt = coords[Math.floor(coords.length / 2)];
        routeEtaMarker = L.marker(midPt, {
          interactive: false,
          icon: L.divIcon({
            className: '',
            html: `<div class="mf-route-eta-badge"><span class="material-symbols-outlined" style="font-size:0.95rem;">directions_car</span>${etaLabel}</div>`,
            iconAnchor: [40, 14]
          })
        }).addTo(guestMap);
        startProgressRunner();
        if (onComplete) onComplete();
      };

      if (reduceMotion) {
        finishRoute();
        return;
      }

      const duration = 900;
      const startTime = performance.now();
      function step(now) {
        const t = Math.min(1, (now - startTime) / duration);
        const eased = 1 - Math.pow(1 - t, 3);
        const count = Math.max(2, Math.floor(eased * coords.length));
        const slice = coords.slice(0, count);
        routeCasingLayer.setLatLngs(slice);
        routeLayer.setLatLngs(slice);
        routeFlowLayer.setLatLngs(slice);
        if (t < 1) {
          routeAnimFrame = requestAnimationFrame(step);
        } else {
          finishRoute();
        }
      }
      routeAnimFrame = requestAnimationFrame(step);
    }

    async function drawGuidedRoute(pharmacy, onComplete) {
      if (!guestMap || !pharmacy) {
        if (onComplete) onComplete();
        return;
      }
      clearRouteLayers();

      guestRouteTitle.textContent = `Route to ${pharmacy.name}`;
      const straightKm = calcKm(userCoords.lat, userCoords.lng, pharmacy.lat, pharmacy.lng);
      guestRouteMeta.textContent = `${userCoords.isExact ? 'From your location' : 'From Malaybalay center'} · Calculating route...`;
      guestRouteSteps.innerHTML = '';

      try {
        const url = `https://router.project-osrm.org/route/v1/driving/${userCoords.lng},${userCoords.lat};${pharmacy.lng},${pharmacy.lat}?overview=full&geometries=geojson&steps=true`;
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 2200);
        const res = await fetch(url, { signal: controller.signal });
        clearTimeout(timer);
        const data = await res.json();
        if (data.code === 'Ok' && data.routes && data.routes.length) {
          const route = data.routes[0];
          const coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
          const km = (route.distance / 1000).toFixed(2);
          const mins = Math.max(1, Math.round(route.duration / 60));
          guestRouteMeta.textContent = `${mins} min · ${km} km (${userCoords.isExact ? 'from your location' : 'from city center'})`;

          const steps = (route.legs && route.legs[0] && route.legs[0].steps) ? route.legs[0].steps : [];
          if (window.innerWidth <= 768 && steps.length) {
            const firstRoad = steps[0].name ? ` — ${steps[0].name}` : '';
            guestRouteTitle.textContent = `${mins} min · ${km} km${firstRoad}`;
          }
          guestRouteSteps.innerHTML = steps.slice(0, 5).map(s => {
            const road = s.name ? ` onto <strong>${s.name}</strong>` : '';
            const action = (s.maneuver && s.maneuver.type) ? s.maneuver.type.replace(/-/g, ' ') : 'Continue';
            const dist = s.distance ? ` (${Math.round(s.distance)} m)` : '';
            return `<li style="text-transform:capitalize;">${action}${road}${dist}</li>`;
          }).join('');

          animateRoute(coords, `${mins} min`, pharmacy, onComplete);
          return;
        }
      } catch (e) {}

      const p1 = [userCoords.lat, userCoords.lng];
      const p2 = [pharmacy.lat, pharmacy.lng];
      const fallbackCoords = Array.from({ length: 24 }, (_, i) => [
        p1[0] + (p2[0] - p1[0]) * (i / 23),
        p1[1] + (p2[1] - p1[1]) * (i / 23)
      ]);
      const estMins = Math.max(1, Math.round((straightKm / 25) * 60));
      guestRouteMeta.textContent = `${estMins} min · ${straightKm.toFixed(2)} km direct · Head toward ${pharmacy.short_address}`;
      guestRouteSteps.innerHTML = `<li>Proceed toward <strong>${pharmacy.address}</strong>.</li>`;
      animateRoute(fallbackCoords, `${estMins} min`, pharmacy, onComplete);
    }

    function findMedicine(query) {
      const q = (query || '').trim().toLowerCase();
      if (!q) return null;
      return MAP_MEDICINES.find(m =>
        m.brand_name.toLowerCase() === q || m.generic_name.toLowerCase() === q
      ) || MAP_MEDICINES.find(m =>
        m.brand_name.toLowerCase().includes(q) ||
        m.generic_name.toLowerCase().includes(q) ||
        m.category.toLowerCase().includes(q)
      ) || null;
    }

    function syncClearBtn() {
      if (guestMapClearBtn) {
        guestMapClearBtn.style.display = guestMapSearchInput.value.trim() ? 'inline-flex' : 'none';
      }
    }

    function renderMapResults(query, opts = {}) {
      const animateIntro = Boolean(opts.animateIntro);
      currentQuery = query;
      guestMapSearchInput.value = query;
      syncClearBtn();
      const med = findMedicine(query);

      Object.values(pharmacyMarkers).forEach(m => guestMap.removeLayer(m));
      pharmacyMarkers = {};
      if (animateIntro) {
        guestMapSheet.classList.remove('revealed');
        clearRouteLayers();
      }

      if (!med) {
        guestMedBadge.textContent = query || 'Not found';
        guestResultCount.textContent = '0 participating pharmacies have';
        guestMedSubtext.textContent = 'Try Paracetamol, Advil, Amoxicillin, Cozaar, or Solmux';
        guestCardsRow.innerHTML = `<div style="padding:12px;color:#6b8f82;font-size:0.88rem;">No matching medicine found in local database.</div>`;
        setSheetVisible(true);
        return;
      }

      guestMedBadge.textContent = `${med.brand_name} · ${med.dosage}`;
      guestMedSubtext.textContent = `${med.generic_name} · ${med.dosage_form} · ${med.requires_prescription ? 'Requires Rx' : 'Over-the-Counter'}`;

      let list = MAP_PHARMACIES.map(p => ({
        ...p,
        isAvailable: Array.isArray(p.available_medicine_ids) && p.available_medicine_ids.includes(med.id),
        distKm: calcKm(userCoords.lat, userCoords.lng, p.lat, p.lng)
      }));

      if (activeFilter === 'available') {
        list = list.filter(p => p.isAvailable);
      } else if (activeFilter === 'open_now') {
        list = list.filter(p => p.is_open);
      } else {
        list.sort((a, b) => a.distKm - b.distKm);
      }

      const availableCount = list.filter(p => p.isAvailable).length;
      guestResultCount.textContent = `${availableCount} of ${list.length} participating pharmacies have`;

      const firstAvailable = list.find(p => p.isAvailable) || list[0] || null;
      let activeIdx = Math.max(0, firstAvailable ? list.findIndex(p => p.id === firstAvailable.id) : 0);

      if (window._mfCarouselTimer) {
        clearInterval(window._mfCarouselTimer);
        window._mfCarouselTimer = null;
      }

      guestCardsRow.classList.add('mf-med-split-wrap');
      const initialPharm = list[activeIdx] || {};
      guestCardsRow.innerHTML = `
        <div class="mf-carousel-col">
          <img src="${MAP_BASE_PATH}${med.image}" alt="${med.brand_name}" />
          <div class="mf-carousel-grad"></div>
        </div>

        <div class="mf-med-info-col">
          <div>
            <div style="font-weight:700;font-size:0.9rem;color:#1a2e27;line-height:1.2;">${med.brand_name}</div>
            <div style="font-size:0.74rem;color:#6b8f82;margin-top:2px;">${med.generic_name} · ${med.dosage}</div>
            <div class="d-flex flex-wrap gap-1 mt-2">
              <span class="badge rounded-pill" style="background:#f1f5f9;color:#475569;font-size:0.66rem;">${med.dosage_form}</span>
              <span class="badge rounded-pill" style="background:${med.requires_prescription ? '#fef3c7' : '#eef8f4'};color:${med.requires_prescription ? '#92400e' : '#0f6e56'};font-size:0.66rem;">
                ${med.requires_prescription ? 'Requires Rx' : 'Over-the-Counter'}
              </span>
            </div>
          </div>
        </div>
      `;

      list.forEach((p, idx) => {
        const isFirst = firstAvailable && p.id === firstAvailable.id;
        const pinColor = isFirst ? '#ff5b27' : (p.isAvailable ? '#1d9e75' : '#94a3b8');
        const m = L.marker([p.lat, p.lng], {
          icon: makePinIcon(pinColor),
          zIndexOffset: isFirst ? 500 : 0
        })
          .addTo(guestMap)
          .bindPopup(`
            <div class="mf-popup-inner">
              <div class="mf-popup-hero">
                <img src="${MAP_BASE_PATH}${p.image}" alt="${p.name}" class="mf-popup-photo" />
                <div class="mf-popup-hero-grad"></div>
                <div class="mf-popup-titlebar">${p.name}</div>
              </div>
              <div class="mf-popup-body">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                  <div class="mf-popup-addr mb-0">${p.short_address}</div>
                  <div style="font-size:0.72rem;font-weight:700;color:#1d9e75;white-space:nowrap;">${p.distKm.toFixed(1)} km</div>
                </div>
                <div style="font-size:0.71rem;color:#64748b;margin-bottom:6px;">${p.is_open ? 'Open' : 'Closed'} · ${p.hours.replace(/^Open · |^Closed · /i, '')}</div>
                <span class="mf-popup-pill ${p.isAvailable ? '' : 'unavail'}" style="background:${p.isAvailable ? '#e8f7f2' : '#fef2f2'};color:${p.isAvailable ? '#0f6e56' : '#b91c1c'};">
                  <span class="material-symbols-outlined" style="font-size:0.85rem;">${p.isAvailable ? 'check_circle' : 'cancel'}</span>
                  ${p.isAvailable ? 'Available' : 'Unavailable'}
                </span>
              </div>
            </div>
          `, { maxWidth: 262, autoPan: false });
        m.on('click', () => {
          activeIdx = idx;
          list.forEach((item) => {
            const mk = pharmacyMarkers[item.id];
            if (!mk) return;
            const active = item.id === p.id;
            mk.setIcon(makePinIcon(active ? '#ff5b27' : (item.isAvailable ? '#1d9e75' : '#94a3b8')));
            mk.setZIndexOffset(active ? 500 : 0);
          });
          if (pharmacyMarkers[p.id]) pharmacyMarkers[p.id].openPopup();
          drawGuidedRoute(p);
        });
        pharmacyMarkers[p.id] = m;
      });

      const openRoutePanelResponsive = () => {
        guestRoutePanel.classList.add('open');
        if (window.innerWidth <= 768) {
          guestRoutePanel.classList.add('minimized');
        } else {
          guestRoutePanel.classList.remove('minimized');
        }
      };

      if (animateIntro && firstAvailable) {
        drawGuidedRoute(firstAvailable, () => {
          if (pharmacyMarkers[firstAvailable.id]) pharmacyMarkers[firstAvailable.id].openPopup();
          openRoutePanelResponsive();
          setTimeout(() => {
            setSheetVisible(true);
            setTimeout(() => {
              if (routeLayer) fitMapToRoute(routeLayer.getLatLngs());
            }, 80);
          }, 220);
        });
      } else if (firstAvailable) {
        if (pharmacyMarkers[firstAvailable.id]) pharmacyMarkers[firstAvailable.id].openPopup();
        openRoutePanelResponsive();
        setSheetVisible(true);
        drawGuidedRoute(firstAvailable, () => {
          if (routeLayer) fitMapToRoute(routeLayer.getLatLngs());
        });
      } else {
        setSheetVisible(true);
      }

      guestCardsRow.querySelectorAll('.guest-pharm-card').forEach(card => {
        card.addEventListener('click', () => {
          guestCardsRow.querySelectorAll('.guest-pharm-card').forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
          const p = list.find(item => String(item.id) === card.dataset.id);
          if (p) {
            if (pharmacyMarkers[p.id]) pharmacyMarkers[p.id].openPopup();
            guestRoutePanel.classList.add('open');
            guestRoutePanel.classList.remove('minimized');
            drawGuidedRoute(p);
          }
        });
      });

      guestCardsRow.querySelectorAll('.guest-card-dir-btn').forEach(btn => {
        btn.addEventListener('click', e => {
          e.stopPropagation();
          const card = btn.closest('.guest-pharm-card');
          guestCardsRow.querySelectorAll('.guest-pharm-card').forEach(c => c.classList.remove('selected'));
          if (card) card.classList.add('selected');
          const p = list.find(item => String(item.id) === btn.dataset.id);
          if (p) {
            if (pharmacyMarkers[p.id]) pharmacyMarkers[p.id].openPopup();
            guestRoutePanel.classList.add('open');
            guestRoutePanel.classList.remove('minimized');
            drawGuidedRoute(p);
          }
        });
      });
    }

    window.openMediFindMap = function (query) {
      const trimmed = (query || '').trim();
      if (!trimmed) return;
      guestMapSheet.classList.remove('revealed');
      if (guestShowSheetBtn) guestShowSheetBtn.classList.remove('visible');
      guestMapView.classList.add('active');
      guestMapView.setAttribute('aria-hidden', 'false');
      if (MAP_MODE === 'overlay') document.body.style.overflow = 'hidden';

      ensureMapInitialized();
      setTimeout(() => {
        guestMap.invalidateSize();
        clearRouteLayers();
        requestUserLocation(() => renderMapResults(trimmed, { animateIntro: true }));
      }, 280);
    };

    if (guestHideSheetBtn) guestHideSheetBtn.addEventListener('click', () => setSheetVisible(false));
    if (guestSheetHandle) guestSheetHandle.addEventListener('click', () => setSheetVisible(false));
    if (guestShowSheetBtn) guestShowSheetBtn.addEventListener('click', () => setSheetVisible(true));

    if (guestMapBackBtn) {
      guestMapBackBtn.addEventListener('click', () => {
        guestMapView.classList.remove('active');
        guestMapView.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      });
    }

    function runNewMapSearch() {
      const val = guestMapSearchInput.value.trim();
      if (!val) { guestMapSearchInput.focus(); return; }
      guestRoutePanel.classList.remove('open');
      clearRouteLayers();
      renderMapResults(val, { animateIntro: true });
    }

    guestMapSearchInput.addEventListener('input', syncClearBtn);
    guestMapSearchInput.addEventListener('keydown', e => {
      if (e.key === 'Enter') { e.preventDefault(); runNewMapSearch(); }
    });
    if (guestMapClearBtn) {
      guestMapClearBtn.addEventListener('click', () => {
        guestMapSearchInput.value = '';
        syncClearBtn();
        guestMapSearchInput.focus();
      });
    }
    if (guestMapSearchSubmit) guestMapSearchSubmit.addEventListener('click', runNewMapSearch);

    if (guestLocateMeBtn) {
      guestLocateMeBtn.addEventListener('click', () => {
        requestUserLocation(() => {
          if (guestMap) guestMap.setView([userCoords.lat, userCoords.lng], 16);
          if (userMarker) userMarker.openPopup();
          renderMapResults(currentQuery);
        });
      });
    }

    function closeGuestRoutePanel() {
      guestRoutePanel.classList.remove('open', 'minimized');
    }

    if (guestRouteCloseBtn) guestRouteCloseBtn.addEventListener('click', closeGuestRoutePanel);
    if (guestRouteMinBtn) {
      guestRouteMinBtn.addEventListener('click', () => {
        guestRoutePanel.classList.toggle('minimized');
      });
    }
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && guestRoutePanel.classList.contains('open')) closeGuestRoutePanel();
    });

    document.querySelectorAll('.guest-chip').forEach(chip => {
      chip.addEventListener('click', () => {
        document.querySelectorAll('.guest-chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        activeFilter = chip.dataset.filter || 'nearest';
        renderMapResults(currentQuery);
      });
    });

    // Optional multi-medicine switcher pills when launched from a multi-med prescription (?meds=A,B,C)
    (function initMultiMedPills() {
      const pillsEl = document.getElementById('guestRxMedPills');
      if (!pillsEl) return;
      const medsParam = new URLSearchParams(window.location.search).get('meds') || '';
      const meds = medsParam.split(',').map(s => s.trim()).filter(Boolean);
      if (meds.length < 2) return;

      pillsEl.style.display = 'flex';
      function renderPills(activeMed) {
        pillsEl.innerHTML = meds.map(m => {
          const isActive = activeMed.toLowerCase().includes(m.toLowerCase()) || m.toLowerCase().includes(activeMed.toLowerCase());
          return `<button type="button" data-rx-med="${m.replace(/"/g, '&quot;')}" style="border:1px solid ${isActive ? '#ff5b27' : '#d5e6df'};background:${isActive ? '#ff5b27' : '#f5fbf8'};color:${isActive ? '#ffffff' : '#1a2e27'};border-radius:999px;padding:4px 11px;font-size:0.75rem;font-weight:600;white-space:nowrap;cursor:pointer;flex-shrink:0;">${m}</button>`;
        }).join('');

        pillsEl.querySelectorAll('[data-rx-med]').forEach(btn => {
          btn.addEventListener('click', () => {
            const targetMed = btn.dataset.rxMed;
            renderMapResults(targetMed, { animateIntro: true });
            renderPills(targetMed);
          });
        });
      }
      renderPills(currentQuery || meds[0]);
    })();

    // Auto-start when embedded (e.g. 04_User/02.3_MedicineMap.php)
    if (MAP_MODE === 'embedded') {
      ensureMapInitialized();
      setTimeout(() => {
        guestMap.invalidateSize();
        requestUserLocation(() => renderMapResults(currentQuery, { animateIntro: true }));
      }, 150);
    }
  })();
</script>

