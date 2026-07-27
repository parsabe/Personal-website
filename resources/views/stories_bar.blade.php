<!-- INSTAGRAM STORIES BAR (TOP OF CONTAINER) -->
<div class="w-full bg-black/40 border-b border-white/10 px-6 py-3 flex items-center gap-4 overflow-x-auto scrollbar-none z-30 shrink-0">
    
    <!-- Add Story Circle -->
    @auth
        <div onclick="document.getElementById('modal-instagram-story').classList.remove('hidden')" class="flex flex-col items-center cursor-pointer group shrink-0">
            <div class="w-14 h-14 rounded-full border-2 border-dashed border-indigo-500 flex items-center justify-center bg-indigo-600/20 group-hover:scale-105 transition-transform relative">
                <span class="text-xl text-indigo-400 font-bold">+</span>
                <span class="absolute bottom-0 right-0 w-4 h-4 bg-indigo-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center border border-black">+</span>
            </div>
            <span class="text-[10px] text-indigo-300 mt-1 font-semibold">Your Story</span>
        </div>
    @endauth

    <!-- Dynamic Stories List -->
    <div id="instagram-stories-bar-list" class="flex items-center gap-4 shrink-0">
        <div class="text-xs text-gray-500 font-mono flex items-center gap-2">
            <span>📷 No active stories.</span>
            @auth
                <span class="text-indigo-400 cursor-pointer underline" onclick="document.getElementById('modal-instagram-story').classList.remove('hidden')">Tap + to share!</span>
            @endauth
        </div>
    </div>
</div>

<!-- INSTAGRAM STORY CREATOR MODAL -->
<div id="modal-instagram-story" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-lg bg-gray-900 border border-white/20 p-6 rounded-3xl space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                📸 Create Instagram Story
            </h3>
            <button onclick="document.getElementById('modal-instagram-story').classList.add('hidden')" class="text-gray-400 hover:text-white text-lg">✕</button>
        </div>

        <form id="form-instagram-story" class="space-y-4">
            <!-- Tool Selector -->
            <div class="grid grid-cols-4 gap-2 text-center text-xs">
                <label class="cursor-pointer p-2.5 bg-black/40 border border-white/10 rounded-xl hover:border-indigo-500 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-600/30">
                    <input type="radio" name="story_type" value="standard" checked class="hidden">
                    <span class="block text-lg mb-1">📝</span>
                    <span class="text-[10px] font-semibold text-gray-300">Standard</span>
                </label>

                <label class="cursor-pointer p-2.5 bg-black/40 border border-white/10 rounded-xl hover:border-indigo-500 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-600/30">
                    <input type="radio" name="story_type" value="poll" class="hidden">
                    <span class="block text-lg mb-1">📊</span>
                    <span class="text-[10px] font-semibold text-gray-300">Poll Sticker</span>
                </label>

                <label class="cursor-pointer p-2.5 bg-black/40 border border-white/10 rounded-xl hover:border-indigo-500 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-600/30">
                    <input type="radio" name="story_type" value="question" class="hidden">
                    <span class="block text-lg mb-1">❓</span>
                    <span class="text-[10px] font-semibold text-gray-300">Question</span>
                </label>

                <label class="cursor-pointer p-2.5 bg-black/40 border border-white/10 rounded-xl hover:border-indigo-500 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-600/30">
                    <input type="radio" name="story_type" value="countdown" class="hidden">
                    <span class="block text-lg mb-1">⏳</span>
                    <span class="text-[10px] font-semibold text-gray-300">Countdown</span>
                </label>
            </div>

            <!-- Story Text / Caption -->
            <div>
                <textarea name="content" rows="3" placeholder="Type text caption or story message..."
                    class="w-full bg-black/50 border border-white/15 rounded-xl p-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <!-- Poll Inputs -->
            <div id="story-poll-inputs" class="space-y-2 hidden">
                <span class="text-xs font-bold text-indigo-400 block">📊 Poll Options (A vs B):</span>
                <input type="text" name="poll_option_a" placeholder="Option A (e.g. Yes 👍)" class="w-full bg-black/50 border border-white/15 rounded-xl px-3 py-2 text-xs text-white">
                <input type="text" name="poll_option_b" placeholder="Option B (e.g. No 👎)" class="w-full bg-black/50 border border-white/15 rounded-xl px-3 py-2 text-xs text-white">
            </div>

            <!-- Question Sticker Input -->
            <div id="story-question-inputs" class="hidden">
                <input type="text" name="sticker_question" placeholder="Ask me a question sticker prompt..." class="w-full bg-black/50 border border-white/15 rounded-xl px-3 py-2 text-xs text-white">
            </div>

            <!-- Mentions Input -->
            <div>
                <input type="text" name="mentions" placeholder="Mentions (comma separated usernames...)" class="w-full bg-black/50 border border-white/15 rounded-xl px-3 py-2 text-xs text-indigo-300 placeholder-gray-500">
            </div>

            <!-- Media Attachment -->
            <div>
                <label class="block text-[11px] text-gray-400 mb-1">Attach Photo or Video (Up to 4GB):</label>
                <input type="file" name="media" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/30 file:text-indigo-300 hover:file:bg-indigo-600/50">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-white/10">
                <button type="button" onclick="document.getElementById('modal-instagram-story').classList.add('hidden')" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl text-xs">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl text-xs shadow-lg hover:opacity-90">Share Story</button>
            </div>
        </form>
    </div>
