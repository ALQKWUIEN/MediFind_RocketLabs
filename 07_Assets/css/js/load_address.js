
// Saved values from session (populated by PHP after a failed submit)
const savedProvince = "<?= (int)($fd['Province'] ?? 0) ?>";
const savedCity     = "<?= (int)($fd['City']     ?? 0) ?>";
const savedBarangay = "<?= (int)($fd['Barangay'] ?? 0) ?>";

// ─── LOAD PROVINCES on page load, then chain city/barangay if saved ───
window.addEventListener('DOMContentLoaded', () => {

    fetch('../02_Actions/01_Authentication-CRUD/get_address.php?type=province')
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('Province_ID');
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value       = p.Province_ID;
                opt.textContent = p.Province_Name;
                if (String(p.Province_ID) === savedProvince) opt.selected = true;
                sel.appendChild(opt);
            });

            // If a province was previously selected, load its cities
            if (savedProvince) {
                return fetch(`../02_Actions/01_Authentication-CRUD/get_address.php?type=city&province_id=${savedProvince}`)
                    .then(r => r.json())
                    .then(data => {
                        const citySelect = document.getElementById('City_ID');
                        brgySelect.innerHTML = '<option value="" disabled selected>City</option>';
                        data.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value       = c.City_ID;
                            opt.textContent = c.City_Name;
                            if (String(c.City_ID) === savedCity) opt.selected = true;
                            citySelect.appendChild(opt);
                        });

                        // If a city was previously selected, load its barangays
                        if (savedCity) {
                            return fetch(`../02_Actions/01_Authentication-CRUD/get_address.php?type=barangay&city_id=${savedCity}`)
                                .then(r => r.json())
                                .then(data => {
                                    const brgySelect = document.getElementById('Barangay_ID');
                                    brgySelect.innerHTML = '<option value="" disabled selected>Barangay</option>';
                                    data.forEach(b => {
                                        const opt = document.createElement('option');
                                        opt.value       = b.Barangay_ID;
                                        opt.textContent = b.Barangay_Name;
                                        if (String(b.Barangay_ID) === savedBarangay) opt.selected = true;
                                        brgySelect.appendChild(opt);
                                    });
                                });
                        }
                    });
            }
        });
});

// ─── PROVINCE CHANGE → load cities ────────────────────────────────
document.getElementById('Province_ID').addEventListener('change', function () {
    const citySelect = document.getElementById('City_ID');
    const brgySelect = document.getElementById('Barangay_ID');

    citySelect.innerHTML = '<option value="" disabled selected>City</option>';
    brgySelect.innerHTML = '<option value="" disabled selected>Barangay</option>';

    fetch(`../02_Actions/01_Authentication-CRUD/get_address.php?type=city&province_id=${this.value}`)
        .then(r => r.json())
        .then(data => {
            data.forEach(c => {
                const opt = document.createElement('option');
                opt.value       = c.City_ID;
                opt.textContent = c.City_Name;
                citySelect.appendChild(opt);
            });
        });
});

// ─── CITY CHANGE → load barangays ─────────────────────────────────
document.getElementById('City_ID').addEventListener('change', function () {
    const brgySelect = document.getElementById('Barangay_ID');

    brgySelect.innerHTML = '<option value="" disabled selected>Barangay</option>';

    fetch(`../02_Actions/01_Authentication-CRUD/get_address.php?type=barangay&city_id=${this.value}`)
        .then(r => r.json())
        .then(data => {
            data.forEach(b => {
                const opt = document.createElement('option');
                opt.value       = b.Barangay_ID;
                opt.textContent = b.Barangay_Name;
                brgySelect.appendChild(opt);
            });
        });
});


// Do not clean password form if email is incorrect 
// Before form submits, save passwords temporarily
// Save passwords to sessionStorage before form submits
document.getElementById('signupForm').addEventListener('submit', function() {
    sessionStorage.setItem('pw',  document.getElementById('password').value);
    sessionStorage.setItem('cpw', document.getElementById('confirmpass').value);
});

// Restore passwords on page load if coming back from an error
window.addEventListener('DOMContentLoaded', function() {
    const pw  = sessionStorage.getItem('pw');
    const cpw = sessionStorage.getItem('cpw');

    if (pw)  {
        document.getElementById('password').value = pw;
        sessionStorage.removeItem('pw');
    }
    if (cpw) {
        document.getElementById('confirmpass').value = cpw;
        sessionStorage.removeItem('cpw');
    }
});