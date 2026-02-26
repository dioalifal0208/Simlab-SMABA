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
                            class="w-full text-sm rounded-md border-gray-300 focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
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

        <div class="p-3 h-80 overflow-y-auto space-y-3 bg-white" x-ref="messageList">
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
                        ? 'max-w-[75%] px-3 py-2 rounded-lg bg-smaba-light-blue/10 border border-smaba-dark-blue/30'
                        : 'max-w-[75%] px-3 py-2 rounded-lg bg-gray-50 border border-gray-100'">
                        <p class="text-sm text-gray-900" x-text="msg.body"></p>
                        <p class="text-[10px] text-gray-500 mt-1" x-text="formatTime(msg.created_at)"></p>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-3 border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="space-y-2">
                <textarea
                    x-model="newMessage"
                    rows="2"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue text-sm"
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
            routes: {
                listUser: '{{ route('contact.conversations.messages') }}',
                sendUser: '{{ route('contact.conversations.store') }}',
                listAdmin: '{{ route('admin.contact-conversations.json') }}',
                messagesAdmin: (id) => '{{ url('/admin/contact-conversations') }}/' + id + '/messages-json',
                sendAdmin: (id) => '{{ url('/admin/contact-conversations') }}/' + id + '/reply',
            },
            init() {
                this.badgeStorageKey = `chatBadgeShown:${this.userId}`;
                this.hasShownBadge = sessionStorage.getItem(this.badgeStorageKey) === '1';
                if (this.isAdmin) {
                    this.fetchConversations().then(() => {
                        if (this.activeConversationId) {
                            this.fetchMessages();
                        }
                    });
                    this.startPolling();
                } else {
                    this.fetchMessages();
                    this.startPolling();
                }
            },
            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.unreadCount = 0;
                    this.fetchMessages(true);
                    this.$nextTick(() => this.scrollToBottom());
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
                    this.$nextTick(() => this.scrollToBottom());
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
                    }
                } catch (e) {
                    this.error = e.message || 'Gagal memuat percakapan';
                }
            },
            async switchConversation() {
                this.messages = [];
                this.fetchMessages(true);
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
                    this.$nextTick(() => this.scrollToBottom());
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
                const el = this.$refs.messageList;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            },
        }
    }
</script>
@endpush
@endunless
@endauth
