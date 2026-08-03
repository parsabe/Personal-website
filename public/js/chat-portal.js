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

let selectedRecipient = null;
let allUsersList = [];

let knownNotifiedMsgIds = new Set();
let previousMessagesHash = '';

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    const isAuth = document.body.dataset.authenticated === 'true';
    if (isAuth) {
        fetchUsers();
        fetchMessages();
        setInterval(() => {
            fetchMessages();
        }, 2000);
        setInterval(() => {
            fetchUsers();
        }, 10000);
    }

    // Auto-open Profile Settings modal if action=profile in query params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'profile') {
        const profileModal = document.getElementById('profileModal');
        if (profileModal) {
            profileModal.classList.remove('hidden');
        }
    }
});

// Select Recipient User Function (Switches to Direct Chat Screen)
export function selectChatUser(userIdOrObj) {
    let userObj = typeof userIdOrObj === 'object' ? userIdOrObj : allUsersList.find(u => u.id == userIdOrObj);
    if (!userObj && typeof userIdOrObj === 'number') {
        userObj = { id: userIdOrObj, name: `User #${userIdOrObj}`, username: `user${userIdOrObj}`, avatar_url: '/images/default-avatar.svg' };
    }
    if (!userObj) return;

    selectedRecipient = userObj;

    // Switch view to Active Chat Screen
    const directoryScreen = document.getElementById('userDirectoryScreen');
    const chatScreen = document.getElementById('activeChatScreen');
    const backBtn = document.getElementById('btnBackToUsers');

    if (directoryScreen) directoryScreen.classList.add('hidden');
    if (chatScreen) chatScreen.classList.remove('hidden');
    if (backBtn) backBtn.classList.remove('hidden');

    // Focus input
    const chatInput = document.getElementById('chatInput');
    if (chatInput) {
        chatInput.placeholder = `Message ${userObj.name}...`;
        chatInput.focus();
    }

    // Update Header UI
    const nameElem = document.getElementById('activeContactName');
    const userElem = document.getElementById('activeContactUsername');
    const avatarElem = document.getElementById('activeContactAvatar');
    const statusElem = document.getElementById('activeContactStatus');
    const dotElem = document.getElementById('activeContactDot');
    const callBtn = document.getElementById('btnCallUser');

    if (userObj.is_me) {
        if (nameElem) nameElem.textContent = '📌 Saved Messages';
        if (userElem) userElem.textContent = '@notes';
        if (avatarElem) avatarElem.src = userObj.avatar_url || '/images/default-avatar.svg';
        if (statusElem) statusElem.innerHTML = '<span class="text-amber-400 font-semibold">Personal Storage & Notes</span>';
        if (chatInput) chatInput.placeholder = 'Save notes, links, photos, files or voice notes...';
    } else {
        if (nameElem) nameElem.textContent = userObj.name;
        if (userElem) userElem.textContent = `@${userObj.username || 'user'}`;
        if (avatarElem) avatarElem.src = userObj.avatar_url || '/images/default-avatar.svg';
        if (statusElem) statusElem.innerHTML = '<span class="text-emerald-400">Online &bull; Direct Message</span>';
        if (chatInput) chatInput.placeholder = `Message ${userObj.name}...`;
    }

    if (dotElem) dotElem.className = 'absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-gray-900 animate-pulse';

    if (callBtn) {
        if (userObj.is_me) {
            callBtn.classList.add('hidden');
            callBtn.classList.remove('flex');
        } else {
            callBtn.classList.remove('hidden');
            callBtn.classList.add('flex');
        }
    }

    const profileBtnText = document.getElementById('btnHeaderProfileText');
    if (profileBtnText) {
        profileBtnText.textContent = userObj.is_me ? 'My Profile' : `${userObj.name}'s Profile`;
    }

    // Restore window if minimized
    if (window.restoreMacWindow) window.restoreMacWindow();

    previousMessagesHash = '';
    lastMessageCount = 0;
    fetchMessages();
}

// Back to User Directory List Screen
export function backToUserDirectory() {
    selectedRecipient = null;

    const directoryScreen = document.getElementById('userDirectoryScreen');
    const chatScreen = document.getElementById('activeChatScreen');
    const backBtn = document.getElementById('btnBackToUsers');
    const callBtn = document.getElementById('btnCallUser');

    if (chatScreen) chatScreen.classList.add('hidden');
    if (directoryScreen) directoryScreen.classList.remove('hidden');
    if (backBtn) backBtn.classList.add('hidden');

    if (callBtn) {
        callBtn.classList.add('hidden');
        callBtn.classList.remove('flex');
    }

    // Reset Header UI
    const nameElem = document.getElementById('activeContactName');
    const userElem = document.getElementById('activeContactUsername');
    const statusElem = document.getElementById('activeContactStatus');

    if (nameElem) nameElem.textContent = 'Members Directory';
    if (userElem) userElem.textContent = '(@all)';
    const profileBtnText = document.getElementById('btnHeaderProfileText');
    if (profileBtnText) {
        profileBtnText.textContent = 'My Profile';
    }
    if (statusElem) statusElem.textContent = 'Select a user below to start chatting';
}

