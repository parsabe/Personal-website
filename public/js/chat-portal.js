/**
 * Chat Portal Module (ESM) - Parsa Besharat
 */

const getCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
};

let selectedScheduledTime = null;
let mediaRecorder = null;
let audioChunks = [];
let videoRecorder = null;
let videoChunks = [];
let videoStream = null;
let lastMessageCount = 0;

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    const isAuth = document.body.dataset.authenticated === 'true';
    if (isAuth) {
        fetchMessages();
        fetchUsers();
        fetchStories();
        setInterval(() => {
            fetchMessages();
            fetchStories();
        }, 2000);
    }
});

// Fetch Messages & Notifications
export async function fetchMessages() {
    try {
        const response = await fetch('/chat/messages');
        const data = await response.json();

        if (data.status === 'success') {
            const stream = document.getElementById('messageStream');
            if (!stream) return;

            if (data.messages.length === 0) {
                stream.innerHTML = '<div class="text-center py-12 text-gray-400 text-xs animate-fade-in">No messages yet. Be the first to start chatting!</div>';
            } else {
                stream.innerHTML = data.messages.map(msg => renderMessageBubble(msg)).join('');

                if (lastMessageCount > 0 && data.messages.length > lastMessageCount) {
                    const newestMsg = data.messages[data.messages.length - 1];
                    if (!newestMsg.is_me) {
                        playNotificationSound();
                        showToastNotification(newestMsg);
                    }
                }
                lastMessageCount = data.messages.length;
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Render Message Bubble
export function renderMessageBubble(msg) {
    let contentHtml = '';

    if (msg.type === 'text') {
        contentHtml = `<p class="whitespace-pre-wrap">${escapeHtml(msg.message || '')}</p>`;
    } else if (msg.type === 'image' || (msg.mime_type && msg.mime_type.startsWith('image/'))) {
        contentHtml = `<img src="${msg.file_url}" class="max-w-xs max-h-60 rounded-2xl border border-white/10 cursor-pointer hover:opacity-90 transition transform hover:scale-105" onclick="window.open('${msg.file_url}', '_blank')">`;
    } else if (msg.type === 'gif') {
        contentHtml = `<img src="${msg.file_url}" class="max-w-xs max-h-48 rounded-2xl border border-white/10">`;
    } else if (msg.type === 'voice') {
        contentHtml = `<div class="flex items-center space-x-2"><span class="text-lg">🎙️</span><audio controls src="${msg.file_url}" class="h-8 w-48 sm:w-60"></audio></div>`;
    } else if (msg.type === 'video') {
        contentHtml = `<video controls src="${msg.file_url}" class="max-w-xs max-h-60 rounded-2xl border border-white/10"></video>`;
    } else if (msg.type === 'file') {
        contentHtml = `
            <div class="flex items-center space-x-3 p-2.5 bg-black/20 rounded-2xl border border-white/10">
                <span class="text-2xl">📄</span>
                <div class="overflow-hidden">
                    <p class="font-semibold text-xs truncate max-w-[180px]">${escapeHtml(msg.file_name || 'Attachment')}</p>
                    <p class="text-[10px] text-gray-400">${msg.file_size || ''}</p>
                </div>
                <a href="${msg.file_url}" download class="px-2.5 py-1 bg-blue-600 hover:bg-blue-500 rounded-xl text-[10px] font-bold text-white">Download</a>
            </div>`;
    }

    const reactionPills = msg.reactions.map(r => {
        const activeClass = r.user_reacted ? 'bg-blue-600/40 border-blue-400 text-white font-bold' : 'bg-black/30 border-white/10 text-gray-300';
        return `<button onclick="window.chatPortal.toggleReaction(${msg.id}, '${r.emoji}')" class="px-2 py-0.5 rounded-full border text-[11px] flex items-center space-x-1 ${activeClass} transition transform hover:scale-110">
            <span>${r.emoji}</span><span>${r.count}</span>
        </button>`;
    }).join('');

    const reactionPickerBar = `
        <div class="flex items-center space-x-1 mt-1 opacity-90 hover:opacity-100">
            <button onclick="window.chatPortal.toggleReaction(${msg.id}, '❤️')" class="hover:scale-125 transition text-xs">❤️</button>
            <button onclick="window.chatPortal.toggleReaction(${msg.id}, '👍')" class="hover:scale-125 transition text-xs">👍</button>
            <button onclick="window.chatPortal.toggleReaction(${msg.id}, '🔥')" class="hover:scale-125 transition text-xs">🔥</button>
            <button onclick="window.chatPortal.toggleReaction(${msg.id}, '😂')" class="hover:scale-125 transition text-xs">😂</button>
            <button onclick="window.chatPortal.toggleReaction(${msg.id}, '🚀')" class="hover:scale-125 transition text-xs">🚀</button>
        </div>`;

    const alignClass = msg.is_me ? 'justify-end' : 'justify-start';
    const bgClass = msg.is_me 
        ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-br-none' 
        : 'bg-white/10 text-gray-100 rounded-bl-none border border-white/10';

    return `
        <div class="flex ${alignClass} mb-2 animate-scale-up">
            <div class="flex items-start space-x-2 max-w-[88%] sm:max-w-md">
                ${!msg.is_me ? `<img src="${msg.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover mt-1">` : ''}
                <div>
                    <div class="p-3 rounded-2xl ${bgClass} shadow-md text-xs">
                        <div class="flex items-center justify-between text-[10px] opacity-75 mb-1 space-x-3">
                            <span class="font-bold">${escapeHtml(msg.sender_name)}</span>
                            <span>${msg.created_at}</span>
                        </div>
                        ${contentHtml}
                    </div>
                    <div class="flex items-center space-x-1 mt-1">
                        ${reactionPills}
                        ${reactionPickerBar}
                    </div>
                </div>
            </div>
        </div>`;
}

// Toggle Emoji Reaction
export async function toggleReaction(msgId, emoji) {
    try {
        await fetch('/chat/react', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
            body: JSON.stringify({ chat_message_id: msgId, emoji: emoji })
        });
        fetchMessages();
    } catch (err) {
        console.error(err);
    }
}

// Fetch Users List
export async function fetchUsers() {
    try {
        const response = await fetch('/chat/users');
        const data = await response.json();
        if (data.status === 'success') {
            const container = document.getElementById('usersListContainer');
            if (container) {
                container.innerHTML = data.users.map(u => `
                    <div class="flex items-center space-x-2.5 p-2 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition transform hover:scale-[1.02]">
                        <img src="${u.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover">
                        <div class="overflow-hidden flex-1">
                            <p class="font-semibold text-xs text-white truncate">${escapeHtml(u.name)}</p>
                            <p class="text-[10px] text-gray-400 truncate">@ ${escapeHtml(u.username)}</p>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Fetch Stories
export async function fetchStories() {
    try {
        const response = await fetch('/chat/stories');
        const data = await response.json();
        if (data.status === 'success') {
            const container = document.getElementById('storiesContainer');
            if (container) {
                container.innerHTML = data.stories.map(s => `
                    <div class="flex flex-col items-center space-y-1 cursor-pointer shrink-0 animate-scale-up" onclick="alert('${escapeHtml(s.user_name)}: ${escapeHtml(s.content || '')}')">
                        <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-tr from-amber-400 via-rose-500 to-purple-600 transition transform hover:scale-110">
                            <img src="${s.avatar_url}" class="w-full h-full rounded-full object-cover border-2 border-gray-900">
                        </div>
                        <span class="text-[10px] text-gray-300 truncate max-w-[50px]">${escapeHtml(s.user_name)}</span>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Send Text Message
export async function dispatchMessage() {
    const input = document.getElementById('chatInput');
    if (!input) return;
    const text = input.value.trim();
    if (!text && !selectedScheduledTime) return;

    const payload = { message: text, type: 'text', scheduled_at: selectedScheduledTime };
    input.value = '';
    clearSchedule();

    try {
        await fetch('/chat/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
            body: JSON.stringify(payload)
        });
        fetchMessages();
    } catch (err) {
        console.error(err);
    }
}

// Handle File Select
export async function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    let type = 'file';
    if (file.type.startsWith('image/')) type = 'image';

    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', type);
    if (selectedScheduledTime) formData.append('scheduled_at', selectedScheduledTime);

    showUploadProgress(true, 10);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/chat/upload', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', getCsrfToken());
    xhr.upload.onprogress = e => {
        if (e.lengthComputable) showUploadProgress(true, Math.round((e.loaded / e.total) * 100));
    };
    xhr.onload = () => {
        showUploadProgress(false, 100);
        clearSchedule();
        fetchMessages();
    };
    xhr.send(formData);
}

// Voice Note Functions
export async function startVoiceRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
        mediaRecorder.start();
        document.getElementById('voiceRecorderBar')?.classList.remove('hidden');
    } catch (err) {
        alert('Microphone permission required.');
    }
}

export function stopAndSendVoiceRecording() {
    if (!mediaRecorder) return;
    mediaRecorder.onstop = async () => {
        const blob = new Blob(audioChunks, { type: 'audio/webm' });
        const file = new File([blob], 'voicenote.webm', { type: 'audio/webm' });
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'voice');
        await fetch('/chat/upload', { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrfToken() }, body: formData });
        document.getElementById('voiceRecorderBar')?.classList.add('hidden');
        fetchMessages();
    };
    mediaRecorder.stop();
}

export function cancelVoiceRecording() {
    if (mediaRecorder) mediaRecorder.stop();
    document.getElementById('voiceRecorderBar')?.classList.add('hidden');
}

// Video Note Recording
export async function openVideoNoteModal() {
    document.getElementById('videoNoteModal')?.classList.remove('hidden');
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        const videoPreview = document.getElementById('videoPreview');
        if (videoPreview) videoPreview.srcObject = videoStream;
    } catch (err) {
        alert('Camera permission required.');
    }
}

