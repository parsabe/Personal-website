/**
 * Dynamic macOS Window Controls (Red, Yellow, Green) & Taskbar Integration
 */
document.addEventListener('DOMContentLoaded', () => {
    const mainContainer = document.getElementById('main-container') || document.querySelector('main') || document.querySelector('.main-window');
    
    // Find Red, Yellow, Green dot buttons
    const macDotsContainer = document.querySelector('.absolute.top-5.right-6, .mac-window-dots');
    
    if (!macDotsContainer) return;

    const dots = macDotsContainer.querySelectorAll('.rounded-full');
    const redDot = dots[0];    // Close
    const yellowDot = dots[1]; // Minimize
    const greenDot = dots[2];  // Maximize / Fullscreen

    let isMaximized = localStorage.getItem('mac_window_state') === 'maximized';
    let isMinimized = false;

    // Apply initial state if saved
    if (isMaximized && mainContainer) {
        applyMaximize(mainContainer);
    }

    // Red Dot (Close / Collapse Window)
    if (redDot) {
        redDot.style.cursor = 'pointer';
        redDot.title = 'Close Window';
        redDot.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!mainContainer) return;

            mainContainer.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            mainContainer.style.transform = 'scale(0.85) translateY(40px)';
            mainContainer.style.opacity = '0';
            mainContainer.style.pointerEvents = 'none';

            // Show floating reopen bar
            showReopenBar();
        });
    }

    // Yellow Dot (Minimize Window)
    if (yellowDot) {
        yellowDot.style.cursor = 'pointer';
        yellowDot.title = 'Minimize Window';
        yellowDot.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!mainContainer) return;

            isMinimized = !isMinimized;
            if (isMinimized) {
                mainContainer.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                mainContainer.style.transform = 'translateY(85vh) scale(0.6)';
                mainContainer.style.opacity = '0.3';
                highlightTaskbarActiveItem();
            } else {
                restoreWindow();
            }
        });
    }

    // Green Dot (Maximize / Toggle Fullscreen)
    if (greenDot) {
        greenDot.style.cursor = 'pointer';
        greenDot.title = 'Maximize / Expand Window';
        greenDot.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!mainContainer) return;

            isMaximized = !isMaximized;
            localStorage.setItem('mac_window_state', isMaximized ? 'maximized' : 'normal');

            if (isMaximized) {
                applyMaximize(mainContainer);
            } else {
                applyNormal(mainContainer);
            }
        });
    }

    function applyMaximize(elem) {
        elem.classList.remove('max-w-6xl', 'h-[88vh]', 'rounded-[2.5rem]', 'p-3', 'lg:p-8');
        elem.classList.add('w-full', 'max-w-full', 'h-screen', 'rounded-none', 'm-0', 'border-0');
        elem.style.transform = 'none';
        elem.style.opacity = '1';
        elem.style.pointerEvents = 'auto';
    }

    function applyNormal(elem) {
        elem.classList.remove('w-full', 'max-w-full', 'h-screen', 'rounded-none', 'm-0', 'border-0');
        elem.classList.add('max-w-6xl', 'h-[88vh]', 'rounded-[2.5rem]');
        elem.style.transform = 'none';
        elem.style.opacity = '1';
        elem.style.pointerEvents = 'auto';
    }

    function restoreWindow() {
        if (!mainContainer) return;
        mainContainer.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        if (isMaximized) {
            applyMaximize(mainContainer);
        } else {
            applyNormal(mainContainer);
        }
        hideReopenBar();
    }

    function showReopenBar() {
        let btn = document.getElementById('btn-reopen-window');
        if (!btn) {
            btn = document.createElement('button');
            btn.id = 'btn-reopen-window';
            btn.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs rounded-full shadow-2xl backdrop-blur-xl border border-white/20 animate-bounce cursor-pointer flex items-center gap-2';
            btn.innerHTML = '<span>🖥️</span> Restore Window';
            btn.onclick = restoreWindow;
            document.body.appendChild(btn);
        }
        btn.style.display = 'flex';
    }

    function hideReopenBar() {
        const btn = document.getElementById('btn-reopen-window');
        if (btn) btn.style.display = 'none';
    }

    function highlightTaskbarActiveItem() {
        const currentPath = window.location.pathname;
        const taskbarItems = document.querySelectorAll('.taskbar-item');
        taskbarItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                item.classList.add('ring-2', 'ring-indigo-400', 'bg-indigo-600/30');
            }
        });
    }

    // Expose restoreWindow globally
    window.restoreMacWindow = restoreWindow;
});
