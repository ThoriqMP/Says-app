<div x-data="adminChat()" x-init="initChat()" class="fixed z-50 bottom-0 right-0 sm:bottom-4 sm:right-4 w-full sm:w-auto">
    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="bg-white dark:bg-gray-800 w-full h-[100vh] sm:h-[500px] sm:w-96 sm:rounded-lg shadow-xl flex flex-col sm:mb-4 border-t sm:border border-gray-200 dark:border-gray-700 overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-3 bg-blue-600 dark:bg-blue-800 flex justify-between items-center text-white shadow-sm shrink-0">
            <div class="flex items-center gap-2">
                <!-- Back Button (Only in Chat View) -->
                <button x-show="view === 'chat'" @click="view = 'contacts'; selectedUser = null" class="mr-1 hover:bg-blue-700 p-1 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <div class="flex flex-col">
                    <h3 class="font-semibold text-sm" x-text="view === 'contacts' ? 'Admin Chat' : selectedUser.name"></h3>
                    <template x-if="view === 'chat' && isTyping">
                        <span class="text-[10px] opacity-90 animate-pulse">sedang mengetik...</span>
                    </template>
                </div>
            </div>
            <button @click="toggleChat" class="hover:text-gray-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- View: Contacts List -->
        <div x-show="view === 'contacts'" class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
            <template x-for="contact in contacts" :key="contact.id">
                <div @click="selectUser(contact)" 
                     class="flex items-center p-3 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                            <span x-text="contact.name.substring(0,2).toUpperCase()"></span>
                        </div>
                        <div x-show="contact.is_online" class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"></div>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between items-baseline">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white" x-text="contact.name"></h4>
                            <span x-show="contact.unread_count > 0" class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-text="contact.unread_count"></span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="contact.role"></p>
                    </div>
                </div>
            </template>
            <div x-show="contacts.length === 0" class="p-4 text-center text-gray-500 text-sm">
                Tidak ada admin lain.
            </div>
        </div>

        <!-- View: Chat Room -->
        <div x-show="view === 'chat'" class="flex-1 flex flex-col overflow-hidden">
            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900" id="chat-messages">
                
                <!-- Welcome Message -->
                <div class="flex justify-center mb-6 mt-2">
                    <div class="bg-blue-50 dark:bg-gray-800 text-blue-600 dark:text-blue-400 text-xs px-4 py-2 rounded-full border border-blue-100 dark:border-gray-700 shadow-sm text-center max-w-[85%]">
                        <p class="font-medium">Silahkan gunakan fitur chat ini untuk berkoordinasi dengan lebih baik</p>
                    </div>
                </div>

                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex flex-col w-full" :class="msg.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                        
                        <!-- Message Wrapper with Swipe Logic -->
                        <div x-data="{ 
                                offset: 0, 
                                startOffset: 0,
                                startX: 0, 
                                isSwiping: false
                             }"
                             class="relative flex items-center w-full transition-all"
                             :class="msg.user_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'"
                             @touchstart="
                                if(msg.user_id !== {{ auth()->id() }}) return;
                                startX = $event.touches[0].clientX; 
                                startOffset = offset;
                                isSwiping = true;
                             "
                             @touchmove="
                                if(!isSwiping) return;
                                let diff = $event.touches[0].clientX - startX;
                                let newOffset = startOffset + diff;
                                
                                // Limit swipe range: Max 0 (closed), Min -80 (fully open + resistance)
                                if(newOffset <= 0 && newOffset >= -80) {
                                    offset = newOffset;
                                }
                             "
                             @touchend="
                                isSwiping = false;
                                if(offset < -30) {
                                    offset = -60; // Snap open
                                } else {
                                    offset = 0; // Snap close
                                }
                             "
                        >
                            <!-- Mobile Swipe Delete Button (Behind Bubble) -->
                            <template x-if="msg.user_id === {{ auth()->id() }}">
                                <div class="absolute right-0 h-full flex items-center justify-center w-[60px]" 
                                     x-show="offset < 0"
                                     style="z-index: 0;">
                                    <button @click="deleteMessage(msg.id)" 
                                            class="bg-red-500 text-white rounded-full p-2 shadow-sm transform scale-90 active:scale-95 transition-transform flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <!-- Message Bubble -->
                            <div class="max-w-[85%] rounded-lg p-2.5 text-sm shadow-sm relative group z-10"
                                :style="`transform: translateX(${offset}px); transition: ${isSwiping ? 'none' : 'transform 0.2s ease-out'}`"
                                :class="msg.user_id === {{ auth()->id() }} 
                                    ? 'bg-blue-600 text-white rounded-br-none' 
                                    : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-none border border-gray-200 dark:border-gray-600'">
                                
                                <span x-text="msg.message" class="break-words"></span>
                                
                                <!-- Desktop Hover Delete Button (Hidden on touch devices usually, but kept for desktop) -->
                                <template x-if="msg.user_id === {{ auth()->id() }}">
                                    <button @click="deleteMessage(msg.id)" 
                                            class="absolute -left-8 top-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity p-1 hidden sm:block">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </template>

                                <!-- Message Status -->
                                <template x-if="msg.user_id === {{ auth()->id() }}">
                                    <div class="flex justify-end mt-1 space-x-0.5">
                                        <!-- 1 Check (Sent/Saved) -->
                                        <svg x-show="!msg.is_read" class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <!-- 2 Checks (Read) -->
                                        <div x-show="msg.is_read" class="flex -space-x-1">
                                        <svg class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <svg class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 px-1" x-text="formatTime(msg.created_at)"></span>
                    </div>
                </template>
                
                <!-- Typing Indicator Animation -->
                <div x-show="isTyping" class="flex items-start">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-2 rounded-bl-none border border-gray-200 dark:border-gray-600 shadow-sm">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>

                <div x-show="messages.length === 0" class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10">
                    Belum ada pesan. Mulai percakapan!
                </div>
            </div>
            
            <!-- Input -->
            <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shrink-0">
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input type="text" x-model="newMessage" @input="handleTyping" placeholder="Ketik pesan..." 
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                        :disabled="isLoading">
                    <button type="submit" 
                            class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="isLoading || !newMessage.trim()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Button -->
    <button @click="toggleChat" 
            x-show="!isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="absolute bottom-4 right-4 sm:static bg-blue-600 hover:bg-blue-700 text-white p-3.5 rounded-full shadow-lg transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 z-50 flex items-center justify-center">
        
        <!-- Unread Badge -->
        <span x-show="totalUnread > 0" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] border-2 border-white dark:border-gray-900"
              x-text="totalUnread">
        </span>

        <span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        </span>
    </button>
