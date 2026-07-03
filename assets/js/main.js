/**
 * Custom JavaScript for Sistem Pencatatan Setoran Hafalan Al-Qur'an
 * Author: Antigravity UI/UX Subagent
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Fade-in animations for cards & elements on load
    initFadeIn();

    // 2. Ripple effect on buttons
    initRippleEffect();

    // 3. Counter Animation on dashboards
    initCounters();

    // 4. Sidebar toggles & expand animation
    initSidebar();

    // 5. Parallax effect for floating shapes
    initParallax();

    // 6. Typing animation for Login / Hero page
    initTyping();

    // 7. Navbar blur on scroll
    initNavbarScroll();

    // 8. Custom client-side table sorting & filter
    initTableSearch();
});

// Fade In effect
function initFadeIn() {
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach((el, index) => {
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Button Ripple Effect
function initRippleEffect() {
    const buttons = document.querySelectorAll('.btn-glass, .btn-glass-secondary, .btn-primary, .btn-danger, .btn-warning, .list-group-item-action');
    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            // Check if already contains relative positioning
            if (getComputedStyle(button).position === 'static') {
                button.style.position = 'relative';
            }

            let x = e.clientX - e.target.getBoundingClientRect().left;
            let y = e.clientY - e.target.getBoundingClientRect().top;

            let ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Dashboard Number Counters
function initCounters() {
    const counters = document.querySelectorAll('.counter-anim');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 1200; // ms
        const stepTime = Math.max(Math.floor(duration / target), 15);
        let current = 0;

        if (target === 0) {
            counter.innerText = '0';
            return;
        }

        const timer = setInterval(() => {
            current += Math.ceil(target / (duration / stepTime));
            if (current >= target) {
                counter.innerText = target;
                clearInterval(timer);
            } else {
                counter.innerText = current;
            }
        }, stepTime);
    });
}

// Sidebar logic
function initSidebar() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    if (sidebarToggle && sidebar && mainContent) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('expanded');
        });
    }
}

// Mouse Parallax for Floating Background Shapes (Login Screen)
function initParallax() {
    const shapes = document.querySelectorAll('.bg-shapes .shape-1, .bg-shapes .shape-2');
    if (shapes.length > 0) {
        document.addEventListener('mousemove', function (e) {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 20;
                shape.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });
    }
}

// Typing Effect
function initTyping() {
    const typingElement = document.getElementById('typing-text');
    if (typingElement) {
        const rawData = typingElement.getAttribute('data-texts') || '["Sistem Pencatatan Setoran Hafalan"]';
        let parsed = JSON.parse(rawData);
        // Support both array and object formats
        const texts = Array.isArray(parsed) ? parsed : Object.values(parsed);
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let delay = 100;

        function type() {
            const currentText = texts[textIndex];
            if (isDeleting) {
                typingElement.innerHTML = currentText.substring(0, charIndex - 1) + '<span class="cursor"></span>';
                charIndex--;
                delay = 50;
            } else {
                typingElement.innerHTML = currentText.substring(0, charIndex + 1) + '<span class="cursor"></span>';
                charIndex++;
                delay = 100;
            }

            if (!isDeleting && charIndex === currentText.length) {
                isDeleting = true;
                delay = 2000;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                delay = 500;
            }

            setTimeout(type, delay);
        }

        setTimeout(type, 1000);
    }
}

// Navbar Blur / Change color on Scroll
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar-glass');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 30) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
}

// Table Search, Sorting, and Pagination (Client-Side)
function initTableSearch() {
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(input => {
        const targetTableId = input.getAttribute('data-target');
        const table = document.getElementById(targetTableId);

        if (table) {
            const rows = table.querySelectorAll('tbody tr');

            input.addEventListener('keyup', function () {
                const filter = input.value.toLowerCase();

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    if (text.indexOf(filter) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
}

// Custom Toast/Notification Manager
const NotificationToast = {
    show: function (title, message, type = 'success', duration = 4000) {
        let toastContainer = document.getElementById('custom-toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'custom-toast-container';
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '25px';
            toastContainer.style.right = '25px';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = 'custom-toast';

        let typeIcon = 'bi-check-circle-fill';
        let typeColor = '#7DAE7B'; // Success Sage Green
        if (type === 'error') {
            typeIcon = 'bi-exclamation-triangle-fill';
            typeColor = '#dc3545';
        } else if (type === 'warning') {
            typeIcon = 'bi-exclamation-circle-fill';
            typeColor = '#ffc107';
        } else if (type === 'info') {
            typeIcon = 'bi-info-circle-fill';
            typeColor = '#0dcaf0';
        }

        toast.innerHTML = `
            <div class="custom-toast-header">
                <i class="bi ${typeIcon} me-2" style="color: ${typeColor}; font-size: 1.2rem;"></i>
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" onclick="this.closest('.custom-toast').remove()"></button>
            </div>
            <div class="custom-toast-body">${message}</div>
            <div class="custom-toast-bar" style="background: ${typeColor}; transition: transform ${duration}ms linear;"></div>
        `;

        toastContainer.appendChild(toast);

        // Force reflow
        toast.offsetHeight;

        // Show toast
        toast.classList.add('show');

        // Progress bar animation
        const bar = toast.querySelector('.custom-toast-bar');
        bar.style.transform = 'scaleX(0)';

        // Remove toast after duration
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, duration);
    }
};

// Expose to window for global access
window.NotificationToast = NotificationToast;
