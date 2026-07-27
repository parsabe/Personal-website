/**
 * Rich Text Blog Editor ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById('rich-editor-area');
    const hiddenContent = document.getElementById('blog-content-input');
    const form = document.getElementById('blog-form');

    if (editor && hiddenContent && form) {
        // Toolbar actions
        document.querySelectorAll('.rich-tool-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const cmd = btn.getAttribute('data-cmd');
                const val = btn.getAttribute('data-val') || null;

                if (cmd === 'createLink') {
                    const url = prompt('Enter URL link:');
                    if (url) document.execCommand(cmd, false, url);
                } else {
                    document.execCommand(cmd, false, val);
                }
                editor.focus();
            });
        });

        // Sync content before form submission
        form.addEventListener('submit', () => {
            hiddenContent.value = editor.innerHTML;
        });
    }
});
