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

window.openWriteBlogModal = openWriteBlogModal;
window.closeWriteBlogModal = closeWriteBlogModal;

export function getYouTubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

export function promptVideoInsert() {
    const url = prompt('Enter Video or YouTube URL (e.g. https://www.youtube.com/watch?v=... or MP4 link):');
    if (!url) return;

    const editor = document.getElementById('rich-editor-area');
    if (!editor) return;

    const ytId = getYouTubeId(url);
    let html = '';

    if (ytId) {
        html = `
            <div class="my-3 p-2 bg-black/60 border border-indigo-500/30 rounded-2xl overflow-hidden shadow-xl max-w-full">
                <div class="relative w-full aspect-video rounded-xl overflow-hidden">
                    <iframe src="https://www.youtube.com/embed/${ytId}" class="w-full h-full border-0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
                <div class="p-2 text-[11px] text-gray-400 font-mono flex items-center justify-between">
                    <span>▶️ YouTube Embedded Video</span>
                    <a href="${url}" target="_blank" class="text-indigo-400 underline">Open Link 🔗</a>
                </div>
            </div>
            <p><br></p>
        `;
    } else {
        html = `
            <div class="my-3 p-2 bg-black/60 border border-white/15 rounded-2xl overflow-hidden shadow-xl max-w-full">
                <video controls src="${url}" class="w-full rounded-xl max-h-96"></video>
            </div>
            <p><br></p>
        `;
    }

    editor.focus();
    document.execCommand('insertHTML', false, html);
}

window.promptVideoInsert = promptVideoInsert;

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
                    if (url) {
                        const ytId = getYouTubeId(url);
                        if (ytId) {
                            promptVideoInsert();
                        } else {
                            document.execCommand(cmd, false, url);
                        }
                    }
                } else {
                    document.execCommand(cmd, false, val);
                }
                editor.focus();
            });
        });

        // Handle Paste event for automatic YouTube link conversion
        editor.addEventListener('paste', (e) => {
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const ytId = getYouTubeId(pastedText);
            if (ytId) {
                e.preventDefault();
                const html = `
                    <div class="my-3 p-2 bg-black/60 border border-indigo-500/30 rounded-2xl overflow-hidden shadow-xl max-w-full">
                        <div class="relative w-full aspect-video rounded-xl overflow-hidden">
                            <iframe src="https://www.youtube.com/embed/${ytId}" class="w-full h-full border-0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                        <div class="p-2 text-[11px] text-gray-400 font-mono flex items-center justify-between">
                            <span>▶️ YouTube Embedded Video</span>
                            <a href="${pastedText}" target="_blank" class="text-indigo-400 underline">Open Link 🔗</a>
                        </div>
                    </div>
                    <p><br></p>
                `;
                document.execCommand('insertHTML', false, html);
            }
        });

        // Sync content before form submission
        form.addEventListener('submit', () => {
            hiddenContent.value = editor.innerHTML;
        });
    }
});