// Fetch Messages & Notifications
export async function fetchMessages() {
    try {
        const url = selectedRecipient ? `/chat/messages?recipient_id=${selectedRecipient.id}` : '/chat/messages';
        const response = await fetch(url);
        const data = await response.json();

        if (data.status === 'success') {
            // Always update unread badges across Dock & Members list regardless of selected recipient
            updateUnreadBadges(data.unread_counts || {});

            // Handle unread messages notifications (toasts & sound)
            if (data.unread_messages && data.unread_messages.length > 0) {
                data.unread_messages.forEach(msg => {
                    if (!knownNotifiedMsgIds.has(msg.id)) {
                        knownNotifiedMsgIds.add(msg.id);
                        playNotificationSound();
                        showToastNotification(msg);
                    }
                });
            }

            const stream = document.getElementById('messageStream');
            if (!stream) return;

            // Remove loading indicator if present
            const loader = document.getElementById('loadingIndicator');
            if (loader) loader.remove();

            if (!selectedRecipient) {
                return; // On directory screen
            }

            const currentHash = JSON.stringify(data.messages.map(m => ({ id: m.id, msg: m.message, file: m.file_url, reactions: m.reactions })));
            if (currentHash !== previousMessagesHash) {
                const wasAtBottom = stream.scrollHeight - stream.scrollTop <= stream.clientHeight + 100;

                if (data.messages.length === 0) {
                    stream.innerHTML = `<div class="text-center py-16 text-gray-400 text-xs font-mono">No messages yet with ${escapeHtml(selectedRecipient.name)}. Start chatting below!</div>`;
                } else {
                    const isNewMessageAdded = data.messages.length > lastMessageCount;
                    stream.innerHTML = data.messages.map(msg => renderMessageBubble(msg)).join('');

                    if (isNewMessageAdded) {
                        const newest = data.messages[data.messages.length - 1];
                        if (!newest.is_me && !knownNotifiedMsgIds.has(newest.id)) {
                            knownNotifiedMsgIds.add(newest.id);
                            playNotificationSound();
                            showToastNotification(newest);
                        }
                    }

                    if (wasAtBottom || isNewMessageAdded) {
                        stream.scrollTop = stream.scrollHeight;
                    }
                }
                lastMessageCount = data.messages.length;
                previousMessagesHash = currentHash;
            }
        }
    } catch (err) {
        console.error('fetchMessages error:', err);
    }
}

