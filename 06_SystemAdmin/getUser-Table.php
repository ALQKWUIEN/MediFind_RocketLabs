<?php
    // Self-contained — safe to include from any page
    require_once __DIR__ . '/../00_Config/config.php';
    require_once __DIR__ . '/../02_Actions/GlobalVariables.php';
    require_once __DIR__ . '/../02_Actions/04_System-Admin-CRUD/select-count.php';
    require_once __DIR__ . '/../02_Actions/04_System-Admin-CRUD/display-users.php';
    ?>

<div class="inventoryTable mt-3">
  <div class="col-12">
    <div class="stat-card-table">
      <h5>MediFind Users</h5>

      <div class="toolbars px-2">
        <div class="row g-2 align-items-center mb-3">

          <!-- Search -->
          <div class="col-12 col-md-6 col-lg-5">
            <div class="input-group searchbar-group">
              <span class="input-group-text search-icon bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" id="searchInput" class="form-control searchbar border-start-0 ps-0"
                placeholder="Search user...">
            </div>
          </div>

          <!-- Role -->
          <div class="col-6 col-md-3 col-lg-2">
            <select id="filterRole" class="form-select border-secondary-subtle">
              <option value="">All Roles</option>
              <option value="Patient/Client">Patient/Client</option>
              <option value="Pharmacy">Pharmacy Admin</option>
            </select>
          </div>

          <!-- Status -->
          <div class="col-6 col-md-3 col-lg-2">
            <select id="filterStatus" class="form-select border-secondary-subtle">
              <option value="">All Status</option>
              <option value="Active">Active</option>
              <option value="Suspended">Suspended</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <!-- Actions -->
          <div class="col-12 col-md-12 col-lg-3 d-flex gap-2 justify-content-start justify-content-lg-end">
            <a href="../06_SystemAdmin/03_Pharmacies.php" style="text-decoration: none; flex: 1 1 0;">
              <button class="btn btn-success w-100 px-3 rounded-3 d-flex align-items-center justify-content-center gap-1"
                style="background-color:#1d9e75 !important; border-color:#1d9e75 !important;">
                <span class="material-symbols-outlined" style="font-size:1.1rem;">local_hospital</span>
                <span class="text-nowrap small">View Pharmacies</span>
              </button>
            </a>

            <button id="statsToggleBtn" onclick="toggleStatsCards()"
              class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center justify-content-center gap-1"
              style="flex: 1 1 0;">
              <span id="statsChevron" class="material-symbols-outlined"
                style="color: #6c757d; font-size: 1.1rem; transition: transform 0.35s ease;">unfold_more</span>
              <span id="statsToggleLabel" class="ms-1">Expand</span>
            </button>

            <!-- Export -->
            <div class="dropdown">
              <button class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center gap-1"
                type="button" data-bs-toggle="dropdown">
                <span class="material-symbols-outlined" style="font-size:1.1rem;">download</span>
                <span class="small">Export</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                    onclick="exportData('csv', '#usersTable')">
                    <span class="material-symbols-outlined" style="font-size:16px;">csv</span> CSV
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                    onclick="exportData('excel', '#usersTable')">
                    <span class="material-symbols-outlined" style="font-size:16px;">table</span> Excel
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                    onclick="exportData('pdf', '#usersTable')">
                    <span class="material-symbols-outlined" style="font-size:16px;">picture_as_pdf</span> PDF
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- Table -->
          <div class="card card-table border">
            <div class="table-responsive rounded table-scroll">
              <table class="table table-hover align-middle mb-5" id="usersTable">
                <thead class="table-head">
                  <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>FULLNAME</th>
                    <th>EMAIL</th>
                    <th>CONTACT NO.</th>
                    <th>ROLE</th>
                    <th>ADDRESS</th>
                    <th>STATUS</th>
                    <th>CREATED</th>
                    <th>ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($stmt && $stmt->rowCount() > 0): ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                      <tr>
                        <td><input type="checkbox" class="form-check-input row-check"></td>
                        <td><?= htmlspecialchars($row['User_ID']) ?></td>
                        <td><?= htmlspecialchars($row['Full_Name']) ?></td>
                        <td><?= htmlspecialchars($row['Email']) ?></td>
                        <td><?= htmlspecialchars($row['Phone']) ?></td>
                        <td><?= htmlspecialchars($row['Role']) ?></td>
                        <td><?= htmlspecialchars($row['Full_Address']) ?></td>
                        <td>
                          <?php
                          $status = trim($row['UserStatus']);
                          $badgeClass = match ($status) {
                            'Active'    => 'badge-active',
                            'Inactive'  => 'badge-inactive',
                            'Suspended' => 'badge-suspended',
                            default     => 'badge-inactive'
                          };
                          ?>
                          <span class="badge-status <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                        </td>
                        <td><?= htmlspecialchars($row['DateCreated']) ?></td>
                        <td>
                          <div class="d-flex gap-2">
                            <button class="btn-action btn-view view-btn" title="View"
                              data-id="<?= htmlspecialchars($row['User_ID']) ?>"
                              data-firstname="<?= htmlspecialchars($row['First_name']) ?>"
                              data-lastname="<?= htmlspecialchars($row['Last_name']) ?>"
                              data-email="<?= htmlspecialchars($row['Email']) ?>"
                              data-phone="<?= htmlspecialchars($row['Phone']) ?>"
                              data-role="<?= htmlspecialchars($row['Role']) ?>"
                              data-status="<?= htmlspecialchars($row['UserStatus']) ?>"
                              data-address="<?= htmlspecialchars($row['Full_Address']) ?>"
                              data-created="<?= htmlspecialchars($row['DateCreated']) ?>"
                              data-approved="<?= htmlspecialchars($row['DateApproved'] ?? 'N/A') ?>"
                              data-username="<?= htmlspecialchars($row['Username']) ?>"
                              data-gender="<?= htmlspecialchars($row['Gender'] ?? 'N/A') ?>"
                              data-age="<?= htmlspecialchars($row['Age'] ?? 'N/A') ?>"
                              data-pic="<?= htmlspecialchars($row['Profile_Pic'] ?? '') ?>">
                              <i class="bi bi-eye"></i>
                            </button>

                            <button class="btn-action btn-edit edit-btn" title="Edit"
                              data-id="<?= htmlspecialchars($row['User_ID']) ?>"
                              data-firstname="<?= htmlspecialchars($row['First_name']) ?>"
                              data-lastname="<?= htmlspecialchars($row['Last_name']) ?>"
                              data-email="<?= htmlspecialchars($row['Email']) ?>"
                              data-phone="<?= htmlspecialchars($row['Phone']) ?>"
                              data-role="<?= htmlspecialchars($row['Role']) ?>"
                              data-status="<?= htmlspecialchars($row['UserStatus']) ?>"
                              data-address="<?= htmlspecialchars($row['Full_Address']) ?>"
                              data-created="<?= htmlspecialchars($row['DateCreated']) ?>"
                              data-approved="<?= htmlspecialchars($row['DateApproved'] ?? 'N/A') ?>"
                              data-username="<?= htmlspecialchars($row['Username']) ?>"
                              data-gender="<?= htmlspecialchars($row['Gender'] ?? 'N/A') ?>"
                              data-age="<?= htmlspecialchars($row['Age'] ?? 'N/A') ?>"
                              data-pic="<?= htmlspecialchars($row['Profile_Pic'] ?? '') ?>">
                              <i class="bi bi-pencil"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="10" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        No users found.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>