<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content" style="height: 70vh;">
            <div class="modal-header p-2 bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-comments me-2"></i>Support Chat</h6>
                <div class="position-absolute start-50 translate-middle-x">
                    <small class="d-none d-lg-inline" style="font-size: 11px; opacity: 0.9; font-weight: 500;">CREEC E-Cooking Inventory Management System</small>
                    <small class="d-lg-none" style="font-size: 8px; opacity: 0.9; font-weight: 500; white-space: nowrap;">CREEC E-Cooking Inventory Management System</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: calc(70vh - 45px);">
                <!-- Guest Registration Form -->
                <div id="guestRegistrationForm" class="d-flex align-items-center justify-content-center h-100">
                    <div class="card border-0 shadow-sm" style="max-width: 400px; width: 100%;">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <i class="fas fa-user-circle fa-3x text-primary mb-3"></i>
                                <h5 class="mb-2">Welcome to Support Chat</h5>
                                <p class="text-muted" style="font-size: 13px;">Please provide your information to start chatting</p>
                            </div>
                            <form id="guestInfoForm" onsubmit="submitGuestInfo(event)">
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 13px;">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guestName" required placeholder="Enter your full name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 13px;">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="guestPhone" required placeholder="+256 700 000 000">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 13px;">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guestLocation" required placeholder="Enter your location">
                                </div>
                                <button type="submit" class="btn btn-primary w-100" id="guestSubmitBtn">
                                    <i class="fas fa-comments me-2"></i>Start Chat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Chat Interface -->
                <div class="d-flex h-100 d-none" id="chatInterface">
                    <div class="border-end" style="width: 200px; display: flex; flex-direction: column;">
                        <!-- Guest Info Display (only for guests) -->
                        <div id="guestInfoDisplay" class="d-none border-bottom p-2" style="background: #f0f2f5;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 16px; flex-shrink: 0;" id="guestAvatarDisplay"></div>
                                <div class="flex-fill">
                                    <div class="fw-bold" style="font-size: 13px;" id="guestNameDisplay"></div>
                                    <small class="text-muted d-block" style="font-size: 10px;"><i class="fas fa-phone me-1"></i><span id="guestPhoneDisplay"></span></small>
                                    <small class="text-muted d-block" style="font-size: 10px;"><i class="fas fa-map-marker-alt me-1"></i><span id="guestLocationDisplay"></span></small>
                                    <span class="badge bg-success mt-1" style="font-size: 9px;"><i class="fas fa-user me-1"></i>Guest</span>
                                </div>
                            </div>
                        </div>
                        <!-- Logged-in User Info Display (only for authenticated users) -->
                        <div id="userInfoDisplay" class="d-none border-bottom p-2" style="background: #f0f2f5;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 16px; flex-shrink: 0; overflow: hidden;" id="userAvatarDisplay"></div>
                                <div class="flex-fill">
                                    <div class="fw-bold" style="font-size: 13px;" id="userNameDisplay"></div>
                                    <small class="text-muted d-block" style="font-size: 10px;"><i class="fas fa-envelope me-1"></i><span id="userEmailDisplay"></span></small>
                                    <span class="badge bg-primary mt-1" style="font-size: 9px;" id="userRoleDisplay"></span>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 border-bottom">
                            <input type="text" class="form-control form-control-sm" id="chatUserSearch" placeholder="Search...">
                        </div>
                        <div class="d-flex border-bottom" id="chatTabsContainer">
                            <button class="flex-fill btn btn-sm chat-tab active position-relative" data-type="technicians" style="font-size: 11px; padding: 6px;">
                                Tech
                                <span class="badge bg-danger rounded-circle position-absolute d-none" id="techniciansTabBadge" style="font-size: 9px; padding: 3px 6px; top: 2px; right: 2px;"></span>
                            </button>
                            <button class="flex-fill btn btn-sm chat-tab position-relative" data-type="trainers" style="font-size: 11px; padding: 6px;">
                                Train
                                <span class="badge bg-danger rounded-circle position-absolute d-none" id="trainersTabBadge" style="font-size: 9px; padding: 3px 6px; top: 2px; right: 2px;"></span>
                            </button>
                            <button class="flex-fill btn btn-sm chat-tab position-relative" data-type="admins" style="font-size: 11px; padding: 6px;">
                                Admin
                                <span class="badge bg-danger rounded-circle position-absolute d-none" id="adminsTabBadge" style="font-size: 9px; padding: 3px 6px; top: 2px; right: 2px;"></span>
                            </button>
                            <button class="flex-fill btn btn-sm chat-tab position-relative" data-type="guests" style="font-size: 11px; padding: 6px;">
                                Guests
                                <span class="badge bg-danger rounded-circle position-absolute d-none" id="guestsTabBadge" style="font-size: 9px; padding: 3px 6px; top: 2px; right: 2px;"></span>
                            </button>
                        </div>
                        <div class="flex-fill overflow-auto" id="chatUsersList"></div>
                    </div>
                    <div class="flex-fill d-flex flex-column">
                        <div class="p-2 border-bottom d-none" id="chatHeaderBar" style="background: #f0f2f5;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 14px;" id="chatAvatarIcon"></div>
                                    <div>
                                        <div class="fw-bold" style="font-size: 16px; color: #111b21;" id="chatUserName"></div>
                                        <small class="text-muted" style="font-size: 13px; color: #667781;" id="chatUserRole"></small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-light p-1" onclick="chatRefresh()" style="font-size: 20px; border: none; background: transparent; color: #54656f;" title="Refresh"><i class="fas fa-sync-alt" id="chatRefreshIcon"></i></button>
                                    <button class="btn btn-sm btn-light p-1" onclick="chatVideoCall()" style="font-size: 20px; border: none; background: transparent; color: #54656f;" title="Video Call"><i class="fas fa-video"></i></button>
                                    <button class="btn btn-sm btn-light p-1" onclick="chatCallUser()" style="font-size: 20px; border: none; background: transparent; color: #54656f;" title="Voice Call"><i class="fas fa-phone"></i></button>
                                    <button class="btn btn-sm btn-light p-1" onclick="showChatInfo()" style="font-size: 20px; border: none; background: transparent; color: #54656f;" title="More"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill overflow-auto p-2" id="chatMessagesArea" style="background: #e5ddd5; background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0icGF0dGVybiIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiPjxwYXRoIGQ9Ik0wIDUwIEwgNTAgMCBMIDEwMCA1MCBMIDUwIDEwMCBaIiBmaWxsPSJub25lIiBzdHJva2U9InJnYmEoMCwwLDAsMC4wMykiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg=='); background-size: 300px;">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-comments fa-2x mb-2 opacity-25"></i>
                                <p style="font-size: 12px;">Select a contact</p>
                            </div>
                        </div>
                        <div class="p-2 border-top d-none" id="chatInputArea" style="background: #f0f0f0;">
                            <div class="d-flex gap-2 align-items-end">
                                <button class="btn btn-light btn-sm rounded-circle p-2" onclick="chatToggleEmoji()" style="width: 36px; height: 36px; border: none;"><i class="fas fa-smile" style="color: #54656f;"></i></button>
                                <button class="btn btn-light btn-sm rounded-circle p-2" onclick="chatToggleAttach()" style="width: 36px; height: 36px; border: none;"><i class="fas fa-paperclip" style="color: #54656f;"></i></button>
                                <textarea class="form-control" id="chatMessageInput" rows="1" placeholder="Type a message" style="resize: none; max-height: 100px; border-radius: 20px; padding: 10px 15px; border: 1px solid #d1d7db; background: white;"></textarea>
                                <button class="btn btn-sm rounded-circle p-2" onclick="chatSendMessage()" style="width: 36px; height: 36px; background: #00a884; border: none;"><i class="fas fa-paper-plane" style="color: white;"></i></button>
                            </div>
                            <div class="position-relative">
                                <div class="position-absolute bottom-0 start-0 bg-white border rounded shadow p-2 d-none" id="chatEmojiPicker" style="z-index: 1000; max-height: 300px; overflow-y: auto; width: 280px;">
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm" id="emojiSearch" placeholder="Search emoji..." style="border-radius: 15px;">
                                    </div>
                                    <div class="d-flex flex-wrap gap-1" id="emojiList">
                                        <span class="chat-emoji" title="Grinning">😀</span><span class="chat-emoji" title="Laughing">😂</span><span class="chat-emoji" title="Rofl">🤣</span><span class="chat-emoji" title="Heart eyes">😍</span><span class="chat-emoji" title="Smiling">🥰</span><span class="chat-emoji" title="Wink">😉</span><span class="chat-emoji" title="Blush">😊</span><span class="chat-emoji" title="Cool">😎</span><span class="chat-emoji" title="Thinking">🤔</span><span class="chat-emoji" title="Surprised">😮</span><span class="chat-emoji" title="Sad">😢</span><span class="chat-emoji" title="Crying">😭</span><span class="chat-emoji" title="Angry">😡</span><span class="chat-emoji" title="Rage">🤬</span><span class="chat-emoji" title="Thumbs up">👍</span><span class="chat-emoji" title="Thumbs down">👎</span><span class="chat-emoji" title="Clap">👏</span><span class="chat-emoji" title="Pray">🙏</span><span class="chat-emoji" title="OK">👌</span><span class="chat-emoji" title="Victory">✌️</span><span class="chat-emoji" title="Love">❤️</span><span class="chat-emoji" title="Fire">🔥</span><span class="chat-emoji" title="100">💯</span><span class="chat-emoji" title="Party">🎉</span><span class="chat-emoji" title="Check">✅</span><span class="chat-emoji" title="Cross">❌</span><span class="chat-emoji" title="Star">⭐</span><span class="chat-emoji" title="Muscle">💪</span><span class="chat-emoji" title="Celebrate">🎊</span><span class="chat-emoji" title="Balloon">🎈</span><span class="chat-emoji" title="Sparkle">🌟</span><span class="chat-emoji" title="Heart">💖</span><span class="chat-emoji" title="Kiss">😘</span><span class="chat-emoji" title="Hug">🤗</span><span class="chat-emoji" title="Angel">😇</span><span class="chat-emoji" title="Party face">🥳</span><span class="chat-emoji" title="Sleep">😴</span><span class="chat-emoji" title="Star eyes">🤩</span><span class="chat-emoji" title="Yum">😋</span><span class="chat-emoji" title="Crazy">🤪</span><span class="chat-emoji" title="Tongue">😜</span><span class="chat-emoji" title="Pleading">🥺</span><span class="chat-emoji" title="Shocked">😱</span><span class="chat-emoji" title="Mind blown">🤯</span><span class="chat-emoji" title="Skull">💀</span><span class="chat-emoji" title="Ghost">👻</span><span class="chat-emoji" title="Robot">🤖</span><span class="chat-emoji" title="Poop">💩</span><span class="chat-emoji" title="Clown">🤡</span><span class="chat-emoji" title="Wave">👋</span><span class="chat-emoji" title="Handshake">🤝</span><span class="chat-emoji" title="Raised hands">🙌</span><span class="chat-emoji" title="Writing">✍️</span><span class="chat-emoji" title="Eyes">👀</span><span class="chat-emoji" title="Brain">🧠</span><span class="chat-emoji" title="Kiss mark">💋</span><span class="chat-emoji" title="Broken heart">💔</span><span class="chat-emoji" title="Orange heart">🧡</span><span class="chat-emoji" title="Yellow heart">💛</span><span class="chat-emoji" title="Green heart">💚</span><span class="chat-emoji" title="Blue heart">💙</span><span class="chat-emoji" title="Purple heart">💜</span><span class="chat-emoji" title="Speech">💬</span><span class="chat-emoji" title="Sparkles">✨</span><span class="chat-emoji" title="Lightning">⚡</span><span class="chat-emoji" title="Rainbow">🌈</span><span class="chat-emoji" title="Sun">☀️</span><span class="chat-emoji" title="Moon">🌙</span><span class="chat-emoji" title="Gift">🎁</span><span class="chat-emoji" title="Cake">🎂</span><span class="chat-emoji" title="Trophy">🏆</span><span class="chat-emoji" title="Medal">🥇</span><span class="chat-emoji" title="Coffee">☕</span><span class="chat-emoji" title="Pizza">🍕</span><span class="chat-emoji" title="Burger">🍔</span><span class="chat-emoji" title="Beer">🍺</span><span class="chat-emoji" title="Wine">🍷</span><span class="chat-emoji" title="Apple">🍎</span><span class="chat-emoji" title="Banana">🍌</span><span class="chat-emoji" title="Car">🚗</span><span class="chat-emoji" title="Plane">✈️</span><span class="chat-emoji" title="Rocket">🚀</span><span class="chat-emoji" title="House">🏠</span><span class="chat-emoji" title="Phone">📱</span><span class="chat-emoji" title="Computer">💻</span><span class="chat-emoji" title="Camera">📷</span><span class="chat-emoji" title="Book">📚</span><span class="chat-emoji" title="Money">💰</span><span class="chat-emoji" title="Clock">🕐</span><span class="chat-emoji" title="Lock">🔒</span><span class="chat-emoji" title="Key">🔑</span><span class="chat-emoji" title="Hammer">🔨</span><span class="chat-emoji" title="Wrench">🔧</span><span class="chat-emoji" title="Light bulb">💡</span><span class="chat-emoji" title="Music">🎵</span><span class="chat-emoji" title="Bell">🔔</span><span class="chat-emoji" title="Warning">⚠️</span>
                                    </div>
                                </div>
                                <div class="position-absolute bottom-0 start-0 bg-white border rounded shadow p-1 d-none" id="chatAttachMenu" style="z-index: 1000;">
                                    <button class="btn btn-sm btn-light text-start w-100" onclick="chatSelectFile('image/*')" style="font-size: 11px;"><i class="fas fa-image text-primary me-1"></i>Photo</button>
                                    <button class="btn btn-sm btn-light text-start w-100" onclick="chatSelectFile('video/*')" style="font-size: 11px;"><i class="fas fa-video text-danger me-1"></i>Video</button>
                                    <button class="btn btn-sm btn-light text-start w-100" onclick="chatSelectFile('.pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx')" style="font-size: 11px;"><i class="fas fa-file text-info me-1"></i>Document</button>
                                    <button class="btn btn-sm btn-light text-start w-100" onclick="chatSelectFile('audio/*')" style="font-size: 11px;"><i class="fas fa-music text-warning me-1"></i>Audio</button>
                                    <button class="btn btn-sm btn-light text-start w-100" onclick="chatSelectFile('*')" style="font-size: 11px;"><i class="fas fa-paperclip text-secondary me-1"></i>Any File</button>
                                    <button class="btn btn-sm btn-light text-start w-100" onclick="chatShareLocation()" style="font-size: 11px;"><i class="fas fa-map-marker-alt text-success me-1"></i>Location</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="file" id="chatFileInput" style="display: none;" multiple>