// Render Message Bubble
export function renderMessageBubble(msg) {
    let contentHtml = '';
    const fileUrl = msg.file_url || msg.file_path || '';
    const fileName = msg.file_name || (fileUrl ? fileUrl.split('/').pop() : 'Attachment');
    const fileSize = msg.file_size || '';

    if (msg.type === 'text') {
        contentHtml = `<p class="whitespace-pre-wrap leading-relaxed">${escapeHtml(msg.message || '')}</p>`;
    } else if (msg.type === 'image' || (msg.mime_type && msg.mime_type.startsWith('image/'))) {
        contentHtml = `
            ${msg.message ? `<p class="whitespace-pre-wrap mb-2 leading-relaxed">${escapeHtml(msg.message)}</p>` : ''}
            <img src="${fileUrl}" class="max-w-xs max-h-60 rounded-2xl border border-white/10 cursor-pointer hover:opacity-90 transition transform hover:scale-105 shadow-lg object-cover" onclick="window.open('${fileUrl}', '_blank')">`;
    } else if (msg.type === 'gif') {
        contentHtml = `
            ${msg.message ? `<p class="whitespace-pre-wrap mb-2 leading-relaxed">${escapeHtml(msg.message)}</p>` : ''}
            <img src="${fileUrl}" class="max-w-xs max-h-48 rounded-2xl border border-white/10 shadow-lg">`;
    } else if (msg.type === 'voice') {
        contentHtml = `
            ${msg.message ? `<p class="whitespace-pre-wrap mb-2 leading-relaxed">${escapeHtml(msg.message)}</p>` : ''}
            <div class="flex items-center space-x-2"><span class="text-lg">🎙️</span><audio controls src="${fileUrl}" class="h-8 w-48 sm:w-60"></audio></div>`;
    } else if (msg.type === 'video') {
        contentHtml = `
            ${msg.message ? `<p class="whitespace-pre-wrap mb-2 leading-relaxed">${escapeHtml(msg.message)}</p>` : ''}
            <video controls src="${fileUrl}" class="max-w-xs max-h-60 rounded-2xl border border-white/10 shadow-lg"></video>`;
    } else if (msg.type === 'file' || fileUrl) {
        contentHtml = `
            ${msg.message ? `<p class="whitespace-pre-wrap mb-2 leading-relaxed">${escapeHtml(msg.message)}</p>` : ''}
            <div class="flex items-center space-x-3 p-3 bg-black/30 rounded-2xl border border-white/15 shadow-inner">
                <span class="text-2xl">📄</span>
                <div class="overflow-hidden flex-1">
                    <p class="font-bold text-xs truncate max-w-[180px] text-white">${escapeHtml(fileName)}</p>
                    ${fileSize ? `<p class="text-[10px] text-gray-400 font-mono">${fileSize}</p>` : ''}
                </div>
                <a href="${fileUrl}" target="_blank" download class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-[10px] font-bold text-white shadow transition">Download</a>
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
            <button onclick="window.chatPortal.deleteSingleMessage(${msg.id})" class="hover:scale-125 transition text-xs text-rose-400 hover:text-rose-300 ml-1.5" title="Delete for both users">🗑️</button>
        </div>`;

    const alignClass = msg.is_me ? 'justify-end' : 'justify-start';
    const bgClass = msg.is_me 
        ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-br-none' 
        : 'bg-white/10 text-gray-100 rounded-bl-none border border-white/10';

    return `
        <div class="flex ${alignClass} mb-2">
            <div class="flex items-start space-x-2 max-w-[88%] sm:max-w-md">
                ${!msg.is_me ? `<img src="${msg.avatar_url}" onclick="window.chatPortal.openPublicProfileModal(${msg.user_id})" class="w-8 h-8 rounded-full border border-white/20 object-cover mt-1 cursor-pointer hover:scale-110 transition shadow-md" title="View ${escapeHtml(msg.sender_name)}'s Profile">` : ''}
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

let previousUsersHash = '';

// Fetch Users List & Render Main Directory & macOS Chat Dock
export async function fetchUsers() {
    try {
        const response = await fetch('/chat/users');
        const data = await response.json();
        if (data.status === 'success') {
            allUsersList = data.users || [];

            const usersHash = JSON.stringify(allUsersList);
            if (usersHash !== previousUsersHash) {
                previousUsersHash = usersHash;
                renderUsersGrid(allUsersList);
                renderMacChatDock(allUsersList);
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Render Users Grid on Main Directory Screen (Saved Messages Pinned #1)
export function renderUsersGrid(users) {
    const grid = document.getElementById('mainUsersGrid');
    if (!grid) return;

    if (!users || users.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center py-10 text-xs text-gray-400 font-mono">No members found.</div>';
        return;
    }

    // Always sort so Saved Messages (u.is_me) is pinned at index 0
    const sortedUsers = [...users].sort((a, b) => (b.is_me ? 1 : 0) - (a.is_me ? 1 : 0));

    grid.innerHTML = sortedUsers.map(u => {
        if (u.is_me) {
            return `
                <div onclick="window.selectChatUser(${u.id})" class="col-span-full p-4 rounded-2xl bg-gradient-to-r from-amber-500/20 via-indigo-600/20 to-purple-600/20 border border-amber-400/40 hover:border-amber-400/80 cursor-pointer transition-all duration-300 transform hover:-translate-y-0.5 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 group">
                    <div class="flex items-center space-x-3.5">
                        <div class="relative shrink-0">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-indigo-600 flex items-center justify-center text-2xl shadow-lg border border-white/20 group-hover:scale-105 transition-transform">
                                📌
                            </div>
                            <span class="unread-badge-${u.id} hidden absolute -top-1 -right-1 w-5 h-5 bg-rose-600 text-white rounded-full text-[10px] font-bold flex items-center justify-center border border-gray-900 animate-bounce">🔴</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-white flex items-center gap-2 group-hover:text-amber-300 transition-colors">
                                <span>📌 Saved Messages</span>
                                <span class="px-2 py-0.5 bg-amber-500/30 text-amber-300 border border-amber-400/40 rounded-full text-[9px] font-mono font-bold">PINNED</span>
                            </h3>
                            <p class="text-xs text-gray-300">Your personal cloud storage, notes to self & bookmarks</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-amber-500/30 border border-amber-400/50 text-amber-200 font-bold rounded-xl text-xs flex items-center justify-center space-x-1.5 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-md shrink-0">
                        <span>🔖 Open Storage</span>
                    </div>
                </div>`;
        }

        return `
            <div onclick="window.selectChatUser(${u.id})" class="p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-blue-600/20 hover:border-blue-400/50 cursor-pointer transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl group space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="relative shrink-0">
                        <img src="${u.avatar_url}" class="w-12 h-12 rounded-full border-2 border-white/20 object-cover shadow-md group-hover:scale-105 transition-transform">
                        <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-gray-900 animate-pulse"></span>
                        <span class="unread-badge-${u.id} hidden absolute -top-1 -right-1 w-5 h-5 bg-rose-600 text-white rounded-full text-[10px] font-bold flex items-center justify-center border border-gray-900 animate-bounce">🔴</span>
                    </div>
                    <div class="overflow-hidden flex-1">
                        <h3 class="font-bold text-sm text-white truncate group-hover:text-blue-400 transition-colors">
                            ${escapeHtml(u.name)}
                        </h3>
                        <p class="text-xs text-gray-400 truncate">@${escapeHtml(u.username)}</p>
                    </div>
                </div>

                <p class="text-xs text-gray-300 line-clamp-2 min-h-[32px]">${escapeHtml(u.bio || 'Member')}</p>

                <button class="w-full py-2 bg-blue-600/40 border border-blue-400/40 text-blue-300 font-bold rounded-xl text-xs flex items-center justify-center space-x-1.5 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-md">
                    <span>💬 Start Direct Chat</span>
                </button>
            </div>`;
    }).join('');
}

// Live Search Filter for Users Directory
export function filterUsersDirectory() {
    const query = (document.getElementById('searchUsersInput')?.value || '').toLowerCase().trim();
    if (!query) {
        renderUsersGrid(allUsersList);
        return;
    }
    const filtered = allUsersList.filter(u => u.name.toLowerCase().includes(query) || u.username.toLowerCase().includes(query));
    renderUsersGrid(filtered);
}

// Render Users in macOS Dock Bar at Bottom (Only users with active chat history)
export function renderMacChatDock(users) {
    const dockContainer = document.getElementById('mac-chat-users-dock');
    if (!dockContainer) return;

    // Filter to only non-me users who have active chat history (has_chatted = true)
    const activeChatUsers = (users || []).filter(u => !u.is_me && u.has_chatted);

    if (activeChatUsers.length === 0) {
        dockContainer.innerHTML = '';
        return;
    }

    dockContainer.innerHTML = activeChatUsers.map(u => `
        <button onclick="window.selectChatUser(${u.id})" title="Chat with ${escapeHtml(u.name)}" 
           class="taskbar-user-item p-1.5 rounded-2xl hover:bg-white/15 transition-all group relative flex items-center justify-center">
            <div class="relative">
                <img src="${u.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover group-hover:scale-125 transition-transform shadow-md">
                <span class="dock-unread-badge-${u.id} hidden absolute -top-1 -right-1 w-3.5 h-3.5 bg-rose-600 rounded-full border border-gray-900 animate-bounce"></span>
            </div>
            <span class="absolute -top-9 px-2.5 py-1 bg-black/90 text-[10px] font-bold text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-xl border border-white/15">
                ${escapeHtml(u.name)}
            </span>
        </button>
    `).join('');
}

// Update Unread Badges in UI
export function updateUnreadBadges(counts) {
    allUsersList.forEach(u => {
        const count = counts[u.id] || 0;
        const listBadge = document.querySelector(`.unread-badge-${u.id}`);
        const dockBadge = document.querySelector(`.dock-unread-badge-${u.id}`);

        if (listBadge) {
            if (count > 0) {
                listBadge.textContent = count;
                listBadge.classList.remove('hidden');
            } else {
                listBadge.classList.add('hidden');
            }
        }

        if (dockBadge) {
            if (count > 0) {
                dockBadge.classList.remove('hidden');
            } else {
                dockBadge.classList.add('hidden');
            }
        }
    });
}

// Fetch Stories (Grouped by User for Instagram Story Tray)
let storyUsersList = [];
let activeUserIndex = 0;
let activeStoryIndex = 0;
let countdownInterval = null;

export async function fetchStories() {
    try {
        const response = await fetch('/chat/stories');
        const data = await response.json();
        if (data.status === 'success') {
            storyUsersList = data.story_users || [];
            const container = document.getElementById('storiesContainer');
            if (container) {
                if (storyUsersList.length === 0) {
                    container.innerHTML = '<span class="text-[10px] text-gray-500 italic p-1">No active stories</span>';
                    return;
                }

                container.innerHTML = storyUsersList.map((u, idx) => `
                    <div class="flex flex-col items-center space-y-1 cursor-pointer shrink-0 animate-scale-up group" onclick="window.openStoryGroupViewer(${idx})">
                        <div class="w-11 h-11 rounded-full p-0.5 bg-gradient-to-tr from-amber-400 via-rose-500 to-purple-600 transition transform group-hover:scale-110 shadow-md">
                            <img src="${u.avatar_url}" class="w-full h-full rounded-full object-cover border-2 border-gray-900">
                        </div>
                        <span class="text-[10px] text-gray-300 truncate max-w-[55px] font-semibold">${escapeHtml(u.user_name)}</span>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

export function openStoryGroupViewer(userIndex) {
    if (!storyUsersList[userIndex]) return;
    activeUserIndex = userIndex;
    activeStoryIndex = 0;

    const modal = document.getElementById('instagramStoryPlayerModal');
    if (modal) modal.classList.remove('hidden');
    renderCurrentStory();
}

export function closeStoryPlayerModal() {
    const modal = document.getElementById('instagramStoryPlayerModal');
    if (modal) modal.classList.add('hidden');
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
}

export function nextStory() {
    const user = storyUsersList[activeUserIndex];
    if (!user) return;
    if (activeStoryIndex < user.stories.length - 1) {
        activeStoryIndex++;
        renderCurrentStory();
    } else if (activeUserIndex < storyUsersList.length - 1) {
        activeUserIndex++;
        activeStoryIndex = 0;
        renderCurrentStory();
    } else {
        closeStoryPlayerModal();
    }
}

export function prevStory() {
    if (activeStoryIndex > 0) {
        activeStoryIndex--;
        renderCurrentStory();
    } else if (activeUserIndex > 0) {
        activeUserIndex--;
        const user = storyUsersList[activeUserIndex];
        activeStoryIndex = user ? user.stories.length - 1 : 0;
        renderCurrentStory();
    }
}

function renderCurrentStory() {
    const user = storyUsersList[activeUserIndex];
    if (!user || !user.stories[activeStoryIndex]) return;
    const story = user.stories[activeStoryIndex];

    const playerUserAvatar = document.getElementById('storyPlayerUserAvatar');
    const playerUserName = document.getElementById('storyPlayerUserName');
    const playerTime = document.getElementById('storyPlayerTime');
    const playerBody = document.getElementById('storyPlayerBody');

    if (playerUserAvatar) playerUserAvatar.src = user.avatar_url;
    if (playerUserName) playerUserName.innerText = user.user_name;
    if (playerTime) playerTime.innerText = story.created_at;

    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }

    let mediaContent = '';
    if (story.media_url) {
        mediaContent = `<img src="${story.media_url}" class="w-full max-h-80 object-cover rounded-2xl border border-white/10 shadow-lg">`;
    }

    let countdownWidget = '';
    if (story.story_type === 'countdown' && story.countdown_target_at) {
        countdownWidget = `
            <div class="p-3 rounded-2xl bg-gradient-to-r from-amber-500/20 via-rose-500/20 to-purple-500/20 border border-amber-400/40 text-center font-mono space-y-1 shadow-xl">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">⏳ LIVE EVENT COUNTDOWN</span>
                <span id="liveCountdownClock" class="text-lg font-extrabold text-white tracking-widest block">Calculating...</span>
            </div>
        `;
    }

    if (playerBody) {
        playerBody.innerHTML = `
            <div class="space-y-3">
                ${mediaContent}
                ${countdownWidget}
                ${story.content ? `<p class="text-xs text-white font-medium bg-black/50 p-3 rounded-xl border border-white/10 leading-relaxed">${escapeHtml(story.content)}</p>` : ''}
            </div>
        `;
    }

    if (story.story_type === 'countdown' && story.countdown_target_at) {
        const targetDate = new Date(story.countdown_target_at).getTime();
        const updateClock = () => {
            const now = new Date().getTime();
            const distance = targetDate - now;
            const clockEl = document.getElementById('liveCountdownClock');
            if (!clockEl) return;

            if (distance < 0) {
                clockEl.innerText = '🎉 EVENT LIVE NOW!';
            } else {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                clockEl.innerText = `${days}d ${hours.toString().padStart(2, '0')}h ${minutes.toString().padStart(2, '0')}m ${seconds.toString().padStart(2, '0')}s`;
            }
        };
        updateClock();
        countdownInterval = setInterval(updateClock, 1000);
    }
}

// Send Text Message
export async function dispatchMessage() {
    const input = document.getElementById('chatInput');
    if (!input || !selectedRecipient) return;
    const text = input.value.trim();
    if (!text && !selectedScheduledTime) return;

    const payload = { 
        message: text, 
        type: 'text', 
        recipient_id: selectedRecipient.id,
        scheduled_at: selectedScheduledTime 
    };
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
    xhr.onload = async () => {
        showUploadProgress(false, 100);
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    await fetch('/chat/send', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                        body: JSON.stringify({
                            type: res.type || type,
                            file_url: res.file_url,
                            file_name: res.file_name || file.name,
                            file_size: file.size,
                            recipient_id: selectedRecipient ? selectedRecipient.id : null,
                            scheduled_at: selectedScheduledTime
                        })
                    });
                }
            } catch (err) {
                console.error('File send error:', err);
            }
        }
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

        showUploadProgress(true, 50);
        try {
            const res = await fetch('/chat/upload', { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrfToken() }, body: formData });
            const uploadData = await res.json();
            showUploadProgress(false, 100);

            if (uploadData.status === 'success') {
                await fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                    body: JSON.stringify({
                        type: 'voice',
                        file_url: uploadData.file_url,
                        recipient_id: selectedRecipient ? selectedRecipient.id : null
                    })
                });
            }
        } catch (e) {
            console.error('Voice send error:', e);
        }
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

        showUploadProgress(true, 50);
        try {
            const res = await fetch('/chat/upload', { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrfToken() }, body: formData });
            const uploadData = await res.json();
            showUploadProgress(false, 100);

            if (uploadData.status === 'success') {
                await fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                    body: JSON.stringify({
                        type: 'video',
                        file_url: uploadData.file_url,
                        recipient_id: selectedRecipient ? selectedRecipient.id : null
                    })
                });
            }
        } catch (e) {
            console.error('Video send error:', e);
        }
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

