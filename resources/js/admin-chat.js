// Admin Chat functionality - Uses /admin-chat/ endpoints for billing chat
// This is separate from CS chat (/chat/ endpoints)

document.addEventListener('DOMContentLoaded', function () {
    if (window.__adminBillingChatInitialized) return;
    window.__adminBillingChatInitialized = true;

    // Wait for axios to be available
    if (typeof window.axios === 'undefined') {
        console.error('[AdminChat] axios is not available. Skip chat init to avoid reload loop.');
        const fallbackContainer = document.getElementById('chatMessages');
        if (fallbackContainer) {
            fallbackContainer.innerHTML = `
                <div class="no-chat-selected">
                    <i class="fas fa-circle-exclamation no-chat-icon" style="color:#ef4444;"></i>
                    <div class="no-chat-text">Gagal memuat modul chat</div>
                    <div class="no-chat-subtext">Silakan refresh halaman. Jika masih terjadi, cek pemuatan asset JS.</div>
                </div>
            `;
        }
        return;
    }

    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const mediaInput = document.getElementById('mediaInput');
    const attachButton = document.getElementById('attachButton');
    const mediaPreview = document.getElementById('mediaPreview');
    const replyPreview = document.getElementById('replyPreview');
    const replyPreviewName = document.getElementById('replyPreviewName');
    const replyPreviewText = document.getElementById('replyPreviewText');
    const replyPreviewClose = document.getElementById('replyPreviewClose');
    let replyToMessageInput = document.getElementById('replyToMessageId');
    const quickReplies = document.getElementById('quickReplies');
    const editMessageModal = document.getElementById('editMessageModal');
    const editMessageForm = document.getElementById('editMessageForm');
    const editMessageInput = document.getElementById('editMessageInput');
    const editMessageError = document.getElementById('editMessageError');
    const editMessageSave = document.getElementById('editMessageSave');
    const editMessageCancel = document.getElementById('editMessageCancel');
    const editMessageModalClose = document.getElementById('editMessageModalClose');
    const deleteMessageModal = document.getElementById('deleteMessageModal');
    const deleteMessageConfirm = document.getElementById('deleteMessageConfirm');
    const deleteMessageCancel = document.getElementById('deleteMessageCancel');
    const deleteMessageModalClose = document.getElementById('deleteMessageModalClose');
    const adminChatContainer = document.querySelector('.admin-chat-container');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarToggleText = document.getElementById('sidebarToggleText');
    const handoffCsButton = document.getElementById('handoffCsButton');
    const MESSAGE_EDIT_WINDOW_MS = 15 * 60 * 1000;
    const messageStore = new Map();

    let selectedMediaFile = null;
    let activeReplyMessage = null;
    let activeEditMessageId = null;
    let activeDeleteMessageId = null;
    const processedRealtimeMessageIds = new Set();
    const FALLBACK_SYNC_INTERVAL_MS = 3000;
    let isSocketConnected = false;
    let fallbackSyncTimer = null;
    let lastRenderedMessagesSignature = '';
    let currentConversationKey = '';

    function initSidebarToggle() {
        if (!adminChatContainer || !sidebarToggleBtn) return;

        const storageKey = 'adminBillingSidebarMinimized';
        const safeGetState = () => {
            try {
                return localStorage.getItem(storageKey) === '1';
            } catch (_) {
                return false;
            }
        };
        const safeSetState = (isMinimized) => {
            try {
                localStorage.setItem(storageKey, isMinimized ? '1' : '0');
            } catch (_) { }
        };

        const applyState = (isMinimized) => {
            adminChatContainer.classList.toggle('sidebar-minimized', isMinimized);
            if (sidebarToggleText) {
                sidebarToggleText.textContent = isMinimized ? 'Tampilkan Sidebar' : 'Lebarkan Chat';
            }

            const icon = sidebarToggleBtn.querySelector('i');
            if (icon) {
                icon.className = isMinimized ? 'fas fa-compress-alt' : 'fas fa-expand-alt';
            }
        };

        applyState(safeGetState());

        sidebarToggleBtn.addEventListener('click', function () {
            const nextState = !adminChatContainer.classList.contains('sidebar-minimized');
            applyState(nextState);
            safeSetState(nextState);
        });
    }
    
    // Sound system for admin chat
    let audioUnlocked = false;
    let preloadedAudio = null;
    
    function initSound() {
        const unlockAudioContext = () => {
            if (audioUnlocked) return;

            if (!preloadedAudio) {
                preloadedAudio = new Audio('/sounds/42289.mp3');
                preloadedAudio.volume = 0.5;
            }
            
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
    
    function playNotificationSound() {
        if (!audioUnlocked) return;
        const now = Date.now();
        if (window.__lastSound && now - window.__lastSound < 1200) {
            return; // throttle to avoid continuous sound
        }
        window.__lastSound = now;
        
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
    
    // Initialize sound system during idle time to keep first render fast.
    if (typeof window.requestIdleCallback === 'function') {
        window.requestIdleCallback(() => initSound(), { timeout: 2000 });
    } else {
        setTimeout(() => initSound(), 800);
    }

    if (!chatMessages || !chatForm) {
        return;
    }

    initSidebarToggle();

    // Setup attach button click
    if (attachButton && mediaInput) {
        attachButton.addEventListener('click', function (e) {
            e.preventDefault();
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

    // Show media preview
    function showMediaPreview(file) {
        if (!mediaPreview) return;

        const isImage = file.type.startsWith('image/');
        const isVideo = file.type.startsWith('video/');

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
                    <i class="fas fa-video" style="font-size: 24px; color: #10b981;"></i>
                    <button type="button" class="remove-media-btn" onclick="window.clearMediaPreview()">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="media-filename">${file.name}</span>
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

    const hasReceiverField = !!document.getElementById('receiverId');
    const isAdmin = window.isAdmin === true && hasReceiverField;
    const userId = window.userId;
    const HIDDEN_STORAGE_KEY = `adminBillingHiddenChats:${String(userId || 'anonymous')}`;

    // API Base URLs - KEY DIFFERENCE: Uses /admin-chat/ instead of /chat/
    const API_BASE = '/admin-chat';
    const INITIAL_LOAD_LIMIT = 100;
    const OLDER_LOAD_LIMIT = 100;
    const CHAT_USER_PAGE_SIZE = 100;
    let pinnedChats = new Set();
    let hiddenChats = new Set();
    let loadedMessages = [];
    let canLoadOlderMessages = false;
    let isLoadingOlderMessages = false;
    let isLoadingMoreChats = false;
    let canLoadMoreChats = true;

    const QUICK_REPLY_TEMPLATES = [
        'Tagihan Anda sudah terbit. Silakan lakukan pembayaran sebelum jatuh tempo agar layanan tetap aktif.',
        'Silakan kirim bukti pembayaran (foto/screenshot) agar kami bantu verifikasi lebih cepat.',
        'Terima kasih, pembayaran Anda sedang kami proses. Mohon tunggu beberapa saat.',
        'Terima kasih sudah menghubungi Admin Billing. Jika ada kendala, kami siap bantu.',
        'Untuk kendala teknis layanan, silakan lanjutkan ke CS agar ditangani lebih cepat.',
        'Terima kasih atas masukan dan perhatian yang telah Anda sampaikan. Kami sangat menghargai setiap saran untuk meningkatkan kualitas pelayanan kami.\n\nSebagai tindak lanjut, kami ingin menanyakan apakah bukti pembayaran sudah diunggah melalui menu Tagihan? Mohon informasinya agar kami dapat melakukan pengecekan dan membantu proses selanjutnya dengan lebih cepat.\n\nTerima kasih atas kerja sama dan kesabarannya. 🙏'
    ];

    const QUICK_REPLY_SHORTCUTS = {
        '/bukti-tagihan': QUICK_REPLY_TEMPLATES[5]
    };

    // ===== Broadcast UI (admin only) =====
    if (isAdmin) {
        initSidebarBroadcast();
    }

    function applyQuickReplyByIndex(index) {
        const text = QUICK_REPLY_TEMPLATES[index];
        if (!text || !messageInput) return;
        messageInput.value = text;
        messageInput.focus();
        messageInput.setSelectionRange(messageInput.value.length, messageInput.value.length);
    }

    if (isAdmin && quickReplies) {
        quickReplies.style.display = 'flex';

        quickReplies.addEventListener('click', function (event) {
            const button = event.target.closest('.quick-reply-chip');
            if (!button) return;
            const index = parseInt(button.dataset.replyIndex || '', 10);
            if (!Number.isNaN(index)) {
                applyQuickReplyByIndex(index);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (!event.altKey || !window.selectedUserId) return;
            const key = parseInt(event.key, 10);
            if (Number.isNaN(key) || key < 1 || key > QUICK_REPLY_TEMPLATES.length) return;
            event.preventDefault();
            applyQuickReplyByIndex(key - 1);
        });
    }

    function initSidebarBroadcast() {
        const panel = document.getElementById('broadcastPanel');
        const toggle = document.getElementById('toggleBroadcast');
        const form = document.getElementById('bcFormSidebar');
        const typeEl = document.getElementById('bcTypeSidebar');
        const variantEl = document.getElementById('bcVariantSidebar');
        const messageEl = document.getElementById('bcMessageSidebar');
        const sendBtn = document.getElementById('bcSendSidebar');
        const statusEl = document.getElementById('bcStatusSidebar');
        const progressBox = document.getElementById('bcProgressSidebar');
        const progressText = document.getElementById('bcProgressTextSidebar');
        const progressPct = document.getElementById('bcProgressPctSidebar');
        const progressBar = document.getElementById('bcProgressBarSidebar');

        if (!panel || !toggle || !form || !typeEl || !variantEl || !messageEl || !sendBtn) {
            return;
        }

        let pollTimer = null;
        let total = 0;

        function setBroadcastVisible(visible) {
            panel.style.display = visible ? 'block' : 'none';
            toggle.textContent = visible ? 'Sembunyikan' : 'Tampilkan';
        }

        // default: hidden, supaya list pelanggan lebih luas
        setBroadcastVisible(false);

        toggle.addEventListener('click', function () {
            const visible = panel.style.display === 'none';
            setBroadcastVisible(visible);
        });

        function syncVariantVisibility() {
            const variantField = variantEl.closest('.broadcast-field');
            const showVariant = typeEl.value === 'greeting';
            if (variantField) {
                variantField.style.display = showVariant ? 'block' : 'none';
            } else {
                variantEl.style.display = showVariant ? 'block' : 'none';
            }
        }

        typeEl.addEventListener('change', syncVariantVisibility);
        syncVariantVisibility();

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const payload = { type: typeEl.value };
            if (payload.type === 'greeting') payload.variant = variantEl.value;
            if (messageEl.value.trim()) payload.message = messageEl.value.trim();

            if (payload.type === 'custom' && !payload.message) {
                alert('Isi pesan wajib untuk custom');
                return;
            }

            sendBtn.disabled = true;
            sendBtn.textContent = 'Mengirim...';
            if (statusEl) statusEl.style.display = 'none';
            if (progressBox) progressBox.style.display = 'block';
            updateProgress(0, total);

            axios.post('/admin-chat/broadcast', payload)
                .then(res => {
                    total = res.data.total || 0;
                    updateProgress(0, total);
                    if (statusEl) {
                        statusEl.textContent = `Broadcast dimulai (${total} pelanggan)`;
                        statusEl.style.display = 'inline-flex';
                    }
                    if (res.data.broadcast_id) {
                        startPoll(res.data.broadcast_id, total);
                    }
                })
                .catch(err => {
                    alert(err.response?.data?.error || 'Gagal kirim broadcast');
                    if (progressBox) progressBox.style.display = 'none';
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Kirim ke semua pelanggan';
                });
        });

        function startPoll(broadcastId, fallbackTotal) {
            stopPoll();
            pollTimer = setInterval(() => {
                axios.get(`/admin-chat/broadcast/${broadcastId}/progress`)
                    .then(res => {
                        const data = res.data || {};
                        const safeTotal = data.total || total || fallbackTotal || 0;
                        updateProgress(data.done || 0, safeTotal);

                        if (data.status === 'completed') {
                            stopPoll();
                            if (statusEl) {
                                statusEl.textContent = `Selesai ${data.done}/${safeTotal}`;
                                statusEl.style.display = 'inline-flex';
                            }
                        } else if (data.status === 'failed') {
                            stopPoll();
                            if (statusEl) {
                                statusEl.textContent = `Gagal: ${data.error || 'unknown'}`;
                                statusEl.style.display = 'inline-flex';
                            }
                        }
                    })
                    .catch(() => {
                        stopPoll();
                        if (statusEl) {
                            statusEl.textContent = 'Gagal memantau progress';
                            statusEl.style.display = 'inline-flex';
                        }
                    });
            }, 2000);
        }

        function stopPoll() {
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        }

        function updateProgress(done, totalCount) {
            if (!progressText || !progressPct || !progressBar) return;

            const hasTotal = totalCount > 0;
            const percent = hasTotal ? Math.floor((done / totalCount) * 100) : 0;
            progressText.textContent = hasTotal ? `${done}/${totalCount} dikirim` : `${done} dikirim...`;
            progressPct.textContent = hasTotal ? `${percent}%` : '...';
            progressBar.style.width = hasTotal ? `${Math.min(percent, 100)}%` : '0%';
        }
    }

    function setUnreadBadgeCount(senderId, rawCount) {
        const count = Math.max(0, parseInt(rawCount, 10) || 0);
        const badge = document.getElementById(`unread-${senderId}`);
        if (badge) {
            if (count > 0) {
                badge.textContent = String(count);
                badge.style.display = 'inline-flex';
            } else {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        }

        const userItem = document.querySelector(`.user-item[data-user-id="${senderId}"]`);
        if (userItem) userItem.dataset.unread = String(count);
    }

    function createUserListItem(user) {
        const safeId = escapeHtml(String(user.id || ''));
        const safeName = escapeHtml(String(user.name || 'Pelanggan'));
        const safeNomerId = escapeHtml(String(user.nomer_id || ''));
        const lastActivity = user.last_message_at ? new Date(user.last_message_at).getTime() : Date.now();

        const item = document.createElement('div');
        item.className = 'user-item';
        item.dataset.userId = safeId;
        item.dataset.userName = safeName;
        item.dataset.nomerId = safeNomerId;
        item.dataset.lastActivity = String(Number.isNaN(lastActivity) ? Date.now() : lastActivity);
        item.title = safeName;
        item.innerHTML = `
            <div class="user-item-content">
                <div class="user-avatar">${getInitials(safeName)}</div>
                <div class="user-details">
                    <div class="user-name">${safeName}</div>
                    <div class="user-meta-row"><div class="user-type">${safeNomerId}</div></div>
                    <span class="pin-badge"><i class="fas fa-thumbtack"></i> Pinned</span>
                </div>
                <span class="unread-badge" id="unread-${safeId}" style="display: none;">0</span>
                <button type="button" class="pin-chat-btn" data-pin-user-id="${safeId}" title="Pin chat agar tampil paling atas" aria-label="Pin chat ${safeName}">
                    <i class="fas fa-thumbtack"></i>
                </button>
            </div>
        `;

            const isPinned = toBooleanFlag(user.is_pinned) || pinnedChats.has(String(user.id || ''));
            setPinnedVisualState(item, isPinned);
            return item;
        }

    function ensureUserItemFromMessage(message) {
        if (!isAdmin || !message || !message.sender_id) return;

        const userList = document.getElementById('userList');
        if (!userList) return;

        const senderId = String(message.sender_id);
        if (userList.querySelector(`[data-user-id="${senderId}"]`)) return;

        userList.insertBefore(createUserListItem({
            id: senderId,
            name: message.sender?.name || message.sender_name || 'Pelanggan',
            nomer_id: message.sender?.email || senderId,
            last_message_at: message.created_at || new Date().toISOString(),
            is_pinned: pinnedChats.has(senderId),
        }), userList.firstElementChild);
    }

    function isCustomerSender(message) {
        return String(message?.sender?.role || message?.sender_role || '').toLowerCase() === 'pelanggan';
    }

    function buildMessagesSignature(messages) {
        if (!Array.isArray(messages) || messages.length === 0) return 'empty';

        const first = messages[0];
        const last = messages[messages.length - 1];
        const firstKey = `${first.id || 'x'}:${first.updated_at || first.edited_at || first.deleted_at || first.created_at || ''}:${first.is_read ? 1 : 0}`;
        const lastKey = `${last.id || 'x'}:${last.updated_at || last.edited_at || last.deleted_at || last.created_at || ''}:${last.is_read ? 1 : 0}`;

        return `${messages.length}|${firstKey}|${lastKey}`;
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
            userList.appendChild(wrapper);
        } else {
            userList.appendChild(wrapper);
        }

        wrapper.style.padding = '8px 4px 14px';
        const button = document.getElementById('loadMoreChatsBtn');
        if (button) {
            button.style.width = '100%';
            button.style.border = '1px solid rgba(255, 255, 255, 0.28)';
            button.style.background = '#202124';
            button.style.color = '#f8fafc';
            button.style.borderRadius = '26px';
            button.style.padding = '14px 16px';
            button.style.fontSize = '15px';
            button.style.fontWeight = '900';
            button.style.display = 'inline-flex';
            button.style.alignItems = 'center';
            button.style.justifyContent = 'center';
            button.style.gap = '10px';
            button.style.cursor = 'pointer';
        }
    }

    function setLoadMoreChatsButtonState(isLoading) {
        const button = document.getElementById('loadMoreChatsBtn');
        if (!button) return;

        button.disabled = isLoading;
        button.innerHTML = isLoading
            ? '<i class="fas fa-spinner fa-spin"></i><span>Memuat chat...</span>'
            : '<i class="fas fa-chevron-down"></i><span>Lihat chat lainnya</span>';
    }

    function renderUserListItems(users, options = {}) {
        const userList = document.getElementById('userList');
        if (!userList || !Array.isArray(users)) return;

        const { append = false, showLoadMore = false } = options;
        const loadMoreWrapper = document.getElementById('loadMoreChatsWrap');
        if (loadMoreWrapper) loadMoreWrapper.remove();

        if (!append) {
            userList.innerHTML = '';
        }

        const existingIds = new Set(Array.from(userList.querySelectorAll('.user-item')).map(item => String(item.dataset.userId || '')));
        users.forEach(user => {
            const userIdValue = String(user.id || '');
            if (!userIdValue || existingIds.has(userIdValue)) return;
            existingIds.add(userIdValue);
            userList.appendChild(createUserListItem(user));
        });

        ensureLoadMoreChatsButton(showLoadMore);

        loadUnreadCounts();
        sortUserList();
        applyUserFilter();
    }

    function loadUserListFromServer(searchTerm = '') {
        if (!isAdmin) return;
        const params = new URLSearchParams({ limit: String(CHAT_USER_PAGE_SIZE) });
        if (searchTerm) params.set('search', searchTerm);

        axios.get(`${API_BASE}/users?${params.toString()}`)
            .then(response => {
                const users = response.data || [];
                canLoadMoreChats = !searchTerm && users.length >= CHAT_USER_PAGE_SIZE;
                renderUserListItems(users, { append: false, showLoadMore: !searchTerm });
            })
            .catch(() => { });
    }

    function loadMoreChatUsers() {
        if (!isAdmin || isLoadingMoreChats || !canLoadMoreChats) return;

        const userList = document.getElementById('userList');
        const offset = userList ? userList.querySelectorAll('.user-item').length : 0;
        isLoadingMoreChats = true;
        setLoadMoreChatsButtonState(true);

        const params = new URLSearchParams({ limit: String(CHAT_USER_PAGE_SIZE), offset: String(offset) });
        axios.get(`${API_BASE}/users?${params.toString()}`)
            .then(response => {
                const users = response.data || [];
                canLoadMoreChats = users.length >= CHAT_USER_PAGE_SIZE;
                renderUserListItems(users, { append: true, showLoadMore: true });
            })
            .catch(() => { })
            .finally(() => {
                isLoadingMoreChats = false;
                setLoadMoreChatsButtonState(false);
            });
    }

    // Load unread counts for admin on page load
    function loadUnreadCounts() {
        if (!isAdmin) return;

        axios.get(`${API_BASE}/unread-count`)
            .then(response => {
                const unreadCounts = response.data || {};
                document.querySelectorAll('.user-item').forEach(item => {
                    const id = item.dataset.userId;
                    if (id) setUnreadBadgeCount(id, unreadCounts[id] || 0);
                });
                sortUserList();
                applyUserFilter();
            })
            .catch(error => { });
    }

    // Function to get initials from name
    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    // WhatsApp-style date formatting
    let lastDisplayedDate = null;

    function formatWhatsAppDate(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        const diffTime = todayOnly - dateOnly;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays === 0) {
            return 'Hari ini';
        } else if (diffDays === 1) {
            return 'Kemarin';
        } else if (diffDays < 7) {
            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            return dayNames[date.getDay()];
        } else {
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'numeric', year: 'numeric' });
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
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
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

    // Load messages - Uses /admin-chat/messages endpoint
    function loadMessages(targetUserId = null, options = {}) {
        const {
            autoScroll = true,
            skipIfUnchanged = false,
            resetSignature = false,
        } = options;
        const query = `limit=${encodeURIComponent(String(INITIAL_LOAD_LIMIT))}`;
        const url = isAdmin && targetUserId
            ? `${API_BASE}/messages/${targetUserId}?${query}`
            : `${API_BASE}/messages?${query}`;

        axios.get(url)
            .then(response => {
                const responseMessages = Array.isArray(response.data) ? response.data : [];
                const conversationKey = isAdmin
                    ? `admin:${String(targetUserId || '')}`
                    : `customer:${String(userId || '')}`;

                if (resetSignature || currentConversationKey !== conversationKey) {
                    currentConversationKey = conversationKey;
                    lastRenderedMessagesSignature = '';
                }

                const nextSignature = buildMessagesSignature(responseMessages);
                if (skipIfUnchanged && nextSignature === lastRenderedMessagesSignature) {
                    return;
                }

                loadedMessages = responseMessages;
                canLoadOlderMessages = loadedMessages.length >= INITIAL_LOAD_LIMIT;
                displayMessages(loadedMessages);
                lastRenderedMessagesSignature = nextSignature;

                if (autoScroll) {
                    scrollToBottom();
                }

                // For customers, mark admin messages as read
                if (!isAdmin) {
                    const unreadAdminMessages = response.data.filter(m =>
                        String(m.sender_id) !== String(userId) && !m.is_read
                    );

                    if (unreadAdminMessages.length > 0) {
                        const adminId = unreadAdminMessages[0].sender_id;
                        axios.post(`${API_BASE}/mark-read/${adminId}`)
                            .catch(err => { });
                    }
                }
            })
            .catch(error => { });
    }

    function loadOlderMessages() {
        if (isLoadingOlderMessages || !canLoadOlderMessages || loadedMessages.length === 0) return Promise.resolve(false);

        const targetUserId = window.selectedUserId || null;
        const oldestMessage = loadedMessages[0];
        if (!oldestMessage?.created_at) return Promise.resolve(false);

        isLoadingOlderMessages = true;
        setLoadMoreButtonState(true);

        const params = new URLSearchParams({
            limit: String(OLDER_LOAD_LIMIT),
            before: oldestMessage.created_at,
        });
        const url = isAdmin && targetUserId
            ? `${API_BASE}/messages/${targetUserId}?${params.toString()}`
            : `${API_BASE}/messages?${params.toString()}`;
        const previousScrollHeight = chatMessages.scrollHeight;

        return axios.get(url)
            .then(response => {
                const olderMessages = Array.isArray(response.data) ? response.data : [];
                if (olderMessages.length === 0) {
                    canLoadOlderMessages = false;
                    renderLoadMoreButton();
                    return false;
                }

                const existingIds = new Set(loadedMessages.map(message => String(message.id || '')));
                const uniqueOlderMessages = olderMessages.filter(message => !existingIds.has(String(message.id || '')));
                loadedMessages = uniqueOlderMessages.concat(loadedMessages);
                canLoadOlderMessages = olderMessages.length >= OLDER_LOAD_LIMIT;
                displayMessages(loadedMessages);

                const nextScrollHeight = chatMessages.scrollHeight;
                chatMessages.scrollTop = nextScrollHeight - previousScrollHeight;

                return uniqueOlderMessages.length > 0;
            })
            .catch(() => false)
            .finally(() => {
                isLoadingOlderMessages = false;
                setLoadMoreButtonState(false);
            });
    }

    // Display messages
    function displayMessages(messages) {
        chatMessages.innerHTML = '';
        lastDisplayedDate = null;
        messageStore.clear();

        if (!Array.isArray(messages) || messages.length === 0) {
            chatMessages.innerHTML = `
                <div class="no-chat-selected">
                    <i class="fas fa-inbox no-chat-icon" style="font-size: 48px; color: #10b981; margin-bottom: 16px;"></i>
                    <div class="no-chat-text" style="color: #10b981;">Belum ada pesan pembayaran</div>
                    <div class="no-chat-subtext">Mulai percakapan dengan mengirim pesan tentang pembayaran</div>
                </div>
            `;
            return;
        }

        renderLoadMoreButton();

        const fragment = document.createDocumentFragment();
        messages.forEach(message => {
            appendMessage(message, false, true, fragment);
        });
        chatMessages.appendChild(fragment);
    }

    function renderLoadMoreButton() {
        if (!canLoadOlderMessages) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'load-more-messages-wrap';
        wrapper.innerHTML = `
            <button type="button" class="load-more-messages-btn" id="loadMoreMessagesBtn">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Tampilkan pesan sebelumnya</span>
            </button>
        `;
        chatMessages.appendChild(wrapper);
    }

    function setLoadMoreButtonState(isLoading) {
        const button = document.getElementById('loadMoreMessagesBtn');
        if (!button) return;

        button.disabled = isLoading;
        button.innerHTML = isLoading
            ? '<i class="fas fa-spinner fa-spin"></i><span>Memuat...</span>'
            : '<i class="fas fa-clock-rotate-left"></i><span>Tampilkan pesan sebelumnya</span>';
    }

    function normalizeMessagePayload(message, fallbackMessage = null) {
        if (!message) return null;

        const normalized = {
            ...(fallbackMessage || {}),
            ...message,
        };

        normalized.chat_type = normalized.chat_type || 'admin';
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
        const replyInput = ensureReplyToMessageInput();
        const replyId = String(replyInput?.value || replyPreview?.dataset.replyMessageId || activeReplyMessage?.id || '').trim();
        if (!replyId) return null;

        return activeReplyMessage?.id && String(activeReplyMessage.id) === replyId
            ? activeReplyMessage
            : (messageStore.get(replyId) || { id: replyId });
    }

    function showReplyPreview(message) {
        if (!message || !message.id || !replyPreview) return;

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

    if (replyPreviewClose) {
        replyPreviewClose.addEventListener('click', clearReplyPreview);
    }

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
                    <span class="message-status-text pending">Menunggu</span>
                </span>
            `;
        }

        if (message.is_read) {
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

        if (message.media_type === 'audio' || String(message.media_original_name || '').startsWith('voice-note-')) {
            return `
                <div class="message-media voice-note-card">
                    <i class="fas fa-microphone"></i>
                    <audio controls preload="metadata" src="${message.media_url}" style="width: 220px; max-width: 100%; height: 36px;"></audio>
                </div>
            `;
        }

        if (message.media_type === 'video') {
            return `
                <div class="message-media">
                    <video controls style="max-width: 250px; max-height: 200px; border-radius: 8px; margin-bottom: 6px;">
                        <source src="${message.media_url}" type="video/mp4">
                    </video>
                </div>
            `;
        }

        return '';
    }

    function renderMessageText(message) {
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
        if (!message || !message.created_at || message.is_deleted) return false;
        const createdAt = new Date(message.created_at).getTime();
        if (Number.isNaN(createdAt)) return false;
        return (Date.now() - createdAt) <= MESSAGE_EDIT_WINDOW_MS;
    }

    function renderMessageActions(message, isSent) {
        if (!message?.id) {
            return '';
        }

        const canManage = isAdmin && isSent && canManageMessage(message);

        return `
            <span class="message-actions">
                <button type="button" class="message-action-btn js-reply-message" data-message-id="${message.id}" title="Balas"><i class="fas fa-reply"></i></button>
                ${canManage ? `<button type="button" class="message-action-btn js-edit-message" data-message-id="${message.id}" title="Edit"><i class="fas fa-pen"></i></button>` : ''}
                ${canManage ? `<button type="button" class="message-action-btn js-delete-message" data-message-id="${message.id}" title="Hapus"><i class="fas fa-trash"></i></button>` : ''}
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
        const text = isHandoff
            ? 'Pesan anda sudah dialihkan ke CS kami silahkan klik disini'
            : message.message;
        const body = isHandoff
            ? `<a href="/dashboard/customer/chat" class="system-notice-link">${escapeHtml(text)}</a>`
            : escapeHtml(text);

        return `
            <div class="system-message-notice ${isHandoff ? 'handoff-notice' : ''}">
                ${body}
            </div>
        `;
    }

    function patchMessageElement(messageDiv, message, isPending = false) {
        const isSent = messageDiv.classList.contains('sent');
        const bubble = messageDiv.querySelector('.message-bubble');
        if (!bubble) return;

        messageDiv.dataset.messageId = message.id ? String(message.id) : '';
        messageDiv.dataset.createdAt = message.created_at || '';
        bubble.innerHTML = renderMessageContent(message, isSent, isPending);
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

    // Append single message
    function appendMessage(message, isPending = false, _isLoading = false, targetContainer = chatMessages) {
        const normalized = cacheMessage(message);
        if (!normalized) return;

        if (normalized.id) {
            const existingMessage = chatMessages.querySelector(`[data-message-id="${normalized.id}"]`);
            if (existingMessage) {
                patchMessageElement(existingMessage, normalized, isPending);
                return;
            }
        }

        if (normalized.created_at && shouldShowDateDivider(normalized.created_at)) {
            targetContainer.appendChild(createDateDivider(normalized.created_at));
        }

        const messageDiv = document.createElement('div');
        if (normalized.message_type === 'handoff_to_cs' || normalized.message_type === 'system') {
            messageDiv.className = 'message system';
            if (normalized.id) {
                messageDiv.dataset.messageId = normalized.id;
            }
            messageDiv.dataset.createdAt = normalized.created_at || '';
            messageDiv.innerHTML = renderSystemNotice(normalized);
            targetContainer.appendChild(messageDiv);
            return;
        }

        const currentUserId = String(userId);
        const messageSenderId = String(normalized.sender_id);
        const senderRole = String(normalized.sender_role || normalized.sender?.role || '').toLowerCase();
        const activeCustomerId = String(window.selectedUserId || document.getElementById('receiverId')?.value || '');
        const isAdminSender = ['administrator', 'admin', 'verifikasi'].includes(senderRole);
        const isSent = messageSenderId === currentUserId || (isAdmin && (isAdminSender || (activeCustomerId && messageSenderId !== activeCustomerId)));
        const senderName = normalized.sender_name || normalized.sender?.name || (isSent ? window.userName : document.getElementById('chatTitle')?.textContent) || 'Unknown';
        const initials = getInitials(senderName);

        messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
        if (normalized.id) {
            messageDiv.dataset.messageId = normalized.id;
        }
        messageDiv.dataset.createdAt = normalized.created_at || '';

        messageDiv.innerHTML = `
            <div class="message-avatar">${initials}</div>
            <div class="message-bubble">
                ${renderMessageContent(normalized, isSent, isPending)}
            </div>
        `;

        targetContainer.appendChild(messageDiv);
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

    function showModal(modal) {
        if (!modal) return;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function hideModal(modal) {
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');

        const anotherOpen = (editMessageModal && editMessageModal.classList.contains('show')) ||
            (deleteMessageModal && deleteMessageModal.classList.contains('show'));
        if (!anotherOpen) {
            document.body.style.overflow = '';
        }
    }

    function openEditModal(messageId) {
        if (!editMessageModal || !editMessageInput) return;
        const messageData = messageStore.get(String(messageId)) || getMessageFromDom(messageId);
        if (!messageData || messageData.is_deleted) return;

        activeEditMessageId = String(messageId);
        editMessageInput.value = messageData.message || '';
        if (editMessageError) {
            editMessageError.style.display = 'none';
            editMessageError.textContent = '';
        }
        showModal(editMessageModal);

        setTimeout(() => {
            editMessageInput.focus();
            editMessageInput.setSelectionRange(editMessageInput.value.length, editMessageInput.value.length);
        }, 50);
    }

    function closeEditModal() {
        activeEditMessageId = null;
        if (editMessageForm) {
            editMessageForm.reset();
        }
        if (editMessageError) {
            editMessageError.style.display = 'none';
            editMessageError.textContent = '';
        }
        hideModal(editMessageModal);
    }

    function openDeleteModal(messageId) {
        if (!deleteMessageModal) return;
        activeDeleteMessageId = String(messageId);
        showModal(deleteMessageModal);
    }

    function closeDeleteModal() {
        activeDeleteMessageId = null;
        hideModal(deleteMessageModal);
    }

    function findMessageElement(messageId) {
        const targetId = String(messageId);
        return Array.from(chatMessages.querySelectorAll('[data-message-id]'))
            .find(element => String(element.dataset.messageId) === targetId) || null;
    }

    function highlightMessage(messageId) {
        const target = findMessageElement(messageId);
        if (!target) return false;

        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        target.classList.add('message-highlight');
        setTimeout(() => target.classList.remove('message-highlight'), 1200);

        return true;
    }

    async function jumpToQuotedMessage(messageId) {
        if (!messageId) return;
        if (highlightMessage(messageId)) return;

        while (canLoadOlderMessages) {
            const loadedOlder = await loadOlderMessages();
            if (highlightMessage(messageId) || !loadedOlder) return;
        }
    }

    if (editMessageForm) {
        editMessageForm.addEventListener('submit', function (event) {
            event.preventDefault();
            if (!activeEditMessageId) return;

            const text = (editMessageInput.value || '').trim();
            if (!text) {
                if (editMessageError) {
                    editMessageError.textContent = 'Pesan tidak boleh kosong.';
                    editMessageError.style.display = 'block';
                }
                return;
            }

            if (editMessageSave) {
                editMessageSave.disabled = true;
                editMessageSave.textContent = 'Menyimpan...';
            }

            axios.put(`${API_BASE}/messages/${activeEditMessageId}`, { message: text })
                .then(response => {
                    const updatedMessage = response?.data?.message;
                    if (updatedMessage) {
                        updateMessageRealtime(updatedMessage, { appendIfMissing: true });
                    }
                    closeEditModal();
                })
                .catch(err => {
                    if (editMessageError) {
                        editMessageError.textContent = err.response?.data?.error || 'Gagal edit pesan';
                        editMessageError.style.display = 'block';
                    }
                })
                .finally(() => {
                    if (editMessageSave) {
                        editMessageSave.disabled = false;
                        editMessageSave.textContent = 'Simpan perubahan';
                    }
                });
        });
    }

    if (editMessageCancel) {
        editMessageCancel.addEventListener('click', closeEditModal);
    }
    if (editMessageModalClose) {
        editMessageModalClose.addEventListener('click', closeEditModal);
    }
    if (editMessageModal) {
        editMessageModal.addEventListener('click', function (event) {
            if (event.target === editMessageModal) {
                closeEditModal();
            }
        });
    }

    if (deleteMessageConfirm) {
        deleteMessageConfirm.addEventListener('click', function () {
            if (!activeDeleteMessageId) return;

            deleteMessageConfirm.disabled = true;
            deleteMessageConfirm.textContent = 'Menghapus...';

            axios.delete(`${API_BASE}/messages/${activeDeleteMessageId}`)
                .then(response => {
                    const updatedMessage = response?.data?.message;
                    if (updatedMessage) {
                        updateMessageRealtime(updatedMessage, { appendIfMissing: true });
                    }
                    closeDeleteModal();
                })
                .catch(err => {
                    alert(err.response?.data?.error || 'Gagal hapus pesan');
                })
                .finally(() => {
                    deleteMessageConfirm.disabled = false;
                    deleteMessageConfirm.textContent = 'Hapus pesan';
                });
        });
    }

    if (deleteMessageCancel) {
        deleteMessageCancel.addEventListener('click', closeDeleteModal);
    }
    if (deleteMessageModalClose) {
        deleteMessageModalClose.addEventListener('click', closeDeleteModal);
    }
    if (deleteMessageModal) {
        deleteMessageModal.addEventListener('click', function (event) {
            if (event.target === deleteMessageModal) {
                closeDeleteModal();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        if (editMessageModal && editMessageModal.classList.contains('show')) {
            closeEditModal();
            return;
        }
        if (deleteMessageModal && deleteMessageModal.classList.contains('show')) {
            closeDeleteModal();
        }
    });

    chatMessages.addEventListener('click', function (event) {
        const loadMoreBtn = event.target.closest('#loadMoreMessagesBtn');
        if (loadMoreBtn) {
            event.preventDefault();
            loadOlderMessages();
            return;
        }

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
            jumpToQuotedMessage(quoted.dataset.jumpMessageId);
            return;
        }

        const editBtn = event.target.closest('.js-edit-message');
        if (editBtn) {
            openEditModal(editBtn.dataset.messageId);
            return;
        }

        const deleteBtn = event.target.closest('.js-delete-message');
        if (deleteBtn) {
            openDeleteModal(deleteBtn.dataset.messageId);
        }
    });

    function updateUnreadBadge(senderId) {
        const userItem = document.querySelector(`.user-item[data-user-id="${senderId}"]`);
        const badge = document.getElementById(`unread-${senderId}`);
        const currentCount = parseInt(userItem?.dataset?.unread || badge?.textContent || '0', 10) || 0;
        setUnreadBadgeCount(senderId, currentCount + 1);
        sortUserList();
        applyUserFilter();
    }

    function loadHiddenChatsFromStorage() {
        try {
            const raw = localStorage.getItem(HIDDEN_STORAGE_KEY);
            if (!raw) return new Set();
            const parsed = JSON.parse(raw);
            if (!Array.isArray(parsed)) return new Set();
            return new Set(parsed.map(value => String(value)));
        } catch (_) {
            return new Set();
        }
    }

    function saveHiddenChatsToStorage() {
        try {
            localStorage.setItem(HIDDEN_STORAGE_KEY, JSON.stringify(Array.from(hiddenChats)));
        } catch (_) { }
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

    function setHiddenVisualState(userItem, isHidden) {
        if (!userItem) return;
        userItem.dataset.hidden = isHidden ? '1' : '0';
        userItem.classList.toggle('is-hidden', isHidden);
    }

    function sortUserList() {
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

    function hideChatFromList() { /* removed feature */ }
    function unhideChatFromList() { /* removed feature */ }

    function togglePinnedChat(userId) {
        const targetUserId = String(userId || '');
        if (!targetUserId) return;

        const shouldPin = !pinnedChats.has(targetUserId);

        if (!shouldPin) {
            pinnedChats.delete(targetUserId);
        } else {
            pinnedChats.add(targetUserId);
        }

        const userItem = document.querySelector(`.user-item[data-user-id="${targetUserId}"]`);
        setPinnedVisualState(userItem, pinnedChats.has(targetUserId));
        sortUserList();
        applyUserFilter(document.querySelector('.chat-search-input')?.value || '');

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
            applyUserFilter(document.querySelector('.chat-search-input')?.value || '');
            alert('Gagal menyimpan pin chat. Silakan coba lagi.');
        });
    }

    function touchUserActivity(userId) {
        const userList = document.getElementById('userList');
        if (!userList) return;
        const userItem = userList.querySelector(`.user-item[data-user-id="${userId}"]`);
        if (!userItem) return;
        userItem.dataset.lastActivity = String(Date.now());
    }

    function initPinnedChats() {
        if (!isAdmin) return;
        const userList = document.getElementById('userList');
        if (!userList) return;

        const userItems = Array.from(userList.querySelectorAll('.user-item'));
        if (userItems.length === 0) return;

        pinnedChats = new Set(
            userItems
                .filter(item => item.dataset.pinned === '1' || item.classList.contains('pinned'))
                .map(item => String(item.dataset.userId || ''))
                .filter(Boolean)
        );
        hiddenChats = loadHiddenChatsFromStorage();

        const baselineActivity = Date.now();
        userItems.forEach((item, index) => {
            if (!item.dataset.lastActivity) {
                item.dataset.lastActivity = String(baselineActivity - index);
            }
            const currentUserId = String(item.dataset.userId || '');
            setPinnedVisualState(item, pinnedChats.has(currentUserId));
            setHiddenVisualState(item, hiddenChats.has(currentUserId));
        });

        sortUserList();

        axios.get(`${API_BASE}/pins`)
            .then(response => {
                const serverPins = Array.isArray(response.data) ? response.data.map(value => String(value)) : [];
                pinnedChats = new Set(serverPins);
                userItems.forEach((item) => {
                    const currentUserId = String(item.dataset.userId || '');
                    setPinnedVisualState(item, pinnedChats.has(currentUserId));
                });
                sortUserList();
                applyUserFilter(document.querySelector('.chat-search-input')?.value || '');
            })
            .catch(() => { });
    }

    function moveUserToTop(userId) {
        touchUserActivity(userId);
        sortUserList();
    }

    function clearUnreadBadge(userId) {
        setUnreadBadgeCount(userId, 0);
        sortUserList();
        applyUserFilter();
    }

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

            const cacheKey = String(messageId);
            const cachedMessage = messageStore.get(cacheKey);
            if (cachedMessage) {
                messageStore.set(cacheKey, {
                    ...cachedMessage,
                    is_read: true,
                });
            }
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    function refreshBillingChatFallback() {
        if (document.visibilityState !== 'visible') return;

        if (isAdmin) {
            if (window.selectedUserId) {
                loadMessages(window.selectedUserId, { autoScroll: false, skipIfUnchanged: true });
            }
            loadUnreadCounts();
            return;
        }

        loadMessages(null, { autoScroll: false, skipIfUnchanged: true });
    }

    function ensureFallbackSync() {
        if (fallbackSyncTimer) return;
        fallbackSyncTimer = setInterval(refreshBillingChatFallback, FALLBACK_SYNC_INTERVAL_MS);
    }

    let isSendingMessage = false;
    let sendMessageFallbackTimer = null;

    function clearSendFallbackTimer() {
        if (sendMessageFallbackTimer) {
            clearTimeout(sendMessageFallbackTimer);
            sendMessageFallbackTimer = null;
        }
    }

    function startSendFallbackTimer() {
        clearSendFallbackTimer();
        sendMessageFallbackTimer = setTimeout(() => {
            isSendingMessage = false;
            if (sendButton) sendButton.disabled = false;
        }, 15000);
    }

    // Enter-to-send for billing chat input (single line input only)
    if (messageInput && messageInput.tagName === 'INPUT') {
        messageInput.addEventListener('keydown', function (event) {
            if (event.isComposing) return;
            if (event.key !== 'Enter' || event.shiftKey) return;
            event.preventDefault();
            if (typeof chatForm.requestSubmit === 'function') {
                chatForm.requestSubmit();
            } else {
                chatForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            }
        });
    }

    // Send message - Uses /admin-chat/send endpoint
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (isSendingMessage) return;

        const rawMessage = messageInput.value.trim();
        const message = QUICK_REPLY_SHORTCUTS[rawMessage.toLowerCase()] || rawMessage;
        if (!message && !selectedMediaFile) return;

        const formData = new FormData();
        formData.append('message', message);

        const replyMessage = getActiveReplyMessage();
        if (replyMessage?.id) {
            formData.append('reply_to_message_id', replyMessage.id);
            formData.append('reply_message_id', replyMessage.id);
        }

        if (selectedMediaFile) {
            formData.append('media', selectedMediaFile);
        }

        if (isAdmin) {
            const receiverId = document.getElementById('receiverId').value;
            if (!receiverId) {
                alert('Pilih pelanggan terlebih dahulu');
                return;
            }
            formData.append('receiver_id', receiverId);
        }

        const receiverIdValue = document.getElementById('receiverId')?.value || null;
        const tempId = `pending-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
        const optimisticMessage = {
            id: tempId,
            sender_id: String(userId),
            receiver_id: receiverIdValue,
            chat_type: 'admin',
            message,
            media_url: selectedMediaFile ? URL.createObjectURL(selectedMediaFile) : null,
            media_type: selectedMediaFile
                ? (selectedMediaFile.type.startsWith('image/') ? 'image' : (selectedMediaFile.type.startsWith('video/') ? 'video' : null))
                : null,
            media_original_name: selectedMediaFile?.name || null,
            is_read: false,
            is_deleted: false,
            created_at: new Date().toISOString(),
            sender_name: window.userName || 'Admin',
            sender_role: 'admin',
            sender: {
                id: String(userId),
                name: window.userName || 'Admin',
                role: 'admin',
            },
            reply_to_message_id: replyMessage?.id || null,
            reply_to_message: replyMessage?.id ? {
                id: replyMessage.id,
                sender_id: replyMessage.sender_id,
                sender_name: replyMessage.sender_name || replyMessage.sender?.name || 'Pengirim',
                message: replyTextForMessage(replyMessage),
                media_type: replyMessage.media_type || null,
            } : null,
        };

        isSendingMessage = true;
        sendButton.disabled = true;
        startSendFallbackTimer();
        messageInput.value = '';
        window.clearMediaPreview();
        clearReplyPreview();

        appendMessage(optimisticMessage, true);
        scrollToBottom();

        axios.post(`${API_BASE}/send`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
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

                if (response.data?.message) {
                    loadedMessages.push(response.data.message);
                }
                const pendingNode = findMessageElement(tempId);
                if (pendingNode) pendingNode.remove();
                appendMessage(response.data.message, false);
                scrollToBottom();

                if (isAdmin && window.selectedUserId) {
                    moveUserToTop(window.selectedUserId);
                }
            })
            .catch(error => {
                const pendingNode = findMessageElement(tempId);
                if (pendingNode) pendingNode.remove();
                console.error('Send error:', error);
                alert('Gagal mengirim pesan: ' + (error.response?.data?.error || 'Unknown error'));
            })
            .finally(() => {
                clearSendFallbackTimer();
                isSendingMessage = false;
                sendButton.disabled = false;
                messageInput.focus();
            });
    });

    // WebSocket listener
    function setupWebSocketListener() {
        if (!window.Echo) {
            setTimeout(setupWebSocketListener, 100);
            return;
        }

        if (!userId) {
            console.error('[AdminChat] Missing userId, skip websocket subscription');
            return;
        }

        const pusherConnection = window.Echo?.connector?.pusher?.connection;
        if (pusherConnection) {
            isSocketConnected = pusherConnection.state === 'connected';
            pusherConnection.bind('connected', () => {
                isSocketConnected = true;
            });
            pusherConnection.bind('disconnected', () => {
                isSocketConnected = false;
            });
            pusherConnection.bind('unavailable', () => {
                isSocketConnected = false;
            });
            pusherConnection.bind('failed', () => {
                isSocketConnected = false;
            });
            pusherConnection.bind('error', () => {
                isSocketConnected = false;
            });
        }

    const channel = `billing-chat.${userId}`;
    const privateChannel = window.Echo.private(channel);

    privateChannel
        // Support both custom broadcastAs event name (.MessageSent) and namespaced fallback.
        .listen('.MessageSent', (e) => {
            processIncomingMessage(e);
        })
        .listen('.MessageRead', (e) => {
            updateMessageReadStatus(e.message_ids);
        })
        .listen('.MessageUpdated', (e) => {
            processMessageUpdated(e);
        })
        .listen('MessageSent', (e) => {
            processIncomingMessage(e);
        })
        .listen('MessageRead', (e) => {
            updateMessageReadStatus(e.message_ids);
        })
        .listen('MessageUpdated', (e) => {
            processMessageUpdated(e);
        });

    if (isAdmin) {
        window.Echo.private('billing-admin-inbox')
            .listen('.MessageSent', (e) => {
                processIncomingMessage(e);
            })
            .listen('.MessageUpdated', (e) => {
                processMessageUpdated(e);
            })
            .listen('MessageSent', (e) => {
                processIncomingMessage(e);
            })
            .listen('MessageUpdated', (e) => {
                processMessageUpdated(e);
            });
    }

        function processIncomingMessage(e) {
            // Only process admin/billing chat messages (strict filter)
            const chatType = e.chat_type || 'cs';
            if (chatType !== 'admin') {
                return; // Ignore non-admin messages (including CS chat)
            }

            const currentUserId = String(userId);
            const eventSenderId = String(e.sender_id);
            const eventMessageId = e.id ? String(e.id) : '';

            if (eventMessageId) {
                if (processedRealtimeMessageIds.has(eventMessageId)) return;
                processedRealtimeMessageIds.add(eventMessageId);
                if (processedRealtimeMessageIds.size > 500) {
                    processedRealtimeMessageIds.delete(processedRealtimeMessageIds.values().next().value);
                }
            }

            // Don't process messages sent by ourselves
            if (eventSenderId === currentUserId) return;

            if (isAdmin) {
                // For billing admin: only process messages FROM customers
                if (!isCustomerSender(e)) return;

                const selectedUserId = String(window.selectedUserId || '');

                // Always move customer to top and play sound when customer sends message
                ensureUserItemFromMessage(e);
                moveUserToTop(e.sender_id);
                playNotificationSound();

                if (!window.selectedUserId || selectedUserId !== eventSenderId) {
                    // Not viewing this customer's chat - update badge
                    updateUnreadBadge(e.sender_id);
                } else {
                    // Currently viewing this customer's chat - show message
                    loadedMessages.push(e);
                    appendMessage(e, false);
                    scrollToBottom();
                    axios.post(`${API_BASE}/mark-read/${e.sender_id}`)
                        .catch(err => { });
                }
            } else {
                // For customer: show all incoming billing admin messages
                loadedMessages.push(e);
                appendMessage(e, false);
                scrollToBottom();
                playNotificationSound();
                axios.post(`${API_BASE}/mark-read/${e.sender_id}`)
                    .catch(err => { });
            }
        }

        function processMessageUpdated(e) {
            if (!e || !e.message) return;
            const payload = e.message;
            if ((payload.chat_type || 'cs') !== 'admin') return;

            const senderId = String(payload.sender_id || '');
            const receiverId = String(payload.receiver_id || '');

            if (isAdmin) {
                const selectedUserId = String(window.selectedUserId || '');
                if (!selectedUserId) return;

                if (selectedUserId === senderId || selectedUserId === receiverId) {
                    updateMessageRealtime(payload, { appendIfMissing: true });
                }
            } else {
                const currentUserId = String(userId);
                if (senderId === currentUserId || receiverId === currentUserId) {
                    updateMessageRealtime(payload, { appendIfMissing: true });
                }
            }
        }
    }

    setupWebSocketListener();
    ensureFallbackSync();

    if (isAdmin) {
        loadUnreadCounts();
    }

    // Admin specific: Handle user selection
    if (isAdmin) {
        initPinnedChats();

        const userList = document.getElementById('userList');
        const chatTitle = document.getElementById('chatTitle');
        const chatAvatar = document.getElementById('chatAvatar');
        const chatSubtitle = document.getElementById('chatSubtitle');
        const chatInputContainer = document.getElementById('chatInputContainer');
        const receiverIdInput = document.getElementById('receiverId');
        const getVisibleUserItems = () => userList ? userList.querySelectorAll('.user-item') : [];

        function openUserChatByItem(item) {
            if (!item) return;

            getVisibleUserItems().forEach(u => u.classList.remove('active'));
            item.classList.add('active');

            const targetUserId = item.dataset.userId;
            const userName = item.dataset.userName;
            const nomerId = item.dataset.nomerId;

            window.selectedUserId = targetUserId;
            clearUnreadBadge(targetUserId);
            touchUserActivity(targetUserId);

            if (chatTitle) {
                chatTitle.textContent = userName;
            }
            if (chatAvatar) {
                chatAvatar.style.display = 'flex';
                chatAvatar.innerHTML = getInitials(userName);
            }
            if (chatSubtitle) {
                chatSubtitle.style.display = 'block';
                chatSubtitle.innerHTML = nomerId ? `<strong>${nomerId}</strong>` : '';
            }
            if (receiverIdInput) receiverIdInput.value = targetUserId;
            if (chatInputContainer) chatInputContainer.style.display = 'block';
            if (handoffCsButton) handoffCsButton.style.display = 'inline-flex';

            loadMessages(targetUserId, { resetSignature: true });

            axios.post(`${API_BASE}/mark-read/${targetUserId}`)
                .catch(err => { });
        }

        function resetSelectedChatPanel() {
            window.selectedUserId = null;
            loadedMessages = [];
            canLoadOlderMessages = false;
            if (receiverIdInput) {
                receiverIdInput.value = '';
            }
            getVisibleUserItems().forEach(u => u.classList.remove('active'));

            if (chatTitle) {
                chatTitle.textContent = 'Pilih pelanggan untuk memulai chat';
            }
            if (chatAvatar) {
                chatAvatar.style.display = 'none';
                chatAvatar.innerHTML = '<i class="fas fa-user"></i>';
            }
            if (chatSubtitle) {
                chatSubtitle.style.display = 'none';
                chatSubtitle.innerHTML = '';
            }
            if (chatInputContainer) {
                chatInputContainer.style.display = 'none';
            }
            if (handoffCsButton) {
                handoffCsButton.style.display = 'none';
                handoffCsButton.disabled = false;
                handoffCsButton.innerHTML = '<i class="fas fa-headset"></i><span>Alihkan ke CS</span>';
            }

            if (chatMessages) {
                chatMessages.innerHTML = `
                    <div class="no-chat-selected">
                        <i class="fas fa-comments-dollar no-chat-icon"></i>
                        <div class="no-chat-text">Chat Pembayaran</div>
                        <div class="no-chat-subtext">Pilih pelanggan dari sidebar untuk memulai percakapan tentang pembayaran</div>
                    </div>
                `;
            }
        }

        if (userList) {
            canLoadMoreChats = userList.querySelectorAll('.user-item').length >= CHAT_USER_PAGE_SIZE;
            ensureLoadMoreChatsButton(canLoadMoreChats);

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

                const userItem = event.target.closest('.user-item');
                if (!userItem || !userList.contains(userItem)) return;
                openUserChatByItem(userItem);
            });

            // Fallback: bind directly in case delegated click is blocked by layout layer.
            userList.querySelectorAll('.user-item').forEach((item) => {
                if (item.dataset.boundOpen === '1') return;
                item.dataset.boundOpen = '1';
                item.addEventListener('click', function (event) {
                    if (event.target.closest('.pin-chat-btn')) return;
                    openUserChatByItem(this);
                });
            });
        }

        if (handoffCsButton) {
            handoffCsButton.addEventListener('click', function () {
                const targetUserId = String(window.selectedUserId || '');
                if (!targetUserId || handoffCsButton.disabled) return;

                handoffCsButton.disabled = true;
                handoffCsButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Mengalihkan...</span>';

                axios.post(`${API_BASE}/handoff-to-cs/${targetUserId}`)
                    .then(response => {
                        if (response.data?.message) {
                            appendMessage(response.data.message, false);
                            scrollToBottom();
                        }
                        handoffCsButton.innerHTML = '<i class="fas fa-check"></i><span>Dialihkan</span>';
                        setTimeout(() => {
                            handoffCsButton.disabled = false;
                            handoffCsButton.innerHTML = '<i class="fas fa-headset"></i><span>Alihkan ke CS</span>';
                        }, 1800);
                    })
                    .catch(error => {
                        console.error('Handoff to CS failed:', error);
                        handoffCsButton.disabled = false;
                        handoffCsButton.innerHTML = '<i class="fas fa-headset"></i><span>Alihkan ke CS</span>';
                        alert('Gagal mengalihkan ke CS. Silakan coba lagi.');
                    });
            });
        }
    } else {
        // Customer: Load messages immediately
        loadMessages(null, { resetSignature: true });
    }

    // Search functionality for admin
    if (isAdmin) {
        const searchInput = document.querySelector('.chat-search-input');
        if (searchInput) {
            let searchDebounceTimer = null;
            searchInput.addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                if (searchDebounceTimer) {
                    clearTimeout(searchDebounceTimer);
                }
                searchDebounceTimer = setTimeout(() => {
                    if (searchTerm.length >= 2) {
                        loadUserListFromServer(searchTerm);
                    } else if (searchTerm.length === 0) {
                        loadUserListFromServer('');
                    } else {
                        applyUserFilter(searchTerm);
                    }
                }, 250);
            });

            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') e.preventDefault();
            });
        }

        // tab filter
        const tabAll = document.getElementById('tabAll');
        const tabUnread = document.getElementById('tabUnread');
        if (tabAll && tabUnread) {
            tabAll.addEventListener('click', () => {
                tabAll.classList.add('active');
                tabUnread.classList.remove('active');
                applyUserFilter(searchInput?.value || '');
            });
            tabUnread.addEventListener('click', () => {
                tabUnread.classList.add('active');
                tabAll.classList.remove('active');
                applyUserFilter(searchInput?.value || '');
            });
        }
    }
});

    // Filter helper: search + tab (all/unread)
    function applyUserFilter(searchTerm = '') {
        const isAdmin = window.isAdmin === true && !!document.getElementById('receiverId');
        if (!isAdmin) return;

        const search = (searchTerm || '').toLowerCase().trim();
        const tabUnreadActive = document.getElementById('tabUnread')?.classList.contains('active');
        const userList = document.getElementById('userList');
        if (!userList) return;

        const userItems = userList.querySelectorAll('.user-item');

        userItems.forEach(item => {
            const userName = (item.dataset.userName || '').toLowerCase();
            const userType = (item.querySelector('.user-type')?.textContent || '').toLowerCase();
            const unread = parseInt(item.dataset.unread || '0', 10);

            let visible = true;
            if (search && !(userName.includes(search) || userType.includes(search))) {
                visible = false;
            }
            if (item.dataset.hidden === '1') {
                visible = false;
            }
            if (tabUnreadActive && unread <= 0) {
                visible = false;
            }

            item.style.display = visible ? 'block' : 'none';
        });

        const loadMoreWrapper = document.getElementById('loadMoreChatsWrap');
        if (loadMoreWrapper) userList.appendChild(loadMoreWrapper);
    }
