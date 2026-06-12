(function () {
    const currentTheme = localStorage.getItem('theme') || 'dark';
    if (currentTheme === 'light') {
        document.documentElement.classList.add('light-theme');
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    // Theme toggle button logic
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const icon = themeToggle.querySelector('.theme-toggle-icon');
        
        // Sync icon state on page load
        if (document.documentElement.classList.contains('light-theme')) {
            if (icon) icon.textContent = '☀️';
        } else {
            if (icon) icon.textContent = '🌙';
        }

        themeToggle.addEventListener('click', function () {
            const isLight = document.documentElement.classList.toggle('light-theme');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            if (icon) {
                icon.textContent = isLight ? '☀️' : '🌙';
            }
        });
    }

    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', function () {
            const isOpen = navLinks.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.querySelectorAll('.password-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            const shouldShow = input.type === 'password';
            input.type = shouldShow ? 'text' : 'password';
            button.textContent = shouldShow ? 'Hide' : 'Show';
        });
    });

    const startDateInput = document.querySelector('[data-start-date]');
    const endDateInput = document.querySelector('[data-end-date]');

    if (startDateInput && endDateInput) {
        const updateEndDateLimit = function () {
            endDateInput.min = startDateInput.value;

            if (endDateInput.value && startDateInput.value && endDateInput.value < startDateInput.value) {
                endDateInput.value = '';
                endDateInput.setCustomValidity('End date cannot be earlier than the start date.');
            } else {
                endDateInput.setCustomValidity('');
            }
        };

        startDateInput.addEventListener('change', updateEndDateLimit);
        endDateInput.addEventListener('change', updateEndDateLimit);
        updateEndDateLimit();
    }
});