let globalAudioCtx = null;

function getAudioContext() {
    if (!globalAudioCtx) {
        const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
        if (AudioCtxClass) {
            globalAudioCtx = new AudioCtxClass();
        }
    }
    if (globalAudioCtx && globalAudioCtx.state === 'suspended') {
        globalAudioCtx.resume().catch(() => {});
    }
    return globalAudioCtx;
}

if (typeof window !== 'undefined') {
    window.addEventListener('click', () => getAudioContext(), { passive: true });
    window.addEventListener('keydown', () => getAudioContext(), { passive: true });
    window.addEventListener('touchstart', () => getAudioContext(), { passive: true });
}

// Audio Chime & Toast Notifications
export function playNotificationSound() {
    const toggle = document.getElementById('soundToggle');
    if (toggle && !toggle.checked) return;

    try {
        const ctx = getAudioContext();
        if (ctx) {
            const now = ctx.currentTime;
            const osc1 = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gain = ctx.createGain();

            osc1.type = 'sine';
            osc2.type = 'sine';

            osc1.frequency.setValueAtTime(587.33, now); // D5
            osc1.frequency.exponentialRampToValueAtTime(880, now + 0.1); // A5

            osc2.frequency.setValueAtTime(880, now + 0.1);
            osc2.frequency.exponentialRampToValueAtTime(1174.66, now + 0.22); // D6

            gain.gain.setValueAtTime(0.25, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.28);

            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(ctx.destination);

            osc1.start(now);
            osc1.stop(now + 0.12);
            osc2.start(now + 0.1);
            osc2.stop(now + 0.28);
        }
    } catch (e) {
        console.warn('Notification sound error:', e);
    }
}

