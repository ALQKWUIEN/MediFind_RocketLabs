/**
 * =============================================================================
 * MediFind - System Reports & LGU Monitoring Mock Data Module
 * File: 07_Assets/js/mock/system_reports.js
 * 
 * TODO: BACKEND ENDPOINT INTEGRATION SPECIFICATION
 * -----------------------------------------------------------------------------
 * When replacing this mock module with live database queries:
 * 1. Target Endpoints:
 *    - GET `02_Actions/04_System-Admin-CRUD/api_reports_kpis.php`
 *      Params: `date_range` (today|7days|30days|this_month), `barangay` (string|all)
 *      Response: { totalUsers, newUsers, newUsersChange, activePharmacies, pendingApprovals, expiredStocks, lowStocks }
 *    - GET `02_Actions/04_System-Admin-CRUD/api_reports_users.php`
 *      Params: `search`, `role`, `status`, `barangay`, `page`, `limit`, `sort_col`, `sort_dir`
 *      Response: { total, filtered, page, pages, data: [ { id, name, email, phone, role, status, barangay, joined } ] }
 *    - GET `02_Actions/04_System-Admin-CRUD/api_reports_pharmacies.php`
 *      Params: `search`, `status`, `barangay`
 *      Response: { total, data: [ { id, name, owner, email, phone, barangay, status, registered, inStock, lowStock, expired } ] }
 *    - GET `02_Actions/04_System-Admin-CRUD/api_reports_stock_monitoring.php`
 *      Params: `barangay`
 *      Response: {
 *        shortages: [ { medicine, generic, category, neededInBarangays, status } ],
 *        fewestCarried: [ { medicine, generic, pharmacyCount, pharmacies: [] } ],
 *        expiringByPharmacy: [ { pharmacy, barangay, expiringCount, nextExpiryDate } ]
 *      }
 *    - GET `02_Actions/04_System-Admin-CRUD/api_reports_charts.php`
 *      Params: `barangay`, `date_range`
 *      Response: { usersByRole, registrationsTimeline, pharmaciesByStatus, pharmaciesByBarangay }
 * =============================================================================
 */

