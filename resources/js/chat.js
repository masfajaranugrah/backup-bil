// Chat functionality
document.addEventListener('DOMContentLoaded', function () {
    if (window.__csChatInitialized) return;

    const waitForAxiosThenInit = (startedAt = Date.now()) => {
        if (typeof window.axios === 'undefined') {
            if (Date.now() - startedAt <= 5000) {
                setTimeout(() => waitForAxiosThenInit(startedAt), 120);
            } else {
                console.error('[CSChat] axios is unavailable after waiting 5s, skip chat initialization.');
            }
            return;
        }

        if (window.__csChatInitialized) return;
        window.__csChatInitialized = true;
        initChat();
    };

    const initChat = () => {

    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const mediaInput = document.getElementById('mediaInput');
    const attachButton = document.getElementById('attachButton');
    const locationButton = document.getElementById('locationButton');
    const plusMenuButton = document.getElementById('plusMenuButton');
    const chatActionSheet = document.getElementById('chatActionSheet');
    const voiceButton = document.getElementById('voiceButton');
    const mediaPreview = document.getElementById('mediaPreview');
    let replyPreview = document.getElementById('replyPreview');
    let replyPreviewName = document.getElementById('replyPreviewName');
    let replyPreviewText = document.getElementById('replyPreviewText');
    let replyPreviewClose = document.getElementById('replyPreviewClose');
    let replyToMessageInput = document.getElementById('replyToMessageId');

    let selectedMediaFile = null;
    let activeReplyMessage = null;
    let mediaRecorder = null;
    let recordedAudioChunks = [];
    let isRecordingVoice = false;

    function getSupportedVoiceMimeType() {
        if (typeof MediaRecorder === 'undefined' || typeof MediaRecorder.isTypeSupported !== 'function') {
            return '';
        }

        return [
            'audio/mp4;codecs=mp4a.40.2',
            'audio/mp4',
            'audio/aac',
            'audio/webm;codecs=opus',
            'audio/webm',
            'audio/ogg;codecs=opus',
            'audio/ogg'
        ].find(type => MediaRecorder.isTypeSupported(type)) || '';
    }

    function getVoiceFileExtension(mimeType) {
        if (mimeType.includes('mp4')) return 'm4a';
        if (mimeType.includes('aac')) return 'aac';
        if (mimeType.includes('ogg')) return 'ogg';
        return 'webm';
    }

    function getChatMediaStreamUrl(message) {
        return message?.media_url || (message?.id ? `/chat/media/${encodeURIComponent(String(message.id))}` : '');
    }

    function getAudioMimeType(message) {
        const source = `${message?.media_original_name || ''} ${message?.media_url || ''}`.toLowerCase();
        if (source.includes('.m4a') || source.includes('.mp4')) return 'audio/mp4';
        if (source.includes('.ogg')) return 'audio/ogg';
        if (source.includes('.mp3')) return 'audio/mpeg';
        if (source.includes('.wav')) return 'audio/wav';
        return 'audio/webm';
    }

    function getAxiosErrorMessage(error, fallback = 'Silakan coba lagi.') {
        const data = error?.response?.data;
        if (!data) return fallback;
        if (typeof data.error === 'string' && data.error.trim()) return data.error;
        if (typeof data.message === 'string' && data.message.trim()) return data.message;

        const firstError = data.errors && Object.values(data.errors).flat().find(Boolean);
        return firstError || fallback;
    }
    
    // Sound system for CS chat
    let audioUnlocked = false;
    let preloadedAudio = null;
    
    function initSound() {
        preloadedAudio = new Audio('/sounds/42289.mp3');
        preloadedAudio.volume = 0.5;
        preloadedAudio.load();
        
        const unlockAudioContext = () => {
            if (audioUnlocked) return;
            
            preloadedAudio.play()
                .then(() => {
                    preloadedAudio.pause();
                    preloadedAudio.currentTime = 0;
                    audioUnlocked = true;
                })
                .catch(() => {});
        };
        
        const events = ['click', 'touchstart', 'keydown', 'scroll', 'mousemove'];
        events.forEach(eventType => {
            document.addEventListener(eventType, unlockAudioContext, { once: true, passive: true });
        });
    }
    
    // Initialize sound system
    initSound();

    if (!chatMessages || !chatForm) {
        return;
    }

    function autoResizeMessageInput() {
        if (!messageInput || messageInput.tagName !== 'TEXTAREA') return;
        messageInput.style.height = 'auto';
        messageInput.style.height = `${Math.min(messageInput.scrollHeight, 120)}px`;
    }

    if (messageInput && messageInput.tagName === 'TEXTAREA') {
        autoResizeMessageInput();
        messageInput.addEventListener('input', autoResizeMessageInput);
        messageInput.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && activeReplyMessage) {
                event.preventDefault();
                clearReplyPreview();
                return;
            }

            if (event.key === 'Enter' && (event.ctrlKey || event.metaKey)) {
                event.preventDefault();
                chatForm.requestSubmit();
            }
        });
    }

    function closeActionSheet() {
        if (!chatActionSheet || !plusMenuButton) return;
        chatActionSheet.classList.remove('is-open');
        chatActionSheet.setAttribute('aria-hidden', 'true');
        plusMenuButton.classList.remove('is-open');
        plusMenuButton.setAttribute('aria-expanded', 'false');
    }

    function toggleActionSheet() {
        if (!chatActionSheet || !plusMenuButton) return;
        const isOpen = chatActionSheet.classList.toggle('is-open');
        chatActionSheet.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        plusMenuButton.classList.toggle('is-open', isOpen);
        plusMenuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    if (plusMenuButton && chatActionSheet) {
        plusMenuButton.addEventListener('click', function (e) {
            e.preventDefault();
            toggleActionSheet();
        });

        document.addEventListener('click', function (event) {
            if (!chatActionSheet.classList.contains('is-open')) return;
            if (event.target.closest('#chatActionSheet') || event.target.closest('#plusMenuButton')) return;
            closeActionSheet();
        });
    }

    // Setup attach button click
    if (attachButton && mediaInput) {
        attachButton.addEventListener('click', function (e) {
            e.preventDefault();
            closeActionSheet();
            mediaInput.click();
        });

        mediaInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                selectedMediaFile = file;
                showMediaPreview(file);
            }
        });
    }

    // Location button
    if (locationButton) {
        locationButton.addEventListener('click', function (e) {
            e.preventDefault();
            closeActionSheet();
            shareLocation();
        });
    }

    async function toggleVoiceRecording() {
        if (!voiceButton) return;

        if (isRecordingVoice && mediaRecorder) {
            mediaRecorder.stop();
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia || typeof MediaRecorder === 'undefined') {
            showErrorDialog({
                title: 'Voice Tidak Didukung',
                message: 'Browser belum mendukung rekam suara.'
            });
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const preferredMimeType = getSupportedVoiceMimeType();
            recordedAudioChunks = [];
            mediaRecorder = preferredMimeType
                ? new MediaRecorder(stream, { mimeType: preferredMimeType })
                : new MediaRecorder(stream);
            mediaRecorder.addEventListener('dataavailable', event => {
                if (event.data && event.data.size > 0) recordedAudioChunks.push(event.data);
            });
            mediaRecorder.addEventListener('stop', () => {
                stream.getTracks().forEach(track => track.stop());
                isRecordingVoice = false;
                voiceButton.classList.remove('is-recording');
                voiceButton.innerHTML = '<i class="fas fa-microphone"></i>';

                const mimeType = mediaRecorder.mimeType || preferredMimeType || 'audio/webm';
                const blob = new Blob(recordedAudioChunks, { type: mimeType });
                if (!blob.size) return;

                const extension = getVoiceFileExtension(mimeType);
                const file = new File([blob], `voice-note-${Date.now()}.${extension}`, { type: mimeType });
                selectedMediaFile = file;
                showMediaPreview(file);
            });

            mediaRecorder.start();
            isRecordingVoice = true;
            voiceButton.classList.add('is-recording');
            voiceButton.innerHTML = '<i class="fas fa-stop"></i>';
        } catch (error) {
            showErrorDialog({
                title: 'Gagal Rekam Voice',
                message: 'Izinkan akses mikrofon untuk merekam voice note.'
            });
        }
    }

    if (voiceButton) {
        voiceButton.addEventListener('click', function (e) {
            e.preventDefault();
            closeActionSheet();
            toggleVoiceRecording();
        });
    }

    // Show media preview
    function showMediaPreview(file) {
        if (!mediaPreview) return;

        const isImage = file.type.startsWith('image/');
        const isVideo = file.type.startsWith('video/');
        const isAudio = file.type.startsWith('audio/') || file.name.startsWith('voice-note-');

        let previewHTML = '';

        if (isImage) {
            const url = URL.createObjectURL(file);
            previewHTML = `
                <div class="media-preview-container">
                    <img src="${url}" alt="Preview" style="max-height: 100px; border-radius: 8px;">
                    <button type="button" class="remove-media-btn" onclick="window.clearMediaPreview()">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="media-filename">${file.name}</span>
                </div>
            `;
        } else if (isVideo) {
            previewHTML = `
                <div class="media-preview-container">
                    <i class="fas fa-video" style="font-size: 24px; color: #3b82f6;"></i>
                    <button type="button" class="remove-media-btn" onclick="window.clearMediaPreview()">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="media-filename">${file.name}</span>
                </div>
            `;
        } else if (isAudio) {
            const url = URL.createObjectURL(file);
            previewHTML = `
                <div class="media-preview-container">
                    <i class="fas fa-microphone" style="font-size: 24px; color: #111827;"></i>
                    <audio controls preload="metadata" src="${url}" style="max-width: 180px; height: 34px;"></audio>
                    <button type="button" class="remove-media-btn" onclick="window.clearMediaPreview()">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="media-filename">Voice note</span>
                </div>
            `;
        }

        mediaPreview.innerHTML = previewHTML;
        mediaPreview.style.display = 'block';
    }

    // Clear media preview
    window.clearMediaPreview = function () {
        selectedMediaFile = null;
        if (mediaInput) mediaInput.value = '';
        if (mediaPreview) {
            mediaPreview.innerHTML = '';
            mediaPreview.style.display = 'none';
        }
    };

    // Location sharing helpers
    function setLocationLoading(isLoading) {
        if (!locationButton) return;
        locationButton.disabled = isLoading;
        locationButton.classList.toggle('loading', isLoading);
        locationButton.innerHTML = isLoading
            ? '<i class="fas fa-spinner fa-spin"></i><span>Mengambil lokasi...</span>'
            : '<i class="fas fa-location-arrow"></i><span>Share lokasi</span>';
    }

    function shareLocation() {
        if (!locationButton) return;
        if (!navigator.geolocation) {
            showErrorDialog({
                title: 'Lokasi Tidak Didukung',
                message: 'Browser tidak mendukung akses lokasi.'
            });
            return;
        }

        if (!window.isSecureContext) {
            showErrorDialog({
                title: 'Aktifkan HTTPS',
                message: 'Akses lokasi hanya berfungsi di koneksi aman (https). Buka aplikasi/web melalui alamat https.'
            });
            return;
        }

        // Jika permission sudah ditolak sebelumnya (terutama PWA), beri panduan cepat
        if (navigator.permissions && navigator.permissions.query) {
            navigator.permissions.query({ name: 'geolocation' }).then((status) => {
                if (status.state === 'denied') {
                    showErrorDialog({
                        title: 'Izin Lokasi Diblokir',
                        message: 'Buka pengaturan situs/PWA, ubah Location ke Allow lalu muat ulang aplikasi.'
                    });
                }
            });
        }

        setLocationLoading(true);

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const link = `https://maps.google.com/?q=${lat},${lng}`;
                const text = `Lokasi saya: ${lat.toFixed(5)}, ${lng.toFixed(5)}\\n${link}`;

                const formData = new FormData();
                formData.append('message', text);
                const locationReplyMessage = getActiveReplyMessage();
                if (locationReplyMessage?.id) {
                    formData.append('reply_to_message_id', locationReplyMessage.id);
                    formData.append('reply_message_id', locationReplyMessage.id);
                }
                if (currentChatSessionId) {
                    formData.append('chat_session_id', currentChatSessionId);
                }

                if (isAdmin) {
                    const receiverIdInput = document.getElementById('receiverId');
                    const receiverId = receiverIdInput ? receiverIdInput.value : '';
                    if (!receiverId) {
                        setLocationLoading(false);
                        showErrorDialog({
                            title: 'Pilih Pelanggan',
                            message: 'Silakan pilih pelanggan sebelum mengirim lokasi.'
                        });
                        return;
                    }
                    formData.append('receiver_id', receiverId);
                }

                axios.post('/chat/send', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                    .then(response => {
                        if (response.data?.message && locationReplyMessage?.id) {
                            response.data.message.reply_to_message_id = response.data.message.reply_to_message_id || locationReplyMessage.id;
                            response.data.message.reply_to_message = response.data.message.reply_to_message || {
                                id: locationReplyMessage.id,
                                sender_id: locationReplyMessage.sender_id,
                                sender_name: locationReplyMessage.sender_name || locationReplyMessage.sender?.name || 'Pengirim',
                                message: replyTextForMessage(locationReplyMessage),
                                media_type: locationReplyMessage.media_type || null,
                            };
                        }

                        appendMessage(response.data.message, false);
                        scrollToBottom();
                        clearReplyPreview();

                        if (isAdmin && window.selectedUserId) {
                            moveUserToTop(window.selectedUserId);
                        }
                    })
                    .catch(error => {
                        showErrorDialog({
                            title: 'Gagal Mengirim Lokasi',
                            message: error.response?.data?.error || 'Silakan coba lagi.'
                        });
                    })
                    .finally(() => setLocationLoading(false));
            },
            (err) => {
                let msg = 'Gagal mengakses lokasi.';
                if (err.code === 1) msg = 'Izin lokasi ditolak. Izinkan akses lokasi untuk mengirim lokasi.';
                else if (err.code === 2) msg = 'Lokasi tidak tersedia saat ini.';
                else if (err.code === 3) msg = 'Permintaan lokasi timeout.';
                showErrorDialog({ title: 'Lokasi Gagal', message: msg });
                setLocationLoading(false);
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    const isAdmin = window.isAdmin === true && !!document.getElementById('receiverId');
    const userId = window.userId;
    let currentChatSessionId = window.chatSessionId ? String(window.chatSessionId) : '';
    const API_BASE = '/chat';
    const INITIAL_LOAD_LIMIT = 150;
    const MESSAGE_EDIT_WINDOW_MS = 15 * 60 * 1000;
    const messageStore = new Map();
    const userFilterState = {
        mode: 'all',
        search: '',
    };
    const RECONNECT_DELAY_MS = 800;
    let isSocketConnected = false;
    let reconnectTimer = null;
    let lastRenderedMessagesSignature = '';
    let currentConversationKey = '';
    const unreadCountSnapshot = new Map();
    let lastUnreadSignature = '';
    let lastUnreadFetchAt = 0;
    let pinnedChats = new Set();
    const processedRealtimeMessageIds = new Set();
    const UNREAD_REFRESH_THROTTLE_MS = 4000;
    let isLoadingMoreChats = false;
    let canLoadMoreChats = true;

    // Load unread counts for admin on page load
    function loadUnreadCounts() {
        if (!isAdmin) return;

        const now = Date.now();
        if (now - lastUnreadFetchAt < UNREAD_REFRESH_THROTTLE_MS) return;
        lastUnreadFetchAt = now;

        axios.get('/chat/unread-count')
            .then(response => {
                const unreadCounts = response.data || {};
                const unreadEntries = Object.entries(unreadCounts)
                    .map(([id, count]) => [String(id), Number(count || 0)])
                    .sort((a, b) => a[0].localeCompare(b[0]));
                const unreadSignature = JSON.stringify(unreadEntries);
                if (unreadSignature === lastUnreadSignature) {
                    return;
                }

                lastUnreadSignature = unreadSignature;
                const allBadges = document.querySelectorAll('.unread-badge');
                let hasVisualChange = false;

                allBadges.forEach((badge) => {
                    const senderId = (badge.id || '').replace('unread-', '');
                    if (!senderId) return;

                    const nextCount = Number(unreadCounts[senderId] || 0);
                    const prevCount = unreadCountSnapshot.has(senderId)
                        ? Number(unreadCountSnapshot.get(senderId) || 0)
                        : null;

                    if (prevCount === nextCount) {
                        return;
                    }

                    unreadCountSnapshot.set(senderId, nextCount);
                    badge.textContent = nextCount > 0 ? String(nextCount) : '0';
                    badge.style.display = nextCount > 0 ? 'inline-flex' : 'none';
                    const userItem = document.querySelector(`.user-item[data-user-id="${senderId}"]`);
                    if (userItem) userItem.dataset.unread = String(nextCount);
                    hasVisualChange = true;
                });

                if (hasVisualChange) {
                    sortUserList();
                    applyUserFilter();
                }
            })
            .catch(error => { });
    }

    // Function to get initials from name
    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    function applyUserFilter() {
        if (!isAdmin) return;

        const userList = document.getElementById('userList');
        if (!userList) return;

        const userItems = userList.querySelectorAll('.user-item');
        let visibleCount = 0;

        userItems.forEach(item => {
            const userName = (item.dataset.userName || '').toLowerCase();
            const userType = (item.querySelector('.user-type')?.textContent || '').toLowerCase();
            const badge = item.querySelector('.unread-badge');
            const unreadCount = parseInt(badge?.textContent || '0', 10) || 0;
            const hasUnread = unreadCount > 0;
            const isActive = item.classList.contains('active');
            const matchesSearch = userFilterState.search === ''
                || userName.includes(userFilterState.search)
                || userType.includes(userFilterState.search);
            const matchesTab = userFilterState.mode === 'all' || hasUnread || isActive;

            if (matchesSearch && matchesTab) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        let noResults = userList.querySelector('.no-results-message');
        if (visibleCount === 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'no-results-message';
                noResults.innerHTML = `
                    <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                        <i class="fas fa-search" style="font-size: 48px; margin-bottom: 12px; opacity: 0.5;"></i>
                        <div style="font-size: 14px; margin-bottom: 4px; font-weight: 500;">Tidak ada hasil</div>
                        <div style="font-size: 12px;">Coba kata kunci lain atau ubah filter chat</div>
                    </div>
                `;
                userList.appendChild(noResults);
            }
            noResults.style.display = 'block';
        } else if (noResults) {
            noResults.style.display = 'none';
        }

        const loadMoreWrapper = document.getElementById('loadMoreChatsWrap');
        if (loadMoreWrapper) userList.appendChild(loadMoreWrapper);
    }

    function setPinnedVisualState(userItem, isPinned) {
        if (!userItem) return;
        userItem.dataset.pinned = isPinned ? '1' : '0';
        userItem.classList.toggle('pinned', isPinned);

        const pinButton = userItem.querySelector('.pin-chat-btn');
        if (pinButton) {
            pinButton.classList.toggle('is-pinned', isPinned);
            pinButton.setAttribute('aria-pressed', isPinned ? 'true' : 'false');
            pinButton.title = isPinned ? 'Lepas pin chat' : 'Pin chat agar tampil paling atas';
        }
    }

    function sortUserList() {
        if (!isAdmin) return;
        const userList = document.getElementById('userList');
        if (!userList) return;

        const sortedItems = Array.from(userList.querySelectorAll('.user-item')).sort((a, b) => {
            const pinnedDiff = (b.dataset.pinned === '1' ? 1 : 0) - (a.dataset.pinned === '1' ? 1 : 0);
            if (pinnedDiff !== 0) return pinnedDiff;

            const unreadDiff = (parseInt(b.dataset.unread || '0', 10) || 0) - (parseInt(a.dataset.unread || '0', 10) || 0);
            if (unreadDiff !== 0) return unreadDiff;

            return (parseInt(b.dataset.lastActivity || '0', 10) || 0) - (parseInt(a.dataset.lastActivity || '0', 10) || 0);
        });

        sortedItems.forEach(item => userList.appendChild(item));

        const loadMoreWrapper = document.getElementById('loadMoreChatsWrap');
        if (loadMoreWrapper) userList.appendChild(loadMoreWrapper);
    }

    function createUserListItem(user) {
        const rawId = String(user.id || '');
        const rawName = String(user.name || 'Pelanggan');
        const rawNomerId = String(user.nomer_id || '');
        const safeId = escapeHtml(rawId);
        const safeName = escapeHtml(rawName);
        const safeNomerId = escapeHtml(rawNomerId);
        const lastActivity = user.last_message_at ? new Date(user.last_message_at).getTime() : 0;
        const isPinned = user.is_pinned === true || user.is_pinned === 1 || user.is_pinned === '1' || pinnedChats.has(String(user.id || ''));

        const item = document.createElement('div');
        item.className = `user-item ${isPinned ? 'pinned' : ''}`;
        item.dataset.userId = rawId;
        item.dataset.userName = rawName;
        item.dataset.nomerId = rawNomerId;
        item.dataset.lastActivity = String(Number.isNaN(lastActivity) ? 0 : lastActivity);
        item.dataset.pinned = isPinned ? '1' : '0';
        item.innerHTML = `
            <div class="user-item-content">
                <div class="user-avatar">${escapeHtml(getInitials(rawName))}</div>
                <div class="user-details">
                    <div class="user-name">${safeName}</div>
                    <div class="user-meta-row"><div class="user-type">${safeNomerId}</div></div>
                    <span class="pin-badge"><i class="fas fa-thumbtack"></i> Pinned</span>
                </div>
                <span class="unread-badge" id="unread-${safeId}" style="display: none;">0</span>
                <button type="button" class="pin-chat-btn ${isPinned ? 'is-pinned' : ''}" data-pin-user-id="${safeId}" title="${isPinned ? 'Lepas pin chat' : 'Pin chat agar tampil paling atas'}" aria-label="Pin chat ${safeName}" aria-pressed="${isPinned ? 'true' : 'false'}">
                    <i class="fas fa-thumbtack"></i>
                </button>
            </div>
        `;

        return item;
    }

    function ensureLoadMoreChatsButton(show = true) {
        const userList = document.getElementById('userList');
        if (!userList) return;

        let wrapper = document.getElementById('loadMoreChatsWrap');
        if (!show || !canLoadMoreChats) {
            if (wrapper) wrapper.remove();
            return;
        }

        if (!wrapper) {
            wrapper = document.createElement('div');
            wrapper.className = 'load-more-chats-wrap';
            wrapper.id = 'loadMoreChatsWrap';
            wrapper.innerHTML = `
                <button type="button" class="load-more-chats-btn" id="loadMoreChatsBtn">
                    <i class="fas fa-chevron-down"></i>
                    <span>Lihat chat lainnya</span>
                </button>
            `;
        }

        userList.appendChild(wrapper);
    }

    function setLoadMoreChatsButtonState(isLoading) {
        const button = document.getElementById('loadMoreChatsBtn');
        if (!button) return;

        button.disabled = isLoading;
        button.innerHTML = isLoading
            ? '<i class="fas fa-spinner fa-spin"></i><span>Memuat chat...</span>'
            : '<i class="fas fa-chevron-down"></i><span>Lihat chat lainnya</span>';
    }

    function loadMoreChatUsers() {
        if (!isAdmin || isLoadingMoreChats || !canLoadMoreChats) return;

        const userList = document.getElementById('userList');
        if (!userList) return;

        isLoadingMoreChats = true;
        setLoadMoreChatsButtonState(true);

        const offset = userList.querySelectorAll('.user-item').length;
        const params = new URLSearchParams({ limit: '100', offset: String(offset) });

        axios.get(`${API_BASE}/users?${params.toString()}`)
            .then(response => {
                const users = Array.isArray(response.data) ? response.data : [];
                const existingIds = new Set(Array.from(userList.querySelectorAll('.user-item')).map(item => String(item.dataset.userId || '')));
                const loadMoreWrapper = document.getElementById('loadMoreChatsWrap');
                if (loadMoreWrapper) loadMoreWrapper.remove();

                users.forEach(user => {
                    const userIdValue = String(user.id || '');
                    if (!userIdValue || existingIds.has(userIdValue)) return;
                    existingIds.add(userIdValue);
                    userList.appendChild(createUserListItem(user));
                });

                canLoadMoreChats = users.length >= 100;
                sortUserList();
                loadUnreadCounts();
                applyUserFilter();
                ensureLoadMoreChatsButton(canLoadMoreChats);
            })
            .catch(() => { })
            .finally(() => {
                isLoadingMoreChats = false;
                setLoadMoreChatsButtonState(false);
            });
    }

    function togglePinnedChat(userId) {
        const targetUserId = String(userId || '');
        if (!targetUserId) return;

        const shouldPin = !pinnedChats.has(targetUserId);
        if (shouldPin) {
            pinnedChats.add(targetUserId);
        } else {
            pinnedChats.delete(targetUserId);
        }

        const userItem = document.querySelector(`.user-item[data-user-id="${targetUserId}"]`);
        setPinnedVisualState(userItem, pinnedChats.has(targetUserId));
        sortUserList();
        applyUserFilter();

        const request = shouldPin
            ? axios.post(`${API_BASE}/pins/${targetUserId}`)
            : axios.delete(`${API_BASE}/pins/${targetUserId}`);

        request.catch(() => {
            if (shouldPin) {
                pinnedChats.delete(targetUserId);
            } else {
                pinnedChats.add(targetUserId);
            }

            setPinnedVisualState(userItem, pinnedChats.has(targetUserId));
            sortUserList();
            applyUserFilter();
            showErrorDialog({
                title: 'Gagal Menyimpan Pin',
                message: 'Silakan coba lagi.'
            });
        });
    }

    function initPinnedChats() {
        if (!isAdmin) return;
        const userList = document.getElementById('userList');
        if (!userList) return;

        const userItems = Array.from(userList.querySelectorAll('.user-item'));
        const baselineActivity = Date.now();
        pinnedChats = new Set(
            userItems
                .filter(item => item.dataset.pinned === '1' || item.classList.contains('pinned'))
                .map(item => String(item.dataset.userId || ''))
                .filter(Boolean)
        );

        userItems.forEach((item, index) => {
            if (!item.dataset.lastActivity) {
                item.dataset.lastActivity = String(baselineActivity - index);
            }
            const currentUserId = String(item.dataset.userId || '');
            setPinnedVisualState(item, pinnedChats.has(currentUserId));
        });

        sortUserList();

        axios.get(`${API_BASE}/pins`)
            .then(response => {
                const serverPins = Array.isArray(response.data) ? response.data.map(value => String(value)) : [];
                pinnedChats = new Set(serverPins);
                Array.from(userList.querySelectorAll('.user-item')).forEach((item) => {
                    const currentUserId = String(item.dataset.userId || '');
                    setPinnedVisualState(item, pinnedChats.has(currentUserId));
                });
                sortUserList();
                applyUserFilter();
            })
            .catch(() => { });
    }

    // WhatsApp-style date formatting
    let lastDisplayedDate = null;

    function formatWhatsAppDate(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        // Reset time part for comparison
        const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        const yesterdayOnly = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate());

        // Calculate days difference
        const diffTime = todayOnly - dateOnly;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays === 0) {
            return 'Hari ini';
        } else if (diffDays === 1) {
            return 'Kemarin';
        } else if (diffDays < 7) {
            // Within this week - show day name
            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            return dayNames[date.getDay()];
        } else {
            // Older than a week - show full date
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric'
            });
        }
    }

    function getDateKey(dateString) {
        const date = new Date(dateString);
        return `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`;
    }

    function shouldShowDateDivider(messageDate) {
        const dateKey = getDateKey(messageDate);
        if (lastDisplayedDate !== dateKey) {
            lastDisplayedDate = dateKey;
            return true;
        }
        return false;
    }

    function createDateDivider(dateString) {
        const divider = document.createElement('div');
        divider.className = 'date-divider';
        divider.innerHTML = `<span class="date-text">${formatWhatsAppDate(dateString)}</span>`;
        return divider;
    }

    function formatWhatsAppTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function toBooleanFlag(value) {
        if (typeof value === 'boolean') return value;
        if (typeof value === 'number') return value === 1;
        if (typeof value === 'string') {
            const normalized = value.trim().toLowerCase();
            return normalized === '1' || normalized === 'true' || normalized === 'yes' || normalized === 'on';
        }
        return false;
    }

    function extractLocationFromMessage(messageText) {
        if (!messageText || typeof messageText !== 'string') return null;
        // Priority: find lat,lng in maps query
        const queryMatch = messageText.match(/maps\.google\.com\/?\?q=(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)/i);
        if (queryMatch) {
            return { lat: parseFloat(queryMatch[1]), lng: parseFloat(queryMatch[2]) };
        }
        // Fallback: first pair of decimals
        const pairMatch = messageText.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
        if (pairMatch) {
            return { lat: parseFloat(pairMatch[1]), lng: parseFloat(pairMatch[2]) };
        }
        return null;
    }

    // Load messages
    function buildMessagesSignature(messages) {
        if (!Array.isArray(messages) || messages.length === 0) return 'empty';
        const first = messages[0];
        const last = messages[messages.length - 1];
        const firstKey = `${first.id || 'x'}:${first.updated_at || first.edited_at || first.deleted_at || first.created_at || ''}:${first.is_read ? 1 : 0}:${first.reply_to_message_id || ''}`;
        const lastKey = `${last.id || 'x'}:${last.updated_at || last.edited_at || last.deleted_at || last.created_at || ''}:${last.is_read ? 1 : 0}:${last.reply_to_message_id || ''}`;
        return `${messages.length}|${firstKey}|${lastKey}`;
    }

    function getScrollSnapshot() {
        return {
            bottomOffset: chatMessages.scrollHeight - chatMessages.scrollTop,
            wasNearBottom: chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < 140,
        };
    }

    function restoreScrollSnapshot(snapshot) {
        if (!snapshot) return;

        if (snapshot.wasNearBottom) {
            scrollToBottom();
            return;
        }

        chatMessages.scrollTop = Math.max(0, chatMessages.scrollHeight - snapshot.bottomOffset);
    }

    function loadMessages(targetUserId = null, options = {}) {
        const {
            autoScroll = true,
            skipIfUnchanged = false,
            resetSignature = false,
        } = options;

        const params = new URLSearchParams({ limit: String(INITIAL_LOAD_LIMIT) });
        if (currentChatSessionId) params.set('session_id', currentChatSessionId);
        const url = isAdmin && targetUserId
            ? `/chat/messages/${targetUserId}?${params.toString()}`
            : `/chat/messages?${params.toString()}`;

        axios.get(url)
            .then(response => {
                const conversationKey = isAdmin
                    ? `admin:${String(targetUserId || '')}`
                    : `user:${String(userId || '')}`;
                const responseMessages = Array.isArray(response.data) ? response.data : [];
                const firstSessionId = responseMessages.find(message => message.chat_session_id)?.chat_session_id;
                if (firstSessionId && !currentChatSessionId) {
                    currentChatSessionId = String(firstSessionId);
                    window.chatSessionId = currentChatSessionId;
                }

                if (resetSignature || currentConversationKey !== conversationKey) {
                    currentConversationKey = conversationKey;
                    lastRenderedMessagesSignature = '';
                }

                const incomingSignature = buildMessagesSignature(responseMessages);
                if (skipIfUnchanged && incomingSignature === lastRenderedMessagesSignature) {
                    return;
                }

                const scrollSnapshot = getScrollSnapshot();

                if (resetSignature || lastRenderedMessagesSignature === '') {
                    displayMessages(responseMessages);
                } else {
                    syncMessagesWithoutReload(responseMessages);
                }

                lastRenderedMessagesSignature = incomingSignature;

                if (autoScroll) {
                    scrollToBottom();
                } else {
                    restoreScrollSnapshot(scrollSnapshot);
                }

                // For customers, mark admin messages as read
                if (!isAdmin) {
                    const unreadAdminMessages = responseMessages.filter(m =>
                        String(m.sender_id) !== String(userId) && !m.is_read
                    );

                    if (unreadAdminMessages.length > 0) {
                        // Get admin ID from first admin message
                        const adminId = unreadAdminMessages[0].sender_id;

                        axios.post(`/chat/mark-read/${adminId}`)
                            .catch(err => { });
                    }
                }
            })
            .catch(error => { });
    }

    function scheduleSocketReconnect() {
        if (reconnectTimer) return;
        reconnectTimer = setTimeout(() => {
            reconnectTimer = null;
            const pusher = window.Echo?.connector?.pusher;
            if (!pusher) return;

            const state = pusher.connection?.state;
            if (state === 'connected' || state === 'connecting') return;
            try {
                pusher.connect();
            } catch (error) { }
        }, RECONNECT_DELAY_MS);
    }

    // Display messages
    function displayMessages(messages) {
        chatMessages.innerHTML = '';
        lastDisplayedDate = null; // Reset date tracker when loading messages
        messageStore.clear();

        messages.forEach(message => {
            appendMessage(message, false, true);
        });
    }

    function syncMessagesWithoutReload(messages) {
        if (!Array.isArray(messages)) return;

        messages.forEach(message => {
            const normalized = normalizeMessagePayload(message, message?.id ? messageStore.get(String(message.id)) : null);
            if (!normalized || !normalized.id) return;

            const existingMessage = chatMessages.querySelector(`[data-message-id="${normalized.id}"]`);
            if (existingMessage) {
                cacheMessage(normalized);
                patchMessageElement(existingMessage, normalized, false);
                return;
            }

            appendMessage(normalized, false, false);
        });
    }

    function normalizeMessagePayload(message, fallbackMessage = null) {
        if (!message) return null;

        const normalized = {
            ...(fallbackMessage || {}),
            ...message,
        };

        normalized.chat_type = normalized.chat_type || 'cs';
        normalized.chat_session_id = normalized.chat_session_id || null;
        normalized.message_type = normalized.message_type || null;
        normalized.message = typeof normalized.message === 'string' ? normalized.message : '';
        normalized.is_read = toBooleanFlag(normalized.is_read);
        normalized.is_deleted = toBooleanFlag(normalized.is_deleted);
        normalized.edited_at = normalized.edited_at || null;
        normalized.deleted_at = normalized.deleted_at || null;
        normalized.media_url = normalized.media_url || null;
        normalized.media_type = normalized.media_type || null;
        normalized.media_original_name = normalized.media_original_name || null;
        normalized.reply_to_message_id = normalized.reply_to_message_id || null;
        normalized.reply_to_message = normalized.reply_to_message || null;
        normalized.created_at = normalized.created_at || fallbackMessage?.created_at || new Date().toISOString();

        return normalized;
    }

    function replyTextForMessage(message) {
        const text = String(message?.message || '').trim();
        if (text) return text;

        switch (message?.media_type) {
            case 'image': return 'Foto';
            case 'video': return 'Video';
            case 'audio': return 'Audio';
            default: return 'Pesan';
        }
    }

    function bindReplyPreviewClose() {
        if (!replyPreviewClose || replyPreviewClose.dataset.bound === '1') return;

        replyPreviewClose.dataset.bound = '1';
        replyPreviewClose.addEventListener('click', function (event) {
            event.preventDefault();
            clearReplyPreview();
            messageInput.focus();
        });
    }

    function ensureReplyToMessageInput() {
        if (replyToMessageInput) return replyToMessageInput;
        if (!chatForm) return null;

        replyToMessageInput = document.createElement('input');
        replyToMessageInput.type = 'hidden';
        replyToMessageInput.id = 'replyToMessageId';
        replyToMessageInput.name = 'reply_to_message_id';
        chatForm.appendChild(replyToMessageInput);

        return replyToMessageInput;
    }

    function getActiveReplyMessage() {
        ensureReplyToMessageInput();
        const replyId = String(replyToMessageInput?.value || replyPreview?.dataset.replyMessageId || activeReplyMessage?.id || '').trim();
        if (!replyId) return null;

        return activeReplyMessage?.id && String(activeReplyMessage.id) === replyId
            ? activeReplyMessage
            : (messageStore.get(replyId) || { id: replyId });
    }

    function ensureReplyPreviewStyles() {
        if (document.getElementById('csReplyPreviewStyles')) return;

        const style = document.createElement('style');
        style.id = 'csReplyPreviewStyles';
        style.textContent = `
            .message-highlight .message-bubble { box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.28), 0 18px 40px rgba(0, 0, 0, 0.2); }
            .message-reply-preview { border-left: 4px solid rgba(15, 23, 42, 0.35); background: rgba(255, 255, 255, 0.16); border-radius: 10px; padding: 8px 10px; margin-bottom: 8px; cursor: pointer; }
            .message.received .message-reply-preview { background: #f1f5f9; border-left-color: #64748b; }
            .reply-preview-name { font-size: 12px; font-weight: 900; margin-bottom: 2px; }
            .reply-preview-text { font-size: 13px; opacity: 0.86; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
            .reply-compose-preview { display: flex; align-items: stretch; gap: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #111827; border-radius: 14px; padding: 10px 12px; margin-bottom: 10px; }
            .reply-compose-body { flex: 1; min-width: 0; }
            .reply-compose-label { font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 2px; }
            .reply-compose-text { font-size: 13px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .reply-compose-close { border: none; background: transparent; color: #64748b; font-size: 16px; cursor: pointer; }
        `;
        document.head.appendChild(style);
    }

    function ensureReplyPreview() {
        ensureReplyPreviewStyles();

        if (replyPreview) {
            bindReplyPreviewClose();
            return true;
        }

        if (!chatForm || !chatForm.parentElement) return false;

        const wrapper = document.createElement('div');
        wrapper.id = 'replyPreview';
        wrapper.className = 'reply-compose-preview';
        wrapper.style.display = 'none';
        wrapper.innerHTML = `
            <div class="reply-compose-body">
                <div class="reply-compose-label" id="replyPreviewName">Balas pesan</div>
                <div class="reply-compose-text" id="replyPreviewText"></div>
            </div>
            <button type="button" class="reply-compose-close" id="replyPreviewClose" aria-label="Batalkan reply">
                <i class="fas fa-times"></i>
            </button>
        `;

        chatForm.parentElement.insertBefore(wrapper, chatForm);
        replyPreview = wrapper;
        replyPreviewName = wrapper.querySelector('#replyPreviewName');
        replyPreviewText = wrapper.querySelector('#replyPreviewText');
        replyPreviewClose = wrapper.querySelector('#replyPreviewClose');
        bindReplyPreviewClose();

        return true;
    }

    function showReplyPreview(message) {
        if (!message || !message.id || !ensureReplyPreview()) return;

        activeReplyMessage = message;
        const replyInput = ensureReplyToMessageInput();
        if (replyInput) replyInput.value = String(message.id);
        if (replyPreviewName) {
            replyPreviewName.textContent = message.sender_name || message.sender?.name || 'Pengirim';
        }
        if (replyPreviewText) {
            replyPreviewText.textContent = replyTextForMessage(message);
        }
        replyPreview.dataset.replyMessageId = String(message.id);
        replyPreview.style.display = 'flex';
        messageInput.focus();
    }

    function clearReplyPreview() {
        activeReplyMessage = null;
        if (replyToMessageInput) replyToMessageInput.value = '';
        if (replyPreview) {
            replyPreview.style.display = 'none';
            replyPreview.dataset.replyMessageId = '';
        }
        if (replyPreviewName) replyPreviewName.textContent = 'Balas pesan';
        if (replyPreviewText) replyPreviewText.textContent = '';
    }

    bindReplyPreviewClose();

    function cacheMessage(message) {
        if (!message || !message.id) return normalizeMessagePayload(message);
        const key = String(message.id);
        const normalized = normalizeMessagePayload(message, messageStore.get(key) || null);
        messageStore.set(key, normalized);
        return normalized;
    }

    function renderMessageStatus(message, isSent, isPending = false) {
        if (!isSent) return '';

        if (isPending) {
            return `
                <span class="message-status-wrap">
                    <i class="fas fa-clock message-status"></i>
                    <span class="message-status-text">Menunggu</span>
                </span>
            `;
        }

        if (toBooleanFlag(message.is_read)) {
            return `
                <span class="message-status-wrap">
                    <i class="fas fa-check-double message-status read"></i>
                    <span class="message-status-text read">Dibaca</span>
                </span>
            `;
        }

        return `
            <span class="message-status-wrap">
                <i class="fas fa-check message-status"></i>
                <span class="message-status-text">Terkirim</span>
            </span>
        `;
    }

    function renderMessageMedia(message) {
        // Detect location in text (pretty render)
        const loc = extractLocationFromMessage(message.message);
        if (loc) {
            const { lat, lng } = loc;
            const mapsLink = `https://maps.google.com/?q=${lat},${lng}`;

            let mapUrl = '';
            const googleKey = typeof window.googleStaticMapKey === 'string' ? window.googleStaticMapKey : '';
            if (googleKey) {
                mapUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lng}&zoom=16&size=640x360&scale=2&maptype=roadmap&markers=color:red%7C${lat},${lng}&key=${googleKey}`;
            } else {
                // Inline SVG fallback to guarantee terlihat (tidak tergantung domain luar)
                const svg = `
<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360" viewBox="0 0 640 360">
  <defs>
    <linearGradient id="g" x1="0" x2="1" y1="0" y2="1">
      <stop offset="0" stop-color="#0f172a"/>
      <stop offset="1" stop-color="#1e293b"/>
    </linearGradient>
  </defs>
  <rect width="640" height="360" fill="url(#g)"/>
  <path d="M320 90c-33 0-60 27-60 60 0 35 53 106 56 110a5 5 0 0 0 8 0c3-4 56-75 56-110 0-33-27-60-60-60zm0 84a24 24 0 1 1 0-48 24 24 0 0 1 0 48z" fill="#22d3ee"/>
  <circle cx="320" cy="150" r="18" fill="#0f172a"/>
  <text x="320" y="315" fill="#e2e8f0" font-size="32" font-family="Inter,Arial,sans-serif" text-anchor="middle" font-weight="700">
    ${lat.toFixed(5)}, ${lng.toFixed(5)}
  </text>
</svg>`;
                mapUrl = 'data:image/svg+xml;utf8,' + encodeURIComponent(svg.trim());
            }
            return `
                <div class="location-card">
                    <a href="${mapsLink}" target="_blank" rel="noopener">
                        <img src="${mapUrl}" alt="Lokasi" class="location-img">
                    </a>
                    <div class="location-footer">
                        <div class="location-coord"><i class="fas fa-map-marker-alt"></i>${lat.toFixed(5)}, ${lng.toFixed(5)}</div>
                        <a class="location-btn" href="${mapsLink}" target="_blank" rel="noopener">Buka Maps</a>
                    </div>
                </div>
            `;
        }

        if (message.is_deleted || !message.media_url || !message.media_type) {
            return '';
        }

        if (message.media_type === 'image') {
            return `
                <div class="message-media">
                    <img src="${message.media_url}" alt="Image" onclick="window.open('${message.media_url}', '_blank')" style="max-width: 250px; max-height: 200px; border-radius: 8px; cursor: pointer; margin-bottom: 6px;">
                </div>
            `;
        }

        const isVoiceNote = message.media_type === 'audio'
            || String(message.media_original_name || '').startsWith('voice-note-')
            || /voice-note-.*\.(webm|m4a|ogg|mp3|wav)/i.test(String(message.media_url || ''));

        if (isVoiceNote) {
            const audioUrl = getChatMediaStreamUrl(message);
            return `
                <div class="message-media voice-note-card">
                    <i class="fas fa-microphone"></i>
                    <audio controls preload="metadata" src="${audioUrl}"></audio>
                </div>
            `;
        }

        if (message.media_type === 'video') {
            return `
                <div class="message-media">
                    <video controls style="max-width: 250px; max-height: 200px; border-radius: 8px; margin-bottom: 6px;">
                        <source src="${message.media_url}" type="video/mp4">
                        Browser tidak mendukung video.
                    </video>
                </div>
            `;
        }

        return '';
    }

    function renderMessageText(message) {
        // If we render location card, suppress raw text to avoid duplicating
        if (extractLocationFromMessage(message.message)) {
            return '';
        }
        if (!message.message || message.message.trim() === '') {
            return '';
        }

        const formattedText = escapeHtml(message.message).replace(/\r\n|\r|\n/g, '<br>');

        if (message.is_deleted) {
            return `<div class="message-text message-text-deleted">${formattedText}</div>`;
        }

        const editedLabel = message.edited_at ? ' <span class="message-edited">(diedit)</span>' : '';
        return `<div class="message-text">${formattedText}${editedLabel}</div>`;
    }

    function renderReplyPreview(message) {
        const reply = message.reply_to_message
            || (message.reply_to_message_id ? messageStore.get(String(message.reply_to_message_id)) : null);
        if (!reply && !message.reply_to_message_id) return '';

        if (!reply) {
            return `
                <div class="message-reply-preview" data-jump-message-id="${escapeHtml(String(message.reply_to_message_id || ''))}">
                    <div class="reply-preview-name">Pesan dibalas</div>
                    <div class="reply-preview-text">Pesan yang dibalas tidak termuat</div>
                </div>
            `;
        }

        return `
            <div class="message-reply-preview" data-jump-message-id="${escapeHtml(String(reply.id || ''))}">
                <div class="reply-preview-name">${escapeHtml(reply.sender_name || reply.sender?.name || 'Pengirim')}</div>
                <div class="reply-preview-text">${escapeHtml(reply.message || replyTextForMessage(reply))}</div>
            </div>
        `;
    }

    function canManageMessage(message) {
        if (!message || message.is_deleted) return false;
        if (isAdmin) return true; // Admin/CS boleh edit/hapus kapan saja

        if (!message.created_at) return false;
        const createdAt = new Date(message.created_at).getTime();
        if (Number.isNaN(createdAt)) return false;
        return (Date.now() - createdAt) <= MESSAGE_EDIT_WINDOW_MS;
    }

    function renderMessageActions(message, isSent) {
        const canManage = isSent && canManageMessage(message) && message.id;
        if (!message.id) return '';

        return `
            <span class="message-actions">
                <button class="message-action-btn js-reply-message" data-message-id="${message.id}" title="Balas"><i class="fas fa-reply"></i></button>
                ${canManage ? `<button class="message-action-btn js-edit-message" data-message-id="${message.id}" title="Edit"><i class="fas fa-pen"></i></button>` : ''}
                ${canManage ? `<button class="message-action-btn js-delete-message" data-message-id="${message.id}" title="Hapus"><i class="fas fa-trash"></i></button>` : ''}
            </span>
        `;
    }

    function renderMessageContent(message, isSent, isPending = false) {
        const statusInfo = renderMessageStatus(message, isSent, isPending);
        const mediaContent = renderMessageMedia(message);
        const textContent = renderMessageText(message);
        const time = formatWhatsAppTime(message.created_at);
        const actionsHtml = renderMessageActions(message, isSent);

        return `
            <div class="message-content">
                ${renderReplyPreview(message)}
                ${mediaContent}
                ${textContent}
                <div class="message-info">
                    ${statusInfo}
                    ${time}
                    ${actionsHtml}
                </div>
            </div>
        `;
    }

    function renderSystemNotice(message) {
        const isHandoff = message.message_type === 'handoff_to_cs';
        const isTransfer = message.message_type === 'transfer_to_session';
        const transferUrlMatch = isTransfer ? String(message.message || '').match(/https?:\/\/\S+|\/dashboard\/customer\/chat\?session_id=[\w-]+/) : null;
        const transferUrl = transferUrlMatch ? transferUrlMatch[0] : null;
        const text = isHandoff
            ? 'Pesan anda sudah dialihkan ke CS kami silahkan klik disini'
            : isTransfer
                ? String(message.message || '').replace(transferUrl || '', '').trim()
            : message.message;
        const body = isHandoff
            ? `<a href="/dashboard/customer/chat" class="system-notice-link">${escapeHtml(text)}</a>`
            : isTransfer && transferUrl
                ? `${escapeHtml(text)}<br><a href="${escapeHtml(transferUrl)}" class="system-notice-link">Buka Chat Baru</a>`
            : escapeHtml(text);

        return `
            <div class="system-message-notice ${isHandoff ? 'handoff-notice' : ''}">
                ${body}
            </div>
        `;
    }

    function messageRenderSignature(message, isPending = false) {
        return [
            message.id || '',
            message.message || '',
            message.media_url || '',
            message.media_type || '',
            message.media_original_name || '',
            message.reply_to_message_id || '',
            JSON.stringify(message.reply_to_message || null),
            message.is_read ? '1' : '0',
            message.is_deleted ? '1' : '0',
            message.edited_at || '',
            message.deleted_at || '',
            message.message_type || '',
            isPending ? 'pending' : 'sent',
        ].join('|');
    }

    function patchMessageElement(messageDiv, message, isPending = false) {
        const isSent = messageDiv.classList.contains('sent');
        const bubble = messageDiv.querySelector('.message-bubble');
        if (!bubble) return;

        const nextRenderSignature = messageRenderSignature(message, isPending);
        if (messageDiv.dataset.renderSignature === nextRenderSignature) return;

        if (message.id) {
            messageDiv.dataset.messageId = String(message.id);
        }
        messageDiv.dataset.createdAt = message.created_at || '';
        messageDiv.dataset.renderSignature = nextRenderSignature;
        bubble.innerHTML = renderMessageContent(message, isSent, isPending);
    }

    function replacePendingMessage(tempId, serverMessage, fallbackMessage = null) {
        if (!tempId) return false;
        const pendingNode = chatMessages.querySelector(`[data-temp-id="${tempId}"]`);
        if (!pendingNode) return false;

        const normalized = cacheMessage(normalizeMessagePayload(serverMessage, fallbackMessage));
        if (!normalized) return false;

        pendingNode.classList.remove('message-pending');
        pendingNode.removeAttribute('data-temp-id');
        pendingNode.dataset.messageId = String(normalized.id || '');
        pendingNode.dataset.createdAt = normalized.created_at || '';
        patchMessageElement(pendingNode, normalized, false);
        return true;
    }

    function updateMessageRealtime(message, options = {}) {
        const normalized = cacheMessage(message);
        if (!normalized || !normalized.id) return;

        const { appendIfMissing = true, autoScroll = false } = options;
        const messageDiv = chatMessages.querySelector(`[data-message-id="${normalized.id}"]`);
        if (messageDiv) {
            patchMessageElement(messageDiv, normalized, false);
        } else if (appendIfMissing) {
            appendMessage(normalized, false, false);
        }

        if (autoScroll) {
            scrollToBottom();
        }
    }

    function getMessageFromDom(messageId) {
        const messageDiv = chatMessages.querySelector(`[data-message-id="${messageId}"]`);
        if (!messageDiv) return null;

        const messageTextEl = messageDiv.querySelector('.message-text');
        const rawText = messageTextEl ? messageTextEl.textContent : '';
        const cleanText = (rawText || '').replace('(diedit)', '').trim();

        return {
            id: messageId,
            sender_id: messageDiv.classList.contains('sent') ? String(userId) : null,
            message: cleanText,
            created_at: messageDiv.dataset.createdAt || new Date().toISOString(),
            is_deleted: messageTextEl ? messageTextEl.classList.contains('message-text-deleted') : false,
        };
    }

    // Append single message
    function appendMessage(message, isPending = false, isLoading = false) {
        const normalized = cacheMessage(message);
        if (!normalized) return;

        // Cek duplikat berdasarkan message ID
        if (normalized.id && chatMessages.querySelector(`[data-message-id="${normalized.id}"]`)) {
            const existingMessage = chatMessages.querySelector(`[data-message-id="${normalized.id}"]`);
            if (existingMessage) {
                patchMessageElement(existingMessage, normalized, isPending);
            }
            return;
        }

        // Show date divider if needed (only when loading messages or for new day)
        if (normalized.created_at && shouldShowDateDivider(normalized.created_at)) {
            chatMessages.appendChild(createDateDivider(normalized.created_at));
        }

        const messageDiv = document.createElement('div');
        if (normalized.message_type === 'handoff_to_cs' || normalized.message_type === 'transfer_to_session' || normalized.message_type === 'system') {
            messageDiv.className = 'message system';
            if (normalized.id) {
                messageDiv.dataset.messageId = normalized.id;
            }
            if (normalized.tempId) {
                messageDiv.dataset.tempId = normalized.tempId;
            }
            messageDiv.dataset.createdAt = normalized.created_at || '';
            messageDiv.dataset.renderSignature = messageRenderSignature(normalized, isPending);
            messageDiv.innerHTML = renderSystemNotice(normalized);
            chatMessages.appendChild(messageDiv);
            return;
        }

        // Convert to string for UUID comparison
        const currentUserId = String(userId);
        const messageSenderId = String(normalized.sender_id);
        const isSent = messageSenderId === currentUserId;

        messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
        if (normalized.id) {
            messageDiv.dataset.messageId = normalized.id;
        }
        if (normalized.tempId) {
            messageDiv.dataset.tempId = normalized.tempId;
        }
        messageDiv.dataset.createdAt = normalized.created_at || '';
        messageDiv.dataset.renderSignature = messageRenderSignature(normalized, isPending);

        const senderName = normalized.sender ? normalized.sender.name : 'Unknown';
        const initials = getInitials(senderName);

        messageDiv.innerHTML = `
            <div class="message-avatar">${initials}</div>
            <div class="message-bubble">
                ${renderMessageContent(normalized, isSent, isPending)}
            </div>
        `;

        chatMessages.appendChild(messageDiv);

        // Hilangkan notifikasi suara - tidak perlu lagi
        // if (!isSent && !isPending) {
        //     playNotificationSound();
        //     showNotification(senderName, message.message);
        // }
    }
    // Notification sound - improved with preloaded audio
    function playNotificationSound() {
        if (!audioUnlocked) return;
        
        try {
            if (preloadedAudio) {
                preloadedAudio.currentTime = 0;
                preloadedAudio.play().catch(() => {});
            } else {
                const audio = new Audio('/sounds/42289.mp3');
                audio.volume = 0.5;
                audio.play().catch(() => {});
            }
        } catch (error) {}
    }

    // Browser notification removed for performance - using sound only

    // Update unread badge untuk admin (reload from server for accuracy)
    function updateUnreadBadge(senderId) {
        const badge = document.getElementById(`unread-${senderId}`);
        if (!badge) return;

        // Realtime: increment locally immediately (tanpa menunggu refresh API periodik).
        const currentCount = parseInt(badge.textContent, 10) || 0;
        const nextCount = currentCount + 1;
        badge.textContent = String(nextCount);
        badge.style.display = 'inline-flex';
        unreadCountSnapshot.set(String(senderId), nextCount);
        const userItem = document.querySelector(`.user-item[data-user-id="${senderId}"]`);
        if (userItem) userItem.dataset.unread = String(nextCount);
        sortUserList();
        applyUserFilter();
    }

    // Move user to top of list when new message arrives
    function ensureUserItemFromMessage(message) {
        if (!isAdmin || !message || !message.sender_id) return;

        const userList = document.getElementById('userList');
        if (!userList) return;

        const senderId = String(message.sender_id);
        if (userList.querySelector(`[data-user-id="${senderId}"]`)) return;

        const senderName = message.sender?.name || message.sender_name || 'Pelanggan';
        const senderLabel = message.sender?.email || senderId;
        const isPinned = pinnedChats.has(senderId);
        const item = document.createElement('div');
        item.className = `user-item ${isPinned ? 'pinned' : ''}`;
        item.dataset.userId = senderId;
        item.dataset.userName = senderName;
        item.dataset.nomerId = senderLabel;
        item.dataset.lastActivity = String(Date.now());
        item.dataset.pinned = isPinned ? '1' : '0';
        item.innerHTML = `
            <div class="user-item-content">
                <div class="user-avatar">${getInitials(senderName)}</div>
                <div class="user-details">
                    <div class="user-name">${escapeHtml(senderName)}</div>
                    <div class="user-meta-row"><div class="user-type">${escapeHtml(senderLabel)}</div></div>
                    <span class="pin-badge"><i class="fas fa-thumbtack"></i> Pinned</span>
                </div>
                <span class="unread-badge" id="unread-${senderId}" style="display: none;">0</span>
                <button type="button" class="pin-chat-btn ${isPinned ? 'is-pinned' : ''}" data-pin-user-id="${senderId}" title="${isPinned ? 'Lepas pin chat' : 'Pin chat agar tampil paling atas'}" aria-label="Pin chat ${escapeHtml(senderName)}" aria-pressed="${isPinned ? 'true' : 'false'}">
                    <i class="fas fa-thumbtack"></i>
                </button>
            </div>
        `;

        userList.insertBefore(item, userList.firstElementChild);
    }

    function moveUserToTop(userId) {
        const userList = document.getElementById('userList');
        if (!userList) return;

        // Find user item
        const userItem = userList.querySelector(`[data-user-id="${userId}"]`);
        if (!userItem) return;

        // If already at top, no need to move
        userItem.dataset.lastActivity = String(Date.now());
        userItem.style.transition = 'all 0.3s ease';
        sortUserList();

        // Add highlight animation
        userItem.style.backgroundColor = '#f0f9ff';
        setTimeout(() => {
            userItem.style.backgroundColor = '';
        }, 1000);
    }

    // Clear unread badge
    function clearUnreadBadge(userId) {
        const badge = document.getElementById(`unread-${userId}`);
        if (badge) {
        badge.textContent = '0';
        badge.style.display = 'none';
        }
        unreadCountSnapshot.set(String(userId), 0);
        const userItem = document.querySelector(`.user-item[data-user-id="${userId}"]`);
        if (userItem) userItem.dataset.unread = '0';
        applyUserFilter();
    }

    // Update message read status (centang 1 -> centang 2)
    function updateMessageReadStatus(messageIds) {
        if (!messageIds || messageIds.length === 0) return;

        messageIds.forEach(messageId => {
            const messageDiv = chatMessages.querySelector(`[data-message-id="${messageId}"]`);

            if (messageDiv) {
                const statusIcon = messageDiv.querySelector('.message-status');
                const statusText = messageDiv.querySelector('.message-status-text');

                if (statusIcon) {
                    statusIcon.className = 'fas fa-check-double message-status read';
                }
                if (statusText) {
                    statusText.textContent = 'Dibaca';
                    statusText.classList.add('read');
                }
            }
        });
    }

    function showPromptDialog({ title = 'Edit Pesan', message = '', placeholder = '', confirmText = 'Simpan', cancelText = 'Batal', defaultValue = '', onConfirm }) {
        const overlay = document.createElement('div');
        overlay.className = 'chat-confirm-overlay';

        overlay.innerHTML = `
            <div class="chat-confirm-dialog chat-prompt-dialog">
                <h4>${escapeHtml(title)}</h4>
                ${message ? `<p>${escapeHtml(message)}</p>` : ''}
                <textarea class="chat-prompt-input" rows="3" placeholder="${escapeHtml(placeholder)}">${escapeHtml(defaultValue)}</textarea>
                <div class="chat-confirm-actions">
                    <button type="button" class="btn-cancel">${escapeHtml(cancelText)}</button>
                    <button type="button" class="btn-confirm">${escapeHtml(confirmText)}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        const textarea = overlay.querySelector('.chat-prompt-input');
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);

        const cleanup = () => {
            overlay.classList.add('hide');
            setTimeout(() => overlay.remove(), 120);
        };

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) cleanup();
        });
        overlay.querySelector('.btn-cancel').addEventListener('click', cleanup);
        overlay.querySelector('.btn-confirm').addEventListener('click', () => {
            const value = textarea.value.trim();
            if (!value) {
                textarea.focus();
                return;
            }
            cleanup();
            if (typeof onConfirm === 'function') onConfirm(value);
        });
    }

    function editMessage(messageId) {
        const messageData = messageStore.get(String(messageId)) || getMessageFromDom(messageId);
        if (!messageData || messageData.is_deleted) return;

        const currentText = (messageData.message || '').trim();

        showPromptDialog({
            title: 'Edit Pesan',
            message: '',
            placeholder: 'Tulis pesan...',
            defaultValue: currentText,
            confirmText: 'Simpan',
            cancelText: 'Batal',
            onConfirm: (value) => {
                axios.put(`${API_BASE}/messages/${messageId}`, { message: value })
                    .then(response => {
                        const updatedMessage = response?.data?.message;
                        if (updatedMessage) {
                            updateMessageRealtime(updatedMessage, { appendIfMissing: true });
                        }
                    })
                    .catch(error => {
                        const errMsg = error.response?.data?.error || '';
                        const expired = error.response?.status === 403 || /expired|window/i.test(errMsg);
                        showErrorDialog({
                            title: expired ? 'Tidak Bisa Diedit' : 'Gagal Edit Pesan',
                            message: expired ? 'Pesan sudah tidak dapat dihapus maupun diedit.' : (errMsg || 'Silakan coba lagi.')
                        });
                    });
            }
        });
    }

    function showConfirmDialog({ title = 'Konfirmasi', message = 'Lanjutkan?', confirmText = 'Hapus', cancelText = 'Batal', onConfirm }) {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'chat-confirm-overlay';

        // Modal content
        overlay.innerHTML = `
            <div class="chat-confirm-dialog">
                <h4>${escapeHtml(title)}</h4>
                <p>${escapeHtml(message)}</p>
                <div class="chat-confirm-actions">
                    <button type="button" class="btn-cancel">${escapeHtml(cancelText)}</button>
                    <button type="button" class="btn-confirm">${escapeHtml(confirmText)}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        const cleanup = () => {
            overlay.classList.add('hide');
            setTimeout(() => overlay.remove(), 120);
        };

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cleanup();
            }
        });

        overlay.querySelector('.btn-cancel').addEventListener('click', () => {
            cleanup();
        });

        overlay.querySelector('.btn-confirm').addEventListener('click', () => {
            cleanup();
            if (typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    }

    function showErrorDialog({ title = 'Terjadi Kesalahan', message = 'Silakan coba lagi.' }) {
        const overlay = document.createElement('div');
        overlay.className = 'chat-confirm-overlay';
        overlay.innerHTML = `
            <div class="chat-confirm-dialog chat-error-dialog">
                <h4>${escapeHtml(title)}</h4>
                <p>${escapeHtml(message)}</p>
                <div class="chat-confirm-actions">
                    <button type="button" class="btn-confirm">Tutup</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        const cleanup = () => {
            overlay.classList.add('hide');
            setTimeout(() => overlay.remove(), 120);
        };

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) cleanup();
        });
        overlay.querySelector('.btn-confirm').addEventListener('click', cleanup);
    }

    function deleteMessage(messageId) {
        showConfirmDialog({
            title: 'Hapus Pesan',
            message: 'Yakin ingin menghapus pesan ini?',
            confirmText: 'Hapus',
            cancelText: 'Batal',
            onConfirm: () => {
                axios.delete(`${API_BASE}/messages/${messageId}`)
                    .then(response => {
                        const updatedMessage = response?.data?.message;
                        if (updatedMessage) {
                            updateMessageRealtime(updatedMessage, { appendIfMissing: true });
                        }
                    })
                    .catch(error => {
                        const errMsg = error.response?.data?.error || '';
                        const expired = error.response?.status === 403 || /expired|window/i.test(errMsg);
                        showErrorDialog({
                            title: expired ? 'Tidak Bisa Dihapus' : 'Gagal Hapus Pesan',
                            message: expired ? 'Pesan sudah tidak dapat dihapus maupun diedit.' : (errMsg || 'Silakan coba lagi.')
                        });
                    });
            }
        });
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Scroll to bottom
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    // Send message with optional media
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const rawMessage = (messageInput.value || '').replace(/\r\n/g, '\n');
        const hasMessage = rawMessage.trim().length > 0;

        // Check if we have message or media
        if (!hasMessage && !selectedMediaFile) return;

        // Use FormData for file upload
        const formData = new FormData();
        formData.append('message', rawMessage);

        const replyMessage = getActiveReplyMessage();
        if (replyMessage?.id) {
            formData.append('reply_to_message_id', replyMessage.id);
            formData.append('reply_message_id', replyMessage.id);
        }

        if (selectedMediaFile) {
            formData.append('media', selectedMediaFile);
            if (selectedMediaFile.type.startsWith('audio/') || selectedMediaFile.name.startsWith('voice-note-')) {
                formData.append('media_type_hint', 'audio');
            }
        }

        if (isAdmin) {
            const receiverId = document.getElementById('receiverId').value;
            if (!receiverId) {
                alert('Pilih user terlebih dahulu');
                return;
            }
            formData.append('receiver_id', receiverId);
        }

        if (currentChatSessionId) {
            formData.append('chat_session_id', currentChatSessionId);
        }

        sendButton.disabled = true;
        messageInput.value = '';

        const tempId = `pending-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
        appendMessage({
            tempId,
            sender_id: String(userId),
            receiver_id: isAdmin ? document.getElementById('receiverId')?.value : null,
            chat_session_id: currentChatSessionId || null,
            message: rawMessage,
            created_at: new Date().toISOString(),
            is_read: false,
            chat_type: 'cs',
            reply_to_message_id: replyMessage?.id || null,
            reply_to_message: replyMessage?.id ? {
                id: replyMessage.id,
                sender_id: replyMessage.sender_id,
                sender_name: replyMessage.sender_name || replyMessage.sender?.name || 'Pengirim',
                message: replyTextForMessage(replyMessage),
                media_type: replyMessage.media_type || null,
            } : null,
        }, true);
        scrollToBottom();

        clearReplyPreview();

        // Clear media preview
        if (typeof window.clearMediaPreview === 'function') {
            window.clearMediaPreview();
        }

        axios.post('/chat/send', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {
                if (response.data?.message && replyMessage?.id) {
                    response.data.message.reply_to_message_id = response.data.message.reply_to_message_id || replyMessage.id;
                    response.data.message.reply_to_message = response.data.message.reply_to_message || {
                        id: replyMessage.id,
                        sender_id: replyMessage.sender_id,
                        sender_name: replyMessage.sender_name || replyMessage.sender?.name || 'Pengirim',
                        message: replyTextForMessage(replyMessage),
                        media_type: replyMessage.media_type || null,
                    };
                }

                if (response.data.message?.chat_session_id) {
                    currentChatSessionId = String(response.data.message.chat_session_id);
                    window.chatSessionId = currentChatSessionId;
                }
                if (!replacePendingMessage(tempId, response.data.message, {
                    reply_to_message_id: replyMessage?.id || null,
                    reply_to_message: replyMessage?.id ? {
                        id: replyMessage.id,
                        sender_id: replyMessage.sender_id,
                        sender_name: replyMessage.sender_name || replyMessage.sender?.name || 'Pengirim',
                        message: replyTextForMessage(replyMessage),
                        media_type: replyMessage.media_type || null,
                    } : null,
                })) {
                    appendMessage(response.data.message, false);
                }
                scrollToBottom();

                if (isAdmin && window.selectedUserId) {
                    moveUserToTop(window.selectedUserId);
                }
            })
            .catch(error => {
                const pendingNode = chatMessages.querySelector(`[data-temp-id="${tempId}"]`);
                if (pendingNode) pendingNode.remove();
                console.error('Send error:', error);
                showErrorDialog({
                    title: 'Gagal Mengirim Pesan',
                    message: getAxiosErrorMessage(error)
                });
            })
            .finally(() => {
                clearReplyPreview();
                sendButton.disabled = false;
                messageInput.focus();
                if (messageInput.tagName === 'TEXTAREA') {
                    autoResizeMessageInput();
                }
            });
    });

    chatMessages.addEventListener('click', function (event) {
        const replyBtn = event.target.closest('.js-reply-message');
        if (replyBtn) {
            event.preventDefault();
            const message = messageStore.get(String(replyBtn.dataset.messageId));
            if (message) {
                showReplyPreview(message);
            }
            return;
        }

        const quoted = event.target.closest('.message-reply-preview[data-jump-message-id]');
        if (quoted) {
            const target = chatMessages.querySelector(`[data-message-id="${quoted.dataset.jumpMessageId}"]`);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                target.classList.add('message-highlight');
                setTimeout(() => target.classList.remove('message-highlight'), 1200);
            }
            return;
        }

        const editBtn = event.target.closest('.js-edit-message');
        if (editBtn) {
            editMessage(editBtn.dataset.messageId);
            return;
        }

        const deleteBtn = event.target.closest('.js-delete-message');
        if (deleteBtn) {
            deleteMessage(deleteBtn.dataset.messageId);
        }
    });

    // Setup WebSocket listener with retry mechanism
    function setupWebSocketListener() {
        if (!window.Echo) {
            setTimeout(setupWebSocketListener, 100);
            return;
        }

        if (!userId) {
            console.error('[CSChat] Missing userId, skip websocket subscription');
            return;
        }

        const channel = `chat.${userId}`;
        const privateChannel = window.Echo.private(channel);
        const wsConnection = window.Echo?.connector?.pusher?.connection;

        if (wsConnection) {
            const markConnected = () => {
                isSocketConnected = true;
            };
            const markDisconnected = () => {
                isSocketConnected = false;
                scheduleSocketReconnect();
            };

            wsConnection.bind('connected', markConnected);
            wsConnection.bind('disconnected', markDisconnected);
            wsConnection.bind('unavailable', markDisconnected);
            wsConnection.bind('error', markDisconnected);

            isSocketConnected = wsConnection.state === 'connected';
        } else {
            isSocketConnected = false;
        }

        privateChannel
            .listen('MessageSent', (e) => {
                processIncomingMessage(e);
            })
            .listen('.MessageSent', (e) => {
                processIncomingMessage(e);
            })
            .listen('MessageRead', (e) => {
                updateMessageReadStatus(e.message_ids);
            })
            .listen('.MessageRead', (e) => {
                updateMessageReadStatus(e.message_ids);
            })
            .listen('.message.read', (e) => {
                updateMessageReadStatus(e.message_ids);
            })
            .listen('MessageUpdated', (e) => {
                processMessageUpdated(e);
            })
            .listen('.MessageUpdated', (e) => {
                processMessageUpdated(e);
            });

        if (isAdmin) {
            window.Echo.private('admin-inbox')
                .listen('MessageSent', (e) => {
                    processIncomingMessage(e);
                })
                .listen('.MessageSent', (e) => {
                    processIncomingMessage(e);
                })
                .listen('MessageUpdated', (e) => {
                    processMessageUpdated(e);
                })
                .listen('.MessageUpdated', (e) => {
                    processMessageUpdated(e);
                });
        }

        function isCustomerSender(e) {
            return String(e?.sender?.role || '').toLowerCase() === 'pelanggan';
        }

        function processIncomingMessage(e) {
            // Only process CS chat messages (ignore admin/billing chat)
            const chatType = e.chat_type || 'cs';
            if (chatType !== 'cs') {
                return; // Ignore non-CS messages
            }

            if (currentChatSessionId && e.chat_session_id && String(e.chat_session_id) !== currentChatSessionId) {
                if (!isAdmin) return;
            }

            const currentUserId = String(userId);
            const eventSenderId = String(e.sender_id);
            const eventReceiverId = String(e.receiver_id);
            const eventMessageId = e.id ? String(e.id) : '';

            if (eventMessageId) {
                if (processedRealtimeMessageIds.has(eventMessageId)) return;
                processedRealtimeMessageIds.add(eventMessageId);
                if (processedRealtimeMessageIds.size > 500) {
                    processedRealtimeMessageIds.delete(processedRealtimeMessageIds.values().next().value);
                }
            }

            // Don't process messages sent by ourselves
            if (eventSenderId === currentUserId) {
                return;
            }

            if (isAdmin) {
                // Sidebar/badge admin hanya untuk pesan pelanggan, bukan balasan admin lain.
                if (!isCustomerSender(e)) return;

                const selectedUserId = String(window.selectedUserId || '');

                ensureUserItemFromMessage(e);

                // Always move customer to top and update badge when customer sends message
                moveUserToTop(e.sender_id);
                
                // Play sound for any incoming customer message
                playNotificationSound();

                if (!window.selectedUserId || selectedUserId !== eventSenderId) {
                    // Not viewing this customer's chat - update badge
                    updateUnreadBadge(e.sender_id);
                } else {
                    // Currently viewing this customer's chat - show message
                    appendMessage(e, false);
                    scrollToBottom();
                    axios.post(`/chat/mark-read/${e.sender_id}`)
                        .catch(err => { });
                }
            } else {
                // For customer: show all incoming CS messages
                appendMessage(e, false);
                scrollToBottom();
                playNotificationSound();
                axios.post(`/chat/mark-read/${e.sender_id}`)
                    .catch(err => { });
            }
        }

        function processMessageUpdated(e) {
            if (!e || !e.message) return;
            const payload = e.message;
            const chatType = payload.chat_type || 'cs';
            if (chatType !== 'cs') return;

            const senderId = String(payload.sender_id || '');
            const receiverId = String(payload.receiver_id || '');
            const currentUserId = String(userId || '');

            if (isAdmin) {
                const selectedUserId = String(window.selectedUserId || '');
                if (!selectedUserId) return;

                if (selectedUserId === senderId || selectedUserId === receiverId) {
                    updateMessageRealtime(payload, { appendIfMissing: true });
                }
            } else if (senderId === currentUserId || receiverId === currentUserId) {
                updateMessageRealtime(payload, { appendIfMissing: true });
            }
        }
    }

    // Start WebSocket listener
    setupWebSocketListener();

    // Load unread counts on page load (for admin)
    if (isAdmin) {
        initPinnedChats();
        loadUnreadCounts();
    }

    // Admin specific: Handle user selection
    if (isAdmin) {
        const chatTitle = document.getElementById('chatTitle');
        const chatAvatar = document.getElementById('chatAvatar');
        const chatActions = document.getElementById('chatActions');
        const chatInputContainer = document.getElementById('chatInputContainer');
        const receiverIdInput = document.getElementById('receiverId');
        const transferChatButton = document.getElementById('transferChatButton');
        const tabButtons = document.querySelectorAll('.tab-button');
        const userList = document.getElementById('userList');

        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                tabButtons.forEach(tab => tab.classList.remove('active'));
                this.classList.add('active');
                userFilterState.mode = this.dataset.filter === 'unread' ? 'unread' : 'all';
                applyUserFilter();
            });
        });

        if (userList) {
            userList.addEventListener('click', function (event) {
                const loadMoreChatsBtn = event.target.closest('#loadMoreChatsBtn');
                if (loadMoreChatsBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    loadMoreChatUsers();
                    return;
                }

                const pinButton = event.target.closest('.pin-chat-btn');
                if (pinButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    togglePinnedChat(pinButton.dataset.pinUserId);
                    return;
                }

                const item = event.target.closest('.user-item');
                if (!item) return;

                // Remove active class from all
                userList.querySelectorAll('.user-item').forEach(u => u.classList.remove('active'));

                // Add active class to clicked
                item.classList.add('active');

                // Get user info
                const targetUserId = item.dataset.userId;
                const userName = item.dataset.userName;

                // Update global variable
                window.selectedUserId = targetUserId;
                currentChatSessionId = '';
                window.chatSessionId = '';

                // Clear unread badge
                clearUnreadBadge(targetUserId);
                clearReplyPreview();

                // Update UI
                chatTitle.textContent = userName;
                chatAvatar.style.display = 'flex';
                chatAvatar.innerHTML = getInitials(userName);
                chatActions.style.display = 'flex';
                receiverIdInput.value = targetUserId;
                chatInputContainer.style.display = 'block';

                // Load messages for this user
                loadMessages(targetUserId, { autoScroll: true, resetSignature: true });

                axios.post(`/chat/mark-read/${targetUserId}`)
                    .catch(err => { });
            });
        }

        if (transferChatButton) {
            transferChatButton.addEventListener('click', function () {
                const targetUserId = String(window.selectedUserId || '');
                if (!targetUserId) return;

                const division = window.prompt('Teruskan ke divisi apa? Contoh: teknis, billing, cs', 'teknis');
                if (!division) return;

                const reason = window.prompt('Alasan diteruskan?', 'Permasalahan memerlukan penanganan divisi lain.');
                if (!reason) return;

                transferChatButton.disabled = true;

                axios.post(`${API_BASE}/transfer/${targetUserId}`, {
                    division,
                    transfer_reason: reason,
                    source_chat_id: currentChatSessionId || null,
                }).then(response => {
                    if (response.data?.message) {
                        appendMessage(response.data.message, false);
                        scrollToBottom();
                    }
                }).catch(error => {
                    showErrorDialog({
                        title: 'Gagal Meneruskan Chat',
                        message: error.response?.data?.error || 'Silakan coba lagi.'
                    });
                }).finally(() => {
                    transferChatButton.disabled = false;
                });
            });
        }

        applyUserFilter();
    } else {
        // User: Load messages immediately
        loadMessages(null, { autoScroll: true, resetSignature: true });
    }

    // Search functionality for admin
    if (isAdmin) {
        const searchInput = document.querySelector('.chat-search-input');
        if (searchInput) {
            let clearButton = null;

            // Add clear button first
            const searchWrapper = searchInput.parentElement;
            if (searchWrapper) {
                clearButton = document.createElement('button');
                clearButton.className = 'search-clear-btn';
                clearButton.type = 'button';
                clearButton.innerHTML = '<i class="fas fa-times"></i>';
                clearButton.style.cssText = `
                    position: absolute;
                    right: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    background: none;
                    border: none;
                    color: #94a3b8;
                    cursor: pointer;
                    font-size: 14px;
                    padding: 6px;
                    display: none;
                    transition: all 0.2s;
                    z-index: 10;
                `;

                clearButton.addEventListener('mouseenter', function () {
                    this.style.color = '#ef4444';
                    this.style.transform = 'translateY(-50%) scale(1.1)';
                });

                clearButton.addEventListener('mouseleave', function () {
                    this.style.color = '#94a3b8';
                    this.style.transform = 'translateY(-50%) scale(1)';
                });

                clearButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    searchInput.value = '';
                    const event = new Event('input', { bubbles: true });
                    searchInput.dispatchEvent(event);
                    searchInput.focus();
                });

                searchWrapper.appendChild(clearButton);
            }

            // Real-time search
            searchInput.addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                userFilterState.search = searchTerm;
                applyUserFilter();

                // Update clear button visibility
                if (clearButton) {
                    clearButton.style.display = searchTerm ? 'block' : 'none';
                }
            });

            // Prevent form submission
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        }
    }
    };

    waitForAxiosThenInit();
});
