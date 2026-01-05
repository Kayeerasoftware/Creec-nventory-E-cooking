# Guest Chat Feature - Implementation Summary

## Overview
Implemented a guest information collection system that prompts guests to provide their name, location, and phone number before accessing the chat feature.

## Changes Made

### 1. Database (Already Exists)
- **Table**: `guests`
- **Columns**: 
  - `id` (primary key)
  - `name` (string)
  - `phone` (string)
  - `location` (string)
  - `session_id` (unique string)
  - `timestamps`

### 2. Frontend (chat_modal.blade.php)
- Added guest registration form that appears when chat modal opens
- Form collects: Name, Phone Number, and Location
- Session management using localStorage to remember guest info
- Guest information is attached to all messages sent
- Messages are filtered to show only guest's own messages

### 3. Backend (ChatController.php)
- **registerGuest()**: Creates new guest record with unique session ID
- **getGuestBySession()**: Retrieves guest info by session ID
- **getOrCreateChat()**: Modified to handle guest sessions
- **sendMessage()**: Modified to attach guest information to messages
- **getMessages()**: Modified to filter messages by guest session

### 4. Routes (web.php)
- `/api/chat/register-guest` - POST - Register new guest
- `/api/chat/get-guest` - POST - Get guest by session ID
- All existing chat routes support guest sessions via `X-Guest-Session` header

## How It Works

1. **Guest Opens Chat**:
   - Modal checks for existing session in localStorage
   - If no session found, shows registration form
   - If session exists, validates it with backend

2. **Guest Registers**:
   - Fills in name, phone, and location
   - Backend creates guest record with unique session ID
   - Session ID stored in localStorage
   - Chat interface becomes available

3. **Guest Sends Messages**:
   - All requests include `X-Guest-Session` header
   - Backend identifies guest by session ID
   - Messages are tagged with guest's name and info
   - Guest data is NOT displayed in chat contacts list

4. **Message Display**:
   - Only messages from/to the specific guest are shown
   - Guest name and contact info are attached to messages
   - Support staff can see guest information with each message

## Key Features

- ✅ Guest information collected before chat access
- ✅ Data stored in database
- ✅ Session persistence using localStorage
- ✅ Guest info attached to all messages
- ✅ Guest data not shown in contacts list
- ✅ Messages filtered by guest session
- ✅ No authentication required for guests

## Testing

1. Open the application
2. Click "Support Chat" button
3. Fill in guest information form
4. Start chatting
5. Close and reopen chat - should remember guest info
6. Clear localStorage to test new guest registration

## Security Notes

- Session IDs are randomly generated 32-character strings
- Guest data is validated before storage
- Messages are filtered to prevent cross-guest data leakage
- Session validation on every request