<script>
let chatCurrentUser = null;
let chatCurrentChat = null;
let guestSessionId = null;
let guestInfo = null;

// Check guest session when modal opens
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('chatModal').addEventListener('shown.bs.modal', function() {
        checkAuthAndSession();
    });
    
    // Tab switching
    document.querySelectorAll('.chat-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.chat-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            chatLoadUsers(this.dataset.type);
        });
    });
});

async function chatSelectGuestUser(id, name, location, chatId, phone) {
    console.log('Selecting guest:', { id, name, location, chatId, phone });
    chatCurrentUser = { id, type: 'guest', name, specialty: location, phone };
    
    document.querySelectorAll('.chat-user-item').forEach(item => item.classList.remove('active'));
    document.querySelector(`.chat-user-item[data-user-id="${id}"]`)?.classList.add('active');
    
    document.getElementById('chatHeaderBar').classList.remove('d-none');
    document.getElementById('chatInputArea').classList.remove('d-none');
    document.getElementById('chatAvatarIcon').textContent = name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    document.getElementById('chatUserName').textContent = name;
    document.getElementById('chatUserRole').innerHTML = `<i class="fas fa-phone me-1"></i>${phone} • <i class="fas fa-map-marker-alt me-1"></i>${location}`;
    
    // Use the chat_id from the guest contact list
    chatCurrentChat = { id: chatId };
    console.log('Chat set:', chatCurrentChat);
    await chatLoadMessages();
    
    // Mark messages as read
    console.log('Marking guest messages as read for chat:', chatId);
    const markReadResponse = await fetch('/api/chat/mark-read', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ chat_id: chatId })
    });
    const markReadResult = await markReadResponse.json();
    console.log('Mark as read result:', markReadResult);
    
    // Update badge counts immediately after marking as read
    loadGuestsBadgeCount();
    loadAllUnreadCounts();
    
    // Reload messages to show updated read status
    setTimeout(() => chatLoadMessages(), 500);
}

