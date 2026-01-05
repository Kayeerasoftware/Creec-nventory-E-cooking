<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; }
        .chat-container { display: flex; height: 100vh; max-width: 1400px; margin: 0 auto; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .users-panel { width: 380px; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; background: white; }
        .users-header { padding: 15px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .search-box { padding: 10px; background: #f6f6f6; }
        .search-box input { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 20px; outline: none; }
        .user-tabs { display: flex; background: #f6f6f6; border-bottom: 1px solid #e0e0e0; }
        .user-tab { flex: 1; padding: 12px; text-align: center; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; }
        .user-tab.active { background: white; border-bottom-color: #667eea; color: #667eea; font-weight: 600; }
        .users-list { flex: 1; overflow-y: auto; }
        .user-item { padding: 12px 20px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 12px; }
        .user-item:hover { background: #f5f5f5; }
        .user-item.active { background: #e8f4f8; }
        .user-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; flex-shrink: 0; object-fit: cover; }
        .user-avatar img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-weight: 600; font-size: 15px; margin-bottom: 2px; }
        .user-specialty { font-size: 13px; color: #667; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-status { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .status-available { background: #25d366; }
        .status-busy { background: #ffa500; }
        .status-unavailable { background: #dc3545; }
        .chat-panel { flex: 1; display: flex; flex-direction: column; background: #e5ddd5; }
        .chat-header { padding: 12px 20px; background: #f0f0f0; border-bottom: 1px solid #ddd; display: flex; align-items: center; justify-content: space-between; }
        .chat-header-info { display: flex; align-items: center; gap: 12px; }
        .chat-header-actions { display: flex; gap: 8px; }
        .messages-container { flex: 1; overflow-y: auto; padding: 20px; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect fill="%23e5ddd5" width="100" height="100"/><circle fill="%23d9d9d9" opacity="0.1" cx="50" cy="50" r="40"/></svg>'); }
        .message { margin-bottom: 12px; display: flex; align-items: flex-end; gap: 8px; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .message.sent { flex-direction: row-reverse; }
        .message-bubble { max-width: 65%; padding: 8px 12px; border-radius: 8px; position: relative; word-wrap: break-word; }
        .message.received .message-bubble { background: white; border-bottom-left-radius: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .message.sent .message-bubble { background: #dcf8c6; border-bottom-right-radius: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .message-text { margin: 0; font-size: 14px; line-height: 1.5; }
        .message-time { font-size: 11px; color: #667; margin-top: 4px; display: flex; align-items: center; gap: 4px; justify-content: flex-end; }
        .message-reply { background: rgba(0,0,0,0.05); padding: 6px 8px; border-radius: 4px; margin-bottom: 6px; border-left: 3px solid #667eea; font-size: 13px; }
        .message-file { display: flex; align-items: center; gap: 8px; padding: 8px; background: rgba(0,0,0,0.05); border-radius: 6px; margin-top: 6px; }
        .message-image { max-width: 100%; border-radius: 6px; margin-top: 6px; cursor: pointer; }
        .input-container { padding: 12px 20px; background: #f0f0f0; border-top: 1px solid #ddd; }
        .input-wrapper { display: flex; align-items: flex-end; gap: 8px; }
        .input-actions { display: flex; gap: 4px; }
        .input-btn { width: 40px; height: 40px; border: none; background: transparent; color: #54656f; cursor: pointer; border-radius: 50%; transition: background 0.2s; display: flex; align-items: center; justify-content: center; }
        .input-btn:hover { background: #e0e0e0; }
        .message-input { flex: 1; padding: 10px 15px; border: none; border-radius: 20px; outline: none; resize: none; max-height: 100px; font-size: 15px; }
        .send-btn { width: 40px; height: 40px; border: none; background: #667eea; color: white; border-radius: 50%; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; }
        .send-btn:hover { background: #5568d3; }
        .send-btn:disabled { background: #ccc; cursor: not-allowed; }
        .emoji-picker { position: absolute; bottom: 60px; right: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 12px; display: none; z-index: 1000; }
        .emoji-picker.show { display: block; }
        .emoji { font-size: 24px; cursor: pointer; padding: 4px; display: inline-block; transition: transform 0.2s; }
        .emoji:hover { transform: scale(1.3); }
        .typing-indicator { display: flex; align-items: center; gap: 4px; padding: 8px 12px; }
        .typing-dot { width: 8px; height: 8px; border-radius: 50%; background: #90949c; animation: typing 1.4s infinite; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-10px); } }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #667; }
        .empty-state i { font-size: 64px; margin-bottom: 16px; opacity: 0.3; }
        .reply-preview { padding: 8px 12px; background: #f0f0f0; border-left: 3px solid #667eea; display: none; align-items: center; justify-content: space-between; }
        .reply-preview.show { display: flex; }
        .attachment-menu { position: absolute; bottom: 60px; left: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 8px; display: none; z-index: 1000; }
        .attachment-menu.show { display: block; }
        .attachment-item { padding: 10px 16px; cursor: pointer; border-radius: 4px; display: flex; align-items: center; gap: 12px; transition: background 0.2s; }
        .attachment-item:hover { background: #f5f5f5; }
        .attachment-item i { width: 24px; text-align: center; }
        .badge-count { background: #25d366; color: white; border-radius: 10px; padding: 2px 6px; font-size: 11px; font-weight: bold; }
        @media (max-width: 768px) {
            .users-panel { width: 100%; position: absolute; left: 0; z-index: 10; transform: translateX(-100%); transition: transform 0.3s; }
            .users-panel.show { transform: translateX(0); }
            .chat-panel { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- Users Panel -->
        <div class="users-panel" id="usersPanel">
            <div class="users-header">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Support Chat</h5>
                <small>Select who to chat with</small>
            </div>
            <div class="search-box">
                <input type="text" id="userSearch" placeholder="Search contacts...">
            </div>
            <div class="user-tabs">
                <div class="user-tab active" data-type="technicians">
                    <i class="fas fa-user-cog"></i> Technicians
                </div>
                <div class="user-tab" data-type="trainers">
                    <i class="fas fa-chalkboard-teacher"></i> Trainers
                </div>
                <div class="user-tab" data-type="admins">
                    <i class="fas fa-user-shield"></i> Admins
                </div>
            </div>
            <div class="users-list" id="usersList"></div>
        </div>

        <!-- Chat Panel -->
        <div class="chat-panel">
            <div class="chat-header" id="chatHeader" style="display: none;">
                <div class="chat-header-info">
                    <button class="input-btn d-md-none" onclick="toggleUsersPanel()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="user-avatar" id="chatAvatar"></div>
                    <div>
                        <div class="user-name" id="chatName"></div>
                        <small class="user-specialty" id="chatSpecialty"></small>
                    </div>
                </div>
                <div class="chat-header-actions">
                    <button class="input-btn" onclick="searchMessages()"><i class="fas fa-search"></i></button>
                    <button class="input-btn" onclick="callUser()"><i class="fas fa-phone"></i></button>
                    <button class="input-btn" onclick="videoCall()"><i class="fas fa-video"></i></button>
                    <button class="input-btn" onclick="showChatInfo()"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer">
                <div class="empty-state">
                    <i class="fas fa-comments"></i>
                    <h5>Select a contact to start chatting</h5>
                    <p>Choose from technicians, trainers, or admins</p>
                </div>
            </div>

            <div class="reply-preview" id="replyPreview">
                <div>
                    <small class="text-muted">Replying to</small>
                    <div id="replyText"></div>
                </div>
                <button class="input-btn" onclick="cancelReply()"><i class="fas fa-times"></i></button>
            </div>

            <div class="input-container" id="inputContainer" style="display: none;">
                <div class="input-wrapper">
                    <div class="input-actions">
                        <button class="input-btn" onclick="toggleEmojiPicker()"><i class="fas fa-smile"></i></button>
                        <button class="input-btn" onclick="toggleAttachmentMenu()"><i class="fas fa-paperclip"></i></button>
                    </div>
                    <textarea class="message-input" id="messageInput" placeholder="Type a message..." rows="1"></textarea>
                    <button class="send-btn" id="sendBtn" onclick="sendMsg()"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>

        <!-- Emoji Picker -->
        <div class="emoji-picker" id="emojiPicker">
            <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 300px;">
                @foreach(['😀','😂','😍','🥰','😊','😎','🤔','😮','😢','😡','👍','👎','❤️','🔥','💯','🎉','✅','❌','⭐','💪','🙏','👏','🎊','🎈','🌟','💖','😘','🤗','😇','🥳'] as $emoji)
                    <span class="emoji" onclick="insertEmoji('{{ $emoji }}')">{{ $emoji }}</span>
                @endforeach
            </div>
        </div>

        <!-- Attachment Menu -->
        <div class="attachment-menu" id="attachmentMenu">
            <div class="attachment-item" onclick="selectFile('image')">
                <i class="fas fa-image" style="color: #667eea;"></i>
                <span>Photo</span>
            </div>
            <div class="attachment-item" onclick="selectFile('video')">
                <i class="fas fa-video" style="color: #e74c3c;"></i>
                <span>Video</span>
            </div>
            <div class="attachment-item" onclick="selectFile('document')">
                <i class="fas fa-file" style="color: #3498db;"></i>
                <span>Document</span>
            </div>
            <div class="attachment-item" onclick="shareLocation()">
                <i class="fas fa-map-marker-alt" style="color: #27ae60;"></i>
                <span>Location</span>
            </div>
        </div>
    </div>

    <input type="file" id="fileInput" style="display: none;" accept="*/*">

    <script>
    let chat = null;
    
    fetch('/api/chat/support-users').then(r => r.json()).then(data => {
        document.getElementById('usersList').innerHTML = (data.technicians || []).map(u => {
            const avatarContent = u.profile_picture 
                ? `<img src="${u.profile_picture}" alt="${u.name}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`
                : u.name.substring(0,2).toUpperCase();
            return `<div class="user-item" onclick="openChat(${u.id}, '${u.type}', '${u.name}', '${u.specialty}', '${u.profile_picture || ''}')">
                <div class="user-avatar">${avatarContent}</div>
                <div class="user-info"><div class="user-name">${u.name}</div><div class="user-specialty">${u.specialty}</div></div>
            </div>`;
        }).join('');
    });
    
    async function openChat(id, type, name, specialty, profilePic) {
        document.getElementById('chatHeader').style.display = 'flex';
        document.getElementById('inputContainer').style.display = 'block';
        const chatAvatar = document.getElementById('chatAvatar');
        if (profilePic && profilePic !== 'null' && profilePic !== '') {
            chatAvatar.innerHTML = `<img src="${profilePic}" alt="${name}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`;
        } else {
            chatAvatar.textContent = name.substring(0,2).toUpperCase();
            chatAvatar.style.fontSize = '';
        }
        document.getElementById('chatName').textContent = name;
        document.getElementById('chatSpecialty').textContent = specialty;
        
        const r = await fetch('/api/chat/get-or-create', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({user_id: 1, support_type: type, support_id: id})
        });
        chat = await r.json();
        loadMsgs();
    }
    
    async function loadMsgs() {
        if (!chat) return;
        const r = await fetch(`/api/chat/${chat.id}/messages`);
        const msgs = await r.json();
        const c = document.getElementById('messagesContainer');
        
        if (msgs.length === 0) {
            c.innerHTML = '<div class="empty-state"><i class="fas fa-comment-dots"></i><h5>No messages yet</h5><p>Start the conversation</p></div>';
            return;
        }
        
        c.innerHTML = msgs.map(m => {
            const sent = m.sender === 'user';
            const time = new Date(m.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'});
            let content = m.type === 'image' ? `<img src="${m.file_path}" class="message-image">` :
                         m.type === 'file' ? `<div class="message-file"><i class="fas fa-file"></i><a href="${m.file_path}" download>Download</a></div>` :
                         `<p class="message-text">${m.message || ''}</p>`;
            return `<div class="message ${sent ? 'sent' : 'received'}"><div class="message-bubble">${content}<div class="message-time">${time}</div></div></div>`;
        }).join('');
        c.scrollTop = c.scrollHeight;
    }
    
    async function sendMsg() {
        const inp = document.getElementById('messageInput');
        const msg = inp.value.trim();
        if (!msg || !chat) { alert('Please type a message and select a contact'); return; }
        
        const res = await fetch('/api/chat/send-message', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({chat_id: chat.id, sender_type: 'user', sender_id: 1, message: msg, message_type: 'text'})
        });
        
        if (res.ok) {
            inp.value = '';
            await loadMsgs();
        } else {
            alert('Failed to send message: ' + res.status);
        }
    }
    
    document.getElementById('messageInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMsg();
        }
    });
    
    document.getElementById('fileInput').addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file || !chat) return;
        
        const fd = new FormData();
        fd.append('file', file);
        
        const r1 = await fetch('/api/chat/upload-file', {method: 'POST', body: fd});
        if (!r1.ok) { alert('Upload failed'); return; }
        
        const data = await r1.json();
        const msgType = file.type.startsWith('image/') ? 'image' : 'file';
        
        const r2 = await fetch('/api/chat/send-message', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({chat_id: chat.id, sender_type: 'user', sender_id: 1, message: file.name, message_type: msgType, file_path: data.url})
        });
        
        if (r2.ok) {
            e.target.value = '';
            loadMsgs();
        } else {
            alert('Failed to send file');
        }
    });
    
    function selectFile(type) {
        document.getElementById('fileInput').accept = type === 'image' ? 'image/*' : type === 'video' ? 'video/*' : '*/*';
        document.getElementById('fileInput').click();
        document.getElementById('attachmentMenu').classList.remove('show');
    }
    
    function toggleEmojiPicker() { document.getElementById('emojiPicker').classList.toggle('show'); }
    function toggleAttachmentMenu() { document.getElementById('attachmentMenu').classList.toggle('show'); }
    function insertEmoji(e) { document.getElementById('messageInput').value += e; }
    function toggleUsersPanel() { document.getElementById('usersPanel').classList.toggle('show'); }
    function searchMessages() {}
    function callUser() {}
    function videoCall() {}
    function showChatInfo() {}
    function cancelReply() {}
    function shareLocation() {}
    
    setInterval(() => chat && loadMsgs(), 5000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
