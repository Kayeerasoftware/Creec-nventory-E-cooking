let currentChat = null;
let currentUser = null;
let allUsers = { technicians: [], trainers: [], admins: [] };
let activeTab = 'technicians';

document.addEventListener('DOMContentLoaded', init);

function init() {
    loadSupportUsers();
    
    document.querySelectorAll('.user-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.user-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            activeTab = tab.dataset.type;
            renderUsersList();
        });
    });

    document.getElementById('userSearch').addEventListener('input', (e) => renderUsersList(e.target.value));
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    document.getElementById('fileInput').addEventListener('change', handleFileSelect);
}

async function loadSupportUsers() {
    const response = await fetch('/api/chat/support-users');
    allUsers = await response.json();
    renderUsersList();
}

function renderUsersList(searchTerm = '') {
    const usersList = document.getElementById('usersList');
    const users = allUsers[activeTab] || [];
    const filtered = users.filter(u => u.name.toLowerCase().includes(searchTerm.toLowerCase()));

    usersList.innerHTML = filtered.map(user => `
        <div class="user-item" onclick='selectUser(${JSON.stringify(user).replace(/'/g, "&apos;")})'>
            <div class="user-avatar">${user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)}</div>
            <div class="user-info">
                <div class="user-name">${user.name}</div>
                <div class="user-specialty">${user.specialty}</div>
            </div>
            <div class="user-status status-available"></div>
        </div>
    `).join('');
}

async function selectUser(user) {
    currentUser = user;
    document.getElementById('chatHeader').style.display = 'flex';
    document.getElementById('inputContainer').style.display = 'block';
    document.getElementById('chatAvatar').textContent = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    document.getElementById('chatName').textContent = user.name;
    document.getElementById('chatSpecialty').textContent = user.specialty;
    
    const response = await fetch('/api/chat/get-or-create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: 1, support_type: user.type, support_id: user.id })
    });
    currentChat = await response.json();
    loadMessages();
}

async function loadMessages() {
    if (!currentChat) return;
    const response = await fetch(`/api/chat/${currentChat.id}/messages`);
    const messages = await response.json();
    
    const container = document.getElementById('messagesContainer');
    if (messages.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="fas fa-comment-dots"></i><h5>No messages yet</h5></div>';
        return;
    }
    
    container.innerHTML = messages.map(msg => {
        const isSent = msg.sender_type === 'user';
        const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        let content = '';
        
        if (msg.message_type === 'image') {
            content = `<img src="${msg.file_path}" class="message-image" alt="Image">`;
        } else if (msg.message_type === 'file' || msg.message_type === 'video') {
            const fileName = msg.file_path.split('/').pop();
            content = `<div class="message-file"><i class="fas fa-file fa-2x"></i><div><strong>${fileName}</strong><br><a href="${msg.file_path}" download>Download</a></div></div>`;
        } else {
            content = `<p class="message-text">${msg.message}</p>`;
        }
        
        return `
            <div class="message ${isSent ? 'sent' : 'received'}">
                <div class="message-bubble">
                    ${content}
                    <div class="message-time">${time}</div>
                </div>
            </div>
        `;
    }).join('');
    
    container.scrollTop = container.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    if (!message || !currentChat) return;
    
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    
    await fetch('/api/chat/send-message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            chat_id: currentChat.id,
            sender_type: 'user',
            sender_id: 1,
            message: message,
            message_type: 'text'
        })
    });
    
    input.value = '';
    sendBtn.disabled = false;
    loadMessages();
}

async function handleFileSelect(e) {
    const file = e.target.files[0];
    if (!file || !currentChat) return;
    
    const formData = new FormData();
    formData.append('file', file);
    
    const uploadResponse = await fetch('/api/chat/upload-file', {
        method: 'POST',
        body: formData
    });
    
    const data = await uploadResponse.json();
    const messageType = file.type.startsWith('image/') ? 'image' : file.type.startsWith('video/') ? 'video' : 'file';
    
    await fetch('/api/chat/send-message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            chat_id: currentChat.id,
            sender_type: 'user',
            sender_id: 1,
            message: '',
            message_type: messageType,
            file_path: data.url
        })
    });
    
    e.target.value = '';
    loadMessages();
}

function selectFile(type) {
    const input = document.getElementById('fileInput');
    input.accept = type === 'image' ? 'image/*' : type === 'video' ? 'video/*' : '.pdf,.doc,.docx,.txt,.xls,.xlsx';
    input.click();
    document.getElementById('attachmentMenu').classList.remove('show');
}

function toggleEmojiPicker() {
    document.getElementById('emojiPicker').classList.toggle('show');
}

function toggleAttachmentMenu() {
    document.getElementById('attachmentMenu').classList.toggle('show');
}

function insertEmoji(emoji) {
    document.getElementById('messageInput').value += emoji;
}

function toggleUsersPanel() {
    document.getElementById('usersPanel').classList.toggle('show');
}

function searchMessages() {}
function callUser() {}
function videoCall() {}
function showChatInfo() {}
function cancelReply() {}
function shareLocation() {}

setInterval(() => currentChat && loadMessages(), 5000);