async function checkAuthAndSession() {
    // Check if user is authenticated
    try {
        const authResponse = await fetch('/api/chat/current-user');
        const authData = await authResponse.json();
        
        if (authData.is_authenticated) {
            // User is logged in, show user info and skip guest form
            showChatInterface();
            displayUserInfo(authData);
            chatLoadUsers();
            return;
        }
    } catch (error) {
        console.log('Not authenticated, checking guest session');
    }
    
    // Not authenticated, check guest session
    guestSessionId = localStorage.getItem('guest_session_id');
    
    if (guestSessionId) {
        try {
            const response = await fetch('/api/chat/get-guest', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ session_id: guestSessionId })
            });
            const data = await response.json();
            
            if (data.success) {
                guestInfo = data.guest;
                showChatInterface();
                chatLoadUsers();
                return;
            }
        } catch (error) {
            console.error('Error checking guest session:', error);
        }
    }
    
    showGuestRegistration();
}

function showGuestRegistration() {
    document.getElementById('guestRegistrationForm').classList.remove('d-none');
    document.getElementById('chatInterface').classList.add('d-none');
}

function showChatInterface() {
    document.getElementById('guestRegistrationForm').classList.add('d-none');
    document.getElementById('chatInterface').classList.remove('d-none');
    
    // Show guest info if guest is logged in
    if (guestInfo) {
        document.getElementById('guestInfoDisplay').classList.remove('d-none');
        document.getElementById('guestAvatarDisplay').textContent = guestInfo.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        document.getElementById('guestNameDisplay').textContent = guestInfo.name;
        document.getElementById('guestPhoneDisplay').textContent = guestInfo.phone;
        document.getElementById('guestLocationDisplay').textContent = guestInfo.location;
        
        // Hide Guests tab for guests
        const guestsTab = document.querySelector('[data-type="guests"]');
        if (guestsTab) guestsTab.style.display = 'none';
    } else {
        // Load guest badge count for authenticated users
        loadGuestsBadgeCount();
    }
}

