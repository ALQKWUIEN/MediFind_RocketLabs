<?php
// 05_PharmacyAdmin/manage_inventory.php
include_once __DIR__ . '/../02_Actions/GlobalVariables.php';
include_once __DIR__ . '/../00_Config/config.php';

// ── GUARD: Users must log in ────────────────────────
if (empty($_SESSION['user_id'])) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

$pharmacyID = (int) ($_SESSION['pharmacy_id'] ?? 0);
if (!$pharmacyID) {
  $stmt = $pdo->prepare("SELECT Pharmacy_ID, Pharmacy_name FROM 09_pharmacies WHERE User_ID = ? LIMIT 1");
  $stmt->execute([$_SESSION['user_id']]);
  $p = $stmt->fetch();
  if ($p) {
    $pharmacyID = (int) $p['Pharmacy_ID'];
    $_SESSION['pharmacy_id'] = $pharmacyID;
    $_SESSION['pharmacy_name'] = $p['Pharmacy_name'];
  } else {
    die("Error: Pharmacy ID not found in session. Please log in again.");
  }
}

// ── Seed 32+ Realistic Philippine Mock Medicines if Inventory is Sparse ──
try {
  $invCountCheck = $pdo->prepare("SELECT COUNT(*) FROM 21_inventory WHERE Pharmacy_ID = ? AND (IsDeleted = 0 OR IsDeleted IS NULL)");
  $invCountCheck->execute([$pharmacyID]);
  $curCount = (int) $invCountCheck->fetchColumn();

  if ($curCount < 30) {
    $philippineMeds = [
      ['generic' => 'Paracetamol', 'brand' => 'Biogesic', 'cat' => 'Analgesics & Antipyretics', 'mfr' => 'Unilab', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'No', 'price' => 8.50, 'qty' => 150, 'days' => 365, 'img' => '../07_Assets/images/medicines/Paracetamol.png', 'desc' => 'First-line analgesic and antipyretic for minor aches, fever, and headaches. Optimal counter stock.'],
      ['generic' => 'Amoxicillin', 'brand' => 'Amoxil', 'cat' => 'Antibiotics', 'mfr' => 'GlaxoSmithKline', 'form' => 'Capsule', 'val' => '500', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 14.00, 'qty' => 8, 'days' => 365, 'img' => '../07_Assets/images/medicines/Amoxicilin.png', 'desc' => 'Broad-spectrum aminopenicillin for respiratory and soft tissue bacterial infections. Low stock warning.'],
      ['generic' => 'Ibuprofen', 'brand' => 'Advil', 'cat' => 'Analgesics & Antipyretics', 'mfr' => 'Pfizer', 'form' => 'Tablet', 'val' => '200', 'unit' => 'mg', 'rx' => 'No', 'price' => 9.50, 'qty' => 85, 'days' => 365, 'img' => '../07_Assets/images/medicines/Ibuprofen.png', 'desc' => 'Potent NSAID reducing acute inflammatory dental pain, migraine, and dysmenorrhea.'],
      ['generic' => 'Losartan Potassium', 'brand' => 'Cozaar', 'cat' => 'Cardiovascular', 'mfr' => 'Organon Philippines', 'form' => 'Tablet', 'val' => '50', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 18.50, 'qty' => 5, 'days' => 365, 'img' => '../07_Assets/images/medicines/Cozaar.png', 'desc' => 'Angiotensin II receptor antagonist (ARB) for essential hypertension and stroke risk reduction.'],
      ['generic' => 'Phenylephrine + Paracetamol', 'brand' => 'Neozep Forte', 'cat' => 'Cold & Respiratory', 'mfr' => 'Unilab', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'No', 'price' => 9.00, 'qty' => 95, 'days' => 365, 'img' => '../07_Assets/images/medicines/Paracetamol.png', 'desc' => 'Dual decongestant and antipyretic targeting nasal congestion, postnasal drip, and sinusitis.'],
      ['generic' => 'Carbocisteine', 'brand' => 'Solmux', 'cat' => 'Cold & Respiratory', 'mfr' => 'Unilab', 'form' => 'Capsule', 'val' => '500', 'unit' => 'mg', 'rx' => 'No', 'price' => 13.50, 'qty' => 45, 'days' => 365, 'img' => '../07_Assets/images/medicines/Solmux.jpg', 'desc' => 'Mucoregulatory agent that liquefies tenacious bronchial secretions in acute bronchitis.'],
      ['generic' => 'Mefenamic Acid', 'brand' => 'Ponstan', 'cat' => 'Analgesics & Antipyretics', 'mfr' => 'Pfizer', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 22.00, 'qty' => 0, 'days' => 365, 'img' => '../07_Assets/images/medicines/Advil.png', 'desc' => 'Potent NSAID for short-term relief of severe dental pain, dysmenorrhea, and post-surgical trauma.'],
      ['generic' => 'Amlodipine Besylate', 'brand' => 'Norvasc', 'cat' => 'Cardiovascular', 'mfr' => 'Pfizer', 'form' => 'Tablet', 'val' => '10', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 19.50, 'qty' => 60, 'days' => 365, 'img' => '../07_Assets/images/medicines/Cozaar.png', 'desc' => 'Dihydropyridine calcium channel blocker for chronic hypertension and angina management.'],
      ['generic' => 'Metformin Hydrochloride', 'brand' => 'Glucophage', 'cat' => 'Endocrine & Metabolic', 'mfr' => 'Merck', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 11.00, 'qty' => 110, 'days' => 365, 'img' => '../07_Assets/images/medicines/Glucophage.jpg', 'desc' => 'Biguanide antihyperglycemic cornerstone for glycemic control in Type 2 Diabetes Mellitus.'],
      ['generic' => 'Cetirizine Dihydrochloride', 'brand' => 'Alnix', 'cat' => 'Antihistamines', 'mfr' => 'Unilab', 'form' => 'Tablet', 'val' => '10', 'unit' => 'mg', 'rx' => 'No', 'price' => 16.00, 'qty' => 6, 'days' => 18, 'img' => '../07_Assets/images/medicines/Allerta.png', 'desc' => 'Second-generation non-drowsy antihistamine for allergic rhinitis, ocular pruritus, and urticaria.'],
      ['generic' => 'Vitex Negundo (Lagundi)', 'brand' => 'Ascof', 'cat' => 'Herbal & Respiratory', 'mfr' => 'Pascual Laboratories', 'form' => 'Tablet', 'val' => '300', 'unit' => 'mg', 'rx' => 'No', 'price' => 10.00, 'qty' => 75, 'days' => 365, 'img' => '../07_Assets/images/medicines/Solmux.jpg', 'desc' => 'Philippine clinically validated herbal formulation for mild to moderate acute cough and bronchospasm.'],
      ['generic' => 'Ibuprofen + Paracetamol', 'brand' => 'Alaxan FR', 'cat' => 'Analgesics & Antipyretics', 'mfr' => 'Unilab', 'form' => 'Capsule', 'val' => '500', 'unit' => 'mg', 'rx' => 'No', 'price' => 11.50, 'qty' => 90, 'days' => 365, 'img' => '../07_Assets/images/medicines/Advil.png', 'desc' => 'Synergistic combination offering rapid relief for acute musculoskeletal pain, torticollis, and lumbago.'],
      ['generic' => 'Phenylephrine + Chlorphenamine + Paracetamol', 'brand' => 'Bioflu', 'cat' => 'Cold & Respiratory', 'mfr' => 'Unilab', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'No', 'price' => 9.50, 'qty' => 105, 'days' => 365, 'img' => '../07_Assets/images/medicines/Paracetamol.png', 'desc' => 'All-in-one multi-symptom cold and flu tablet resolving fever, runny nose, and body malaise.'],
      ['generic' => 'Aluminum + Magnesium Hydroxide + Simethicone', 'brand' => 'Kremil-S', 'cat' => 'Gastrointestinal', 'mfr' => 'Unilab', 'form' => 'Tablet', 'val' => '178', 'unit' => 'mg', 'rx' => 'No', 'price' => 8.00, 'qty' => 65, 'days' => 365, 'img' => '../07_Assets/images/medicines/Glucophage.jpg', 'desc' => 'Antacid and antiflatulent combination for rapid relief of hyperacidity, peptic ulcer, and gas.'],
      ['generic' => 'Paracetamol Pediatric Oral Solution', 'brand' => 'Tempra', 'cat' => 'Analgesics & Antipyretics', 'mfr' => 'Taisho Pharmaceuticals', 'form' => 'Syrup', 'val' => '120', 'unit' => 'mg/5mL', 'rx' => 'No', 'price' => 125.00, 'qty' => 22, 'days' => 365, 'img' => '../07_Assets/images/medicines/Paracetamol.png', 'desc' => 'Pediatric alcohol-free antipyretic syrup formulated for infants and children with acute fever.'],
      ['generic' => 'Ascorbic Acid + Zinc', 'brand' => 'Ceelin Plus', 'cat' => 'Vitamins & Minerals', 'mfr' => 'Unilab', 'form' => 'Syrup', 'val' => '100', 'unit' => 'mg', 'rx' => 'No', 'price' => 145.00, 'qty' => 4, 'days' => 22, 'img' => '../07_Assets/images/medicines/Enervon-C.jpg', 'desc' => 'ZincPlus pediatric daily health supplement to boost cellular immunity. Near expiry attention.'],
      ['generic' => 'Salbutamol Sulfate', 'brand' => 'Ventolin', 'cat' => 'Cold & Respiratory', 'mfr' => 'GlaxoSmithKline', 'form' => 'Tablet', 'val' => '2', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 6.50, 'qty' => 0, 'days' => 365, 'img' => '../07_Assets/images/medicines/Solmux.jpg', 'desc' => 'Selective beta-2 adrenergic agonist for rapid relief of asthma bronchospasm and reactive airways.'],
      ['generic' => 'Omeprazole', 'brand' => 'Omez', 'cat' => 'Gastrointestinal', 'mfr' => 'Torrent Pharma', 'form' => 'Capsule', 'val' => '20', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 28.00, 'qty' => 55, 'days' => 365, 'img' => '../07_Assets/images/medicines/Glucophage.jpg', 'desc' => 'Proton pump inhibitor decreasing gastric acid production in gastroesophageal reflux disease.'],
      ['generic' => 'Cefalexin Monohydrate', 'brand' => 'Ceporex', 'cat' => 'Antibiotics', 'mfr' => 'Unilab', 'form' => 'Capsule', 'val' => '500', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 21.00, 'qty' => 7, 'days' => 365, 'img' => '../07_Assets/images/medicines/Amoxicilin.png', 'desc' => 'First-generation oral cephalosporin antibiotic active against susceptible streptococcal infections.'],
      ['generic' => 'Vitamin B-Complex (B1, B6, B12)', 'brand' => 'Neurobion', 'cat' => 'Vitamins & Minerals', 'mfr' => 'P&G Health', 'form' => 'Tablet', 'val' => '100', 'unit' => 'mg', 'rx' => 'No', 'price' => 24.00, 'qty' => 130, 'days' => 365, 'img' => '../07_Assets/images/medicines/Neurobion.jpg', 'desc' => 'Neurotropic vitamin complex supporting peripheral nerve regeneration and alleviating numbness.'],
      ['generic' => 'Multivitamins + Iron', 'brand' => 'Enervon-C', 'cat' => 'Vitamins & Minerals', 'mfr' => 'Unilab', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'No', 'price' => 9.00, 'qty' => 140, 'days' => 365, 'img' => '../07_Assets/images/medicines/Enervon-C.jpg', 'desc' => 'High-potency Vitamin C with B-complex vitamins for sustained energy and immune defense.'],
      ['generic' => 'Clopidogrel', 'brand' => 'Plavix', 'cat' => 'Cardiovascular', 'mfr' => 'Sanofi', 'form' => 'Tablet', 'val' => '75', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 42.00, 'qty' => 35, 'days' => 365, 'img' => '../07_Assets/images/medicines/Cozaar.png', 'desc' => 'Antiplatelet agent preventing atherothrombotic vascular events in post-MI and stroke patients.'],
      ['generic' => 'Azithromycin', 'brand' => 'Zithromax', 'cat' => 'Antibiotics', 'mfr' => 'Pfizer', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 75.00, 'qty' => 18, 'days' => 365, 'img' => '../07_Assets/images/medicines/Amoxicilin.png', 'desc' => 'Azalide macrolide antibiotic indicated for community-acquired pneumonia and sinusitis.'],
      ['generic' => 'Dicycloverine + Paracetamol', 'brand' => 'Buscopan Venus', 'cat' => 'Gastrointestinal', 'mfr' => 'Sanofi', 'form' => 'Tablet', 'val' => '10', 'unit' => 'mg', 'rx' => 'No', 'price' => 19.50, 'qty' => 60, 'days' => 365, 'img' => '../07_Assets/images/medicines/Paracetamol.png', 'desc' => 'Targeted antispasmodic and analgesic for smooth muscle cramping, visceral colic, and dysmenorrhea.'],
      ['generic' => 'Hyoscine N-Butylbromide', 'brand' => 'Buscopan', 'cat' => 'Gastrointestinal', 'mfr' => 'Sanofi', 'form' => 'Tablet', 'val' => '10', 'unit' => 'mg', 'rx' => 'No', 'price' => 17.00, 'qty' => 80, 'days' => 365, 'img' => '../07_Assets/images/medicines/Glucophage.jpg', 'desc' => 'Peripheral anticholinergic agent providing spasmolytic action in the gastrointestinal tract.'],
      ['generic' => 'Bisacodyl', 'brand' => 'Dulcolax', 'cat' => 'Gastrointestinal', 'mfr' => 'Sanofi', 'form' => 'Tablet', 'val' => '5', 'unit' => 'mg', 'rx' => 'No', 'price' => 12.00, 'qty' => 50, 'days' => 365, 'img' => '../07_Assets/images/medicines/Glucophage.jpg', 'desc' => 'Locally acting stimulant laxative stimulating colon motility for overnight constipation relief.'],
      ['generic' => 'Naproxen Sodium', 'brand' => 'Flanax', 'cat' => 'Analgesics & Antipyretics', 'mfr' => 'Bayer Philippines', 'form' => 'Tablet', 'val' => '550', 'unit' => 'mg', 'rx' => 'No', 'price' => 16.50, 'qty' => 70, 'days' => 365, 'img' => '../07_Assets/images/medicines/Advil.png', 'desc' => 'Long-acting NSAID delivering up to 12 hours relief from acute bursitis, tendonitis, and arthritis.'],
      ['generic' => 'Dextromethorphan + Guaifenesin', 'brand' => 'Robitussin DM', 'cat' => 'Cold & Respiratory', 'mfr' => 'Haleon', 'form' => 'Syrup', 'val' => '100', 'unit' => 'mL', 'rx' => 'No', 'price' => 165.00, 'qty' => 15, 'days' => 365, 'img' => '../07_Assets/images/medicines/Solmux.jpg', 'desc' => 'Antitussive and expectorant syrup suppressing non-productive coughing while loosening phlegm.'],
      ['generic' => 'Ciprofloxacin', 'brand' => 'Ciprobay', 'cat' => 'Antibiotics', 'mfr' => 'Bayer', 'form' => 'Tablet', 'val' => '500', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 48.00, 'qty' => 20, 'days' => 365, 'img' => '../07_Assets/images/medicines/Amoxicilin.png', 'desc' => 'Fluoroquinolone bactericidal antibiotic for complicated urinary tract and enteric infections.'],
      ['generic' => 'Simvastatin', 'brand' => 'Zocor', 'cat' => 'Cardiovascular', 'mfr' => 'Organon', 'form' => 'Tablet', 'val' => '20', 'unit' => 'mg', 'rx' => 'Yes', 'price' => 26.00, 'qty' => 40, 'days' => 365, 'img' => '../07_Assets/images/medicines/Cozaar.png', 'desc' => 'Lipid-lowering statin reducing LDL cholesterol and cardiovascular morbidity in dyslipidemia.'],
      ['generic' => 'Loratadine', 'brand' => 'Claritin', 'cat' => 'Antihistamines', 'mfr' => 'Bayer', 'form' => 'Tablet', 'val' => '10', 'unit' => 'mg', 'rx' => 'No', 'price' => 28.50, 'qty' => 30, 'days' => 365, 'img' => '../07_Assets/images/medicines/Allerta.png', 'desc' => 'Non-sedating selective peripheral H1 antihistamine for allergic rhinitis and chronic urticaria.'],
      ['generic' => 'Povidone-Iodine 1% Solution', 'brand' => 'Betadine / Bactidol', 'cat' => 'Antiseptics & Topical', 'mfr' => 'Mundipharma', 'form' => 'Solution', 'val' => '120', 'unit' => 'mL', 'rx' => 'No', 'price' => 135.00, 'qty' => 25, 'days' => 365, 'img' => '../07_Assets/images/logo.png', 'desc' => 'Broad-spectrum oral gargle and antiseptic solution reducing oral cavity pathogens and sore throat.']
    ];

    foreach ($philippineMeds as $pm) {
      // 1. Resolve Generic
      $genStmt = $pdo->prepare("SELECT Gen_ID FROM 13_meds_generic_name WHERE Generic_Name = ? LIMIT 1");
      $genStmt->execute([$pm['generic']]);
      $genId = $genStmt->fetchColumn();
      if (!$genId) {
        $pdo->prepare("INSERT INTO 13_meds_generic_name (Generic_Name) VALUES (?)")->execute([$pm['generic']]);
        $genId = (int) $pdo->lastInsertId();
      }

      // 2. Resolve Brand
      $brandStmt = $pdo->prepare("SELECT Brand_ID FROM 14_meds_brand_name WHERE Brand_Name = ? LIMIT 1");
      $brandStmt->execute([$pm['brand']]);
      $brandId = $brandStmt->fetchColumn();
      if (!$brandId) {
        $pdo->prepare("INSERT INTO 14_meds_brand_name (Brand_Name) VALUES (?)")->execute([$pm['brand']]);
        $brandId = (int) $pdo->lastInsertId();
      }

      // 3. Resolve Category
      $catStmt = $pdo->prepare("SELECT Category_ID FROM 18_meds_categories WHERE Category_Name = ? LIMIT 1");
      $catStmt->execute([$pm['cat']]);
      $catId = $catStmt->fetchColumn();
      if (!$catId) {
        $pdo->prepare("INSERT INTO 18_meds_categories (Category_Name) VALUES (?)")->execute([$pm['cat']]);
        $catId = (int) $pdo->lastInsertId();
      }

      // 4. Resolve Manufacturer
      $mfrStmt = $pdo->prepare("SELECT Manufacturer_ID FROM 19_meds_manufacturer WHERE Manufacturer_Name = ? LIMIT 1");
      $mfrStmt->execute([$pm['mfr']]);
      $mfrId = $mfrStmt->fetchColumn();
      if (!$mfrId) {
        $pdo->prepare("INSERT INTO 19_meds_manufacturer (Manufacturer_Name) VALUES (?)")->execute([$pm['mfr']]);
        $mfrId = (int) $pdo->lastInsertId();
      }

      // 5. Resolve Dosage Form
      $formStmt = $pdo->prepare("SELECT DosageForm_ID FROM 15_meds_dosage_form WHERE Dosage_Form = ? LIMIT 1");
      $formStmt->execute([$pm['form']]);
      $formId = $formStmt->fetchColumn();
      if (!$formId) {
        $pdo->prepare("INSERT INTO 15_meds_dosage_form (Dosage_Form) VALUES (?)")->execute([$pm['form']]);
        $formId = (int) $pdo->lastInsertId();
      }

      // 6. Resolve Dosage Value & Unit
      $valStmt = $pdo->prepare("SELECT DosageVal_ID FROM 16_meds_dosage_value WHERE Dosage_Value = ? LIMIT 1");
      $valStmt->execute([$pm['val']]);
      $valId = $valStmt->fetchColumn();
      if (!$valId) {
        $pdo->prepare("INSERT INTO 16_meds_dosage_value (Dosage_Value) VALUES (?)")->execute([$pm['val']]);
        $valId = (int) $pdo->lastInsertId();
      }

      $unitStmt = $pdo->prepare("SELECT Unit_ID FROM 17_meds_dosage_unit WHERE Unit_Name = ? LIMIT 1");
      $unitStmt->execute([$pm['unit']]);
      $unitId = $unitStmt->fetchColumn();
      if (!$unitId) {
        $pdo->prepare("INSERT INTO 17_meds_dosage_unit (Unit_Name) VALUES (?)")->execute([$pm['unit']]);
        $unitId = (int) $pdo->lastInsertId();
      }

      $rxId = (strtolower($pm['rx']) === 'yes') ? 1 : 2;

      // 7. Insert or resolve in 12_meds_medicines
      $medFind = $pdo->prepare("SELECT Medicine_ID FROM 12_meds_medicines WHERE Gen_ID = ? AND Brand_ID = ? LIMIT 1");
      $medFind->execute([$genId, $brandId]);
      $medId = $medFind->fetchColumn();

      if (!$medId) {
        $insMed = $pdo->prepare("
          INSERT INTO 12_meds_medicines (Gen_ID, Brand_ID, DosageForm_ID, DosageVal_ID, Unit_ID, Category_ID, Manufacturer_ID, Require_rx_ID)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insMed->execute([$genId, $brandId, $formId, $valId, $unitId, $catId, $mfrId, $rxId]);
        $medId = (int) $pdo->lastInsertId();
      }

      // Link Image in 24_medicine_images
      if ($medId && !empty($pm['img'])) {
        $pdo->prepare("
          INSERT INTO 24_medicine_images (Medicine_ID, Image_Path, Is_Primary, Date_Uploaded)
          VALUES (?, ?, 1, NOW())
          ON DUPLICATE KEY UPDATE Image_Path = VALUES(Image_Path)
        ")->execute([$medId, $pm['img']]);
      }

      // 8. Insert or update in 21_inventory for this pharmacy
      $expDate = date('Y-m-d', strtotime('+' . $pm['days'] . ' days'));
      $insInv = $pdo->prepare("
        INSERT INTO 21_inventory (Pharmacy_ID, Medicine_ID, Quantity, Price, Expiry_date, Last_updated)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE Quantity = VALUES(Quantity), Price = VALUES(Price), Expiry_date = VALUES(Expiry_date)
      ");
      $insInv->execute([$pharmacyID, $medId, $pm['qty'], $pm['price'], $expDate]);
    }
  }
} catch (Exception $e) {
}

// Detailed Administrative & Clinical Metadata Lookup
$medMetaMap = [
  'Paracetamol' => [
    'indication' => 'First-line antipyretic and analgesic for relief of mild to moderate fever, headaches, muscle aches, toothaches, and osteoarthritis pain.',
    'storage' => 'Store below 30°C in a dry place. Protect from moisture and direct sunlight.',
    'shelf' => 'Shelf Bay A-01 (OTC Counter Shelf)',
    'reorder' => '10 units (Safety reorder threshold)'
  ],
  'Amoxicillin' => [
    'indication' => 'Broad-spectrum aminopenicillin for ear, nose, throat, lower respiratory tract, and skin bacterial infections.',
    'storage' => 'Store below 25°C. Keep bottle tightly closed to protect from atmospheric moisture.',
    'shelf' => 'Prescription Dispensary Bay B-02',
    'reorder' => '15 units (Antibiotic critical reserve)'
  ],
  'Ibuprofen' => [
    'indication' => 'Nonsteroidal anti-inflammatory drug (NSAID) indicated for reduction of dental pain, primary dysmenorrhea, migraine, and inflammatory fever.',
    'storage' => 'Store at room temperature (15°C to 30°C). Avoid excessive heat and direct sunlight.',
    'shelf' => 'Shelf Bay A-02 (OTC Analgesics)',
    'reorder' => '10 units (Safety reorder threshold)'
  ],
  'Losartan Potassium' => [
    'indication' => 'Angiotensin II receptor antagonist (ARB) for treatment of essential hypertension and nephropathy in Type 2 diabetic patients.',
    'storage' => 'Store below 30°C in the original blister pack to protect from light.',
    'shelf' => 'Prescription Dispensary Bay C-01 (Cardio Vault)',
    'reorder' => '10 units (Cardiovascular maintenance reserve)'
  ],
  'Phenylephrine + Paracetamol' => [
    'indication' => 'Formulated decongestant and analgesic for relief of clogged nose, runny nose, sinus pressure, postnasal drip, and feverish headache.',
    'storage' => 'Keep in a cool, dry place away from heat and light.',
    'shelf' => 'Shelf Bay A-03 (Cold & Cough Counter)',
    'reorder' => '12 units (High-turnover seasonal stock)'
  ],
  'Carbocisteine' => [
    'indication' => 'Mucoregulatory agent that reduces mucus viscosity and facilitates expectoration in acute and chronic bronchopulmonary disorders.',
    'storage' => 'Store at temperatures not exceeding 30°C. Protect from moisture.',
    'shelf' => 'Shelf Bay A-04 (Respiratory Solutions)',
    'reorder' => '10 units'
  ],
  'Mefenamic Acid' => [
    'indication' => 'Potent NSAID for short-term symptomatic relief of moderate musculoskeletal pain, post-operative dental pain, and menorrhagia.',
    'storage' => 'Store at temperatures not exceeding 25°C.',
    'shelf' => 'Prescription Dispensary Bay B-04',
    'reorder' => '10 units'
  ],
  'Amlodipine Besylate' => [
    'indication' => 'Dihydropyridine calcium channel blocker for first-line management of essential hypertension and chronic stable angina pectoris.',
    'storage' => 'Store in a dry place between 15°C and 30°C. Protect from light.',
    'shelf' => 'Prescription Dispensary Bay C-02',
    'reorder' => '10 units'
  ],
  'Metformin Hydrochloride' => [
    'indication' => 'Biguanide antihyperglycemic medication used as cornerstone monotherapy or combined regimen for glycemic control in Type 2 Diabetes Mellitus.',
    'storage' => 'Store below 30°C.',
    'shelf' => 'Prescription Dispensary Bay D-01 (Endocrine)',
    'reorder' => '15 units'
  ],
  'Cetirizine Dihydrochloride' => [
    'indication' => 'Second-generation H1-receptor antihistamine for seasonal allergic rhinitis, perennial allergic rhinitis, and chronic idiopathic urticaria.',
    'storage' => 'Store in a dry place below 30°C. Protect from light.',
    'shelf' => 'Shelf Bay A-05 (Allergy & Antihistamines)',
    'reorder' => '10 units'
  ],
  'Vitex Negundo (Lagundi)' => [
    'indication' => 'Clinically proven Philippine herbal preparation for relief of cough due to common cold, flu, and acute/chronic mild-to-moderate bronchospasm.',
    'storage' => 'Store in original packaging below 30°C.',
    'shelf' => 'Shelf Bay A-06 (Herbal Dispensary)',
    'reorder' => '10 units'
  ],
  'Ibuprofen + Paracetamol' => [
    'indication' => 'Dual-action synergistic formula combining peripheral and central analgesia for fast relief of severe musculoskeletal pain, stiff neck, and tendonitis.',
    'storage' => 'Keep in a cool, dry place away from sunlight.',
    'shelf' => 'Shelf Bay A-02 (Fast-Acting Analgesics)',
    'reorder' => '12 units'
  ],
  'Phenylephrine + Chlorphenamine + Paracetamol' => [
    'indication' => 'Complete multi-symptom flu treatment tackling fever, nasal congestion, sneezing, rhinorrhea, and whole body malaise.',
    'storage' => 'Store below 30°C.',
    'shelf' => 'Shelf Bay A-03 (Cold & Flu)',
    'reorder' => '12 units'
  ],
  'Aluminum + Magnesium Hydroxide + Simethicone' => [
    'indication' => 'Antacid and antiflatulent combination for rapid neutralization of hyperacidity, peptic ulcer pain, heartburn, and gastroesophageal reflux.',
    'storage' => 'Store at room temperature not exceeding 30°C.',
    'shelf' => 'Shelf Bay E-01 (Gastrointestinal Shelf)',
    'reorder' => '10 units'
  ],
  'Paracetamol Pediatric Oral Solution' => [
    'indication' => 'Pediatric pleasant-tasting alcohol-free syrup for reduction of fever and pain following routine pediatric vaccinations and teething.',
    'storage' => 'Store below 30°C. Keep bottle cap securely tightened.',
    'shelf' => 'Shelf Bay P-01 (Pediatric Liquid Shelf)',
    'reorder' => '8 units'
  ],
  'Ascorbic Acid + Zinc' => [
    'indication' => 'Enhanced ZincPlus formulation supporting optimal immune system function and cellular repair in growing infants and children.',
    'storage' => 'Store at temperatures not exceeding 30°C. Protect from direct sunlight.',
    'shelf' => 'Shelf Bay P-02 (Pediatric Vitamins)',
    'reorder' => '10 units'
  ],
  'Salbutamol Sulfate' => [
    'indication' => 'Short-acting selective beta-2 adrenoceptor agonist providing rapid bronchodilation in reversible airway obstruction due to asthma and COPD.',
    'storage' => 'Store below 30°C. Protect from light.',
    'shelf' => 'Prescription Dispensary Bay B-05',
    'reorder' => '10 units'
  ],
  'Omeprazole' => [
    'indication' => 'Proton pump inhibitor that irreversibly inhibits gastric H+/K+-ATPase, suppressing gastric acid production in GERD and Zollinger-Ellison syndrome.',
    'storage' => 'Store in original moisture-proof blister package below 25°C.',
    'shelf' => 'Prescription Dispensary Bay E-02',
    'reorder' => '10 units'
  ],
  'Cefalexin Monohydrate' => [
    'indication' => 'First-generation oral cephalosporin antibiotic active against gram-positive cocci causing skin infections, pharyngitis, and uncomplicated cystitis.',
    'storage' => 'Store below 30°C in a dry location.',
    'shelf' => 'Prescription Dispensary Bay B-03',
    'reorder' => '10 units'
  ],
  'Vitamin B-Complex (B1, B6, B12)' => [
    'indication' => 'High-potency neurotropic vitamins B1, B6, and B12 essential for nerve metabolism and regeneration, indicated for peripheral neuropathy and numbness.',
    'storage' => 'Store in a dry place at temperatures not exceeding 25°C.',
    'shelf' => 'Shelf Bay V-01 (Vitamins & Supplements)',
    'reorder' => '15 units'
  ]
];

// ── Strict Normalized Multi-Table Query ─────────────
$sql = "
  SELECT 
    i.Inventory_ID,
    i.Pharmacy_ID,
    i.Medicine_ID,
    i.Quantity,
    i.Price,
    i.Price_Unit_ID,
    i.Expiry_date,
    i.Last_updated,
    gn.Generic_Name,
    bn.Brand_Name,
    df.Dosage_Form,
    dv.Dosage_Value,
    du.Unit_Name,
    c.Category_Name,
    m.Manufacturer_Name,
    rx.Requirement_Status AS Requires_Rx,
    up.Unit AS Price_Per,
    COALESCE(img.Image_Path, '../07_Assets/images/logo.png') AS Med_Image_URL,
    CASE
      WHEN i.Expiry_date <= CURDATE() THEN 'Expired'
      WHEN i.Quantity = 0 THEN 'Out of Stock'
      WHEN i.Quantity <= 10 THEN 'Low Stock'
      ELSE 'Available'
    END AS Availability_Status
  FROM 21_inventory i
  INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
  LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
  LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
  LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
  LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
  LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
  LEFT JOIN 18_meds_categories c ON med.Category_ID = c.Category_ID
  LEFT JOIN 19_meds_manufacturer m ON med.Manufacturer_ID = m.Manufacturer_ID
  LEFT JOIN 20_meds_requires_rx rx ON med.Require_rx_ID = rx.Require_rx_ID
  LEFT JOIN 22_inventory_unit_price up ON i.Price_Unit_ID = up.Price_Unit_ID
  LEFT JOIN (
    SELECT Medicine_ID, MIN(Image_Path) AS Image_Path 
    FROM 24_medicine_images 
    GROUP BY Medicine_ID
  ) img ON med.Medicine_ID = img.Medicine_ID
  WHERE i.Pharmacy_ID = ? AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
  ORDER BY i.Quantity ASC, i.Last_updated DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$pharmacyID]);
$inventoryRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Distinct Categories for Filter
$catStmt = $pdo->query("SELECT DISTINCT Category_Name FROM 18_meds_categories ORDER BY Category_Name ASC");
$allCategories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// Stat metrics
$totalMeds = count($inventoryRows);
$lowStockMeds = 0;
$expiringMeds = 0;
$totalValue = 0.0;
$today = date('Y-m-d');
$thirtyDays = date('Y-m-d', strtotime('+30 days'));

foreach ($inventoryRows as $r) {
  $q = (int) $r['Quantity'];
  $p = (float) $r['Price'];
  $exp = $r['Expiry_date'];
  $totalValue += ($q * $p);
  if ($q <= 10)
    $lowStockMeds++;
  if ($exp >= $today && $exp <= $thirtyDays)
    $expiringMeds++;
}
?>
<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Manage Inventory - MediFind</title>

  <!-- External libraries via CDN with pinned versions -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

  <!-- Scripts & CSS -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/02_ManageInventory.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/scanner.css" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    html,
    body {
      height: 100%;
      overflow-y: auto !important;
      background-color: #f8faf9;
    }

    .wrapper {
      min-height: 100vh;
      display: flex;
    }

    .main-panel {
      flex: 1;
      min-height: 100vh;
      overflow-y: auto !important;
      display: flex;
      flex-direction: column;
    }

    .hero {
      overflow: hidden;
      max-height: 400px;
      opacity: 1;
      transition: max-height 0.35s ease, opacity 0.3s ease, padding 0.3s ease, margin 0.3s ease;
    }

    .hero.collapsed {
      max-height: 0 !important;
      opacity: 0;
      padding: 0 !important;
      margin: 0 !important;
    }

    .badge-status {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      display: inline-block;
    }

    .badge-available {
      background-color: #d4f5e2;
      color: #1a7a45;
    }

    .badge-low-stock {
      background-color: #fef3c7;
      color: #b45309;
    }

    .badge-out-of-stock {
      background-color: #fde8e8;
      color: #c0392b;
    }

    .badge-expired {
      background-color: #fee2e2;
      color: #991b1b;
    }

    /* Action Buttons: Blue replaced with warm orange (#d97706) */
    .btn-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.2s ease;
      margin: 0 2px;
    }

    .btn-view {
      background-color: #fef3c7;
      color: #d97706;
    }

    .btn-view:hover {
      background-color: #d97706;
      color: #ffffff;
    }

    .btn-edit {
      background-color: #fef3c7;
      color: #d97706;
    }

    .btn-edit:hover {
      background-color: #d97706;
      color: #ffffff;
    }

    /* Scanner Button: Replaced blue with Warm Orange */
    .scanner-btn-orange {
      background: #d97706 !important;
      border-color: #d97706 !important;
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(217, 119, 6, 0.25);
    }

    .scanner-btn-orange:hover {
      background: #b45309 !important;
      border-color: #b45309 !important;
    }
  </style>
