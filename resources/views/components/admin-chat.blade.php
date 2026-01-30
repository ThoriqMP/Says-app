<div x-data="adminChat()" x-init="initChat()" class="fixed bottom-4 right-4 z-50">
    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="bg-white dark:bg-gray-800 w-96 h-[500px] rounded-lg shadow-xl flex flex-col mb-4 border border-gray-200 dark:border-gray-700 overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-3 bg-blue-600 dark:bg-blue-800 flex justify-between items-center text-white shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
                <div class="flex flex-col">
                    <h3 class="font-semibold text-sm">Admin Chat</h3>
                    <template x-if="typingUsers.length > 0">
                        <span class="text-[10px] opacity-90 animate-pulse" x-text="typingUsers.join(', ') + ' sedang mengetik...'"></span>
                    </template>
                </div>
            </div>
            <button @click="toggleChat" class="hover:text-gray-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900" id="chat-messages">
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex flex-col" :class="msg.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                     <div class="max-w-[85%] rounded-lg p-2.5 text-sm shadow-sm relative group"
                          :class="msg.user_id === {{ auth()->id() }} 
                            ? 'bg-blue-600 text-white rounded-br-none' 
                            : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-none border border-gray-200 dark:border-gray-600'">
                         <span class="text-[10px] font-bold block mb-1 opacity-90" 
                               :class="msg.user_id === {{ auth()->id() }} ? 'text-blue-100' : 'text-blue-600 dark:text-blue-400'"
                               x-text="msg.user.name"></span>
                         <span x-text="msg.message" class="break-words"></span>
                         
                         <!-- Message Status for Own Messages -->
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
                     <span class="text-[10px] text-gray-400 mt-1 px-1" x-text="formatTime(msg.created_at)"></span>
                </div>
            </template>
            
            <!-- Typing Indicator Animation -->
            <div x-show="typingUsers.length > 0" class="flex items-start" style="display: none;">
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
        <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
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

    <!-- Floating Button -->
    <button @click="toggleChat" 
            class="bg-blue-600 hover:bg-blue-700 text-white p-3.5 rounded-full shadow-lg transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 z-50 flex items-center justify-center relative">
        
        <!-- Unread Badge -->
        <span x-show="!isOpen && unreadCount > 0" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] border-2 border-white dark:border-gray-900"
              x-text="unreadCount"
              style="display: none;">
        </span>

        <span x-show="!isOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        </span>
        <span x-show="isOpen" style="display: none;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </span>
    </button>
</div>

<script>
function adminChat() {
    return {
        isOpen: false,
        messages: [],
        newMessage: '',
        isLoading: false,
        intervalId: null,
        unreadCount: 0,
        typingUsers: [],
        typingTimeout: null,
        
        initChat() {
            // Check localStorage state
            if (localStorage.getItem('adminChatOpen') === 'true') {
                this.isOpen = true;
                this.startPolling();
                this.markAsRead();
            } else {
                // Poll for unread counts even if closed (less frequent maybe?)
                this.startPolling();
            }
        },
        
        toggleChat() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('adminChatOpen', this.isOpen);
            
            if (this.isOpen) {
                this.markAsRead();
                this.$nextTick(() => this.scrollToBottom());
            }
        },
        
        startPolling() {
            this.fetchMessages();
            if (this.intervalId) clearInterval(this.intervalId);
            this.intervalId = setInterval(() => {
                this.fetchMessages();
            }, 3000);
        },
        
        stopPolling() {
            // We keep polling for notifications
            // if (this.intervalId) {
            //     clearInterval(this.intervalId);
            //     this.intervalId = null;
            // }
        },
        
        fetchMessages() {
            fetch("{{ route('chat.index') }}")
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    const shouldScroll = this.isOpen && this.messages.length !== data.messages.length && this.isNearBottom();
                    
                    this.messages = data.messages;
                    this.unreadCount = data.unread_count;
                    this.typingUsers = data.typing_users;
                    
                    if (shouldScroll) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                    
                    // If open and there are unread messages, mark them as read
                    if (this.isOpen && this.unreadCount > 0) {
                        this.markAsRead();
                    }
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                });
        },
        
        markAsRead() {
            if (this.unreadCount === 0) return;
            
            fetch("{{ route('chat.mark-read') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => {
                this.unreadCount = 0;
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
            }, 500); // Debounce 500ms
        },
        
        sendMessage() {
            if (!this.newMessage.trim()) return;
            
            this.isLoading = true;
            
            fetch("{{ route('chat.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: this.newMessage })
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
                // Coba parse error response jika ada
                alert('Gagal mengirim pesan: ' + (error.message || 'Silakan coba lagi.'));
            })
            .finally(() => {
                this.isLoading = false;
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