function displayUserInfo(authData) {
    document.getElementById('userInfoDisplay').classList.remove('d-none');
    const avatarEl = document.getElementById('userAvatarDisplay');
    
    // Display profile picture if available
    if (authData.profile_picture) {
        avatarEl.innerHTML = `<img src="${authData.profile_picture}" alt="${authData.name}" style="width: 100%; height: 100%; object-fit: cover;">`;
    } else {
        avatarEl.textContent = authData.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    }
    
    document.getElementById('userNameDisplay').textContent = authData.name;
    document.getElementById('userEmailDisplay').textContent = authData.email || 'N/A';
    
    // Set role badge with icon
    const roleIcons = {
        'admin': '<i class="fas fa-user-shield me-1"></i>Admin',
        'technician': '<i class="fas fa-tools me-1"></i>Technician',
        'trainer': '<i class="fas fa-chalkboard-teacher me-1"></i>Trainer',
        'user': '<i class="fas fa-user me-1"></i>User'
    };
    document.getElementById('userRoleDisplay').innerHTML = roleIcons[authData.role] || roleIcons['user'];
}

async function submitGuestInfo(event) {
    event.preventDefault();
    
    const name = document.getElementById('guestName').value.trim();
    const phone = document.getElementById('guestPhone').value.trim();
    const location = document.getElementById('guestLocation').value.trim();
    
    if (!name || !phone || !location) {
        alert('Please fill in all fields');
        return;
    }
    
    const submitBtn = document.getElementById('guestSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Please wait...';
    
    try {
        const response = await fetch('/api/chat/register-guest', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ name, phone, location })
        });
        
        const data = await response.json();
        
        if (data.success) {
            guestSessionId = data.session_id;
            guestInfo = data.guest;
            localStorage.setItem('guest_session_id', guestSessionId);
            showChatInterface();
            chatLoadUsers();
        } else {
            alert('Failed to register. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-comments me-2"></i>Start Chat';
        }
    } catch (error) {
        console.error('Error registering guest:', error);
        alert('An error occurred. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-comments me-2"></i>Start Chat';
    }
}

async function chatLoadUsers(type = 'technicians') {
    try {
        console.log('Loading users for type:', type);
        
        // Load guest contacts if type is 'guests'
        if (type === 'guests') {
            try {
                const response = await fetch('/api/chat/guest-contacts');
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Guest contacts error:', errorText);
                    document.getElementById('chatUsersList').innerHTML = '<div class="p-3 text-center text-danger"><small>Error loading guests</small></div>';
                    return;
                }
                
                const guests = await response.json();
                console.log('Guests loaded:', guests);
                
                if (guests.length === 0) {
                    document.getElementById('chatUsersList').innerHTML = '<div class="p-3 text-center text-muted"><small>No guest contacts</small></div>';
                    updateGuestsTabBadge(0);
                    return;
                }
                
                document.getElementById('chatUsersList').innerHTML = guests.map(guest => {
                    const statusColor = guest.is_online ? 'bg-success' : 'bg-danger';
                    const statusTitle = guest.is_online ? 'Online' : 'Offline';
                    
                    return `
                    <div class="p-2 border-bottom chat-user-item" data-user-id="${guest.id}" onclick="chatSelectGuestUser(${guest.id}, '${guest.name}', '${guest.specialty}', ${guest.chat_id}, '${guest.phone}')" style="cursor: pointer;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="position-relative">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 11px;">
                                    ${guest.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)}
                                </div>
                                <div class="position-absolute bottom-0 end-0 rounded-circle border border-white ${statusColor}" style="width: 10px; height: 10px;" title="${statusTitle}"></div>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold" style="font-size: 12px;">${guest.name}</div>
                                <small class="text-muted" style="font-size: 10px;">${guest.phone} • ${guest.specialty}</small>
                            </div>
                        </div>
                    </div>
                `}).join('');
                
                // Update Guests tab badge with count of guests with unread messages
                const guestsWithUnread = guests.filter(g => g.unread_count > 0).length;
                updateGuestsTabBadge(guestsWithUnread);
            } catch (error) {
                console.error('Error loading guest contacts:', error);
                document.getElementById('chatUsersList').innerHTML = '<div class="p-3 text-center text-danger"><small>Error loading guests</small></div>';
            }
            return;
        }
        
        // Get current user info to filter them out
        let currentUserId = null;
        let currentUserType = null;
        try {
            const authResponse = await fetch('/api/chat/current-user');
            const authData = await authResponse.json();
            if (authData.is_authenticated) {
                currentUserType = authData.type;
                currentUserId = authData.id || 1;
            }
        } catch (e) {}
        
        const response = await fetch('/api/chat/support-users');
        const users = await response.json();
        console.log('Users response:', users);
        
        let usersList = users[type] || [];
        
        // Filter out current user if they are in the same category
        if (currentUserId && currentUserType) {
            const typeMap = {
                'technician': 'technicians',
                'trainer': 'trainers',
                'admin': 'admins'
            };
            if (typeMap[currentUserType] === type) {
                usersList = usersList.filter(u => u.id !== currentUserId);
            }
        }
        
        console.log('Filtered users:', usersList);
        
        if (usersList.length === 0) {
            document.getElementById('chatUsersList').innerHTML = '<div class="p-3 text-center text-muted"><small>No users available</small></div>';
            return;
        }
        
        document.getElementById('chatUsersList').innerHTML = usersList.map(user => {
            const statusColor = user.is_online ? 'bg-success' : 'bg-danger';
            const statusTitle = user.is_online ? 'Online' : 'Offline';
            
            const avatarContent = user.profile_picture 
                ? `<img src="${user.profile_picture}" alt="${user.name}" style="width: 100%; height: 100%; object-fit: cover;">`
                : user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
            
            return `
            <div class="p-2 border-bottom chat-user-item" data-user-id="${user.id}" onclick="chatSelectUser(${user.id}, '${type}', '${user.name}', '${user.specialty}', '${user.profile_picture || ''}')" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-2">
                    <div class="position-relative">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 11px; overflow: hidden;">
                            ${avatarContent}
                        </div>
                        <div class="position-absolute bottom-0 end-0 rounded-circle border border-white ${statusColor}" style="width: 10px; height: 10px;" title="${statusTitle}"></div>
                    </div>
                    <div class="flex-fill">
                        <div class="fw-bold" style="font-size: 12px;">${user.name}</div>
                        <small class="text-muted" style="font-size: 10px;">${user.specialty}</small>
                    </div>
                </div>
            </div>
        `}).join('');
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('chatUsersList').innerHTML = '<div class="p-3 text-center text-danger"><small>Error loading users</small></div>';
    }
}