</head>

<body data-active="manage_inventory">
  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div class="content">
        <div class="content-inventory px-3 py-2">

          <!-- ══ STAT CARDS ═════════════════════════════════════ -->
          <div class="hero" id="statsHero">
            <div style="padding: 1rem 0;">
              <div class="row g-3">

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">pill</span>
                      </div>
                      <span class="card-label label-green">Total Medicines</span>
                    </div>
                    <div class="card-value"><?= number_format($totalMeds) ?></div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge-up">
                        <span class="material-symbols-outlined">inventory</span>Active list
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-amber">
                        <span class="material-symbols-outlined">brightness_alert</span>
                      </div>
                      <span class="card-label label-amber">Low Stock Warnings</span>
                    </div>
                    <div class="card-value"><?= number_format($lowStockMeds) ?></div>
                    <div class="card-sub">&le; 10 units threshold</div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-red">
                        <span class="material-symbols-outlined">event_busy</span>
                      </div>
                      <span class="card-label label-red">Expiring Soon</span>
                    </div>
                    <div class="card-value"><?= number_format($expiringMeds) ?></div>
                    <div class="card-sub">Within next 30 days</div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">local_atm</span>
                      </div>
                      <span class="card-label label-green">Inventory Value</span>
                    </div>
                    <div class="card-value">₱<?= number_format($totalValue, 2) ?></div>
                    <div class="card-sub">Estimated retail total</div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- ══ END STAT CARDS ══════════════════════════════════ -->

          <!-- ══ INVENTORY TABLE & TOOLBAR ══════════════════════ -->
          <div class="inventoryTable mt-2">
            <div class="col-12">
              <div class="stat-card-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="fw-bold mb-0 text-dark">Inventory Masterlist</h5>
                  <span class="badge bg-light text-secondary border"><?= count($inventoryRows) ?> items listed</span>
                </div>

                <!-- Toolbar -->
                <div class="toolbars">
                  <div class="row g-2 align-items-center mb-3">

                    <!-- Search Input -->
                    <div class="col-12 col-lg-3">
                      <div class="input-group searchbar-group">
                        <span class="input-group-text search-icon bg-white border-end-0">
                          <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="inventorySearchInput" class="form-control searchbar border-start-0 ps-0"
                          placeholder="Search generic, brand, category...">
                      </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-6 col-lg-2">
                      <select id="filterCategory" class="form-select border-secondary-subtle">
                        <option value="">All Categories</option>
                        <?php foreach ($allCategories as $cat): ?>
                          <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-6 col-lg-2">
                      <select id="filterStatus" class="form-select border-secondary-subtle">
                        <option value="">All Statuses</option>
                        <option value="available">Available</option>
                        <option value="low stock">Low Stock</option>
                        <option value="out of stock">Out of Stock</option>
                        <option value="expired">Expired</option>
                      </select>
                    </div>

                    <!-- Action Buttons: Warm Orange replaces blue -->
                    <div class="col-12 col-lg-5 d-flex gap-2 justify-content-lg-end justify-content-between">

                      <!-- Scan & Direct Update Button (Warm Orange) -->
                      <button type="button"
                        class="btn scanner-btn-orange px-3 rounded-3 d-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#scanStockModal">
                        <i class="bi bi-qr-code-scan"></i>
                        <span class="text-nowrap fw-semibold">Scan &amp; Direct Update</span>
                      </button>

                      <!-- Add New Stock Modal Button -->
                      <button id="addBTN" class="btn btn-success px-3 rounded-3 d-flex align-items-center gap-1"
                        style="background-color:#1d9e75 !important; border-color:#1d9e75 !important;">
                        <i class="bi bi-plus-lg"></i>
                        <span class="text-nowrap">Add Stock</span>
                      </button>

                      <!-- Collapse Stats Button -->
                      <button class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center gap-1"
                        id="statsToggleBtn" onclick="toggleStatsCards()">
                        <span id="statsChevron" class="material-symbols-outlined"
                          style="color: #87a199; font-size: 1.1rem; transition: transform 0.35s ease;">expand</span>
                        <span id="statsToggleLabel" class="d-none d-sm-inline">Collapse</span>
                      </button>
                    </div>

                  </div>
                </div>
                <!-- End Toolbar -->

                <div class="card card-table border">
                  <div class="table-responsive rounded table-scroll">
                    <table class="table table-hover align-middle mb-0" id="mainInventoryTable">

                      <thead class="table-head">
                        <tr>
                          <th>ID</th>
                          <th>GENERIC NAME</th>
                          <th>BRAND</th>
                          <th>CATEGORY</th>
                          <th>DOSAGE</th>
                          <th>PRICE (₱)</th>
                          <th>QTY</th>
                          <th>EXPIRY DATE</th>
                          <th>STATUS</th>
                          <th>ACTIONS</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if (!empty($inventoryRows)): ?>
                          <?php foreach ($inventoryRows as $row): ?>
                            <?php
                            $statusClass = 'badge-' . strtolower(str_replace(' ', '-', $row['Availability_Status']));
                            $dosageFull = ($row['Dosage_Value'] ? $row['Dosage_Value'] . ' ' . $row['Unit_Name'] : '') . ($row['Dosage_Form'] ? ' (' . $row['Dosage_Form'] . ')' : '');
                            $pricePer = $row['Price_Per'] ? ' / ' . $row['Price_Per'] : '';
                            ?>
                            <tr class="inv-row"
                              data-category="<?= htmlspecialchars(strtolower($row['Category_Name'] ?? '')) ?>"
                              data-status="<?= htmlspecialchars(strtolower($row['Availability_Status'] ?? '')) ?>"
                              data-search="<?= htmlspecialchars(strtolower(($row['Generic_Name'] ?? '') . ' ' . ($row['Brand_Name'] ?? '') . ' ' . ($row['Category_Name'] ?? '') . ' ' . ($row['Inventory_ID'] ?? ''))) ?>">
                              <td><span class="text-muted fw-mono">#<?= htmlspecialchars($row['Inventory_ID']) ?></span>
                              </td>
                              <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['Generic_Name']) ?></div>
                                <?php if (!empty($row['Requires_Rx']) && (stripos($row['Requires_Rx'], 'yes') !== false || stripos($row['Requires_Rx'], 'require') !== false)): ?>
                                  <span class="badge bg-danger-subtle text-danger" style="font-size: 10px;">Rx Required</span>
                                <?php endif; ?>
                              </td>
                              <td><?= htmlspecialchars($row['Brand_Name'] ?? 'Generics') ?></td>
                              <td><span
                                  class="text-secondary"><?= htmlspecialchars($row['Category_Name'] ?? 'Uncategorized') ?></span>
                              </td>
                              <td><?= htmlspecialchars($dosageFull) ?></td>
                              <td class="fw-semibold text-success">₱<?= number_format($row['Price'], 2) ?><span
                                  class="text-muted small fw-normal"><?= htmlspecialchars($pricePer) ?></span></td>
                              <td>
                                <strong class="<?= ((int) $row['Quantity'] <= 10) ? 'text-danger' : 'text-dark' ?>">
                                  <?= htmlspecialchars($row['Quantity']) ?>
                                </strong>
                              </td>
                              <td><?= date('M d, Y', strtotime($row['Expiry_date'])) ?></td>
                              <td>
                                <span class="badge-status <?= $statusClass ?>">
                                  <?= htmlspecialchars($row['Availability_Status']) ?>
                                </span>
                              </td>
                              <td>
                                <div class="d-flex gap-1 justify-content-start">
                                  <?php
                                  $mMeta = $medMetaMap[$row['Generic_Name']] ?? [
                                    'indication' => 'Prescribed medication indicated for clinical treatment in compliance with Philippine health standards.',
                                    'storage' => 'Store at temperatures not exceeding 30°C in a dry place. Protect from heat and light.',
                                    'shelf' => 'Standard Pharmacy Dispensary Rack',
                                    'reorder' => '10 units (Minimum stock safety threshold)'
                                  ];
                                  ?>
                                  <!-- View Details Button (Warm Orange) -->
                                  <button class="btn-action btn-view custom-view-btn" title="View Medicine Details & Stock"
                                    data-image="<?= htmlspecialchars($row['Med_Image_URL'] ?? '') ?>"
                                    data-id="<?= htmlspecialchars($row['Inventory_ID']) ?>"
                                    data-generic="<?= htmlspecialchars($row['Generic_Name']) ?>"
                                    data-brand="<?= htmlspecialchars($row['Brand_Name'] ?? '') ?>"
                                    data-category="<?= htmlspecialchars($row['Category_Name'] ?? '') ?>"
                                    data-manufacturer="<?= htmlspecialchars($row['Manufacturer_Name'] ?? '') ?>"
                                    data-dosageform="<?= htmlspecialchars($row['Dosage_Form'] ?? '') ?>"
                                    data-dosagevalue="<?= htmlspecialchars($row['Dosage_Value'] ?? '') ?>"
                                    data-dosageunit="<?= htmlspecialchars($row['Unit_Name'] ?? '') ?>"
                                    data-dosage="<?= htmlspecialchars($dosageFull) ?>"
                                    data-price="<?= htmlspecialchars($row['Price']) ?>"
                                    data-priceper="<?= htmlspecialchars($row['Price_Per'] ?? 'unit') ?>"
                                    data-qty="<?= htmlspecialchars($row['Quantity']) ?>"
                                    data-expiry="<?= htmlspecialchars($row['Expiry_date']) ?>"
                                    data-rx="<?= htmlspecialchars($row['Requires_Rx'] ?? 'No') ?>"
                                    data-updated="<?= htmlspecialchars($row['Last_updated'] ?? 'Today') ?>"
                                    data-status="<?= htmlspecialchars($row['Availability_Status']) ?>"
                                    data-indication="<?= htmlspecialchars($mMeta['indication']) ?>"
                                    data-storage="<?= htmlspecialchars($mMeta['storage']) ?>"
                                    data-shelf="<?= htmlspecialchars($mMeta['shelf']) ?>"
                                    data-reorder="<?= htmlspecialchars($mMeta['reorder']) ?>">
                                    <i class="bi bi-eye"></i>
                                  </button>

                                  <!-- Edit Button -->
                                  <button class="btn-action btn-edit edit-btn" title="Edit stock"
                                    data-image="<?= htmlspecialchars($row['Med_Image_URL'] ?? '') ?>"
                                    data-id="<?= htmlspecialchars($row['Inventory_ID']) ?>"
                                    data-generic="<?= htmlspecialchars($row['Generic_Name']) ?>"
                                    data-brand="<?= htmlspecialchars($row['Brand_Name'] ?? '') ?>"
                                    data-category="<?= htmlspecialchars($row['Category_Name'] ?? '') ?>"
                                    data-manufacturer="<?= htmlspecialchars($row['Manufacturer_Name'] ?? '') ?>"
                                    data-dosageform="<?= htmlspecialchars($row['Dosage_Form'] ?? '') ?>"
                                    data-dosagevalue="<?= htmlspecialchars($row['Dosage_Value'] ?? '') ?>"
                                    data-dosageunit="<?= htmlspecialchars($row['Unit_Name'] ?? '') ?>"
                                    data-dosage="<?= htmlspecialchars($dosageFull) ?>"
                                    data-price="<?= htmlspecialchars($row['Price']) ?>"
                                    data-priceper="<?= htmlspecialchars($row['Price_Per'] ?? '') ?>"
                                    data-priceunitid="<?= htmlspecialchars($row['Price_Unit_ID'] ?? '1') ?>"
                                    data-qty="<?= htmlspecialchars($row['Quantity']) ?>"
                                    data-expiry="<?= htmlspecialchars($row['Expiry_date']) ?>"
                                    data-rx="<?= htmlspecialchars($row['Requires_Rx'] ?? 'No') ?>"
                                    data-status="<?= htmlspecialchars($row['Availability_Status']) ?>">
                                    <i class="bi bi-pencil"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                              <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                              No medicines registered in this pharmacy inventory.
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
          <!-- ══ END TABLE ═════════════════════════════════════ -->

        </div>
      </div>
    </div>
  </div>

  <!-- ══ USER-UI STYLE MEDICINE VIEW DETAILS MODAL ═════════════ -->
  <div class="modal fade" id="viewMedicineModal" tabindex="-1" aria-labelledby="viewMedicineModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content"
        style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.12);">

        <div class="modal-header bg-white border-bottom px-4 py-3">
          <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-success" style="font-size: 1.6rem;">medication</span>
            <h5 class="modal-title fw-bold text-dark mb-0" id="viewMedicineModalLabel">Medicine Stock &amp; Clinical
              Details</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4 bg-light">

          <!-- Hero Card (Matching User UI layout with picture) -->
          <div class="hero-card"
            style="background: #ffffff; border-radius: 20px; border: 1px solid #e2ede8; overflow: hidden; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03);">
            <div class="hero-inner" style="display: flex; gap: 20px; padding: 20px; align-items: flex-start;">
              <!-- Picture Container with Fallback -->
              <div class="medicine-img-wrap"
                style="width: 140px; height: 140px; flex-shrink: 0; background: #f0faf5; border-radius: 16px; border: 1px solid #d8ede4; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <img id="detailMedImage" src="../07_Assets/images/logo.png" alt="Medicine Photo"
                  style="width: 100%; height: 100%; object-fit: contain; padding: 8px;">
              </div>

              <!-- Header Info -->
              <div class="hero-info" style="flex: 1; min-width: 0;">
                <div class="badge-row mb-2" style="display: flex; flex-wrap: wrap; gap: 6px;">
                  <span id="detailRxBadge" class="badge"
                    style="background: #fee2e2; color: #991b1b; border-radius: 20px; font-weight: 600; padding: 4px 10px; font-size: 11px;">Rx
                    Required</span>
                  <span id="detailCategoryBadge" class="badge"
                    style="background: #e8f7ef; color: #1d9e75; border-radius: 20px; font-weight: 600; padding: 4px 10px; font-size: 11px;">Category</span>
                  <span id="detailFormBadge" class="badge"
                    style="background: #f3f4f6; color: #4b5563; border-radius: 20px; font-weight: 600; padding: 4px 10px; font-size: 11px;">Tablet</span>
                  <span id="detailStatusBadge" class="badge"
                    style="background: #d4f5e2; color: #1a7a45; border-radius: 20px; font-weight: 600; padding: 4px 10px; font-size: 11px;">Available</span>
                </div>

                <h3 id="detailGenericName" class="medicine-title fw-bold text-dark mb-1" style="font-size: 1.35rem;">
                  Amoxicillin 500mg</h3>
                <p id="detailBrandManufacturer" class="medicine-subtitle text-muted small mb-2"
                  style="font-size: 0.88rem;">Amoxil &bull; Manufactured by GlaxoSmithKline</p>

                <div class="price-row d-flex align-items-baseline gap-2 mb-2">
                  <span id="detailPrice" class="price-main fw-bold text-success"
                    style="font-size: 1.5rem;">₱14.00</span>
                  <span id="detailPricePer" class="price-unit text-muted small">/ capsule</span>
                </div>

                <p class="text-muted small mb-0 d-flex align-items-center gap-1">
                  <span class="material-symbols-outlined" style="font-size: 1.1rem; color: #d97706;">shelves</span>
                  <span>Dispensary Shelf: <strong id="detailShelfText" class="text-dark">Prescription Dispensary Bay
                      B-02</strong></span>
                </p>
              </div>
            </div>

            <hr class="hero-divider" style="margin: 0; border: 0; border-top: 1px solid #eef3f0;">

            <!-- Quick info strip (Stock & Diagnostics) -->
            <div class="info-grid"
              style="display: grid; grid-template-columns: repeat(4, 1fr); padding: 12px 16px; background: #fafdfb;">
              <div class="info-cell text-center" style="border-right: 1px solid #eef3f0;">
                <p class="text-muted small mb-1" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">
                  Current Stock</p>
                <p id="detailQuantity" class="fw-bold mb-0 text-dark fs-6">120 units</p>
              </div>
              <div class="info-cell text-center" style="border-right: 1px solid #eef3f0;">
                <p class="text-muted small mb-1" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">
                  Expiry Date</p>
                <p id="detailExpiryDate" class="fw-bold mb-0 text-dark fs-6">Nov 15, 2026</p>
              </div>
              <div class="info-cell text-center" style="border-right: 1px solid #eef3f0;">
                <p class="text-muted small mb-1" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">
                  Inventory SKU</p>
                <p id="detailInventoryID" class="fw-bold mb-0 text-secondary fs-6">#INV-1024</p>
              </div>
              <div class="info-cell text-center">
                <p class="text-muted small mb-1" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">
                  Reorder Trigger</p>
                <p id="detailReorderLevel" class="fw-bold mb-0 text-dark fs-6">10 units</p>
              </div>
            </div>
          </div>

          <!-- Detailed Clinical & Stock Operational Notes -->
          <div class="card p-3 border-0 shadow-sm rounded-4 bg-white">
            <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
              <span class="material-symbols-outlined" style="font-size: 1.25rem; color: #d97706;">clinical_notes</span>
              Inventory Diagnostic &amp; Dispensing Specifications
            </h6>

            <div id="detailStockReport" class="p-2 mb-3 rounded-3 small fw-semibold"
              style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
              Optimal supply available for counter POS checkout.
            </div>

            <div class="row g-2 mb-2">
              <div class="col-12 col-md-6">
                <div class="p-2 rounded-2 bg-light border">
                  <div class="text-muted small fw-semibold text-uppercase"
                    style="font-size: 10px; letter-spacing: 0.5px;">Clinical Indications &amp; Therapeutic Usage</div>
                  <div id="detailClinicalIndication" class="small text-dark mt-1">
                    First-line antibiotic indicated for bacterial infections.
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="p-2 rounded-2 bg-light border">
                  <div class="text-muted small fw-semibold text-uppercase"
                    style="font-size: 10px; letter-spacing: 0.5px;">Storage &amp; Temperature Control</div>
                  <div id="detailStorageNotes" class="small text-dark mt-1">
                    Store at temperatures not exceeding 30°C. Protect from moisture and direct light.
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-2">
              <div class="col-12 col-md-6">
                <div class="p-2 rounded-2 bg-light border">
                  <div class="text-muted small fw-semibold text-uppercase"
                    style="font-size: 10px; letter-spacing: 0.5px;">Stock Reorder &amp; Minimum Threshold</div>
                  <div id="detailReorderNotes" class="small text-dark mt-1">
                    Maintain minimum safety buffer of 10 units. Restock triggered automatically.
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="p-2 rounded-2 bg-light border">
                  <div class="text-muted small fw-semibold text-uppercase"
                    style="font-size: 10px; letter-spacing: 0.5px;">Philippine Regulatory &amp; Discounts</div>
                  <div class="small text-muted mt-1">
                    Philippine FDA Compliant &bull; 20% statutory Senior Citizen (RA 9994) &amp; PWD (RA 10754) discount
                    applies at POS.
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer bg-white border-top">
          <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Close</button>
          <button type="button" id="detailBtnRestock" class="btn scanner-btn-orange px-3 fw-semibold">
            <i class="bi bi-qr-code-scan me-1"></i> Restock via Scanner
          </button>
          <button type="button" id="detailBtnEdit" class="btn btn-success px-4"
            style="background: #1d9e75 !important; border-color: #1d9e75 !important;">
            <i class="bi bi-pencil-square me-1"></i> Edit Stock
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- ══ SCAN & DIRECT UPDATE MODAL ═══════════════════════════ -->
  <div class="modal fade" id="scanStockModal" tabindex="-1" aria-labelledby="scanStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content scanner-modal-content">
        <div class="modal-header scanner-modal-header">
          <h5 class="modal-title d-flex align-items-center gap-2 text-white" id="scanStockModalLabel">
            <span class="material-symbols-outlined">qr_code_scanner</span>
            Scan &amp; Direct Stock Update
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">

          <!-- Nav Tabs -->
          <div class="scanner-nav-tabs">
            <button type="button" id="scannerTabBarcode" class="scanner-nav-tab active">
              <span class="material-symbols-outlined">barcode_scanner</span>
              Camera / Barcode Reader
            </button>
            <button type="button" id="scannerTabReceipt" class="scanner-nav-tab">
              <span class="material-symbols-outlined">receipt_long</span>
              Simulated Receipt / OCR Text
            </button>
          </div>

          <!-- Tab 1: Camera Barcode Viewport & Manual Code Input -->
          <div id="scannerPanelBarcode">
            <div class="scanner-viewport-wrap" id="scannerViewport">
              <div id="scannerVideoFeed" style="width: 100%; height: 100%;"></div>
              <div class="scanner-guide-box">
                <div class="scanner-laser"></div>
              </div>
            </div>

            <!-- Manual Barcode / Medicine ID Entry -->
            <div class="input-group mb-2">
              <span class="input-group-text bg-white border-end-0">
                <span class="material-symbols-outlined text-muted" style="font-size: 1.25rem;">barcode</span>
              </span>
              <input type="text" id="scannerManualCode" class="form-control"
                placeholder="Or type medicine name, barcode, or ID (e.g. Amoxicillin, 12, Biogesic)...">
              <button class="btn btn-success px-4" id="scannerBtnLookup"
                style="background: #1d9e75 !important; border-color: #1d9e75 !important;">
                Lookup
              </button>
            </div>
            <div class="form-text small text-muted">
              Point your camera at a barcode/QR code, or manually input medicine names to directly adjust stock.
            </div>

            <!-- Match Result Area -->
            <div id="scannerResultBox" style="display: none;"></div>
          </div>

          <!-- Tab 2: Simulated Receipt OCR Scanner -->
          <div id="scannerPanelReceipt" style="display: none;">
            <div class="ocr-input-wrap">
              <label class="form-label fw-semibold small text-dark mb-1">
                Receipt Delivery Text Parser (Simulated OCR)
              </label>
              <textarea id="scannerReceiptText" class="ocr-textarea" placeholder="Paste delivery invoice or supplier receipt lines here, for example:
