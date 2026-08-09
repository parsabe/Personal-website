/**
 * Sidebar ESM Module
 */
export function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (!sidebar || !overlay) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
}

// Bind to window for inline onclick attributes
window.toggleSidebar = toggleSidebar;