export function showToastNotification(msg, type = 'success') {
    const toast = document.getElementById('toastNotification');
    if (!toast) return;

    let messageText = '';
    let senderName = 'New Message';
    let avatarUrl = '/images/default-avatar.svg';
    let senderUserId = null;

    if (typeof msg === 'object' && msg !== null) {
        senderName = msg.sender_name || 'New Message';
        avatarUrl = msg.avatar_url || '/images/default-avatar.svg';
        senderUserId = msg.user_id || null;
        messageText = msg.message || (msg.type ? `Sent a ${msg.type} attachment` : 'New notification received');
    } else {
        messageText = String(msg);
    }

    const avatarElem = document.getElementById('toastAvatar');
    const senderElem = document.getElementById('toastSender');
    const msgElem = document.getElementById('toastMessage');

    if (avatarElem) avatarElem.src = avatarUrl;
    if (senderElem) senderElem.textContent = senderName;
    if (msgElem) msgElem.textContent = messageText;

    if (senderUserId) {
        toast.onclick = (e) => {
            if (e.target.tagName === 'BUTTON') return;
            selectChatUser(senderUserId);
            hideToastNotification();
        };
        toast.classList.add('cursor-pointer');
    } else {
        toast.onclick = null;
        toast.classList.remove('cursor-pointer');
    }

    toast.classList.remove('hidden');
    setTimeout(hideToastNotification, 5000);
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
export function viewActiveContactProfile() {
    if (selectedRecipient) {
        openPublicProfileModal(selectedRecipient.id);
    } else {
        toggleProfileModal();
    }
}

export function toggleProfileModal() { document.getElementById('profileModal')?.classList.toggle('hidden'); }
export function toggleAddStoryModal() { document.getElementById('addStoryModal')?.classList.toggle('hidden'); }
export function toggleSettingsModal() { document.getElementById('settingsModal')?.classList.toggle('hidden'); }
export function updateInputControlsState() {
    const input = document.getElementById('chatInput');
    const scheduleBtn = document.getElementById('btnScheduleMsg');
    const hasText = input && input.value.trim().length > 0;
    if (scheduleBtn) {
        if (hasText) {
            scheduleBtn.disabled = false;
            scheduleBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'text-gray-400');
            scheduleBtn.classList.add('text-amber-300', 'hover:text-amber-200', 'cursor-pointer');
            scheduleBtn.title = 'Schedule Message';
        } else {
            scheduleBtn.disabled = true;
            scheduleBtn.classList.add('opacity-50', 'cursor-not-allowed', 'text-gray-400');
            scheduleBtn.classList.remove('text-amber-300', 'hover:text-amber-200', 'cursor-pointer');
            scheduleBtn.title = 'Type a message first to schedule';
        }
    }
}

