/**
 * JobConnect - Main JavaScript
 * Handles dynamic forms, cascading dropdowns, and UI interactions
 */

// ============================================
// Mobile Menu Toggle
// ============================================

function toggleMobileMenu() {
    const nav = document.getElementById('navMenu');
    nav.classList.toggle('show');
}


// ============================================
// Cascading Dropdowns (AJAX)
// ============================================

/**
 * Load cities by country (for job creation/edit forms)
 */
function loadCities(countryId) {
    const citySelect = document.getElementById('citySelect');
    const districtSelect = document.getElementById('districtSelect');

    if (!citySelect) return;

    // Clear districts
    if (districtSelect) {
        districtSelect.innerHTML = '<option value="">Select district</option>';
    }

    if (!countryId) {
        citySelect.innerHTML = '<option value="">Select city</option>';
        return;
    }

    // Try AJAX first, fall back to filtering existing options
    fetch(`/api/cities?country_id=${countryId}`)
        .then(res => res.json())
        .then(data => {
            let html = '<option value="">Select city</option>';
            data.forEach(city => {
                html += `<option value="${city.id}">${escapeHtml(city.name)}</option>`;
            });
            citySelect.innerHTML = html;
        })
        .catch(() => {
            // Fallback: filter existing options by data attribute
            filterSelectByData(citySelect, 'country', countryId);
        });
}

/**
 * Load districts by city
 */
function loadDistricts(cityId) {
    const districtSelect = document.getElementById('districtSelect');
    if (!districtSelect) return;

    if (!cityId) {
        districtSelect.innerHTML = '<option value="">Select district</option>';
        return;
    }

    fetch(`/api/districts?city_id=${cityId}`)
        .then(res => res.json())
        .then(data => {
            let html = '<option value="">Select district</option>';
            data.forEach(d => {
                html += `<option value="${d.id}">${escapeHtml(d.name)}</option>`;
            });
            districtSelect.innerHTML = html;
        })
        .catch(() => {
            filterSelectByData(districtSelect, 'city', cityId);
        });
}

/**
 * Load job titles by category
 */
function loadJobTitles(categoryId) {
    const titleSelect = document.getElementById('jobTitle');
    if (!titleSelect) return;

    if (!categoryId) {
        // Show all titles
        const options = titleSelect.querySelectorAll('option');
        options.forEach(opt => opt.style.display = '');
        titleSelect.value = '';
        return;
    }

    fetch(`/api/job-titles?category_id=${categoryId}`)
        .then(res => res.json())
        .then(data => {
            let html = '<option value="">Select title</option>';
            data.forEach(t => {
                html += `<option value="${t.id}">${escapeHtml(t.name)}</option>`;
            });
            // Also keep all titles visible as fallback
            const currentOptions = titleSelect.querySelectorAll('option');
            currentOptions.forEach(opt => {
                if (opt.value === '') return;
                if (opt.dataset.category === categoryId) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
            titleSelect.value = '';
        })
        .catch(() => {
            // Fallback: filter by data attribute
            filterSelectByData(titleSelect, 'category', categoryId);
        });
}

/**
 * Filter select options by data attribute
 */
function filterSelectByData(select, dataKey, value) {
    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        if (opt.value === '') {
            opt.style.display = '';
        } else if (opt.dataset[dataKey] === String(value)) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
    select.value = '';
}


// ============================================
// Dynamic Skills Section
// ============================================

let skillIndex = 1; // Start from 1 since index 0 already exists

/**
 * Add a new skill row
 */
function addSkillRow() {
    const container = document.getElementById('skillsContainer');
    if (!container) return;

    const currentRows = container.querySelectorAll('.skill-row');
    if (typeof MAX_SKILLS !== 'undefined' && currentRows.length >= MAX_SKILLS) {
        alert(`Maximum ${MAX_SKILLS} skills allowed per job posting.`);
        return;
    }

    // Build skill options
    let skillOpts = '<option value="">Select skill</option>';
    if (typeof skillOptions !== 'undefined') {
        skillOptions.forEach(s => {
            skillOpts += `<option value="${s.id}">${escapeHtml(s.name)}</option>`;
        });
    }

    // Build proficiency options
    let profOpts = '<option value="">Select level</option>';
    if (typeof proficiencyOptions !== 'undefined') {
        proficiencyOptions.forEach(p => {
            profOpts += `<option value="${p.id}">${escapeHtml(p.name)}</option>`;
        });
    }

    const row = document.createElement('div');
    row.className = 'skill-row';
    row.dataset.index = skillIndex;
    row.innerHTML = `
        <div class="form-group" style="margin: 0;">
            <label class="form-label">Skill</label>
            <select name="skills[${skillIndex}][skill_id]" class="form-control skill-select" required>
                ${skillOpts}
            </select>
        </div>
        <div class="form-group" style="margin: 0;">
            <label class="form-label">Min. Proficiency</label>
            <select name="skills[${skillIndex}][proficiency_level_id]" class="form-control" required>
                ${profOpts}
            </select>
        </div>
        <button type="button" class="btn-remove" onclick="removeSkillRow(this)" style="align-self: end;">✕</button>
    `;

    container.appendChild(row);
    skillIndex++;
    updateSkillCount();
}

/**
 * Remove a skill row
 */
function removeSkillRow(btn) {
    const container = document.getElementById('skillsContainer');
    const rows = container.querySelectorAll('.skill-row');

    if (rows.length <= 1) {
        alert('At least one skill is required.');
        return;
    }

    btn.closest('.skill-row').remove();
    updateSkillCount();
}

/**
 * Update skill count display
 */
function updateSkillCount() {
    const container = document.getElementById('skillsContainer');
    const countEl = document.getElementById('skillsCount');
    const addBtn = document.getElementById('addSkillBtn');

    if (!container || !countEl) return;

    const count = container.querySelectorAll('.skill-row').length;
    const max = typeof MAX_SKILLS !== 'undefined' ? MAX_SKILLS : 5;

    countEl.textContent = `${count} / ${max} skills`;

    if (addBtn) {
        if (count >= max) {
            addBtn.style.display = 'none';
        } else {
            addBtn.style.display = '';
        }
    }
}


// ============================================
// Flash Message Auto-dismiss
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss flash messages after 5 seconds
    const flashAlert = document.getElementById('flashAlert');
    if (flashAlert) {
        setTimeout(() => {
            flashAlert.style.opacity = '0';
            flashAlert.style.transform = 'translateY(-10px)';
            flashAlert.style.transition = 'all 0.3s ease';
            setTimeout(() => flashAlert.remove(), 300);
        }, 5000);
    }

    // Initialize skill count on page load
    updateSkillCount();

    // Initialize skill index based on existing rows
    const container = document.getElementById('skillsContainer');
    if (container) {
        const rows = container.querySelectorAll('.skill-row');
        skillIndex = rows.length;
    }

    // Filter initial cascading selections based on current values
    initCascadingSelects();
});