export function closeVideoNoteModal() {
    if (videoStream) videoStream.getTracks().forEach(t => t.stop());
    document.getElementById('videoNoteModal')?.classList.add('hidden');
}

export function startVideoRecording() {
    if (!videoStream) return;
    videoRecorder = new MediaRecorder(videoStream);
    videoChunks = [];
    videoRecorder.ondataavailable = e => videoChunks.push(e.data);
    videoRecorder.start();
    document.getElementById('recordVideoBtn')?.classList.add('hidden');
    document.getElementById('stopVideoBtn')?.classList.remove('hidden');
}

export function stopVideoRecording() {
    if (!videoRecorder) return;
    videoRecorder.onstop = async () => {
        const blob = new Blob(videoChunks, { type: 'video/webm' });
        const file = new File([blob], 'videonote.webm', { type: 'video/webm' });
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'video');
        await fetch('/chat/upload', { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrfToken() }, body: formData });
        closeVideoNoteModal();
        fetchMessages();
    };
    videoRecorder.stop();
}

// Profile & Story Forms
export async function saveProfileSettings(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('profileForm'));
    try {
        const res = await fetch('/chat/profile', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': getCsrfToken() },
            body: formData
        });
        const data = await res.json();
        if (data.status === 'success') {
            alert('Profile updated successfully!');
            toggleProfileModal();
            location.reload();
        } else {
            alert(data.message || 'Error updating profile');
        }
    } catch (err) {
        console.error(err);
    }
}

