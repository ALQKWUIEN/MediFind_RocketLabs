function validateStep1() {
    const fname = document.getElementById('Fname').value.trim();
    const lname = document.getElementById('Lname').value.trim();
    const contactNo = document.getElementById('ContactNo').value.trim();
    const gender = document.getElementById('gender').value;
    const birthDate = document.getElementById('birth_date').value;

    if (!fname || !lname || !contactNo || !gender || !birthDate) {
        alert('Please fill in all required fields in Step 1.');
        return false;
    }
    changeStep(2);
}

function validateStep2() {
    const province = document.getElementById('Province_ID').value;
    const city = document.getElementById('City_ID').value;
    const barangay = document.getElementById('Barangay_ID').value;
    const street = document.getElementById('Street').value.trim();

    console.log('province:', province, 'city:', city, 'barangay:', barangay, 'street:', street); 

    if (!province || !city || !barangay || !street) {
        alert('Please fill in all required fields in Step 2.');
        return false;
    }
    
    changeStep(3);
}

// ← only ONE changeStep, using element IDs
function changeStep(stepNumber) {
    document.querySelectorAll('.form-step').forEach(step => {
        step.style.display = 'none';
    });
    document.getElementById('step' + stepNumber).style.display = 'block';
}

function validateForm() {
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmpass').value;

    if (!email || !username || !password || !confirmPassword) {
        alert('Please fill in all required fields in Step 3.');
        return false;
    }

    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return false;
    }
F
    return true;
}

function togglePassword(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    }
}