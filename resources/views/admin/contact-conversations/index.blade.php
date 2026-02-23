<x-app-layout :hideChrome="false" :hideFooter="true">
    {{-- Chat Interface Container - Full height minus navbar (64px) --}}
    <div class="flex bg-gray-50 overflow-hidden" x-data="chatApp()" style="height: calc(100vh - 64px);">
        
        {{-- LEFT SIDEBAR: Conversation List --}}
        <div class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col z-10" :class="{'hidden md:flex': mobileChatOpen, 'flex': !mobileChatOpen}">
            
            {{-- Sidebar Header & Search --}}
            <div class="p-4 border-b border-gray-100 bg-white">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input 
                        type="text" 
                        x-model="searchQuery" 
                        class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-gray-100 border-none rounded-full focus:ring-2 focus:ring-green-500 focus:bg-white transition-colors"
                        placeholder="Cari pengguna..."
                    >
                </div>
            </div>

            {{-- Conversation List --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <template x-if="loadingList">
                    <div class="p-4 space-y-4">
                        <x-skeleton class="h-16 w-full rounded-lg" />
                        <x-skeleton class="h-16 w-full rounded-lg" />
                        <x-skeleton class="h-16 w-full rounded-lg" />
                    </div>
                </template>

                <template x-for="chat in filteredConversations" :key="chat.id">
                    <div 
                        @click="selectChat(chat)"
                        class="relative px-4 py-4 cursor-pointer transition-colors duration-200 hover:bg-gray-50 border-b border-gray-50 group"
                        :class="{'bg-green-50 border-l-4 border-l-blue-600 border-b-transparent': activeConversation && activeConversation.id === chat.id, 'border-l-4 border-l-transparent': !activeConversation || activeConversation.id !== chat.id}"
                    >
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-semibold text-sm text-gray-900 truncate" x-text="chat.user_name"></span>
                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2" x-text="formatTime(chat.last_message_at)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-xs text-gray-500 truncate w-3/4 group-hover:text-gray-700" x-text="chat.last_message || 'Tidak ada pesan'"></p>
                            
                            {{-- Status Badge (Optional) --}}
                            <template x-if="chat.status === 'open'">
                                <span class="h-2 w-2 rounded-full bg-green-500" title="Terbuka"></span>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="filteredConversations.length === 0 && !loadingList" class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">Tidak ada pesan ditemukan.</p>
                </div>
            </div>
        </div>

        {{-- RIGHT MAIN AREA: Chat Window --}}
        <div class="flex-1 flex flex-col bg-gray-50 relative" :class="{'flex fixed inset-0 z-20': mobileChatOpen, 'hidden md:flex': !mobileChatOpen}">
            
            {{-- Empty State --}}
            <div x-show="!activeConversation" class="hidden md:flex flex-col items-center justify-center h-full text-gray-400">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-green-100">
                    <i class="fas fa-comments text-4xl text-green-300"></i>
                </div>
                <p class="text-lg font-medium text-gray-500">Pilih percakapan untuk memulai</p>
                <p class="text-sm text-gray-400">Anda dapat membalas pesan dari pengguna di sini.</p>
            </div>

            {{-- Chat Content (shown when activeConversation is set) --}}
            <template x-if="activeConversation">
                <div class="flex flex-col h-full w-full bg-[#f3f4f6] dark:bg-black"> {{-- Chat Background --}}
                    
                    {{-- Chat Header --}}
                    <div class="h-16 px-4 md:px-6 bg-white border-b border-gray-200 flex items-center justify-between shadow-sm z-10">
                        <div class="flex items-center">
                            {{-- Back Button (Mobile) --}}
                            <button @click="closeMobileChat()" class="mr-3 md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="fas fa-arrow-left text-lg"></i>
                            </button>
                            
                            {{-- User Info --}}
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold text-sm mr-3">
                                    <span x-text="getInitials(activeConversation.user_name)"></span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900" x-text="activeConversation.user_name"></h3>
                                    <p class="text-xs text-gray-500" x-text="activeConversation.user_email"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions (Refresh) --}}
                        <div class="flex items-center space-x-2">
                            <button @click="fetchMessages(activeConversation.id)" class="p-2 text-gray-400 hover:text-green-600 transition-colors rounded-full" title="Perbarui Chat">
                                <i class="fas fa-sync-alt" :class="{'fa-spin': loadingMessages}"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Messages Area --}}
                    <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4" id="messages-container">
                        
                        <template x-if="loadingMessages">
                            <div class="flex justify-center py-4">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                        </template>

                        <template x-for="msg in activeMessages" :key="msg.id">
                            <div class="flex flex-col" :class="msg.sender_type === 'admin' ? 'items-end' : 'items-start'">
                                <div 
                                    class="max-w-[80%] md:max-w-[60%] px-4 py-3 shadow-sm relative text-sm leading-relaxed"
                                    :class="msg.sender_type === 'admin' 
                                        ? 'bg-green-600 text-white rounded-2xl rounded-tr-sm' 
                                        : 'bg-white text-gray-800 rounded-2xl rounded-tl-sm border border-gray-200'"
                                >
                                    <p class="whitespace-pre-wrap" x-text="msg.body"></p>
                                    
                                    {{-- Time --}}
                                    <div class="mt-1 text-[10px] text-right opacity-70"
                                         :class="msg.sender_type === 'admin' ? 'text-green-100' : 'text-gray-400'">
                                        <span x-text="formatMessageTime(msg.created_at)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Input Area --}}
                    <div class="p-4 bg-white border-t border-gray-200">
                        <form @submit.prevent="sendMessage" class="flex items-end gap-2">
                            <div class="flex-1 relative">
                                <textarea 
                                    x-model="newMessage" 
                                    class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 resize-none py-3 px-4 pr-10 text-sm max-h-32" 
                                    rows="1" 
                                    placeholder="Ketik balasan..."
                                    @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                                ></textarea>
                            </div>
                            <button 
                                type="submit" 
                                class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                                :disabled="!newMessage.trim() || sending"
                            >
                                <i x-show="!sending" class="fas fa-paper-plane"></i>
                                <i x-show="sending" class="fas fa-circle-notch fa-spin"></i>
                            </button>
                        </form>
                        <div class="text-xs text-gray-400 mt-2 text-center md:text-left hidden md:block">
                            Tekan Enter untuk mengirim, Shift + Enter untuk baris baru.
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Alpine.js Logic --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatApp', () => ({
                conversations: [],
                activeConversation: null,
                activeMessages: [],
                searchQuery: '',
                newMessage: '',
                loadingList: false,
                loadingMessages: false,
                sending: false,
                mobileChatOpen: false,
                pollInterval: null,

                init() {
                    this.fetchConversations().then(() => {
                        // Check for 'open' query parameter
                        const urlParams = new URLSearchParams(window.location.search);
                        const openId = urlParams.get('open');
                        
                        if (openId) {
                            // Find conversation with this ID
                            const targetChat = this.conversations.find(c => c.id == openId);
                            if (targetChat) {
                                this.selectChat(targetChat);
                                
                                // Clean URL without refreshing
                                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                                window.history.pushState({path: newUrl}, '', newUrl);
                            }
                        }
                    });
                    
                    // Poll for new messages every 5 seconds
                    this.pollInterval = setInterval(() => {
                        this.poll();
                    }, 5000);

                    // Listen for SPA chat open events from notification click
                    window.addEventListener('open-chat', (event) => {
                        const id = event.detail.id;
                        if (id) {
                            const chat = this.conversations.find(c => c.id == id);
                            if (chat) {
                                this.selectChat(chat);
                            } else {
                                // If not in current list (maybe new), fetch list then open
                                this.fetchConversations().then(() => {
                                    const newChat = this.conversations.find(c => c.id == id);
                                    if (newChat) this.selectChat(newChat);
                                });
                            }
                        }
                    });
                },

                get filteredConversations() {
                    if (this.searchQuery === '') return this.conversations;
                    return this.conversations.filter(c => 
                        c.user_name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                        c.user_email.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                getInitials(name) {
                    if (!name) return '?';
                    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                },

                formatTime(datetime) {
                    if (!datetime) return '';
                    const date = new Date(datetime);
                    const now = new Date();
                    const diff = now - date;
                    const oneDay = 24 * 60 * 60 * 1000;

                    if (diff < oneDay && now.getDate() === date.getDate()) {
                        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    }
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                },

                formatMessageTime(datetime) {
                    return new Date(datetime).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                },

                async fetchConversations() {
                    this.loadingList = true;
                    try {
                        const response = await axios.get('{{ route("admin.contact-conversations.json") }}');
                        this.conversations = response.data.conversations;
                    } catch (error) {
                        console.error('Error fetching conversations:', error);
                    } finally {
                        this.loadingList = false;
                    }
                },

                async selectChat(chat) {
                    this.activeConversation = chat;
                    this.mobileChatOpen = true;
                    await this.fetchMessages(chat.id);
                },

                async fetchMessages(id) {
                    this.loadingMessages = true;
                    try {
                        const response = await axios.get(`/admin/contact-conversations/${id}/messages-json`);
                        // Ensure we are active on the same chat (race condition check)
                        if (this.activeConversation && this.activeConversation.id === id) {
                            this.activeMessages = response.data.messages;
                            this.scrollToBottom();
                        }
                    } catch (error) {
                        console.error('Error fetching messages:', error);
                    } finally {
                        this.loadingMessages = false;
                    }
                },

                async sendMessage() {
                    if (!this.newMessage.trim()) return;

                    this.sending = true;
                    const payload = { pesan: this.newMessage };
                    const chatId = this.activeConversation.id;

                    try {
                        const response = await axios.post(`/admin/contact-conversations/${chatId}/reply`, payload);
                        
                        // Add message to UI immediately
                        this.activeMessages.push(response.data.data);
                        this.newMessage = '';
                        this.scrollToBottom();

                        // Update list preview
                        const chatIndex = this.conversations.findIndex(c => c.id === chatId);
                        if (chatIndex !== -1) {
                            this.conversations[chatIndex].last_message = response.data.data.body;
                            this.conversations[chatIndex].last_message_at = response.data.data.created_at;
                            // Move to top
                            const chat = this.conversations.splice(chatIndex, 1)[0];
                            this.conversations.unshift(chat);
                        }

                    } catch (error) {
                        console.error('Error sending message:', error);
                        // Ideally show toast
                    } finally {
                        this.sending = false;
                    }
                },

                async poll() {
                    // Update list silently
                    try {
                        const response = await axios.get('{{ route("admin.contact-conversations.json") }}');
                        // Smart merge could be here, but for now simple replace to keep state fresh
                        // But replacing resets search/scroll, so better maybe just check for changes?
                        // For simplicity in this iteration: replace list but preserve selection.
                        this.conversations = response.data.conversations;

                        // If chat active, poll messages
                        if (this.activeConversation) {
                            const msgResponse = await axios.get(`/admin/contact-conversations/${this.activeConversation.id}/messages-json`);
                            const freshMessages = msgResponse.data.messages;
                            
                            // Only update if length changed to avoid jitter (simple check)
                            if (freshMessages.length !== this.activeMessages.length) {
                                this.activeMessages = freshMessages;
                                this.scrollToBottom();
                            }
                        }
                    } catch (e) { console.error('Poll error', e); }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('messages-container');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                closeMobileChat() {
                    this.mobileChatOpen = false;
                    this.activeConversation = null;
                }
            }));
        });
    </script>
</x-app-layout>