export async function submitStory(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('storyForm'));
    try {
        await fetch('/chat/stories', { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrfToken() }, body: formData });
        toggleAddStoryModal();
        fetchStories();
    } catch (err) {
        console.error(err);
    }
}

// Audio Chime & Toast Notifications
export function playNotificationSound() {
    if (!document.getElementById('soundToggle')?.checked) return;
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, ctx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15);
        gain.gain.setValueAtTime(0.15, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.3);
    } catch (e) {}
}

export function showToastNotification(msg) {
    const toast = document.getElementById('toastNotification');
    if (!toast) return;
    document.getElementById('toastAvatar').src = msg.avatar_url;
    document.getElementById('toastSender').innerText = msg.sender_name;
    document.getElementById('toastMessage').innerText = msg.message || (msg.type + ' attachment');
    toast.classList.remove('hidden');
    setTimeout(hideToastNotification, 4500);
}

export function hideToastNotification() {
    document.getElementById('toastNotification')?.classList.add('hidden');
}

// UI Toggles & Modals
export function switchGateTab(tab) {
    if (tab === 'login') {
        document.getElementById('gateLoginForm')?.classList.remove('hidden');
        document.getElementById('gateRegisterForm')?.classList.add('hidden');
        document.getElementById('gateLoginTab').className = 'flex-1 py-2.5 rounded-xl bg-blue-600 text-white transition';
        document.getElementById('gateRegisterTab').className = 'flex-1 py-2.5 rounded-xl text-gray-400 hover:text-white transition';
    } else {
        document.getElementById('gateLoginForm')?.classList.add('hidden');
        document.getElementById('gateRegisterForm')?.classList.remove('hidden');
        document.getElementById('gateLoginTab').className = 'flex-1 py-2.5 rounded-xl text-gray-400 hover:text-white transition';
        document.getElementById('gateRegisterTab').className = 'flex-1 py-2.5 rounded-xl bg-indigo-600 text-white transition';
    }
}