function updateGuestsTabBadge(count) {
    const badge = document.getElementById('guestsTabBadge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }
}

function updateAllTabBadges(counts) {
    const badges = {
        'technicians': document.getElementById('techniciansTabBadge'),
        'trainers': document.getElementById('trainersTabBadge'),
        'admins': document.getElementById('adminsTabBadge'),
        'guests': document.getElementById('guestsTabBadge')
    };
    
    Object.keys(badges).forEach(type => {
        const badge = badges[type];
        if (badge && counts[type] !== undefined) {
            if (counts[type] > 0) {
                badge.textContent = counts[type];
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }
    });
}

async function loadAllUnreadCounts() {
    try {
        const response = await fetch('/api/chat/unread-counts');
        if (response.ok) {
            const counts = await response.json();
            updateAllTabBadges(counts);
        }
    } catch (error) {
        console.error('Error loading unread counts:', error);
    }
}

async function loadGuestsBadgeCount() {
    try {
        const response = await fetch('/api/chat/guest-contacts');
        if (response.ok) {
            const guests = await response.json();
            const guestsWithUnread = guests.filter(g => g.unread_count > 0).length;
            updateGuestsTabBadge(guestsWithUnread);
        }
    } catch (error) {
        console.error('Error loading guests badge:', error);
    }
    
    // Also load all other tab badges
    loadAllUnreadCounts();
}

async function chatSelectUser(id, type, name, specialty, profilePicture) {
    console.log('Selecting user:', { id, type, name, specialty, profilePicture });
    chatCurrentUser = { id, type, name, specialty, profile_picture: profilePicture };
    
    // Highlight selected user
    document.querySelectorAll('.chat-user-item').forEach(item => item.classList.remove('active'));
    document.querySelector(`.chat-user-item[data-user-id="${id}"]`)?.classList.add('active');
    
    // Get user's online status and location
    const response = await fetch('/api/chat/support-users');
    const users = await response.json();
    const user = users[type].find(u => u.id === id);
    chatCurrentUser.is_online = user?.is_online || false;
    chatCurrentUser.location = user?.location || 'N/A';
    
    // Show chat interface
    document.getElementById('chatHeaderBar').classList.remove('d-none');
    document.getElementById('chatInputArea').classList.remove('d-none');
    
    const chatAvatarIcon = document.getElementById('chatAvatarIcon');
    if (profilePicture && profilePicture !== 'null' && profilePicture !== '') {
        chatAvatarIcon.innerHTML = `<img src="${profilePicture}" alt="${name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
    } else {
        chatAvatarIcon.textContent = name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        chatAvatarIcon.style.fontSize = '14px';
    }
    
    document.getElementById('chatUserName').textContent = name;
    
    // Show location and online/offline status
    const statusText = chatCurrentUser.is_online ? '<span class="text-success"><i class="fas fa-circle" style="font-size: 8px;"></i> Online</span>' : '<span class="text-danger"><i class="fas fa-circle" style="font-size: 8px;"></i> Offline</span>';
    document.getElementById('chatUserRole').innerHTML = `${specialty} • <i class="fas fa-map-marker-alt me-1"></i>${chatCurrentUser.location} • ${statusText}`;
    
    // Get or create chat
    try {
        console.log('Creating chat with guest:', guestInfo);
        const chatResponse = await fetch('/api/chat/get-or-create', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Guest-Session': guestSessionId || ''
            },
            body: JSON.stringify({ 
                user_id: guestInfo?.id || 0, 
                support_type: type, 
                support_id: id,
                guest_name: guestInfo?.name || 'Guest',
                guest_phone: guestInfo?.phone || '',
                guest_location: guestInfo?.location || ''
            })
        });
        chatCurrentChat = await chatResponse.json();
        console.log('Chat created:', chatCurrentChat);
        await chatLoadMessages();
        
        // Mark messages as read
        console.log('Marking messages as read for chat:', chatCurrentChat.id);
        const markReadResponse = await fetch('/api/chat/mark-read', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Guest-Session': guestSessionId || ''
            },
            body: JSON.stringify({ chat_id: chatCurrentChat.id })
        });
        const markReadResult = await markReadResponse.json();
        console.log('Mark as read result:', markReadResult);
        
        // Update badge counts immediately after marking as read
        loadAllUnreadCounts();
        
        // Reload messages to show updated read status
        setTimeout(() => chatLoadMessages(), 500);
    } catch (error) {
        console.error('Error creating chat:', error);
        alert('Failed to create chat');
    }
}

async function chatSendMessage() {
    const input = document.getElementById('chatMessageInput');
    const message = input.value.trim();
    if (!message || !chatCurrentChat) {
        console.log('Cannot send:', { message, chatCurrentChat });
        return;
    }
    
    console.log('Sending message:', message);
    
    try {
        // Check if user is authenticated
        const authResponse = await fetch('/api/chat/current-user');
        const authData = await authResponse.json();
        
        let senderType = 'guest';
        let senderId = guestInfo?.id || 0;
        
        if (authData.is_authenticated) {
            senderType = authData.type;
            senderId = authData.id;
        }
        
        console.log('Sending as:', { senderType, senderId });
        
        const response = await fetch('/api/chat/send-message', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Guest-Session': guestSessionId || ''
            },
            body: JSON.stringify({
                chat_id: chatCurrentChat.id,
                sender_type: senderType,
                sender_id: senderId,
                message: message,
                message_type: 'text'
            })
        });
        
        const result = await response.json();
        console.log('Send result:', result);
        
        if (response.ok && result.success) {
            input.value = '';
            input.style.height = 'auto';
            chatLoadMessages();
        } else {
            console.error('Failed to send:', result);
            alert('Failed to send message');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Error sending message');
    }
}

// Auto-expand textarea
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('chatMessageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
        
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatSendMessage();
            }
        });
    }
});

function chatSelectFile(accept) {
    const fileInput = document.getElementById('chatFileInput');
    fileInput.accept = accept === '*' ? '' : accept;
    fileInput.onchange = function(e) {
        Array.from(e.target.files).forEach(file => chatUploadFile(file));
        e.target.value = '';
    };
    fileInput.click();
    document.getElementById('chatAttachMenu').classList.add('d-none');
}

async function chatUploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    try {
        const response = await fetch('/api/chat/upload-file', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        });
        const data = await response.json();
        
        if (data.url) {
            await chatSendFileMessage(data.name, data.url, data.type);
        }
    } catch (error) {
        console.error('Upload error:', error);
    }
}

async function chatSendFileMessage(fileName, filePath, fileType) {
    const messageType = fileType.startsWith('image/') ? 'image' : 
                       fileType.startsWith('video/') ? 'video' : 
                       fileType.startsWith('audio/') ? 'audio' : 'file';
    
    await fetch('/api/chat/send-message', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Guest-Session': guestSessionId || ''
        },
        body: JSON.stringify({
            chat_id: chatCurrentChat.id,
            sender_type: 'guest',
            sender_id: guestInfo?.id || 0,
            message: fileName,
            message_type: messageType,
            file_path: filePath,
            receiver_type: chatCurrentUser.type,
            receiver_name: chatCurrentUser.name
        })
    });
    
    chatLoadMessages();
}

async function chatLoadMessages() {
    if (!chatCurrentChat) return;
    
    try {
        const response = await fetch(`/api/chat/${chatCurrentChat.id}/messages`, {
            headers: { 'X-Guest-Session': guestSessionId || '' }
        });
        const messages = await response.json();
        console.log('Loaded messages:', messages);
        
        const messagesArea = document.getElementById('chatMessagesArea');
        if (messages.length === 0) {
            messagesArea.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-comment-dots fa-2x mb-2 opacity-25"></i><p style="font-size: 12px;">No messages yet</p></div>';
            return;
        }
        
        // Check if user is authenticated
        let currentUserId = guestInfo?.id || 0;
        let currentUserType = 'guest';
        
        try {
            const authResponse = await fetch('/api/chat/current-user');
            const authData = await authResponse.json();
            if (authData.is_authenticated) {
                currentUserType = authData.type;
                currentUserId = authData.id;
            }
        } catch (e) {}
        
        console.log('Current user:', { currentUserType, currentUserId });
        
        let html = '';
        let lastDate = null;
        
        messages.forEach((msg, index) => {
            const msgDate = new Date(msg.created_at);
            const dateStr = formatMessageDate(msgDate);
            
            // Add date separator if date changed
            if (dateStr !== lastDate) {
                html += `<div class="text-center my-2"><span class="badge bg-light text-dark" style="font-size: 11px; padding: 4px 12px; border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">${dateStr}</span></div>`;
                lastDate = dateStr;
            }
            
            const time = msgDate.toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit', hour12: true});
            const isUser = msg.sender_type === currentUserType && msg.sender_id === currentUserId;
            
            console.log('Message:', { sender_type: msg.sender_type, sender_id: msg.sender_id, isUser });
            
            // Read status
            const readStatus = msg.is_read ? 
                '<i class="fas fa-check-double" style="color: #53bdeb;"></i>' : 
                '<i class="fas fa-check" style="color: #8696a0;"></i>';
            
            let content = '';
            
            // Handle different message types
            if (msg.message_type === 'image' && msg.file_path) {
                content = `<img src="${msg.file_path}" class="img-fluid rounded" style="max-width: 250px; cursor: pointer;" onclick="window.open('${msg.file_path}', '_blank')" title="Click to view full size">`;
            } else if (msg.message_type === 'video' && msg.file_path) {
                content = `<video controls class="rounded" style="max-width: 250px;"><source src="${msg.file_path}">Your browser does not support video.</video>`;
            } else if (msg.message_type === 'audio' && msg.file_path) {
                content = `<div class="d-flex align-items-center gap-2"><i class="fas fa-music text-primary"></i><audio controls style="max-width: 200px;"><source src="${msg.file_path}">Your browser does not support audio.</audio></div>`;
            } else if ((msg.message_type === 'file' || msg.message_type === 'document') && msg.file_path) {
                const fileName = msg.file_name || msg.message || 'Download File';
                const fileIcon = getFileIcon(msg.file_type);
                content = `<div class="d-flex align-items-center gap-2 p-2" style="cursor: pointer; background: rgba(0,0,0,0.05); border-radius: 8px; min-width: 200px;" onclick="window.open('${msg.file_path}', '_blank')" title="Click to download">
                    <i class="${fileIcon} fa-2x text-primary"></i>
                    <div class="flex-fill">
                        <div class="fw-bold" style="font-size: 13px;">${escapeHtml(fileName)}</div>
                        <small class="text-muted">Click to download</small>
                    </div>
                    <i class="fas fa-download text-secondary"></i>
                </div>`;
            } else if (msg.message_type === 'location' && msg.file_path) {
                content = `<div class="d-flex align-items-center gap-2 p-2" style="cursor: pointer; background: rgba(0,0,0,0.05); border-radius: 8px;" onclick="window.open('${msg.file_path}', '_blank')" title="Click to view location">
                    <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                    <div class="flex-fill">
                        <div class="fw-bold" style="font-size: 13px;">Location</div>
                        <small class="text-muted">Click to view on map</small>
                    </div>
                    <i class="fas fa-external-link-alt text-secondary"></i>
                </div>`;
            } else {
                content = `<span style="font-size: 14px;">${escapeHtml(msg.message || '')}</span>`;
            }
            
            const paddingStyle = (msg.message_type === 'image' || msg.message_type === 'video') ? '4px' : '6px 7px 8px 9px';
            
            html += `<div class="mb-1 d-flex ${isUser ? 'justify-content-end' : 'justify-content-start'}">
                <div class="d-inline-block" style="max-width: 65%;">
                    <div class="d-inline-block" style="font-size: 14px; background: ${isUser ? '#d9fdd3' : '#ffffff'}; color: #000000; padding: ${paddingStyle}; border-radius: 7.5px; box-shadow: 0 1px 0.5px rgba(0,0,0,0.13); word-wrap: break-word; word-break: break-word; white-space: pre-wrap; line-height: 1.4; position: relative;">
                        ${content}
                        <span style="font-size: 11px; color: rgba(0,0,0,0.45); margin-left: 8px; float: right; margin-top: 2px;">${time} ${isUser ? readStatus : ''}</span>
                    </div>
                </div>
            </div>`;
        });
        
        messagesArea.innerHTML = html;
        messagesArea.scrollTop = messagesArea.scrollHeight;
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

function formatMessageDate(date) {
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    
    const msgDate = new Date(date);
    msgDate.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);
    yesterday.setHours(0, 0, 0, 0);
    
    if (msgDate.getTime() === today.getTime()) {
        return 'Today';
    } else if (msgDate.getTime() === yesterday.getTime()) {
        return 'Yesterday';
    } else if (date.getFullYear() === new Date().getFullYear()) {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    } else {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getFileIcon(fileType) {
    if (!fileType) return 'fas fa-file';
    if (fileType.includes('pdf')) return 'fas fa-file-pdf';
    if (fileType.includes('word') || fileType.includes('doc')) return 'fas fa-file-word';
    if (fileType.includes('excel') || fileType.includes('sheet')) return 'fas fa-file-excel';
    if (fileType.includes('powerpoint') || fileType.includes('presentation')) return 'fas fa-file-powerpoint';
    if (fileType.includes('zip') || fileType.includes('rar') || fileType.includes('compressed')) return 'fas fa-file-archive';
    if (fileType.includes('text')) return 'fas fa-file-alt';
    if (fileType.includes('image')) return 'fas fa-file-image';
    if (fileType.includes('video')) return 'fas fa-file-video';
    if (fileType.includes('audio')) return 'fas fa-file-audio';
    return 'fas fa-file';
}

function chatToggleEmoji() {
    document.getElementById('chatEmojiPicker').classList.toggle('d-none');
    document.getElementById('chatAttachMenu').classList.add('d-none');
}

// Emoji search functionality
document.addEventListener('DOMContentLoaded', function() {
    const emojiSearch = document.getElementById('emojiSearch');
    if (emojiSearch) {
        emojiSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const emojis = document.querySelectorAll('.chat-emoji');
            
            emojis.forEach(emoji => {
                const title = emoji.getAttribute('title')?.toLowerCase() || '';
                if (title.includes(searchTerm) || searchTerm === '') {
                    emoji.style.display = 'inline-block';
                } else {
                    emoji.style.display = 'none';
                }
            });
        });
    }
    
    // Click emoji to insert
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('chat-emoji')) {
            const input = document.getElementById('chatMessageInput');
            input.value += e.target.textContent;
            input.focus();
        }
    });
    
    // Close emoji/attach menus when clicking outside
    document.addEventListener('click', function(e) {
        const emojiPicker = document.getElementById('chatEmojiPicker');
        const attachMenu = document.getElementById('chatAttachMenu');
        const emojiBtn = e.target.closest('[onclick="chatToggleEmoji()"]');
        const attachBtn = e.target.closest('[onclick="chatToggleAttach()"]');
        
        if (!emojiPicker.contains(e.target) && !emojiBtn) {
            emojiPicker.classList.add('d-none');
        }
        if (!attachMenu.contains(e.target) && !attachBtn) {
            attachMenu.classList.add('d-none');
        }
    });
});

function chatToggleAttach() {
    document.getElementById('chatAttachMenu').classList.toggle('d-none');
    document.getElementById('chatEmojiPicker').classList.add('d-none');
}

function chatShareLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const locationUrl = `https://maps.google.com/?q=${lat},${lng}`;
            
            await fetch('/api/chat/send-message', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Guest-Session': guestSessionId || ''
                },
                body: JSON.stringify({
                    chat_id: chatCurrentChat.id,
                    sender_type: 'guest',
                    sender_id: guestInfo?.id || 0,
                    message: `Location: ${lat}, ${lng}`,
                    message_type: 'location',
                    file_path: locationUrl,
                    receiver_type: chatCurrentUser.type,
                    receiver_name: chatCurrentUser.name
                })
            });
            
            chatLoadMessages();
        });
    }
    document.getElementById('chatAttachMenu').classList.add('d-none');
}

