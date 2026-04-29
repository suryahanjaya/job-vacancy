/**
 * JobConnect - Main JavaScript
 * Handles dynamic forms, cascading dropdowns, and UI interactions
 */

// ============================================
// Entry Point
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    initAlerts();
    initSkillsState();
    updateSkillCount();
    initCascadingSelects();
});

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
 * Initialize skill index based on existing rows (for edit form)
 */
function initSkillsState() {
    const container = document.getElementById('skillsContainer');
    if (!container) return;

    skillIndex = container.querySelectorAll('.skill-row').length;
}

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
        <button type="button" class="btn-remove" onclick="removeSkillRow(this)">✕</button>
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
        showAlert('At least one skill is required.', 'error');
        return;
    }

    btn.closest('.skill-row')?.remove();
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
// Alert system
// ============================================
function initAlerts() {
    document.querySelectorAll('.alert').forEach(alert => {

        const timer = setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 400);
        }, 5000);

        alert.addEventListener('click', () => {
            clearTimeout(timer);
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 400);
        });
    });
}

function showAlert(message, type = 'error') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;

    document.body.appendChild(alert);

    const timer = setTimeout(() => {
        alert.classList.add('fade-out');
        setTimeout(() => alert.remove(), 400);
    }, 5000);

    alert.addEventListener('click', () => {
        clearTimeout(timer);
        alert.classList.add('fade-out');
        setTimeout(() => alert.remove(), 400);
    });
}

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

// ============================================
// Custom confirm dialog
// ============================================
function showConfirm(message, onYes) {
    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';

    overlay.innerHTML = `
        <div class="confirm-box">
            <p>${message}</p>
            <div class="confirm-actions">
                <button class="confirm-btn cancel" id="cancelBtn">Cancel</button>
                <button class="confirm-btn confirm" id="okBtn">Yes</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    overlay.querySelector('#cancelBtn').onclick = () => {
        overlay.remove();
    };

    overlay.querySelector('#okBtn').onclick = () => {
        overlay.remove();
        onYes();
    };
}

/**
 * Handle form submission with confirmation
 */
function handleConfirm(form, message) {
    showConfirm(message, () => {
        form.submit();
    });
}

// ============================================
// Chart.js for Admin Dashboard
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('jobsChart');
    if (!canvas) return;

    const container = canvas.parentElement;
    const emptyState = container.querySelector('.empty-state');
    const select = document.getElementById('groupBySelect');

    // Parse initial chart data
    let data = [];

    try {
        data = JSON.parse(canvas.dataset.chart || '[]');
        if (!Array.isArray(data)) data = [];
    } catch (err) {
        console.error('Invalid chart data JSON:', err);
        data = [];
    }

    // helper: toggle UI state
    function toggleState(hasData) {
        if (emptyState) {
            emptyState.style.display = hasData ? 'none' : 'flex';
        }
        canvas.style.display = hasData ? 'block' : 'none';
    }

    const hasInitialData = data.length > 0;

    // initial state
    toggleState(hasInitialData);

    // Create Chart
    const chart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: data.map(item => item.label),
            datasets: [{
                label: 'Jobs',
                data: data.map(item => Number(item.total) || 0),
                backgroundColor: '#148bdb'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Dropdown handler
    if (!select) return;

    select.addEventListener('change', async (e) => {
        const groupBy = e.target.value;

        try {
            // empty selection → clear chart + show empty state
            if (!groupBy) {
                chart.data.labels = [];
                chart.data.datasets[0].data = [];
                chart.update();

                toggleState(false);
                return;
            }

            const res = await fetch(
                `/admin/chart-data?groupBy=${encodeURIComponent(groupBy)}`
            );

            const newData = await res.json();
            const hasData = Array.isArray(newData) && newData.length > 0;

            const labels = newData.map(item => item.label);
            const values = newData.map(item => Number(item.total) || 0);

            chart.data.labels = labels;
            chart.data.datasets[0].data = values;
            chart.update();

            // toggle UI based on result
            toggleState(hasData);

        } catch (err) {
            console.error('Failed to fetch chart data:', err);
            toggleState(false);
        }
    });
});

/**
 * Reset groupBy select on page show (back navigation)
 */
window.addEventListener('pageshow', () => {
    const select = document.getElementById('groupBySelect');
    if (select) select.value = "";
});