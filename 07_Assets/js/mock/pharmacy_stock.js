/**
 * =============================================================================
 * MediFind - Pharmacy Stock & Recommendation Engine (Mock Module)
 * File: 07_Assets/js/mock/pharmacy_stock.js
 * 
 * TODO: BACKEND INTEGRATION SPECIFICATION
 * -----------------------------------------------------------------------------
 * When replacing this mock data with live database records:
 * 1. Endpoint: GET/POST `02_Actions/04_System-Admin-CRUD/get-pharmacy-stock.php`
 * 2. Parameters:
 *    - `medicine_ids[]` (int array of 12_meds_medicines.Medicine_ID)
 *    - `patient_barangay` (string name or 08_address_barangay.Barangay_ID)
 * 3. Database Tables Involved:
 *    - `09_pharmacies` ph
 *    - `21_inventory` inv (ph.Pharmacy_ID = inv.Pharmacy_ID)
 *    - `view_05_medicine_master` med (inv.Medicine_ID = med.Medicine_ID)
 *    - `view_02_address` addr (ph.Address_ID = addr.Address_ID)
 * 4. Sample SQL Query:
 *    SELECT 
 *        ph.Pharmacy_ID AS pharmacyId,
 *        ph.Pharmacy_name AS name,
 *        addr.Barangay_Name AS barangay,
 *        addr.Full_Address AS address,
 *        ph.Phone AS contact,
 *        1 AS openNow,
 *        'Open · Closes 9PM' AS openingHours,
 *        COALESCE(
 *          (SELECT Distance_KM FROM 25_barangay_distances 
 *           WHERE From_Barangay = :patient_barangay AND To_Barangay = addr.Barangay_Name LIMIT 1),
 *          1.2
 *        ) AS distanceKm,
 *        JSON_ARRAYAGG(
 *          JSON_OBJECT(
 *            'medicineId', inv.Medicine_ID,
 *            'generic', med.Generic_Name,
 *            'brand', med.Brand_Name,
 *            'dosage', med.Dosage,
 *            'qty', inv.Quantity,
 *            'price', inv.Price
 *          )
 *        ) AS stock
 *    FROM 09_pharmacies ph
 *    JOIN view_02_address addr ON ph.Address_ID = addr.Address_ID
 *    JOIN 21_inventory inv ON ph.Pharmacy_ID = inv.Pharmacy_ID
 *    JOIN view_05_medicine_master med ON inv.Medicine_ID = med.Medicine_ID
 *    WHERE ph.Approval_ID = 2 -- Approved only
 *      AND inv.Medicine_ID IN (:medicine_ids)
 *    GROUP BY ph.Pharmacy_ID;
 * 5. Expected Response Shape:
 *    [
 *      {
 *        "pharmacyId": 1,
 *        "name": "Rose Pharmacy",
 *        "barangay": "Poblacion",
 *        "address": "Sayre Highway, Poblacion, Malaybalay City",
 *        "contact": "0917-123-4567",
 *        "distanceKm": 0.5,
 *        "openNow": true,
 *        "openingHours": "Open · Closes 10PM",
 *        "stock": [
 *          { "medicineId": 1, "generic": "Paracetamol", "brand": "Biogesic", "dosage": "500mg", "qty": 150, "price": 2.75 }
 *        ]
 *      }
 *    ]
 * =============================================================================
 */