function chatRefresh() {
    const icon = document.getElementById('chatRefreshIcon');
    icon.classList.add('fa-spin');
    
    if (chatCurrentChat) {
        chatLoadMessages().then(() => {
            setTimeout(() => icon.classList.remove('fa-spin'), 500);
        });
    } else {
        chatLoadUsers().then(() => {
            setTimeout(() => icon.classList.remove('fa-spin'), 500);
        });
    }
}

function showChatInfo() {
    alert('Chat info feature coming soon!');
}

function chatCallUser() {
    if (chatCurrentUser?.phone) {
        window.location.href = `tel:${chatCurrentUser.phone}`;
    }
}

function chatVideoCall() {
    if (!chatCurrentUser) {
        alert('Please select a contact first');
        return;
    }
    
    // Create video call modal
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-video me-2"></i>Video Call with ${chatCurrentUser.name}</h6>
                    <button type="button" class="btn-close btn-close-white" onclick="chatEndVideoCall()"></button>
                </div>
                <div class="modal-body p-0" style="height: 400px; background: #000;">
                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                        <div class="text-center">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                ${chatCurrentUser.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)}
                            </div>
                            <h5>${chatCurrentUser.name}</h5>
                            <p class="text-muted">${chatCurrentUser.specialty}</p>
                            <div class="mt-4">
                                <div class="spinner-border text-primary me-2" role="status"></div>
                                <span>Connecting...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-danger" onclick="chatEndVideoCall()"><i class="fas fa-phone-slash me-2"></i>End Call</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Simulate call connection
    setTimeout(() => {
        const connectingText = modal.querySelector('.modal-body .text-center');
        if (connectingText) {
            connectingText.innerHTML = `
                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    ${chatCurrentUser.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)}
                </div>
                <h5>${chatCurrentUser.name}</h5>
                <p class="text-muted">${chatCurrentUser.specialty}</p>
                <div class="mt-4">
                    <span class="text-success"><i class="fas fa-circle me-1"></i>Connected</span>
                </div>
            `;
        }
    }, 2000);
    
    window.currentVideoCallModal = bsModal;
}

