@auth
@unless(request()->routeIs('admin.contact-conversations.*'))
<div
    x-data="chatWidget()"
    x-init="init()"
    class="fixed bottom-4 right-4 z-50"
>
    <button
        @click="toggle()"
        x-show="!open && !isModalOpen"
        x-transition
        class="relative flex items-center justify-center w-14 h-14 rounded-full shadow-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition border-2 border-white"
        style="display: none;"
    >
        <i class="fas fa-comments text-2xl text-white"></i>
        <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 inline-flex items-center justify-center h-5 min-w-[1.25rem] px-1 rounded-full bg-red-500 text-[11px] font-bold text-white shadow-sm" x-text="unreadCount"></span>
    </button>

    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden"
        style="display: none;"
    >
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <template x-if="!isAdmin">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Percakapan dengan Admin</p>
                        <p class="text-[11px] text-gray-500">Balasan admin akan muncul di sini.</p>
                    </div>
                </template>
                <template x-if="isAdmin">
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-gray-900">Percakapan Pengguna</p>
                        <select
                            x-model="activeConversationId"
                            @change="switchConversation"
                            class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-600 focus:ring-indigo-600"
                        >
                            <template x-if="conversations.length === 0">
                                <option value="">Tidak ada percakapan</option>
                            </template>
                            <template x-for="conv in conversations" :key="conv.id">
                                <option :value="conv.id" x-text="conv.user_name + ' (' + conv.user_email + ')'"></option>
                            </template>
                        </select>
                        <p class="text-[11px] text-gray-500">Balas langsung dari widget.</p>
                    </div>
                </template>
            </div>
            <button @click="toggle()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div id="chat-container" class="p-3 overflow-y-auto space-y-3 bg-white" style="max-height: 400px;" x-ref="messageList">
            <template x-if="loading">
                <p class="text-sm text-gray-500 text-center">Memuat...</p>
            </template>
            <template x-if="error">
                <p class="text-sm text-red-600 text-center" x-text="error"></p>
            </template>
            <template x-if="!loading && messages.length === 0 && !error">
                <p class="text-sm text-gray-500 text-center">Belum ada pesan. Mulai percakapan di bawah.</p>
            </template>

            <template x-for="msg in messages" :key="msg.id">
                <div :class="isOwnMessage(msg) ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="isOwnMessage(msg)
                        ? 'max-w-[75%] px-3 py-2 rounded-lg bg-indigo-500/10 border border-indigo-600/30'
                        : 'max-w-[75%] px-3 py-2 rounded-lg bg-gray-50 border border-gray-100'">
                        <p class="text-sm text-gray-900" x-text="msg.body"></p>
                        <div class="flex items-center justify-between mt-1 space-x-2">
                            <p class="text-[10px] text-gray-500" x-text="formatTime(msg.created_at)"></p>
                            <template x-if="isOwnMessage(msg)">
                                <div class="flex items-center">
                                    <i :class="msg.read_at ? 'fas fa-check-double text-indigo-600' : 'fas fa-check-double text-gray-400'" class="text-[10px]"></i>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                </div>
            </template>
            
            <div x-show="isOtherTyping" x-transition class="flex justify-start mb-2">
                <div class="px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-100 flex items-center space-x-2">
                    <div class="flex space-x-1">
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                    </div>
                    <p class="text-[11px] text-gray-500 italic"><span x-text="typingUser"></span> sedang mengetik...</p>
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="space-y-2">
                <textarea
                    x-model="newMessage"
                    @input="handleTyping"
                    rows="2"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 text-sm"
                    placeholder="Tulis pesan..."
                    maxlength="500"
                    required
                ></textarea>
                <div class="flex items-center justify-between">
                    <p class="text-[11px] text-gray-400">Maks 500 karakter.</p>
                        <button
                            type="submit"
                            :disabled="sending || !newMessage.trim()"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-60 transition"
                        >
                            <span x-show="!sending">Kirim</span>
                            <span x-show="sending">Mengirim...</span>
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function chatWidget() {
        return {
            open: false,
            loading: false,
            sending: false,
            messages: [],
            error: '',
            newMessage: '',
            unreadCount: 0,
            poller: null,
            lastCount: 0,
            isAdmin: document.body.dataset.userRole === 'admin',
            conversations: [],
            activeConversationId: null,
            userId: document.body ? (document.body.dataset.userId || 'guest') : 'guest',
            badgeStorageKey: '',
            hasShownBadge: false,
            isOtherTyping: false,
            typingUser: '',
            typingTimeout: null,
            lastTypingEventTime: 0,
            selectedUserId: null,
            routes: {
                listUser: "{{ route('contact.conversations.messages') }}",
                sendUser: "{{ route('contact.conversations.store') }}",
                listAdmin: "{{ route('admin.contact-conversations.json') }}",
                messagesAdmin: (id) => "{{ url('/admin/contact-conversations') }}/" + id + "/messages-json",
                sendAdmin: (id) => "{{ url('/admin/contact-conversations') }}/" + id + "/reply",
            },
            init() {
                this.badgeStorageKey = `chatBadgeShown:${this.userId}`;
                this.hasShownBadge = sessionStorage.getItem(this.badgeStorageKey) === '1';
                if (this.isAdmin) {
                    this.fetchConversations().then(() => {
                        if (this.activeConversationId) {
                            this.fetchMessages().then(() => {
                                setTimeout(() => this.scrollToBottom(), 100);
                            });
                            this.listenForMessages();
                            this.listenForTyping();
                            this.listenForReadStatus();
                        }
                    });
                    this.startPolling();
                } else {
                    this.fetchMessages().then(() => {
                        this.listenForMessages();
                        this.listenForTyping();
                        this.listenForReadStatus();
                        setTimeout(() => this.scrollToBottom(), 100);
                    });
                    this.startPolling();
                }
            },
            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.unreadCount = 0;
                    this.markNotificationsAsRead();
                    this.fetchMessages(true);
                    setTimeout(() => this.scrollToBottom(), 100);
                }
            },
            async markNotificationsAsRead() {
                try {
                    await fetch("{{ route('notifications.read-by-category') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ category: 'message' })
                    });
                    
                    // Instant UI feedback: Hide global notification dot if visible
                    const dot = document.querySelector('[data-role="notification-dot"]');
                    if (dot) dot.style.display = 'none';
                    
                } catch (e) {
                    console.error('Failed to mark notifications as read:', e);
                }
            },
            startPolling() {
                if (this.poller) return;
                this.poller = setInterval(() => this.fetchMessages(), 12000);
            },
            async fetchMessages(isOpenAction = false) {
                if (this.isAdmin && !this.activeConversationId) {
                    return;
                }
                this.loading = this.messages.length === 0;
                this.error = '';
                const firstBadgeCheck = !this.hasShownBadge;
                try {
                    const url = this.isAdmin ? this.routes.messagesAdmin(this.activeConversationId) : this.routes.listUser;
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Gagal memuat pesan');
                    const data = await res.json();
                    const previousCount = this.messages.length;
                    const newMessages = data.messages || [];
                    const newCount = newMessages.length;
                    
                    // Set conversation ID for user side if not set
                    if (!this.isAdmin && data.conversation_id) {
                        this.activeConversationId = data.conversation_id;
                        this.selectedUserId = data.receiver_id;
                    }

                    if (this.isAdmin && data.conversation) {
                        this.selectedUserId = data.conversation.user_id;
                    }

                    this.messages = newMessages;
                    if (this.open) {
                        this.unreadCount = 0;
                    } else if (firstBadgeCheck && newCount > 0) {
                        this.unreadCount = newCount;
                    } else {
                        this.unreadCount = 0;
                    }
                    if (firstBadgeCheck) {
                        this.markBadgeShown();
                    }
                    this.lastCount = newCount;
                    
                    // Trigger scroll to bottom
                    setTimeout(() => this.scrollToBottom(), 100);
                } catch (e) {
                    this.error = e.message || 'Gagal memuat pesan';
                } finally {
                    this.loading = false;
                }
            },
            async fetchConversations() {
                try {
                    const res = await fetch(this.routes.listAdmin, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Gagal memuat percakapan');
                    const data = await res.json();
                    this.conversations = data.conversations || [];
                    if (!this.activeConversationId && this.conversations.length > 0) {
                        this.activeConversationId = this.conversations[0].id;
                        this.selectedUserId = this.conversations[0].user_id;
                    }
                } catch (e) {
                    this.error = e.message || 'Gagal memuat percakapan';
                }
            },
            async switchConversation() {
                this.messages = [];
                if (window.Echo) {
                    window.Echo.leave(`chat.${this.activeConversationId}`);
                }
                this.fetchMessages(true);
                this.listenForMessages();
                this.listenForTyping();
            },
            listenForMessages() {
                if (!this.activeConversationId || !window.Echo) return;

                window.Echo.private(`chat.${this.activeConversationId}`)
                    .listen('MessageSent', (e) => {
                        console.log('Message received:', e.id);
                        
                        // Avoid duplicates if we are the sender
                        const exists = this.messages.find(m => m.id === e.id);
                        if (exists) return;

                        this.messages.push({
                            id: e.id,
                            body: e.body,
                            sender_type: e.sender_type,
                            sender_id: e.sender_id,
                            created_at: e.created_at,
                            read_at: e.read_at
                        });
                        
                        // If window is open and we receive a message that is not ours, mark as read
                        if (this.open && e.sender_type !== (this.isAdmin ? 'admin' : 'user')) {
                            this.fetchMessages(); // Triggers mark as read on server
                        }
                        
                        setTimeout(() => this.scrollToBottom(), 100);
                        
                        // If window is closed, show unread count
                        if (!this.open) {
                            this.unreadCount++;
                        }
                    });
            },
            listenForTyping() {
                if (!window.Echo || !this.userId || this.userId === 'guest') return;

                console.log('Listening for typing events on channel:', `typing.${this.userId}`);

                window.Echo.private(`typing.${this.userId}`)
                    .listen('.typing', (e) => {
                        console.log('Typing event received:', e);
                        this.typingUser = e.senderName;
                        this.isOtherTyping = true;
                        
                        if (this.typingTimeout) clearTimeout(this.typingTimeout);
                        
                        this.typingTimeout = setTimeout(() => {
                            this.isOtherTyping = false;
                        }, 2000);
                        
                        setTimeout(() => this.scrollToBottom(), 100);
                    });
            },
            listenForReadStatus() {
                if (!window.Echo || !this.userId || this.userId === 'guest') return;

                console.log('Listening for read status events on channel:', `user.${this.userId}`);

                window.Echo.private(`user.${this.userId}`)
                    .listen('.message.read', (e) => {
                        console.log('Read status received:', e);
                        
                        // Guard & Cross-conversation safety
                        if (!e || !e.conversation_id || !e.last_read_id) return;
                        if (e.conversation_id != this.activeConversationId) return;

                        // Update local messages
                        this.messages = this.messages.map(msg => {
                            if (this.isOwnMessage(msg) && msg.id <= e.last_read_id) {
                                return { ...msg, read_at: new Date().toISOString() };
                            }
                            return msg;
                        });
                    });
            },
            async sendMessage() {
                if (!this.newMessage.trim()) return;
                if (this.isAdmin && !this.activeConversationId) {
                    this.error = 'Pilih percakapan terlebih dahulu.';
                    return;
                }
                this.sending = true;
                this.error = '';
                try {
                    const formData = new FormData();
                    formData.append('pesan', this.newMessage.trim());
                    const url = this.isAdmin ? this.routes.sendAdmin(this.activeConversationId) : this.routes.sendUser;
                    const res = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        let msg = data.message || 'Gagal mengirim pesan';
                        if (res.status === 422 && data.errors) {
                            msg = Object.values(data.errors).map(e => e[0]).join('<br>');
                        }
                        throw new Error(msg);
                    }
                    if (data.data) {
                        this.messages.push(data.data);
                    } else {
                        await this.fetchMessages();
                    }
                    this.newMessage = '';
                    setTimeout(() => this.scrollToBottom(), 100);
                } catch (e) {
                    this.error = e.message || 'Gagal mengirim pesan';
                } finally {
                    this.sending = false;
                }
            },
            markBadgeShown() {
                this.hasShownBadge = true;
                sessionStorage.setItem(this.badgeStorageKey, '1');
            },
            isOwnMessage(msg) {
                const ownType = this.isAdmin ? 'admin' : 'user';
                return msg && msg.sender_type === ownType;
            },
            formatTime(ts) {
                try {
                    const d = new Date(ts);
                    return d.toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short' });
                } catch (e) {
                    return ts;
                }
            },
            scrollToBottom() {
                const container = document.getElementById('chat-container');
                if (container) {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            },
            handleTyping() {
                if (!this.activeConversationId || !this.selectedUserId) return;
                
                const now = Date.now();
                if (now - this.lastTypingEventTime < 1500) return; // Throttle to every 1.5s
                
                this.lastTypingEventTime = now;
                console.log('Typing event sending to:', this.selectedUserId);
                
                fetch("{{ route('chat.typing') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ receiver_id: this.selectedUserId })
                });
            },
        }
    }
</script>
@endpush
@endunless
@endauth

