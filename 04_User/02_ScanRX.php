<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Scan Prescriptions</title>
    <link rel="icon" href="/07_Assets/images/logo.png" type="image/png" />
    <link href="/07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/07_Assets/css/scanrx.css" />

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <div id="sidebar-container"></div>

      <div class="main-panel d-flex flex-column flex-grow-1">
        <div id="topbar-container"></div>

        <div id="content">
          <div class="content-body">
            <!-- Page Title -->
            <div class="page-title-block">
              <div class="page-title-icon">
                <span class="material-symbols-outlined">medication</span>
              </div>
              <div class="page-title-text">
                <h2>My Prescriptions</h2>
                <p>View your scanned prescriptions</p>
              </div>
            </div>

            <!-- Two-column layout -->
            <div class="prescription-layout">
              <!-- LEFT: Prescriptions Table -->
              <div class="prescription-table-panel">
                <table class="prescription-table">
                  <thead>
                    <tr>
                      <th>
                        <input
                          type="checkbox"
                          class="rx-checkbox"
                          id="select-all"
                        />
                      </th>
                      <th>Medicine Name</th>
                      <th>Date Scanned</th>
                      <th>Available Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="rx-checkbox" /></td>
                      <td>Amoxicillin</td>
                      <td>Dec 31, 2026</td>
                      <td>
                        <div class="action-btns">
                          <button class="btn-view">
                            <span class="material-symbols-outlined"
                              >visibility</span
                            >
                            View
                          </button>
                          <button class="btn-delete">
                            <span class="material-symbols-outlined"
                              >delete</span
                            >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- RIGHT: Prescription Reader -->
              <div class="prescription-reader-panel">
                <div class="reader-icon-wrap">
                  <img
                    src="/07_Assets/images/icons/location.png"
                    alt="Prescription Icon"
                    class="reader-icon"
                    width="100"
                  />
                </div>
                <h2 class="reader-title">
                  <span class="orange">Prescription</span>
                  <span class="green">Reader</span>
                </h2>
                <p class="reader-subtitle">
                  Upload your prescription and we'll find the medicine for you.
                </p>
                <div class="reader-actions">
                  <input
                    type="file"
                    id="rxFileInput"
                    accept="image/png, image/jpeg"
                    hidden
                  />

                  <a
                    class="btn-scan"
                    href="/04_User/02.2_ScannedRx.html"
                    style="text-decoration: none"
                  >
                    <span class="material-symbols-outlined"
                      >document_scanner</span
                    >
                    Scan Prescription
                  </a>

                  <!-- Change <a> to <button> and trigger the input -->
                  <button
                    class="btn-file"
                    onclick="document.getElementById('rxFileInput').click()"
                  >
                    <span class="material-symbols-outlined"
                      >add_photo_alternate</span
                    >
                    Select PNG/JPEG File
                  </button>

                  <p class="drop-hint">or drop file here</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="floating-btns">
      <button class="float-btn" onclick="history.back()">
        <span class="material-symbols-outlined">smart_toy</span>
      </button>
    </div>

    <script>
      document
        .getElementById("rxFileInput")
        .addEventListener("change", function () {
          const file = this.files[0];
          if (!file) return;

          // Store the file so 02.2_ScannedRx.html can use it
          const reader = new FileReader();
          reader.onload = (e) => {
            sessionStorage.setItem("uploadedRx", e.target.result);
            window.location.href = "/04_User/02.2_ScannedRx.html";
          };
          reader.readAsDataURL(file);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/07_Assets/css/js/sidebar_and_topbar.js"></script>

    <script>
      document
        .getElementById("select-all")
        .addEventListener("change", function () {
          document
            .querySelectorAll(".rx-checkbox")
            .forEach((cb) => (cb.checked = this.checked));
        });
    </script>
  </body>
</html>