(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    define([], factory);
  } else if (typeof module === 'object' && module.exports) {
    module.exports = factory();
  } else {
    root.SystemReportsMock = factory();
  }
}(typeof self !== 'undefined' ? self : this, function () {

  // ── MALAYBALAY BARANGAYS ──────────────────────────────────────────────
  const BARANGAYS = [
    "Poblacion",
    "Casisang",
    "Sumpong",
    "Aglayan",
    "Bangcud",
    "Impalambong",
    "Kalasungay",
    "San Jose",
    "Dalwangan",
    "Canayan",
    "Laguitas"
  ];

  // ── 38 CONSISTENT USERS ────────────────────────────────────────────────
  // 18 Patients, 14 Pharmacists, 2 Owners, 4 Admins
  const MOCK_USERS = [
    // Patients (18)
    { id: 1, name: "Maria Santos", email: "maria.santos@gmail.com", phone: "0917-234-5678", role: "Patient", status: "Active", barangay: "Casisang", joined: "2024-02-14" },
    { id: 2, name: "Juan dela Cruz", email: "juan.delacruz@yahoo.com", phone: "0928-876-5432", role: "Patient", status: "Active", barangay: "Poblacion", joined: "2024-03-01" },
    { id: 3, name: "Ana Patricia Reyes", email: "ana.reyes@hotmail.com", phone: "0919-334-9912", role: "Patient", status: "Active", barangay: "Sumpong", joined: "2024-03-12" },
    { id: 4, name: "Carlo Mendoza", email: "carlo.mendoza@gmail.com", phone: "0945-123-8890", role: "Patient", status: "Active", barangay: "Aglayan", joined: "2024-04-05" },
    { id: 5, name: "Luzviminda Ramos", email: "luz.ramos@gmail.com", phone: "0917-555-1200", role: "Patient", status: "Active", barangay: "Impalambong", joined: "2024-04-18" },
    { id: 6, name: "Grace Fernandez", email: "grace.fernandez@yahoo.com", phone: "0908-444-2211", role: "Patient", status: "Active", barangay: "Bangcud", joined: "2024-05-02" },
    { id: 7, name: "Mark Anthony Tan", email: "mark.tan@gmail.com", phone: "0930-111-4455", role: "Patient", status: "Active", barangay: "Kalasungay", joined: "2024-05-19" },
    { id: 8, name: "Elena Bautista", email: "elena.bautista@gmail.com", phone: "0917-987-6543", role: "Patient", status: "Active", barangay: "San Jose", joined: "2024-06-08" },
    { id: 9, name: "Rolando Gutierrez", email: "rolando.g@yahoo.com", phone: "0922-333-7788", role: "Patient", status: "Active", barangay: "Casisang", joined: "2024-06-22" },
    { id: 10, name: "Maricel Dizon", email: "maricel.d@gmail.com", phone: "0918-777-6655", role: "Patient", status: "Active", barangay: "Poblacion", joined: "2024-07-04" },
    { id: 11, name: "Danilo Aquino", email: "danilo.aquino@gmail.com", phone: "0947-888-9900", role: "Patient", status: "Suspended", barangay: "Sumpong", joined: "2024-07-15" },
    { id: 12, name: "Rowena Castillo", email: "rowena.c@yahoo.com", phone: "0917-666-4433", role: "Patient", status: "Active", barangay: "Aglayan", joined: "2024-08-01" },
    { id: 13, name: "Eduardo Manalo", email: "eduardo.manalo@gmail.com", phone: "0909-555-2233", role: "Patient", status: "Active", barangay: "Bangcud", joined: "2024-08-18" },
    { id: 14, name: "Kristine Joy Flores", email: "kristine.flores@gmail.com", phone: "0919-444-1122", role: "Patient", status: "Active", barangay: "Impalambong", joined: "2024-09-02" },
    { id: 15, name: "Benjamin Ocampo", email: "benjamin.o@yahoo.com", phone: "0928-111-9988", role: "Patient", status: "Active", barangay: "Dalwangan", joined: "2024-09-14" },
    { id: 16, name: "Sheryl Navarro", email: "sheryl.navarro@gmail.com", phone: "0917-888-3344", role: "Patient", status: "Active", barangay: "Poblacion", joined: "2024-10-01" },
    { id: 17, name: "Dennis Villanueva", email: "dennis.v@gmail.com", phone: "0939-222-7766", role: "Patient", status: "Active", barangay: "Casisang", joined: "2024-10-08" },
    { id: 18, name: "Patricia Mae Cruz", email: "patricia.cruz@gmail.com", phone: "0945-333-6677", role: "Patient", status: "Active", barangay: "Sumpong", joined: "2024-10-15" },

    // Pharmacists (14)
    { id: 19, name: "RPh. Camille Lopez", email: "camille.lopez@rosepharma.ph", phone: "0917-101-2020", role: "Pharmacist", status: "Active", barangay: "Casisang", joined: "2024-01-15" },
    { id: 20, name: "RPh. Gabriel Soriano", email: "gabriel.s@mercurydrug.com", phone: "0928-202-3030", role: "Pharmacist", status: "Active", barangay: "Casisang", joined: "2024-01-20" },
    { id: 21, name: "RPh. Hannah Valdez", email: "hannah.v@generika.ph", phone: "0919-303-4040", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-02-10" },
    { id: 22, name: "RPh. Ian Kenneth Diaz", email: "ian.diaz@watsons.com.ph", phone: "0945-404-5050", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-02-22" },
    { id: 23, name: "RPh. Jennifer Sy", email: "jennifer.sy@rojon.ph", phone: "0917-505-6060", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-03-05" },
    { id: 24, name: "RPh. Kevin Mercado", email: "kevin.m@tgp.com.ph", phone: "0908-606-7070", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-03-18" },
    { id: 25, name: "RPh. Lea Angela Torres", email: "lea.torres@aglayanpharma.ph", phone: "0930-707-8080", role: "Pharmacist", status: "Active", barangay: "Aglayan", joined: "2024-04-12" },
    { id: 26, name: "RPh. Manuel Roxas", email: "manuel.roxas@sumpongcare.ph", phone: "0917-808-9090", role: "Pharmacist", status: "Active", barangay: "Sumpong", joined: "2024-04-28" },
    { id: 27, name: "RPh. Nicole Salazar", email: "nicole.s@rosepharma.ph", phone: "0922-909-0101", role: "Pharmacist", status: "Active", barangay: "Casisang", joined: "2024-05-15" },
    { id: 28, name: "RPh. Oliver Pineda", email: "oliver.p@mercurydrug.com", phone: "0918-010-1212", role: "Pharmacist", status: "Active", barangay: "Casisang", joined: "2024-06-01" },
    { id: 29, name: "RPh. Queenie Alcantara", email: "queenie.a@generika.ph", phone: "0947-121-2323", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-07-10" },
    { id: 30, name: "RPh. Rafael Domingo", email: "rafael.d@watsons.com.ph", phone: "0917-232-3434", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-08-05" },
    { id: 31, name: "RPh. Stephanie Lim", email: "stephanie.lim@rojon.ph", phone: "0909-343-4545", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-09-12" },
    { id: 32, name: "RPh. Tristan Tolentino", email: "tristan.t@tgp.com.ph", phone: "0919-454-5656", role: "Pharmacist", status: "Active", barangay: "Poblacion", joined: "2024-10-02" },

    // Pharmacy Owners (2)
    { id: 33, name: "Rosemarie Tan", email: "rosemarie.tan@rosepharma.ph", phone: "0917-555-0101", role: "Owner", status: "Active", barangay: "Casisang", joined: "2024-01-10" },
    { id: 34, name: "Ramon Sy", email: "ramon.sy@mercuryhealth.ph", phone: "0928-555-0202", role: "Owner", status: "Active", barangay: "Casisang", joined: "2024-01-12" },

    // System Admins (4)
    { id: 35, name: "Princess Dayan", email: "admin.dayan@malaybalay.gov.ph", phone: "0917-999-0001", role: "Admin", status: "Active", barangay: "Poblacion", joined: "2024-01-01" },
    { id: 36, name: "Engr. Victoriano Chavez", email: "v.chavez@malaybalay.gov.ph", phone: "0917-999-0002", role: "Admin", status: "Active", barangay: "Casisang", joined: "2024-01-01" },
    { id: 37, name: "Atty. Katrina Ybañez", email: "legal.lgu@malaybalay.gov.ph", phone: "0917-999-0003", role: "Admin", status: "Active", barangay: "Sumpong", joined: "2024-01-05" },
    { id: 38, name: "Dr. Roberto Guingona", email: "health.officer@malaybalay.gov.ph", phone: "0917-999-0004", role: "Admin", status: "Active", barangay: "Impalambong", joined: "2024-01-05" }
  ];

  // ── 12 CONSISTENT PHARMACIES ───────────────────────────────────────────
  // 8 Approved, 3 Pending, 1 Suspended / Incomplete
  const MOCK_PHARMACIES = [
    // Approved (8)
    {
      id: 1,
      name: "Rose Pharmacy",
      owner: "Rosemarie Tan",
      email: "contact@rosepharma.ph",
      phone: "0917-123-4567",
      barangay: "Casisang",
      status: "Approved",
      registered: "2024-01-15",
      itemsInStock: 320,
      lowStockCount: 4,
      expiredCount: 0
    },
    {
      id: 2,
      name: "Mercury Drug",
      owner: "Ramon Sy",
      email: "malaybalay@mercurydrug.com",
      phone: "0919-444-9821",
      barangay: "Casisang",
      status: "Approved",
      registered: "2024-01-20",
      itemsInStock: 485,
      lowStockCount: 6,
      expiredCount: 1
    },
    {
      id: 3,
      name: "Generika Drugstore",
      owner: "Gina Dela Rosa",
      email: "info@generika-malaybalay.ph",
      phone: "0928-555-0192",
      barangay: "Poblacion",
      status: "Approved",
      registered: "2024-02-10",
      itemsInStock: 260,
      lowStockCount: 2,
      expiredCount: 0
    },
    {
      id: 4,
      name: "Watsons Pharmacy",
      owner: "William Co",
      email: "store1042@watsons.com.ph",
      phone: "0917-889-1123",
      barangay: "Poblacion",
      status: "Approved",
      registered: "2024-02-22",
      itemsInStock: 390,
      lowStockCount: 5,
      expiredCount: 1
    },
    {
      id: 5,
      name: "Rojon Pharmacy",
      owner: "Roberto Jon",
      email: "rojon.malaybalay@gmail.com",
      phone: "0908-123-7788",
      barangay: "Poblacion",
      status: "Approved",
      registered: "2024-03-05",
      itemsInStock: 180,
      lowStockCount: 7,
      expiredCount: 0
    },
    {
      id: 6,
      name: "TGP The Generics Pharmacy",
      owner: "Teresa Garcia",
      email: "tgp.chavez@gmail.com",
      phone: "0930-999-3321",
      barangay: "Poblacion",
      status: "Approved",
      registered: "2024-03-18",
      itemsInStock: 340,
      lowStockCount: 3,
      expiredCount: 0
    },
    {
      id: 7,
      name: "Aglayan Community Pharmacy",
      owner: "Arthur Gomez",
      email: "aglayan.pharma@gmail.com",
      phone: "0945-888-2109",
      barangay: "Aglayan",
      status: "Approved",
      registered: "2024-04-12",
      itemsInStock: 145,
      lowStockCount: 8,
      expiredCount: 2
    },
    {
      id: 8,
      name: "Sumpong Care Drugstore",
      owner: "Sofia Mercado",
      email: "sumpong.care@gmail.com",
      phone: "0956-777-4312",
      barangay: "Sumpong",
      status: "Approved",
      registered: "2024-04-28",
      itemsInStock: 210,
      lowStockCount: 4,
      expiredCount: 0
    },

    // Pending (3)
    {
      id: 9,
      name: "Bukidnon Health Hub Pharmacy",
      owner: "Brenda Lee",
      email: "brenda.lee.ph@gmail.com",
      phone: "0917-777-8899",
      barangay: "Bangcud",
      status: "Pending",
      registered: "2024-10-14",
      itemsInStock: 0,
      lowStockCount: 0,
      expiredCount: 0
    },
    {
      id: 10,
      name: "San Jose Meds & Wellness",
      owner: "Samuel P. Cruz",
      email: "samuel.cruz@sanjosemeds.ph",
      phone: "0928-333-4411",
      barangay: "San Jose",
      status: "Pending",
      registered: "2024-10-18",
      itemsInStock: 0,
      lowStockCount: 0,
      expiredCount: 0
    },
    {
      id: 11,
      name: "Kalasungay Pharma Care",
      owner: "Karen Villanueva",
      email: "karen.pharma@gmail.com",
      phone: "0939-555-6677",
      barangay: "Kalasungay",
      status: "Pending",
      registered: "2024-10-19",
      itemsInStock: 0,
      lowStockCount: 0,
      expiredCount: 0
    },

    // Suspended / Incomplete (1)
    {
      id: 12,
      name: "", // truly missing name
      owner: "", // truly missing owner
      email: "",
      phone: "",
      barangay: "",
      status: "Suspended",
      registered: "2024-07-01",
      itemsInStock: 0,
      lowStockCount: 0,
      expiredCount: 0
    }
  ];

  // ── LGU STOCK & SHORTAGE MONITORING DATA ──────────────────────────────
  const STOCK_MONITORING = {
    // 1. Medicines Out of Stock in ALL Pharmacies (City-Wide Shortage Alert)
    shortages: [
      {
        id: 101,
        brand: "Verorab",
        generic: "Rabies Vaccine (Inactivated)",
        category: "Biological / Vaccine",
        severity: "Critical Alert",
        affectedBarangays: "All 11 Barangays",
        lastReported: "3 days ago",
        status: "Out of Stock City-Wide"
      },
      {
        id: 102,
        brand: "Dexamethasone Vial",
        generic: "Dexamethasone Sodium Phosphate 4mg/mL",
        category: "Corticosteroid",
        severity: "Critical Alert",
        affectedBarangays: "All 11 Barangays",
        lastReported: "5 days ago",
        status: "Out of Stock City-Wide"
      },
      {
        id: 103,
        brand: "Adrenalin",
        generic: "Epinephrine 1mg/mL Ampule",
        category: "Emergency / Anaphylaxis",
        severity: "Urgent Shortage",
        affectedBarangays: "All 11 Barangays",
        lastReported: "1 week ago",
        status: "Out of Stock City-Wide"
      }
    ],

    // 2. Medicines Carried by Fewest Pharmacies (Supply Vulnerability Index)
    fewestCarried: [
      {
        medicine: "Digoxin 0.25mg Tablet",
        generic: "Digoxin",
        category: "Cardiovascular",
        pharmacyCount: 1,
        totalPharmacies: 8,
        carriedBy: "Mercury Drug (Casisang)",
        vulnerability: "High Vulnerability",
        cityStockTotal: 18
      },
      {
        medicine: "Levothyroxine 50mcg",
        generic: "Levothyroxine Sodium",
        category: "Thyroid Hormone",
        pharmacyCount: 2,
        totalPharmacies: 8,
        carriedBy: "Rose Pharmacy, Mercury Drug",
        vulnerability: "Moderate Vulnerability",
        cityStockTotal: 42
      },
      {
        medicine: "Regular Insulin 100IU/mL",
        generic: "Human Insulin Recombinant",
        category: "Antidiabetic",
        pharmacyCount: 2,
        totalPharmacies: 8,
        carriedBy: "Rose Pharmacy, Watsons Pharmacy",
        vulnerability: "Moderate Vulnerability",
        cityStockTotal: 25
      },
      {
        medicine: "Methotrexate 2.5mg",
        generic: "Methotrexate",
        category: "Immunosuppressive / Antineoplastic",
        pharmacyCount: 2,
        totalPharmacies: 8,
        carriedBy: "Mercury Drug, TGP",
        vulnerability: "Moderate Vulnerability",
        cityStockTotal: 30
      }
    ],

    // 3. Pharmacies with Most Expiring Items (Compliance & Waste Alert)
    expiringPharmacies: [
      {
        pharmacy: "Aglayan Community Pharmacy",
        barangay: "Aglayan",
        owner: "Arthur Gomez",
        expiringCount: 2,
        items: "Ibuprofen 200mg (Exp: Oct 2024), Cetirizine 10mg (Exp: Nov 2024)",
        riskLevel: "Attention Needed"
      },
      {
        pharmacy: "Mercury Drug",
        barangay: "Casisang",
        owner: "Ramon Sy",
        expiringCount: 1,
        items: "Amoxicillin 250mg Suspension (Exp: Nov 2024)",
        riskLevel: "Low Risk"
      },
      {
        pharmacy: "Watsons Pharmacy",
        barangay: "Poblacion",
        owner: "William Co",
        expiringCount: 1,
        items: "Cough Syrup Guiafenesin (Exp: Dec 2024)",
        riskLevel: "Low Risk"
      }
    ]
  };

  // ── FILTER ENGINE ──────────────────────────────────────────────────────
  function getFilteredData(filters = {}) {
    const brgy = filters.barangay && filters.barangay !== 'all' ? filters.barangay : '';
    const dateRange = filters.dateRange || 'month';

    // Filter Users
    let users = [...MOCK_USERS];
    if (brgy) {
      users = users.filter(u => u.barangay.toLowerCase() === brgy.toLowerCase());
    }

    // Filter Pharmacies
    let pharmacies = [...MOCK_PHARMACIES];
    if (brgy) {
      pharmacies = pharmacies.filter(p => p.barangay.toLowerCase() === brgy.toLowerCase());
    }

    // Calculate KPIs
    const totalUsers = users.length;
    const activeUsers = users.filter(u => u.status === 'Active').length;
    const newUsers = dateRange === 'today' ? 1 : (dateRange === '7days' ? 3 : 6);
    const newUsersPct = dateRange === 'today' ? '+2.8%' : (dateRange === '7days' ? '+8.5%' : '+18.7%');

    const activePharmacies = pharmacies.filter(p => p.status === 'Approved').length;
    const pendingPharmacies = pharmacies.filter(p => p.status === 'Pending').length;
    const expiredStocks = pharmacies.reduce((sum, p) => sum + p.expiredCount, 0);
    const lowStockItems = pharmacies.reduce((sum, p) => sum + p.lowStockCount, 0);

    // Chart 1: Users by Role
    const userRoleCounts = {
      Patient: users.filter(u => u.role === 'Patient').length,
      Pharmacist: users.filter(u => u.role === 'Pharmacist').length,
      Owner: users.filter(u => u.role === 'Owner').length,
      Admin: users.filter(u => u.role === 'Admin').length
    };

    // Chart 2: Registrations Over Time (Simulated dynamic trend)
    const timelineLabels = ["May", "Jun", "Jul", "Aug", "Sep", "Oct"];
    const timelineData = brgy ? [2, 1, 3, 2, 4, users.length] : [4, 6, 8, 5, 9, 6];

    // Chart 3: Pharmacies by Status
    const pharmacyStatusCounts = {
      Approved: pharmacies.filter(p => p.status === 'Approved').length,
      Pending: pharmacies.filter(p => p.status === 'Pending').length,
      Suspended: pharmacies.filter(p => p.status === 'Suspended').length
    };

    // Chart 4: Pharmacies by Barangay
    const brgyDistribution = {};
    BARANGAYS.forEach(b => {
      brgyDistribution[b] = MOCK_PHARMACIES.filter(p => p.barangay.toLowerCase() === b.toLowerCase()).length;
    });

    return {
      kpis: {
        totalUsers,
        newUsers,
        newUsersPct,
        activePharmacies,
        pendingApprovals: pendingPharmacies,
        expiredStocks,
        lowStockItems
      },
      charts: {
        usersByRole: userRoleCounts,
        timeline: { labels: timelineLabels, data: timelineData },
        pharmacyStatus: pharmacyStatusCounts,
        pharmacyByBarangay: brgyDistribution
      },
      users: users,
      pharmacies: pharmacies,
      stockMonitoring: STOCK_MONITORING
    };
  }

  // ── INPUT MOCK DATA METHODS ───────────────────────────────────────────
  function addMockUser(user) {
    const newId = MOCK_USERS.length > 0 ? Math.max(...MOCK_USERS.map(u => u.id || 0)) + 1 : 1;
    const now = new Date();
    const formattedDate = now.toISOString().split('T')[0];
    const fullUser = {
      id: newId,
      name: user.name && user.name.trim() !== '' ? user.name.trim() : `Mock User #${newId}`,
      email: user.email && user.email.trim() !== '' ? user.email.trim() : `user${newId}@example.com`,
      phone: user.phone && user.phone.trim() !== '' ? user.phone.trim() : '0917-000-0000',
      role: user.role || 'Patient',
      status: user.status || 'Active',
      barangay: user.barangay || 'Poblacion',
      joined: user.joined || formattedDate
    };
    MOCK_USERS.unshift(fullUser);
    return fullUser;
  }

  function addMockPharmacy(pharmacy) {
    const newId = MOCK_PHARMACIES.length > 0 ? Math.max(...MOCK_PHARMACIES.map(p => p.id || 0)) + 1 : 1;
    const now = new Date();
    const formattedDate = now.toISOString().split('T')[0];
    const fullPharm = {
      id: newId,
      name: pharmacy.name && pharmacy.name.trim() !== '' ? pharmacy.name.trim() : `Mock Pharmacy #${newId}`,
      owner: pharmacy.owner && pharmacy.owner.trim() !== '' ? pharmacy.owner.trim() : 'Not provided',
      email: pharmacy.email && pharmacy.email.trim() !== '' ? pharmacy.email.trim() : `pharmacy${newId}@example.ph`,
      phone: pharmacy.phone && pharmacy.phone.trim() !== '' ? pharmacy.phone.trim() : '0928-000-0000',
      barangay: pharmacy.barangay || 'Poblacion',
      status: pharmacy.status || 'Pending',
      registered: pharmacy.registered || formattedDate,
      itemsInStock: parseInt(pharmacy.itemsInStock, 10) || 0,
      lowStockCount: parseInt(pharmacy.lowStockCount, 10) || 0,
      expiredCount: parseInt(pharmacy.expiredCount, 10) || 0
    };
    MOCK_PHARMACIES.unshift(fullPharm);
    return fullPharm;
  }

  function addMockShortage(shortage) {
    const newId = STOCK_MONITORING.shortages.length > 0 ? Math.max(...STOCK_MONITORING.shortages.map(s => s.id || 0)) + 1 : 101;
    const fullShortage = {
      id: newId,
      brand: shortage.brand && shortage.brand.trim() !== '' ? shortage.brand.trim() : 'Critical Medicine',
      generic: shortage.generic && shortage.generic.trim() !== '' ? shortage.generic.trim() : 'Generic Formulation',
      category: shortage.category || 'Essential Medicine',
      severity: shortage.severity || 'Critical Alert',
      affectedBarangays: shortage.affectedBarangays || 'All 11 Barangays',
      lastReported: shortage.lastReported || 'Just now',
      status: shortage.status || 'Out of Stock City-Wide'
    };
    STOCK_MONITORING.shortages.unshift(fullShortage);
    return fullShortage;
  }

  return {
    getBarangays: () => [...BARANGAYS],
    getFilteredData: getFilteredData,
    getAllUsers: () => [...MOCK_USERS],
    getAllPharmacies: () => [...MOCK_PHARMACIES],
    getStockMonitoring: () => JSON.parse(JSON.stringify(STOCK_MONITORING)),
    addMockUser: addMockUser,
    addMockPharmacy: addMockPharmacy,
    addMockShortage: addMockShortage
  };

}));