export function toggleScheduleModal() {
    const input = document.getElementById('chatInput');
    const text = input ? input.value.trim() : '';
    if (!text && document.getElementById('scheduleModal')?.classList.contains('hidden')) {
        showToastNotification('Please type a message first before scheduling.', 'warning');
        return;
    }
    document.getElementById('scheduleModal')?.classList.toggle('hidden');
}

export function addEmoji(e) { 
    const input = document.getElementById('chatInput');
    if (input) {
        input.value += e;
        input.focus();
        updateInputControlsState();
    }
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

export function previewHeaderImage(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => {
            const el = document.getElementById('profileHeaderPreview');
            if (el) {
                el.src = ev.target.result;
                el.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

export async function selectAvatarFromGallery(avatarPath) {
    try {
        const response = await fetch('/user/profile/select-avatar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ avatar_path: avatarPath })
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Active avatar updated!');
            setTimeout(() => window.location.reload(), 800);
        } else {
            alert(data.message || 'Error updating avatar');
        }
    } catch (e) {
        console.error(e);
    }
}

export async function selectHeaderFromGallery(headerPath) {
    try {
        const response = await fetch('/user/profile/select-header', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ header_path: headerPath })
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Active cover banner updated!');
            setTimeout(() => window.location.reload(), 800);
        } else {
            alert(data.message || 'Error updating cover header');
        }
    } catch (e) {
        console.error(e);
    }
}

export async function deleteAvatarFromGallery(avatarPath) {
    if (!confirm('Remove this photo from your avatar gallery?')) return;
    try {
        const response = await fetch('/user/profile/delete-avatar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ avatar_path: avatarPath })
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Avatar removed.');
            setTimeout(() => window.location.reload(), 800);
        } else {
            alert(data.message || 'Error removing avatar');
        }
    } catch (e) {
        console.error(e);
    }
}

export async function deleteHeaderFromGallery(headerPath) {
    if (!confirm('Remove this cover header from your gallery?')) return;
    try {
        const response = await fetch('/user/profile/delete-header', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ header_path: headerPath })
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Cover header removed.');
            setTimeout(() => window.location.reload(), 800);
        } else {
            alert(data.message || 'Error removing cover header');
        }
    } catch (e) {
        console.error(e);
    }
}

// User Article Editing Modal & Handlers
export function openEditArticleModal(id, title, content) {
    const modal = document.getElementById('editArticleModal');
    const inputId = document.getElementById('editArticleId');
    const inputTitle = document.getElementById('editArticleTitle');
    const inputContent = document.getElementById('editArticleContent');

    if (inputId) inputId.value = id;
    if (inputTitle) inputTitle.value = title;
    if (inputContent) inputContent.value = content;
    if (modal) modal.classList.remove('hidden');
}

export function closeEditArticleModal() {
    const modal = document.getElementById('editArticleModal');
    if (modal) modal.classList.add('hidden');
}

export async function submitArticleEdit(event) {
    event.preventDefault();
    const id = document.getElementById('editArticleId').value;
    const title = document.getElementById('editArticleTitle').value;
    const content = document.getElementById('editArticleContent').value;

    try {
        const response = await fetch(`/user/articles/${id}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ title, content })
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Article updated successfully!');
            closeEditArticleModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            alert('Failed to update article: ' + (data.message || 'Error occurred'));
        }
    } catch (e) {
        console.error('Edit Article error:', e);
        alert('An error occurred while updating the article.');
    }
}

export async function deleteUserArticle(id) {
    if (!confirm('Are you sure you want to delete this published article?')) return;

    try {
        const response = await fetch(`/user/articles/${id}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Article deleted successfully.');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            alert('Failed to delete article.');
        }
    } catch (e) {
        console.error('Delete Article error:', e);
        alert('An error occurred while deleting the article.');
    }
}

// User Account Self-Deletion Handlers
export function openDeleteAccountModal() {
    const modal = document.getElementById('deleteAccountModal');
    if (modal) modal.classList.remove('hidden');
}

export function closeDeleteAccountModal() {
    const modal = document.getElementById('deleteAccountModal');
    if (modal) modal.classList.add('hidden');
}

export async function submitAccountDeletion(event) {
    event.preventDefault();

    const selectedReasonEl = document.querySelector('input[name="deletion_reason"]:checked');
    const reason = selectedReasonEl ? selectedReasonEl.value : 'No longer using platform';
    const customReason = document.getElementById('deletionCustomReason')?.value || '';

    if (!confirm('FINAL CONFIRMATION: Deleting your account will immediately log you out and delete your profile and published articles. Are you completely sure?')) {
        return;
    }

    try {
        const response = await fetch('/user/delete-account', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ reason, custom_reason: customReason })
        });
        const data = await response.json();
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = '/';
        } else {
            alert('Failed to delete account: ' + (data.message || 'Error occurred'));
        }
    } catch (e) {
        console.error('Account deletion error:', e);
        alert('An error occurred while processing account deletion.');
    }
}