export function toggleContactsSidebar() { document.getElementById('contactsSidebar')?.classList.toggle('hidden'); }
export function toggleProfileModal() { document.getElementById('profileModal')?.classList.toggle('hidden'); }
export function toggleAddStoryModal() { document.getElementById('addStoryModal')?.classList.toggle('hidden'); }
export function toggleSettingsModal() { document.getElementById('settingsModal')?.classList.toggle('hidden'); }
export function toggleEmojiPicker() { document.getElementById('emojiPicker')?.classList.toggle('hidden'); }
export function toggleGifPicker() { document.getElementById('gifPicker')?.classList.toggle('hidden'); }
export function toggleScheduleModal() { document.getElementById('scheduleModal')?.classList.toggle('hidden'); }

export function startAudioCall() { document.getElementById('audioCallModal')?.classList.remove('hidden'); }
export function endAudioCall() { document.getElementById('audioCallModal')?.classList.add('hidden'); }

export function changeTheme(t) {
    const el = document.getElementById('chatBoxContainer');
    if (!el) return;
    el.classList.remove('theme-cyberpunk', 'theme-emerald', 'theme-sunset', 'theme-light');
    if (t !== 'sapphire') el.classList.add(`theme-${t}`);
}

export function addEmoji(e) { 
    const input = document.getElementById('chatInput');
    if (input) input.value += e; 
    toggleEmojiPicker(); 
}

export async function sendGif(url) {
    toggleGifPicker();
    await fetch('/chat/send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
        body: JSON.stringify({ type: 'gif', file_url: url })
    });
    fetchMessages();
}

export function applySchedule() {
    const val = document.getElementById('scheduleDateTime')?.value;
    if (val) {
        selectedScheduledTime = val;
        document.getElementById('scheduledNotice')?.classList.remove('hidden');
        document.getElementById('scheduledTimeLabel').innerText = val;
    }
    toggleScheduleModal();
}

export function clearSchedule() { 
    selectedScheduledTime = null; 
    document.getElementById('scheduledNotice')?.classList.add('hidden'); 
}

export function showUploadProgress(show, percent) {
    const el = document.getElementById('uploadProgressBarContainer');
    if (!el) return;
    if (show) {
        el.classList.remove('hidden');
        document.getElementById('uploadProgressBar').style.width = percent + '%';
        document.getElementById('uploadPercentText').innerText = percent + '%';
    } else { 
        el.classList.add('hidden'); 
    }
}

export function handleKeyPress(e) { 
    if (e.key === 'Enter' && !e.shiftKey) { 
        e.preventDefault(); 
        dispatchMessage(); 
    } 
}

export function previewAvatarImage(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => document.getElementById('profileAvatarPreview').src = ev.target.result;
        reader.readAsDataURL(file);
    }
}

export function escapeHtml(t) { 
    return String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); 
}

// Global Namespace Export for HTML Inline Attributes
window.chatPortal = {
    fetchMessages,
    renderMessageBubble,
    toggleReaction,
    fetchUsers,
    fetchStories,
    dispatchMessage,
    handleFileSelect,
    startVoiceRecording,
    stopAndSendVoiceRecording,
    cancelVoiceRecording,
    openVideoNoteModal,
    closeVideoNoteModal,
    startVideoRecording,
    stopVideoRecording,
    saveProfileSettings,
    submitStory,
    playNotificationSound,
    showToastNotification,
    hideToastNotification,
    switchGateTab,
    toggleContactsSidebar,
    toggleProfileModal,
    toggleAddStoryModal,
    toggleSettingsModal,
    toggleEmojiPicker,
    toggleGifPicker,
    toggleScheduleModal,
    startAudioCall,
    endAudioCall,
    changeTheme,
    addEmoji,
    sendGif,
    applySchedule,
    clearSchedule,
    showUploadProgress,
    handleKeyPress,
    previewAvatarImage,
    escapeHtml
};

// Bind to window root for direct inline onclick handlers
Object.assign(window, window.chatPortal);