/**
 * Initialize cascading selects on page load
 */
function initCascadingSelects() {
    // Filter job titles by selected category
    const categorySelect = document.getElementById('jobCategory');
    const titleSelect = document.getElementById('jobTitle');

    if (categorySelect && titleSelect && categorySelect.value) {
        const selectedTitle = titleSelect.value;
        const options = titleSelect.querySelectorAll('option');
        options.forEach(opt => {
            if (opt.value === '' || opt.dataset.category === categorySelect.value) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        titleSelect.value = selectedTitle;
    }

    // Filter cities by selected country
    const countrySelect = document.getElementById('countrySelect');
    const citySelect = document.getElementById('citySelect');

    if (countrySelect && citySelect && countrySelect.value) {
        const selectedCity = citySelect.value;
        const options = citySelect.querySelectorAll('option');
        options.forEach(opt => {
            if (opt.value === '' || opt.dataset.country === countrySelect.value) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        citySelect.value = selectedCity;
    }

    // Filter districts by selected city
    const districtSelect = document.getElementById('districtSelect');
    if (citySelect && districtSelect && citySelect.value) {
        const selectedDistrict = districtSelect.value;
        const options = districtSelect.querySelectorAll('option');
        options.forEach(opt => {
            if (opt.value === '' || opt.dataset.city === citySelect.value) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        districtSelect.value = selectedDistrict;
    }
}


// ============================================
// Utility Functions
// ============================================

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

/**
 * Navbar scroll effect
 */
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        if (window.scrollY > 10) {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.3)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    }
});

/**
 * Edit profile field
 */
function editField(field) {
    const row = document.getElementById(field + 'Text').closest('.profile-row');

    // Reset only inside the clicked row
    document.querySelectorAll('.profile-row').forEach(r => {
        r.querySelectorAll('.profile-value').forEach(el => el.style.display = 'inline');
        r.querySelectorAll('.form-control').forEach(el => el.style.display = 'none');
        r.querySelectorAll('.profile-btn.edit').forEach(el => el.style.display = 'inline-block');
        r.querySelectorAll('.profile-btn.save').forEach(el => el.style.display = 'none');
        r.querySelectorAll('.profile-btn.cancel').forEach(el => el.style.display = 'none');
    });

    // Activate only current row
    const text = document.getElementById(field + 'Text');
    const input = document.getElementById(field + 'Input');
    const editBtn = document.getElementById(field + 'Edit');
    const saveBtn = document.getElementById(field + 'Save');
    const cancelBtn = document.getElementById(field + 'Cancel');

    text.style.display = 'none';
    input.style.display = 'inline-block';

    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-block';
    cancelBtn.style.display = 'inline-block';

    input.focus();
    toggleSave(field);
}

/**
 * Confirm account deactivation
 */
function confirmDeactivate() {
    if (confirm('Are you sure you want to deactivate your account? This action cannot be undone.')) {
        document.getElementById('deactivateForm').submit();
    }
}

/**
 * Cancel edit
 */
function cancelEdit(field) {
    const text = document.getElementById(field + 'Text');
    const input = document.getElementById(field + 'Input');
    const editBtn = document.getElementById(field + 'Edit');
    const saveBtn = document.getElementById(field + 'Save');
    const cancelBtn = document.getElementById(field + 'Cancel');

    // Restore original value
    input.value = text.innerText.trim();

    // Only reset one field
    text.style.display = 'inline';
    input.style.display = 'none';

    editBtn.style.display = 'inline-block';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
}

/**
 * Toggle password change form
 */
let pwOpen = false;
let savedScrollY = 0;

function togglePassword() {
    const body = document.getElementById('passwordForm');
    const card = body.closest('.profile-password-card');

    if (!pwOpen) {
        // store current scroll position
        savedScrollY = window.scrollY;

        body.classList.remove('is-hidden');
        card.classList.add('pw-open');

        pwOpen = true;
    } else {
        body.classList.add('is-hidden');
        card.classList.remove('pw-open');

        pwOpen = false;

        // restore original scroll position
        window.scrollTo({ top: savedScrollY, behavior: 'smooth' });
    }
}

/**
 * Toggle save button when input is not blank
 */
function toggleSave(field) {
    const input = document.getElementById(field + 'Input');
    const saveBtn = document.getElementById(field + 'Save');

    if (!input || !saveBtn) return;

    const value = input.value.trim();

    if (value.length === 0) {
        saveBtn.style.display = 'none';
    } else {
        saveBtn.style.display = 'inline-block';
    }
}