Amoxicillin 500mg x 20
Biogesic 500mg - Qty: 50
Paracetamol x 30"></textarea>
              <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="text-muted small">Supports lines with medicine name and quantity format.</span>
                <button type="button" id="scannerBtnParseReceipt" class="btn btn-success btn-sm px-3"
                  style="background: #1d9e75 !important; border-color: #1d9e75 !important;">
                  <i class="bi bi-magic me-1"></i> Parse &amp; Match
                </button>
              </div>
            </div>

            <!-- Parsed Results -->
            <div id="scannerReceiptResults" style="display: none;"></div>
          </div>

        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ EXISTING ADD / UPDATE MODAL INCLUDED ═════════════════ -->
  <?php include '../02_Actions/03_Pharmacy-Admin-CRUD/modal_add-update.php'; ?>

  <!-- Scripts -->
  <script type="module" src="../07_Assets/js/scanner.js"></script>

  <script>
    // Stats Hero Collapse
    function toggleStatsCards() {
      const hero = document.getElementById('statsHero');
      const label = document.getElementById('statsToggleLabel');
      const chevron = document.getElementById('statsChevron');
      const isVisible = !hero.classList.contains('collapsed');

      hero.classList.toggle('collapsed', isVisible);
      if (label) label.textContent = isVisible ? 'Expand' : 'Collapse';
      if (chevron) chevron.classList.toggle('rotated', isVisible);
    }

    // Client-side search and filters for table
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('inventorySearchInput');
      const filterCategory = document.getElementById('filterCategory');
      const filterStatus = document.getElementById('filterStatus');
      const rows = document.querySelectorAll('.inv-row');

      function filterTable() {
        const query = searchInput.value.toLowerCase().trim();
        const cat = filterCategory.value.toLowerCase().trim();
        const stat = filterStatus.value.toLowerCase().trim();

        rows.forEach(r => {
          const rSearch = r.getAttribute('data-search') || '';
          const rCat = r.getAttribute('data-category') || '';
          const rStat = r.getAttribute('data-status') || '';

          const matchQuery = !query || rSearch.includes(query);
          const matchCat = !cat || rCat === cat;
          const matchStat = !stat || rStat === stat;

          if (matchQuery && matchCat && matchStat) {
            r.style.display = '';
          } else {
            r.style.display = 'none';
          }
        });
      }

      if (searchInput) searchInput.addEventListener('input', filterTable);
      if (filterCategory) filterCategory.addEventListener('change', filterTable);
      if (filterStatus) filterStatus.addEventListener('change', filterTable);

      // Check if URL has ?openScan=1
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('openScan') === '1') {
        const scanModalEl = document.getElementById('scanStockModal');
        if (scanModalEl) {
          const bsModal = new bootstrap.Modal(scanModalEl);
          bsModal.show();

          const autoCode = urlParams.get('code');
          if (autoCode) {
            const manualInput = document.getElementById('scannerManualCode');
            if (manualInput) {
              manualInput.value = autoCode;
              setTimeout(() => {
                const btnLookup = document.getElementById('scannerBtnLookup');
                if (btnLookup) btnLookup.click();
              }, 450);
            }
          }
        }
      }

      // ── VIEW DETAILS MODAL POPULATOR ──────────────────────────
      const viewModalEl = document.getElementById('viewMedicineModal');
      const viewModalInstance = viewModalEl ? new bootstrap.Modal(viewModalEl) : null;
      let activeRowButton = null;

      document.querySelectorAll('.custom-view-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          activeRowButton = this;
          openViewModal(this);
        });
      });

      // Double-click table row to view details
      document.querySelectorAll('.inv-row').forEach(row => {
        row.addEventListener('dblclick', function () {
          const btn = this.querySelector('.custom-view-btn');
          if (btn) {
            activeRowButton = btn;
            openViewModal(btn);
          }
        });
      });

      function openViewModal(btn) {
        if (!viewModalInstance) return;

        const imgUrl = btn.dataset.image || '../07_Assets/images/logo.png';
        const generic = btn.dataset.generic || 'Medicine';
        const brand = btn.dataset.brand || 'Generic';
        const mfr = btn.dataset.manufacturer || 'Licensed Philippine Manufacturer';
        const cat = btn.dataset.category || 'General OTC';
        const form = btn.dataset.dosageform || 'Tablet';
        const dosageVal = btn.dataset.dosagevalue || '';
        const dosageUnit = btn.dataset.dosageunit || '';
        const dosageStr = (dosageVal ? `${dosageVal} ${dosageUnit}` : '') + (form ? ` ${form}` : '');
        const price = parseFloat(btn.dataset.price || 0);
        const pricePer = btn.dataset.priceper || 'unit';
        const qty = parseInt(btn.dataset.qty || 0);
        const expiry = btn.dataset.expiry || 'N/A';
        const rx = btn.dataset.rx || 'No';
        const updated = btn.dataset.updated || 'Today';
        const status = btn.dataset.status || 'Available';
        const invId = btn.dataset.id || '0';
        const shelf = btn.dataset.shelf || 'Prescription Dispensary Rack';
        const reorder = btn.dataset.reorder || '10 units (Minimum stock safety threshold)';
        const indication = btn.dataset.indication || 'Prescribed medication indicated for clinical treatment in compliance with Philippine health standards.';
        const storage = btn.dataset.storage || 'Store at temperatures not exceeding 30°C in a dry place. Protect from heat and light.';

        // Populate elements
        document.getElementById('detailMedImage').src = imgUrl;
        document.getElementById('detailGenericName').textContent = generic + (dosageVal ? ` ${dosageVal} ${dosageUnit}` : '');
        document.getElementById('detailBrandManufacturer').textContent = `${brand} • Manufactured by ${mfr}`;
        document.getElementById('detailPrice').textContent = `₱${price.toFixed(2)}`;
        document.getElementById('detailPricePer').textContent = `/ ${pricePer}`;
        document.getElementById('detailShelfText').textContent = shelf;

        document.getElementById('detailQuantity').textContent = `${qty} units`;
        document.getElementById('detailExpiryDate').textContent = expiry;
        document.getElementById('detailInventoryID').textContent = `#INV-${invId}`;
        document.getElementById('detailReorderLevel').textContent = reorder.split(' ')[0] + ' units';

        // Badges
        const rxBadge = document.getElementById('detailRxBadge');
        const isRx = rx.toLowerCase().includes('yes') || rx.toLowerCase().includes('require');
        if (isRx) {
          rxBadge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 small fw-bold';
          rxBadge.textContent = 'Rx Required';
        } else {
          rxBadge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small fw-bold';
          rxBadge.textContent = 'No Rx (OTC)';
        }

        document.getElementById('detailCategoryBadge').textContent = cat;
        document.getElementById('detailFormBadge').textContent = form;

        const statusBadge = document.getElementById('detailStatusBadge');
        statusBadge.textContent = status;
        if (qty === 0) {
          statusBadge.className = 'badge bg-danger text-white px-2 py-1 small';
        } else if (qty <= 10) {
          statusBadge.className = 'badge bg-warning text-dark px-2 py-1 small';
        } else {
          statusBadge.className = 'badge bg-success text-white px-2 py-1 small';
        }

        // Diagnostic stock report text
        const reportBox = document.getElementById('detailStockReport');
        if (qty === 0) {
          reportBox.style.background = '#fef2f2';
          reportBox.style.borderColor = '#fecaca';
          reportBox.style.color = '#991b1b';
          reportBox.textContent = 'CRITICAL: Medicine is currently OUT OF STOCK. Restock immediately to maintain dispensary service.';
        } else if (qty <= 10) {
          reportBox.style.background = '#fffbeb';
          reportBox.style.borderColor = '#fde68a';
          reportBox.style.color = '#b45309';
          reportBox.textContent = `WARNING: Stock is below minimum safety threshold (10 units). Remaining quantity: ${qty} units.`;
        } else {
          reportBox.style.background = '#f0fdf4';
          reportBox.style.borderColor = '#bbf7d0';
          reportBox.style.color = '#166534';
          reportBox.textContent = `Inventory level is healthy (${qty} units available). Optimal supply available for POS checkout.`;
        }

        document.getElementById('detailClinicalIndication').textContent = indication;
        document.getElementById('detailStorageNotes').textContent = storage;
        document.getElementById('detailReorderNotes').textContent = reorder;

        viewModalInstance.show();
      }

      // Restock action inside details modal
      const btnRestockModal = document.getElementById('detailBtnRestock');
      if (btnRestockModal) {
        btnRestockModal.addEventListener('click', function () {
          if (viewModalInstance) viewModalInstance.hide();
          if (activeRowButton) {
            const genericName = activeRowButton.dataset.generic || '';
            const scanModalEl = document.getElementById('scanStockModal');
            if (scanModalEl) {
              const sModal = new bootstrap.Modal(scanModalEl);
              sModal.show();
              const manualInput = document.getElementById('scannerManualCode');
              if (manualInput) {
                manualInput.value = genericName;
                setTimeout(() => {
                  const btnLookup = document.getElementById('scannerBtnLookup');
                  if (btnLookup) btnLookup.click();
                }, 400);
              }
            }
          }
        });
      }

      // Edit action inside details modal
      const btnEditModal = document.getElementById('detailBtnEdit');
      if (btnEditModal) {
        btnEditModal.addEventListener('click', function () {
          if (viewModalInstance) viewModalInstance.hide();
          if (activeRowButton) {
            const editBtn = activeRowButton.closest('tr').querySelector('.edit-btn');
            if (editBtn) {
              setTimeout(() => { editBtn.click(); }, 350);
            }
          }
        });
      }

    });
  </script>
</body>

</html>