/**
 * Rich Text Blog Editor ESM Module
 */

export function openWriteBlogModal() {
    const modal = document.getElementById('write-blog-modal');
    if (modal) {
        modal.classList.remove('hidden');
        const editor = document.getElementById('rich-editor-area');
        if (editor) editor.focus();
    }
}

export function closeWriteBlogModal() {
    const modal = document.getElementById('write-blog-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

export function insertTableToEditor(rows = 3, cols = 3) {
    const editor = document.getElementById('rich-editor-area');
    if (!editor) return;

    let tableHtml = '<div class="overflow-x-auto my-3"><table class="w-full text-left border-collapse border border-white/20 rounded-xl overflow-hidden shadow-lg text-xs font-sans"><thead><tr>';
    for (let c = 1; c <= cols; c++) {
        tableHtml += `<th class="p-2.5 bg-indigo-900/50 border border-white/20 text-white font-bold">Header ${c}</th>`;
    }
    tableHtml += '</tr></thead><tbody>';

    for (let r = 1; r <= rows; r++) {
        tableHtml += '<tr>';
        for (let c = 1; c <= cols; c++) {
            tableHtml += `<td class="p-2 bg-black/40 border border-white/10 text-gray-200">Data ${r}-${c}</td>`;
        }
        tableHtml += '</tr>';
    }
    tableHtml += '</tbody></table></div><p><br></p>';

    document.execCommand('insertHTML', false, tableHtml);
}

window.openWriteBlogModal = openWriteBlogModal;
window.closeWriteBlogModal = closeWriteBlogModal;
window.insertTableToEditor = insertTableToEditor;

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
                } else if (cmd === 'insertTable') {
                    const rowsStr = prompt('Enter number of table rows:', '3');
                    const colsStr = prompt('Enter number of table columns:', '3');
                    const rows = parseInt(rowsStr) || 3;
                    const cols = parseInt(colsStr) || 3;
                    insertTableToEditor(rows, cols);
                } else if (cmd === 'insertUnorderedList') {
                    document.execCommand('insertUnorderedList', false, null);
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
