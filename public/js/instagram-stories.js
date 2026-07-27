/**
 * Instagram Stories Bar & Interactive Tools ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const storiesBarList = document.getElementById('instagram-stories-bar-list');
    const storyForm = document.getElementById('form-instagram-story');

    // Poll options toggle in creator form
    const storyTypeRadios = document.querySelectorAll('input[name="story_type"]');
    const pollInputs = document.getElementById('story-poll-inputs');
    const questionInputs = document.getElementById('story-question-inputs');

    if (storyTypeRadios.length > 0) {
        storyTypeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                const val = radio.value;
                if (pollInputs) pollInputs.classList.toggle('hidden', val !== 'poll');
                if (questionInputs) questionInputs.classList.toggle('hidden', val !== 'question');
            });
        });
    }

    // Fetch active stories
    async function loadInstagramStories() {
        if (!storiesBarList) return;

        try {
            const res = await fetch('/chat/stories');
            const data = await res.json();

            if (res.ok && data.stories && data.stories.length > 0) {
                storiesBarList.innerHTML = '';
                data.stories.forEach(s => {
                    const circle = document.createElement('div');
                    circle.className = 'flex flex-col items-center cursor-pointer group shrink-0';
                    circle.onclick = () => openStoryViewer(s);

                    circle.innerHTML = `
                        <div class="w-14 h-14 rounded-full p-[2px] bg-gradient-to-tr from-yellow-500 via-pink-500 to-purple-600 shadow-md group-hover:scale-105 transition-transform">
                            <img src="${s.avatar_url}" class="w-full h-full rounded-full object-cover border-2 border-black">
                        </div>
                        <span class="text-[10px] text-gray-300 mt-1 font-medium truncate max-w-[60px]">${s.username}</span>
                    `;
                    storiesBarList.appendChild(circle);
                });
            } else {
                storiesBarList.innerHTML = `
                    <div class="text-xs text-gray-400 font-mono flex items-center gap-2">
                        <span>📷 No active stories.</span>
                    </div>
                `;
            }
        } catch (e) {
            console.error('Stories fetch error:', e);
        }
    }

    // Story viewer opener
    function openStoryViewer(story) {
        const modal = document.getElementById('modal-view-story');
        const avatar = document.getElementById('story-viewer-avatar');
        const username = document.getElementById('story-viewer-username');
        const time = document.getElementById('story-viewer-time');
        const text = document.getElementById('story-viewer-text');
        const media = document.getElementById('story-viewer-media');
        const video = document.getElementById('story-viewer-video');
        const pollContainer = document.getElementById('story-viewer-poll');

        if (!modal) return;

        if (avatar) avatar.src = story.avatar_url;
        if (username) username.textContent = `@${story.username}`;
        if (time) time.textContent = story.created_at;
        if (text) text.textContent = story.content || '';

        // Media handling
        if (media) media.classList.add('hidden');
        if (video) { video.classList.add('hidden'); video.pause(); }

        if (story.media_url) {
            const ext = story.media_url.split('.').pop().toLowerCase();
            if (['mp4', 'mov', 'webm'].includes(ext)) {
                if (video) {
                    video.src = story.media_url;
                    video.classList.remove('hidden');
                }
            } else {
                if (media) {
                    media.src = story.media_url;
                    media.classList.remove('hidden');
                }
            }
        }

        // Poll handling
        if (pollContainer) {
            if (story.poll_options) {
                pollContainer.classList.remove('hidden');
                const pollA = document.getElementById('poll-text-a');
                const pollB = document.getElementById('poll-text-b');
                const countA = document.getElementById('poll-count-a');
                const countB = document.getElementById('poll-count-b');
                const btnA = document.getElementById('poll-btn-a');
                const btnB = document.getElementById('poll-btn-b');

                if (pollA) pollA.textContent = story.poll_options.option_a || 'Option A';
                if (pollB) pollB.textContent = story.poll_options.option_b || 'Option B';
                if (countA) countA.textContent = story.poll_options.votes_a || 0;
                if (countB) countB.textContent = story.poll_options.votes_b || 0;

                if (btnA) btnA.onclick = () => votePoll(story.id, 'a');
                if (btnB) btnB.onclick = () => votePoll(story.id, 'b');
            } else {
                pollContainer.classList.add('hidden');
            }
        }

        modal.classList.remove('hidden');
    }

    async function votePoll(storyId, option) {
        try {
            const res = await fetch('/chat/stories/vote', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
                body: JSON.stringify({ story_id: storyId, option: option })
            });
            const data = await res.json();
            if (res.ok) {
                alert(data.message);
                loadInstagramStories();
            } else {
                alert(data.message || 'Voting failed.');
            }
        } catch (e) {
            alert('Vote error.');
        }
    }

    // Handle Story Form submit
    if (storyForm) {
        storyForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(storyForm);

            try {
                const res = await fetch('/chat/stories', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken || '' },
                    body: formData
                });

                const data = await res.json();

                if (res.ok) {
                    alert('Instagram Story shared!');
                    document.getElementById('modal-instagram-story')?.classList.add('hidden');
                    storyForm.reset();
                    loadInstagramStories();
                } else {
                    alert(data.message || 'Posting failed.');
                }
            } catch (err) {
                alert('Connection error sharing story.');
            }
        });
    }

    loadInstagramStories();
});