function chatEndVideoCall() {
    if (window.currentVideoCallModal) {
        window.currentVideoCallModal.hide();
        setTimeout(() => {
            const modal = document.querySelector('.modal');
            if (modal) modal.remove();
        }, 300);
    }
}

function getFileIcon(fileType) {
    if (!fileType) return 'fas fa-file';
    if (fileType.includes('pdf')) return 'fas fa-file-pdf';
    if (fileType.includes('word') || fileType.includes('doc')) return 'fas fa-file-word';
    if (fileType.includes('excel') || fileType.includes('sheet')) return 'fas fa-file-excel';
    if (fileType.includes('powerpoint') || fileType.includes('presentation')) return 'fas fa-file-powerpoint';
    if (fileType.includes('zip') || fileType.includes('rar')) return 'fas fa-file-archive';
    if (fileType.includes('text')) return 'fas fa-file-alt';
    return 'fas fa-file';
}

function formatFileSize(bytes) {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

// Auto-refresh messages every 2 seconds
let lastMessageCount = 0;
let lastMessageData = null;
setInterval(() => {
    if (chatCurrentChat) {
        fetch(`/api/chat/${chatCurrentChat.id}/messages`, {
            headers: { 'X-Guest-Session': guestSessionId || '' }
        })
        .then(response => response.json())
        .then(messages => {
            const currentData = JSON.stringify(messages);
            // Reload if message count changed OR message data changed (read status updated)
            if (messages.length !== lastMessageCount || currentData !== lastMessageData) {
                lastMessageCount = messages.length;
                lastMessageData = currentData;
                chatLoadMessages();
            }
        });
    }
}, 2000);

// Update badge counts every 5 seconds
setInterval(() => {
    if (!guestInfo) {
        loadAllUnreadCounts();
    }
}, 5000);

// Update last_seen every 30 seconds for authenticated users
setInterval(() => {
    if (!guestInfo) {
        fetch('/api/chat/update-last-seen', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }
}, 30000);

// Update online status in user list every 15 seconds
setInterval(() => {
    if (chatCurrentUser && !guestInfo) {
        fetch('/api/chat/support-users')
        .then(response => response.json())
        .then(users => {
            const activeTab = document.querySelector('.chat-tab.active')?.dataset.type;
            if (activeTab && activeTab !== 'guests') {
                const usersList = users[activeTab] || [];
                usersList.forEach(user => {
                    const userItem = document.querySelector(`.chat-user-item[data-user-id="${user.id}"]`);
                    if (userItem) {
                        const statusDot = userItem.querySelector('.position-absolute.rounded-circle');
                        if (statusDot) {
                            statusDot.className = `position-absolute bottom-0 end-0 rounded-circle border border-white ${user.is_online ? 'bg-success' : 'bg-danger'}`;
                            statusDot.title = user.is_online ? 'Online' : 'Offline';
                        }
                    }
                });
            }
        });
    }
}, 15000);
</script>

<style>
.chat-user-item {
    transition: background-color 0.2s;
}
.chat-user-item:hover {
    background-color: #f8f9fa;
}
.chat-user-item.active {
    background-color: #e7f3ff;
    border-left: 3px solid #0d6efd;
}
.chat-emoji {
    font-size: 24px;
    cursor: pointer;
    padding: 4px;
    display: inline-block;
    transition: transform 0.2s;
    border-radius: 4px;
}
.chat-emoji:hover {
    transform: scale(1.3);
    background: #f0f0f0;
}
</style>
