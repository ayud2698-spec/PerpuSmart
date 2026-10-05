/**
 * PerpuSmart — Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar Toggle (Admin) ──────────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            // Update icon
            const icon = sidebarToggle.querySelector('[data-lucide]');
            if (icon) {
                const isOpen = sidebar.classList.contains('open');
                icon.setAttribute('data-lucide', isOpen ? 'panel-left-open' : 'panel-left-close');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function (e) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }

    // ── Auto-dismiss Alerts ────────────────────────────────
    const alerts = document.querySelectorAll('.ps-alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .5s, transform .5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ── Kode Buku Auto-uppercase ───────────────────────────
    const kodeInput = document.getElementById('kode_buku');
    if (kodeInput) {
        kodeInput.addEventListener('input', function () {
            const pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos);
        });
    }

    // ── Search Input — Live feedback ───────────────────────
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.closest('form').submit();
            }
        });
    }

    // ── Form Submission Loading State ──────────────────────
    const forms = document.querySelectorAll('.ps-auth-form, .ps-form');
    forms.forEach(function (form) {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('[type="submit"]');
            if (btn) {
                btn.disabled = true;
                const span = btn.querySelector('span');
                if (span) span.textContent = 'Memproses...';
                // Re-enable after 5s (failsafe)
                setTimeout(() => { btn.disabled = false; }, 5000);
            }
        });
    });

    // ── Confirm Delete Enhancement ────────────────────────
    document.querySelectorAll('form[onsubmit]').forEach(function (form) {
        // Already handled by inline onsubmit
    });

    // ── Table Row Click (makes whole row clickable for view) ─
    document.querySelectorAll('#bukuTable tbody tr').forEach(function (row) {
        row.style.cursor = 'pointer';
    });

    // ── Reinitialize Lucide after dynamic content ──────────
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // ── Animate stat numbers ───────────────────────────────
    document.querySelectorAll('.ps-stat-number, .ps-user-stat-num').forEach(function (el) {
        const target = parseInt(el.textContent.replace(/[^\d]/g, ''), 10);
        if (isNaN(target) || target === 0) return;
        let current = 0;
        const duration = 800;
        const step = target / (duration / 16);
        const timer = setInterval(function () {
            current = Math.min(current + step, target);
            el.textContent = Math.floor(current).toLocaleString('id-ID');
            if (current >= target) clearInterval(timer);
        }, 16);
    });

});