// Twitter/X Style User Posts & Public Profile Inspector
let activePublicInspectorUserId = null;

export async function submitMyUserPost(event) {
    if (event) event.preventDefault();
    const content = document.getElementById('myPostContent')?.value || '';
    const mediaInput = document.getElementById('myPostMedia');

    const formData = new FormData();
    if (content) formData.append('content', content);
    if (mediaInput && mediaInput.files[0]) {
        formData.append('media', mediaInput.files[0]);
    }

    try {
        const response = await fetch('/user/posts/create', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': getCsrfToken() },
            body: formData
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification('Post published to your profile!');
            if (document.getElementById('myPostContent')) document.getElementById('myPostContent').value = '';
            if (mediaInput) mediaInput.value = '';
            if (document.getElementById('postMediaSelectedText')) document.getElementById('postMediaSelectedText').innerText = '';
        } else {
            alert(data.message || 'Failed to publish post.');
        }
    } catch (e) {
        console.error(e);
    }
}

export async function openPublicProfileModal(userId) {
    activePublicInspectorUserId = userId;
    const modal = document.getElementById('publicUserProfileModal');
    if (modal) modal.classList.remove('hidden');

    try {
        // Fetch stats
        const statsRes = await fetch(`/user/${userId}/stats`);
        const statsData = await statsRes.json();
        if (statsData.status === 'success') {
            document.getElementById('publicPostsCount').innerText = statsData.stats.posts;
            document.getElementById('publicFollowersCount').innerText = statsData.stats.followers;
            document.getElementById('publicFollowingCount').innerText = statsData.stats.following;

            const followBtn = document.getElementById('publicFollowBtn');
            if (followBtn) {
                if (statsData.stats.is_following) {
                    followBtn.innerText = '✓ Following';
                    followBtn.className = 'px-5 py-2 rounded-xl text-xs font-bold shadow-lg transition bg-gray-800 text-gray-300 hover:bg-rose-900 hover:text-white';
                } else {
                    followBtn.innerText = '+ Follow';
                    followBtn.className = 'px-5 py-2 rounded-xl text-xs font-bold shadow-lg transition bg-blue-600 hover:bg-blue-500 text-white';
                }
            }
        }

        // Fetch posts
        const postsRes = await fetch(`/user/${userId}/posts`);
        const postsData = await postsRes.json();
        if (postsData.status === 'success') {
            document.getElementById('publicUserName').innerText = postsData.user.name;
            document.getElementById('publicUserHandle').innerText = '@' + postsData.user.username;
            document.getElementById('publicAvatarImg').src = postsData.user.avatar_url;

            const headerImg = document.getElementById('publicHeaderBanner');
            if (headerImg) {
                if (postsData.user.header_url) {
                    headerImg.src = postsData.user.header_url;
                    headerImg.classList.remove('hidden');
                } else {
                    headerImg.classList.add('hidden');
                }
            }

            const bioElem = document.getElementById('publicUserBio');
            if (bioElem) {
                bioElem.innerText = postsData.user.bio || 'No bio added yet.';
            }

            const fullProfileLink = document.getElementById('publicFullProfilePageBtn');
            if (fullProfileLink) {
                fullProfileLink.href = `/user/${userId}/profile`;
            }

            const feed = document.getElementById('publicUserPostsFeed');
            if (feed) {
                if (!postsData.posts || postsData.posts.length === 0) {
                    feed.innerHTML = '<p class="text-xs text-gray-500 italic p-3 text-center">No posts published yet.</p>';
                } else {
                    feed.innerHTML = postsData.posts.map(p => `
                        <div class="p-3.5 rounded-2xl bg-black/50 border border-white/10 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <img src="${p.avatar_url}" class="w-8 h-8 rounded-full border border-white/20 object-cover">
                                    <div>
                                        <span class="font-bold text-white block">${escapeHtml(p.user_name)}</span>
                                        <span class="text-[10px] text-gray-400 font-mono">@${escapeHtml(p.username)} • ${p.created_at}</span>
                                    </div>
                                </div>
                            </div>

                            ${p.content ? `<p class="text-gray-200 leading-relaxed font-sans text-xs">${escapeHtml(p.content)}</p>` : ''}

                            ${p.media_url && p.media_type === 'image' ? `
                                <img src="${p.media_url}" class="w-full max-h-64 object-cover rounded-xl border border-white/10 shadow-md">
                            ` : ''}

                            ${p.media_url && p.media_type === 'video' ? `
                                <video src="${p.media_url}" controls class="w-full max-h-64 rounded-xl border border-white/10 shadow-md"></video>
                            ` : ''}

                            <div class="flex items-center space-x-4 pt-1 text-[11px] text-gray-400">
                                <button onclick="toggleLikeUserPost(${p.id})" class="flex items-center space-x-1 hover:text-rose-400 transition ${p.is_liked ? 'text-rose-500 font-bold' : ''}">
                                    <span>${p.is_liked ? '❤️' : '🤍'}</span>
                                    <span>${p.likes_count} Likes</span>
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            }
        }
    } catch (e) {
        console.error(e);
    }
}

export function closePublicProfileModal() {
    const modal = document.getElementById('publicUserProfileModal');
    if (modal) modal.classList.add('hidden');
    activePublicInspectorUserId = null;
}

export async function toggleFollowUserPublic() {
    if (!activePublicInspectorUserId) return;
    try {
        const response = await fetch(`/user/${activePublicInspectorUserId}/follow`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': getCsrfToken() }
        });
        const data = await response.json();
        if (data.status === 'success') {
            showToastNotification(data.action === 'followed' ? 'You are now following this user!' : 'Unfollowed user.');
            openPublicProfileModal(activePublicInspectorUserId);
        }
    } catch (e) {
        console.error(e);
    }
}

export async function toggleLikeUserPost(postId) {
    try {
        const response = await fetch(`/user/posts/${postId}/like`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': getCsrfToken() }
        });
        const data = await response.json();
        if (data.status === 'success') {
            if (activePublicInspectorUserId) {
                openPublicProfileModal(activePublicInspectorUserId);
            }
        }
    } catch (e) {
        console.error(e);
    }
}

export function toggleStoryTypeFields(val) {
    const countdownContainer = document.getElementById('countdownTargetContainer');
    if (countdownContainer) {
        if (val === 'countdown') countdownContainer.classList.remove('hidden');
        else countdownContainer.classList.add('hidden');
    }
}

export function escapeHtml(t) { 
    return String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); 
}

// Two-Way Single Message Deletion
export async function deleteSingleMessage(msgId) {
    if (!confirm('Are you sure you want to delete this message for both users?')) return;
    try {
        const response = await fetch(`/chat/messages/${msgId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            const bubble = document.getElementById(`msg-bubble-${msgId}`);
            if (bubble) bubble.remove();
            fetchMessages();
        } else {
            alert(data.message || 'Failed to delete message.');
        }
    } catch (e) {
        console.error('Delete message error:', e);
    }
}

// Clear Entire Chat History
export async function confirmClearChatHistory() {
    if (!selectedRecipient) return;
    if (!confirm(`Are you sure you want to erase all chat history with ${selectedRecipient.name}?`)) return;
    try {
        const response = await fetch('/chat/messages/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ recipient_id: selectedRecipient.id })
        });
        const data = await response.json();
        if (data.status === 'success') {
            const stream = document.getElementById('messageStream');
            if (stream) stream.innerHTML = '<div class="text-center py-16 text-gray-400 text-xs font-mono">Chat history erased.</div>';
        }
    } catch (e) {
        console.error('Clear chat error:', e);
    }
}

// Block / Unblock User
export async function toggleBlockSelectedUser() {
    if (!selectedRecipient) return;
    try {
        const response = await fetch(`/user/${selectedRecipient.id}/block`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            alert(data.message);
            const btnText = document.getElementById('blockUserBtnText');
            if (btnText) btnText.textContent = data.is_blocked ? 'Unblock' : 'Block';
        }
    } catch (e) {
        console.error('Block user error:', e);
    }
}

// Auto-Delete Timer Settings
export function saveAutoDeleteTimer(val) {
    localStorage.setItem('chat_auto_delete_timer', val);
    showToastNotification('Auto-Delete Timer', `Messages auto-expiration set to: ${val}`);
}

// Wipe Local Chat Caches
export function wipeLocalChatCaches() {
    if (confirm('Wipe all local message caches and session storage?')) {
        localStorage.clear();
        sessionStorage.clear();
        alert('Local chat caches and session data wiped.');
        window.location.reload();
    }
}

// Location Marker Attachment
export function attachLocationMarker() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude.toFixed(4);
            const lng = pos.coords.longitude.toFixed(4);
            const input = document.getElementById('chatInput');
            if (input) input.value += ` 📍 Location: https://maps.google.com/?q=${lat},${lng}`;
        }, () => {
            const input = document.getElementById('chatInput');
            if (input) input.value += ` 📍 Location: https://maps.google.com/?q=50.9181,13.3411`;
        });
    }
}