(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    define([], factory);
  } else if (typeof module === 'object' && module.exports) {
    module.exports = factory();
  } else {
    root.PharmacyStockMock = factory();
  }
}(typeof self !== 'undefined' ? self : this, function () {

  // Master medicine catalog available for search/multi-select
  const MEDICINE_CATALOG = [
    { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg · Tablet", category: "Analgesic / Antipyretic" },
    { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg · Capsule", category: "Antibiotic" },
    { medicineId: 3, generic: "Ibuprofen", brand: "Medicol Advance", dosage: "200mg · Softgel", category: "NSAID" },
    { medicineId: 4, generic: "Cetirizine", brand: "Alnix", dosage: "10mg · Tablet", category: "Antihistamine" },
    { medicineId: 5, generic: "Losartan", brand: "Cozaar", dosage: "50mg · Tablet", category: "Antihypertensive" },
    { medicineId: 6, generic: "Metformin", brand: "Glucophage", dosage: "500mg · Tablet", category: "Antidiabetic" },
    { medicineId: 7, generic: "Mefenamic Acid", brand: "Ponstan", dosage: "500mg · Capsule", category: "Analgesic" },
    { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg · Capsule", category: "Mucolytic" },
    { medicineId: 9, generic: "Multivitamins + Iron", brand: "Enervon C", dosage: "500mg · Tablet", category: "Vitamins" },
    { medicineId: 10, generic: "Omeprazole", brand: "Losec", dosage: "20mg · Capsule", category: "Antacid / PPI" },
    { medicineId: 11, generic: "Amlodipine", brand: "Norvasc", dosage: "5mg · Tablet", category: "Antihypertensive" },
    { medicineId: 12, generic: "Ascorbic Acid (Vitamin C)", brand: "Ceelin", dosage: "500mg · Tablet", category: "Immunity" }
  ];

  // Approved pharmacies with live-like stock levels in Malaybalay City
  const MOCK_PHARMACIES = [
    {
      pharmacyId: 1,
      name: "Rose Pharmacy",
      barangay: "Poblacion",
      address: "Sayre Highway, Poblacion, Malaybalay City, Bukidnon",
      contact: "0917-123-4567",
      distanceKm: 0.5,
      openNow: true,
      openingHours: "Open · Closes 10PM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 150, price: 2.75 },
        { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg", qty: 45, price: 8.50 },
        { medicineId: 3, generic: "Ibuprofen", brand: "Medicol Advance", dosage: "200mg", qty: 8, price: 3.00 }, // low stock
        { medicineId: 4, generic: "Cetirizine", brand: "Alnix", dosage: "10mg", qty: 65, price: 5.00 },
        { medicineId: 5, generic: "Losartan", brand: "Cozaar", dosage: "50mg", qty: 40, price: 6.00 },
        { medicineId: 6, generic: "Metformin", brand: "Glucophage", dosage: "500mg", qty: 70, price: 4.50 },
        { medicineId: 7, generic: "Mefenamic Acid", brand: "Ponstan", dosage: "500mg", qty: 25, price: 4.00 },
        { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg", qty: 85, price: 7.00 },
        { medicineId: 9, generic: "Multivitamins + Iron", brand: "Enervon C", dosage: "500mg", qty: 120, price: 8.00 },
        { medicineId: 10, generic: "Omeprazole", brand: "Losec", dosage: "20mg", qty: 30, price: 7.25 }
      ]
    },
    {
      pharmacyId: 2,
      name: "Generika Drugstore",
      barangay: "Poblacion",
      address: "Fortich St., Poblacion, Malaybalay City, Bukidnon",
      contact: "0928-555-0192",
      distanceKm: 0.8,
      openNow: true,
      openingHours: "Open · Closes 9PM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 90, price: 2.50 },
        { medicineId: 3, generic: "Ibuprofen", brand: "Medicol Advance", dosage: "200mg", qty: 35, price: 2.90 },
        { medicineId: 5, generic: "Losartan", brand: "Cozaar", dosage: "50mg", qty: 85, price: 5.50 },
        { medicineId: 6, generic: "Metformin", brand: "Glucophage", dosage: "500mg", qty: 60, price: 4.00 },
        { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg", qty: 6, price: 6.50 }, // low stock
        { medicineId: 11, generic: "Amlodipine", brand: "Norvasc", dosage: "5mg", qty: 50, price: 3.80 },
        { medicineId: 12, generic: "Ascorbic Acid (Vitamin C)", brand: "Ceelin", dosage: "500mg", qty: 140, price: 2.00 }
      ]
    },
    {
      pharmacyId: 3,
      name: "Mercury Drug",
      barangay: "Casisang",
      address: "Sayre Highway, Casisang, Malaybalay City, Bukidnon",
      contact: "0919-444-9821",
      distanceKm: 1.1,
      openNow: true,
      openingHours: "Open · 24 Hours",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 220, price: 3.00 },
        { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg", qty: 55, price: 9.00 },
        { medicineId: 3, generic: "Ibuprofen", brand: "Medicol Advance", dosage: "200mg", qty: 4, price: 3.25 }, // low stock <= 10
        { medicineId: 4, generic: "Cetirizine", brand: "Alnix", dosage: "10mg", qty: 95, price: 5.50 },
        { medicineId: 5, generic: "Losartan", brand: "Cozaar", dosage: "50mg", qty: 110, price: 6.25 },
        { medicineId: 6, generic: "Metformin", brand: "Glucophage", dosage: "500mg", qty: 90, price: 4.75 },
        { medicineId: 7, generic: "Mefenamic Acid", brand: "Ponstan", dosage: "500mg", qty: 40, price: 4.25 },
        { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg", qty: 115, price: 7.25 },
        { medicineId: 9, generic: "Multivitamins + Iron", brand: "Enervon C", dosage: "500mg", qty: 130, price: 8.50 },
        { medicineId: 10, generic: "Omeprazole", brand: "Losec", dosage: "20mg", qty: 45, price: 7.50 },
        { medicineId: 11, generic: "Amlodipine", brand: "Norvasc", dosage: "5mg", qty: 75, price: 4.20 },
        { medicineId: 12, generic: "Ascorbic Acid (Vitamin C)", brand: "Ceelin", dosage: "500mg", qty: 180, price: 2.25 }
      ]
    },
    {
      pharmacyId: 4,
      name: "Watsons Pharmacy",
      barangay: "Poblacion",
      address: "Centrio Mall, Sayre Highway, Poblacion, Malaybalay City",
      contact: "0917-889-1123",
      distanceKm: 1.4,
      openNow: true,
      openingHours: "Open · Closes 9PM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 130, price: 2.75 },
        { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg", qty: 0, price: 8.50 }, // out of stock
        { medicineId: 4, generic: "Cetirizine", brand: "Alnix", dosage: "10mg", qty: 50, price: 5.00 },
        { medicineId: 7, generic: "Mefenamic Acid", brand: "Ponstan", dosage: "500mg", qty: 60, price: 4.10 },
        { medicineId: 9, generic: "Multivitamins + Iron", brand: "Enervon C", dosage: "500mg", qty: 200, price: 7.80 },
        { medicineId: 10, generic: "Omeprazole", brand: "Losec", dosage: "20mg", qty: 9, price: 7.00 }, // low stock
        { medicineId: 12, generic: "Ascorbic Acid (Vitamin C)", brand: "Ceelin", dosage: "500mg", qty: 190, price: 2.00 }
      ]
    },
    {
      pharmacyId: 5,
      name: "Rojon Pharmacy",
      barangay: "Poblacion",
      address: "546C+P3R, Sayre Hwy, Poblacion, Malaybalay City",
      contact: "0908-123-7788",
      distanceKm: 1.7,
      openNow: true,
      openingHours: "Open · Closes 10PM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 80, price: 2.50 },
        { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg", qty: 15, price: 8.20 },
        { medicineId: 3, generic: "Ibuprofen", brand: "Medicol Advance", dosage: "200mg", qty: 12, price: 2.80 },
        { medicineId: 7, generic: "Mefenamic Acid", brand: "Ponstan", dosage: "500mg", qty: 30, price: 3.90 },
        { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg", qty: 0, price: 6.80 }, // out of stock
        { medicineId: 11, generic: "Amlodipine", brand: "Norvasc", dosage: "5mg", qty: 45, price: 3.90 }
      ]
    },
    {
      pharmacyId: 6,
      name: "TGP The Generics Pharmacy",
      barangay: "Poblacion",
      address: "Chavez St., Poblacion, Malaybalay City, Bukidnon",
      contact: "0930-999-3321",
      distanceKm: 2.0,
      openNow: false,
      openingHours: "Closed · Opens 8AM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 180, price: 2.00 },
        { medicineId: 5, generic: "Losartan", brand: "Cozaar", dosage: "50mg", qty: 70, price: 3.50 },
        { medicineId: 6, generic: "Metformin", brand: "Glucophage", dosage: "500mg", qty: 95, price: 3.20 },
        { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg", qty: 40, price: 5.50 },
        { medicineId: 11, generic: "Amlodipine", brand: "Norvasc", dosage: "5mg", qty: 100, price: 3.00 },
        { medicineId: 12, generic: "Ascorbic Acid (Vitamin C)", brand: "Ceelin", dosage: "500mg", qty: 160, price: 1.75 }
      ]
    },
    {
      pharmacyId: 7,
      name: "Aglayan Community Pharmacy",
      barangay: "Aglayan",
      address: "Purok 3, Aglayan, Malaybalay City, Bukidnon",
      contact: "0945-888-2109",
      distanceKm: 8.5,
      openNow: true,
      openingHours: "Open · Closes 8PM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 75, price: 2.75 },
        { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg", qty: 20, price: 8.50 },
        { medicineId: 4, generic: "Cetirizine", brand: "Alnix", dosage: "10mg", qty: 18, price: 5.25 },
        { medicineId: 8, generic: "Carbocisteine", brand: "Solmux", dosage: "500mg", qty: 25, price: 7.00 },
        { medicineId: 12, generic: "Ascorbic Acid (Vitamin C)", brand: "Ceelin", dosage: "500mg", qty: 50, price: 2.25 }
      ]
    },
    {
      pharmacyId: 8,
      name: "Sumpong Care Drugstore",
      barangay: "Sumpong",
      address: "National Highway, Sumpong, Malaybalay City, Bukidnon",
      contact: "0956-777-4312",
      distanceKm: 2.8,
      openNow: true,
      openingHours: "Open · Closes 9PM",
      stock: [
        { medicineId: 1, generic: "Paracetamol", brand: "Biogesic", dosage: "500mg", qty: 110, price: 2.60 },
        { medicineId: 2, generic: "Amoxicillin", brand: "Amoxil", dosage: "500mg", qty: 30, price: 8.30 },
        { medicineId: 3, generic: "Ibuprofen", brand: "Medicol Advance", dosage: "200mg", qty: 22, price: 3.10 },
        { medicineId: 5, generic: "Losartan", brand: "Cozaar", dosage: "50mg", qty: 45, price: 5.80 },
        { medicineId: 6, generic: "Metformin", brand: "Glucophage", dosage: "500mg", qty: 35, price: 4.20 },
        { medicineId: 10, generic: "Omeprazole", brand: "Losec", dosage: "20mg", qty: 15, price: 7.10 }
      ]
    }
  ];

  /**
   * Approximate distances from patient's selected barangay
   */
  const BARANGAY_DISTANCES = {
    "Poblacion": { "Poblacion": 0.5, "Casisang": 1.5, "Sumpong": 2.2, "Impalambong": 1.2, "Kalasungay": 4.5, "Aglayan": 9.2, "San Jose": 3.8, "Dalwangan": 12.0 },
    "Casisang": { "Poblacion": 1.5, "Casisang": 0.4, "Sumpong": 3.4, "Impalambong": 2.5, "Kalasungay": 5.8, "Aglayan": 7.5, "San Jose": 2.6, "Dalwangan": 13.5 },
    "Sumpong": { "Poblacion": 2.2, "Casisang": 3.4, "Sumpong": 0.5, "Impalambong": 1.8, "Kalasungay": 3.2, "Aglayan": 11.0, "San Jose": 5.2, "Dalwangan": 10.2 },
    "Impalambong": { "Poblacion": 1.2, "Casisang": 2.5, "Sumpong": 1.8, "Impalambong": 0.4, "Kalasungay": 3.9, "Aglayan": 10.1, "San Jose": 4.1, "Dalwangan": 11.1 },
    "Aglayan": { "Poblacion": 9.2, "Casisang": 7.5, "Sumpong": 11.0, "Impalambong": 10.1, "Kalasungay": 13.5, "Aglayan": 0.6, "San Jose": 6.8, "Dalwangan": 20.5 },
    "Kalasungay": { "Poblacion": 4.5, "Casisang": 5.8, "Sumpong": 3.2, "Impalambong": 3.9, "Kalasungay": 0.7, "Aglayan": 13.5, "San Jose": 7.2, "Dalwangan": 7.8 },
    "San Jose": { "Poblacion": 3.8, "Casisang": 2.6, "Sumpong": 5.2, "Impalambong": 4.1, "Kalasungay": 7.2, "Aglayan": 6.8, "San Jose": 0.5, "Dalwangan": 15.0 },
    "Dalwangan": { "Poblacion": 12.0, "Casisang": 13.5, "Sumpong": 10.2, "Impalambong": 11.1, "Kalasungay": 7.8, "Aglayan": 20.5, "San Jose": 15.0, "Dalwangan": 0.8 }
  };

  /**
   * Helper: compute stock chip status
   * - Available: qty > 10
   * - Low Stock: 1 <= qty <= 10
   * - Out of Stock: qty <= 0
   */
  function getStockChipStatus(qty) {
    if (qty > 10) {
      return { status: 'available', label: `In Stock · ${qty} pcs`, cssClass: 'stock-chip-green' };
    } else if (qty > 0) {
      return { status: 'low', label: `Low Stock · ${qty} left`, cssClass: 'stock-chip-amber' };
    } else {
      return { status: 'out', label: 'Out of Stock', cssClass: 'stock-chip-red' };
    }
  }

  /**
   * Core Recommendation Engine
   * Ranks approved pharmacies based on:
   * 1. Pharmacies having ALL requested medicines in stock (qty > 0)
   * 2. Then pharmacies with partial stock
   * 3. Within each group: sort by total available quantity descending, then distanceKm ascending
   */
  function findRecommendedPharmacies(options) {
    const requestedMedicineIds = options.medicineIds || [];
    const patientBarangay = options.patientBarangay || 'Poblacion';

    if (!requestedMedicineIds.length) {
      return [];
    }

    const totalRequested = requestedMedicineIds.length;

    // Process each pharmacy
    const results = MOCK_PHARMACIES.map(pharmacy => {
      // Adjust distance based on patient's barangay if available
      let distance = pharmacy.distanceKm;
      if (BARANGAY_DISTANCES[patientBarangay] && BARANGAY_DISTANCES[patientBarangay][pharmacy.barangay] !== undefined) {
        distance = BARANGAY_DISTANCES[patientBarangay][pharmacy.barangay];
      }

      // Check each requested medicine in this pharmacy's stock
      const matchedMeds = requestedMedicineIds.map(medId => {
        const catalogItem = MEDICINE_CATALOG.find(m => m.medicineId === medId) || { generic: `Medicine #${medId}`, brand: '', dosage: '' };
        const inStock = pharmacy.stock.find(s => s.medicineId === medId);

        const qty = inStock ? inStock.qty : 0;
        const price = inStock ? inStock.price : null;
        const chip = getStockChipStatus(qty);

        return {
          medicineId: medId,
          generic: (inStock && inStock.generic) || catalogItem.generic,
          brand: (inStock && inStock.brand) || catalogItem.brand,
          dosage: (inStock && inStock.dosage) || catalogItem.dosage,
          qty: qty,
          price: price,
          chip: chip
        };
      });

      // Count in-stock matches (qty > 0)
      const inStockCount = matchedMeds.filter(m => m.qty > 0).length;
      const totalAvailableQty = matchedMeds.reduce((sum, m) => sum + (m.qty > 0 ? m.qty : 0), 0);
      const hasAll = inStockCount === totalRequested;
      const hasPartial = inStockCount > 0 && inStockCount < totalRequested;

      let matchType = 'none';
      let matchBadgeText = `0 of ${totalRequested} in stock`;
      let matchBadgeClass = 'badge-match-red';

      if (hasAll) {
        matchType = 'all';
        matchBadgeText = `Has all ${totalRequested} of ${totalRequested}`;
        matchBadgeClass = 'badge-match-green';
      } else if (hasPartial) {
        matchType = 'partial';
        matchBadgeText = `Has ${inStockCount} of ${totalRequested}`;
        matchBadgeClass = 'badge-match-amber';
      }

      return {
        ...pharmacy,
        distanceKm: distance,
        matchedMeds: matchedMeds,
        inStockCount: inStockCount,
        totalRequested: totalRequested,
        totalAvailableQty: totalAvailableQty,
        matchType: matchType,
        matchBadgeText: matchBadgeText,
        matchBadgeClass: matchBadgeClass
      };
    });

    // Ranking algorithm:
    // 1. matchType === 'all' first, then 'partial', then 'none'
    // 2. Within group: totalAvailableQty descending
    // 3. Then distanceKm ascending
    results.sort((a, b) => {
      const typeRank = { all: 1, partial: 2, none: 3 };
      if (typeRank[a.matchType] !== typeRank[b.matchType]) {
        return typeRank[a.matchType] - typeRank[b.matchType];
      }
      if (b.totalAvailableQty !== a.totalAvailableQty) {
        return b.totalAvailableQty - a.totalAvailableQty;
      }
      return a.distanceKm - b.distanceKm;
    });

    return results;
  }

  /**
   * Generate Patient Recommendation Message Preview
   */
  function generateRecommendationMessage(patientName, pharmacy, medicines) {
    const greeting = patientName && patientName.trim() ? `Hi ${patientName.trim()},` : "Hello,";
    
    const availableMeds = medicines.filter(m => m.qty > 0);
    let medDetails = "";

    if (availableMeds.length > 0) {
      const medListParts = availableMeds.map(m => {
        const brandStr = m.brand ? ` (${m.brand})` : "";
        const priceStr = m.price ? ` at ₱${m.price.toFixed(2)}/pc` : "";
        return `${m.generic} ${m.dosage}${brandStr} [${m.qty} in stock${priceStr}]`;
      });
      medDetails = medListParts.join("; ");
    } else {
      medDetails = "currently checking availability for your requested medicines";
    }

    return `${greeting} based on current stock, ${pharmacy.name} in ${pharmacy.barangay} has: ${medDetails}. Address: ${pharmacy.address}. Distance: ~${pharmacy.distanceKm.toFixed(1)} km. Operating Hours: ${pharmacy.openingHours}. Contact: ${pharmacy.contact}. Recommended via MediFind Malaybalay.`;
  }

  return {
    getCatalog: function () {
      return [...MEDICINE_CATALOG];
    },
    getPharmacies: function () {
      return [...MOCK_PHARMACIES];
    },
    findRecommendations: findRecommendedPharmacies,
    generateMessage: generateRecommendationMessage,
    getStockChipStatus: getStockChipStatus
  };

}));