</div>

<script>
function adminChat() {
    return {
        isOpen: false,
        view: 'contacts', // 'contacts' or 'chat'
        contacts: [],
        selectedUser: null,
        messages: [],
        newMessage: '',
        isLoading: false,
        intervalId: null,
        totalUnread: 0,
        isTyping: false,
        typingTimeout: null,
        
        initChat() {
            if (localStorage.getItem('adminChatOpen') === 'true') {
                this.isOpen = true;
                // If there was a selected user persisted, we could restore it, 
                // but for now let's default to contacts list for safety
                this.startPolling();
            } else {
                this.startPolling();
            }
        },
        
        toggleChat() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('adminChatOpen', this.isOpen);
            
            if (this.isOpen) {
                if (this.view === 'chat' && this.selectedUser) {
                    this.markAsRead();
                    this.$nextTick(() => this.scrollToBottom());
                } else {
                    this.fetchContacts();
                }
            }
        },
        
        selectUser(user) {
            this.selectedUser = user;
            this.view = 'chat';
            this.messages = []; // Clear previous messages immediately
            this.fetchMessages();
            this.markAsRead();
        },
        
        startPolling() {
            // Poll everything every 3 seconds
            if (this.intervalId) clearInterval(this.intervalId);
            this.intervalId = setInterval(() => {
                this.fetchContacts(); // Always fetch contacts to update unread counts and online status
                
                if (this.isOpen && this.view === 'chat' && this.selectedUser) {
                    this.fetchMessages();
                }
            }, 3000);
            
            // Initial fetch
            this.fetchContacts();
        },
        
        fetchContacts() {
            fetch("{{ route('chat.contacts') }}")
                .then(res => res.json())
                .then(data => {
                    this.contacts = data.contacts;
                    this.totalUnread = data.total_unread;
                });
        },
        
        fetchMessages() {
            if (!this.selectedUser) return;
            
            fetch(`/chat/messages/${this.selectedUser.id}`)
                .then(res => res.json())
                .then(data => {
                    const shouldScroll = this.isOpen && this.view === 'chat' && this.messages.length !== data.messages.length && this.isNearBottom();
                    
                    this.messages = data.messages;
                    this.isTyping = data.is_typing;
                    
                    if (shouldScroll) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                    
                    if (this.isOpen && this.view === 'chat' && this.messages.some(m => !m.is_read && m.user_id === this.selectedUser.id)) {
                        this.markAsRead();
                    }
                });
        },
        
        markAsRead() {
            if (!this.selectedUser) return;
            
            fetch("{{ route('chat.mark-read') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ sender_id: this.selectedUser.id })
            })
            .then(() => {
                // Update local unread count immediately for better UX
                const contact = this.contacts.find(c => c.id === this.selectedUser.id);
                if (contact) contact.unread_count = 0;
            });
        },
        
        handleTyping() {
            if (this.typingTimeout) clearTimeout(this.typingTimeout);
            
            this.typingTimeout = setTimeout(() => {
                fetch("{{ route('chat.typing') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
            }, 500);
        },
        
        sendMessage() {
            if (!this.newMessage.trim() || !this.selectedUser) return;
            
            this.isLoading = true;
            
            fetch("{{ route('chat.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    message: this.newMessage,
                    receiver_id: this.selectedUser.id
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(msg => {
                this.messages.push(msg);
                this.newMessage = '';
                this.$nextTick(() => this.scrollToBottom());
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Gagal mengirim pesan: ' + (error.message || 'Silakan coba lagi.'));
            })
            .finally(() => {
                this.isLoading = false;
            });
        },
        
        deleteMessage(id) {
            if (!confirm('Tarik pesan ini? (Hapus untuk semua)')) return;
            
            fetch(`/chat/messages/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (res.ok) {
                    this.messages = this.messages.filter(m => m.id !== id);
                } else {
                    alert('Gagal menghapus pesan.');
                }
            });
        },
        
        scrollToBottom() {
            const container = document.getElementById('chat-messages');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
        
        isNearBottom() {
            const container = document.getElementById('chat-messages');
            if (!container) return false;
            return container.scrollHeight - container.scrollTop - container.clientHeight < 100;
        },
        
        formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