</div>

<!-- STORY VIEWER MODAL -->
<div id="modal-view-story" class="fixed inset-0 bg-black/90 backdrop-blur-lg z-50 flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-sm bg-gray-900 border border-white/20 rounded-3xl overflow-hidden shadow-2xl flex flex-col relative max-h-[85vh]">
        <!-- Top bar with user avatar & timer -->
        <div class="p-4 bg-gradient-to-b from-black/80 to-transparent flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <img id="story-viewer-avatar" src="{{ asset('images/profile.jpg') }}" class="w-10 h-10 rounded-full border-2 border-indigo-500 object-cover">
                <div>
                    <span id="story-viewer-username" class="text-xs font-bold text-white block">@user</span>
                    <span id="story-viewer-time" class="text-[10px] text-gray-400 block">Just now</span>
                </div>
            </div>
            <button onclick="document.getElementById('modal-view-story').classList.add('hidden')" class="text-white hover:text-gray-300 text-xl">✕</button>
        </div>

        <!-- Story Content Body -->
        <div class="flex-1 p-6 flex flex-col items-center justify-center text-center space-y-4 overflow-y-auto min-h-[300px]">
            <img id="story-viewer-media" class="w-full max-h-64 object-contain rounded-2xl hidden mb-2 border border-white/10">
            <video id="story-viewer-video" controls class="w-full max-h-64 rounded-2xl hidden mb-2 border border-white/10"></video>
            
            <p id="story-viewer-text" class="text-sm font-semibold text-white leading-relaxed"></p>

            <!-- Poll Display -->
            <div id="story-viewer-poll" class="w-full space-y-2 hidden p-3 bg-black/60 border border-indigo-500/40 rounded-2xl font-mono text-xs">
                <div class="text-[10px] text-indigo-400 font-bold uppercase mb-1">📊 Story Poll</div>
                <button id="poll-btn-a" class="w-full py-2 bg-indigo-600/30 hover:bg-indigo-600/50 border border-indigo-400 text-white rounded-xl flex justify-between px-4">
                    <span id="poll-text-a">Option A</span>
                    <span id="poll-count-a">0</span>
                </button>
                <button id="poll-btn-b" class="w-full py-2 bg-purple-600/30 hover:bg-purple-600/50 border border-purple-400 text-white rounded-xl flex justify-between px-4">
                    <span id="poll-text-b">Option B</span>
                    <span id="poll-count-b">0</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- External Instagram Stories ESM Script -->
<script type="module" src="{{ asset('js/instagram-stories.js') }}"></script>