// Paste from Clipboard
export async function pasteFromClipboard() {
    try {
        const text = await navigator.clipboard.readText();
        const input = document.getElementById('chatInput');
        if (input && text) {
            input.value += (input.value ? ' ' : '') + text;
        }
    } catch (e) {
        console.log('Clipboard paste not allowed or empty.');
    }
}

// Global Namespace Export for HTML Inline Attributes
window.chatPortal = {
    selectChatUser,
    backToUserDirectory,
    filterUsersDirectory,
    fetchMessages,
    renderMessageBubble,
    toggleReaction,
    fetchUsers,
    fetchStories,
    openStoryGroupViewer,
    closeStoryPlayerModal,
    nextStory,
    prevStory,
    toggleStoryTypeFields,
    submitMyUserPost,
    openPublicProfileModal,
    closePublicProfileModal,
    toggleFollowUserPublic,
    toggleLikeUserPost,
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
    viewActiveContactProfile,
    toggleAddStoryModal,
    toggleSettingsModal,
    toggleEmojiPicker,
    toggleGifPicker,
    toggleScheduleModal,
    updateInputControlsState,
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
    previewHeaderImage,
    selectAvatarFromGallery,
    selectHeaderFromGallery,
    deleteAvatarFromGallery,
    deleteHeaderFromGallery,
    openEditArticleModal,
    closeEditArticleModal,
    submitArticleEdit,
    deleteUserArticle,
    openDeleteAccountModal,
    closeDeleteAccountModal,
    submitAccountDeletion,
    deleteSingleMessage,
    confirmClearChatHistory,
    toggleBlockSelectedUser,
    saveAutoDeleteTimer,
    wipeLocalChatCaches,
    attachLocationMarker,
    pasteFromClipboard,
    escapeHtml
};

// Bind to window root for direct inline onclick handlers
Object.assign(window, window.chatPortal);
