<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Scan Prescriptions</title>
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/scannedrx.css" />

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
            <div class="page-wrapper container-fluid">

              <a href="02_ScanRX.php" class="back-btn" onclick="history.back()" href="#">
                <span class="material-symbols-outlined">arrow_back</span>
                Back
              </a>

              <div class="content-box">
                <div class="row align-items-start">
                  <!-- LEFT PANEL -->
                  <div class="col-lg-5 left-panel">
                    <h3 class="scan-title">Prescription scanned!</h3>
                    <p class="scan-subtitle">
                      We extracted the details below.<br />
                      Please review before continuing.
                    </p>

                    <div class="pill-icon-wrap">
                      <span class="material-symbols-outlined">pill</span>
                    </div>

                    <h4 class="medicine-title">Paracetamol 500mg</h4>

                    <table class="details-table">
                      <tr>
                        <td>Medicine Name</td>
                        <td>Paracetamol</td>
                      </tr>
                      <tr>
                        <td>Strength</td>
                        <td>500mg</td>
                      </tr>
                      <tr>
                        <td>Form</td>
                        <td>Tablet</td>
                      </tr>
                      <tr>
                        <td>Dose</td>
                        <td>1 tablet</td>
                      </tr>
                      <tr>
                        <td>Frequency</td>
                        <td>After meals</td>
                      </tr>
                      <tr>
                        <td>Quantity</td>
                        <td>30</td>
                      </tr>
                      <tr>
                        <td>Note</td>
                        <td>Take daily after eating</td>
                      </tr>
                    </table>

                    <button
                      class="btn-continue"
                      onclick="window.location.href = '02.3_MedicineMap.php   '"
                    >
                      Continue
                      <span class="material-symbols-outlined align-middle"
                        >arrow_right_alt</span
                      >
                    </button>

                    <button class="btn-rescan">Rescan</button>
                  </div>

                  <!-- RIGHT PANEL -->
                  <div class="col-lg-7 right-panel">
                    <div class="image-placeholder">Insert Image</div>

                    <!--
            <img
              src="your-prescription-image.png"
              alt="Prescription"
              class="img-fluid rounded shadow-sm"
            />
            -->
                  </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
    <script>
      const savedImage = sessionStorage.getItem("uploadedRx");
      if (savedImage) {
        document.querySelector(".image-placeholder").outerHTML =
          `<img src="${savedImage}" alt="Prescription" class="img-fluid rounded shadow-sm" style="width:100%;" />`;
        sessionStorage.removeItem("uploadedRx"); // clean up
      }
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
