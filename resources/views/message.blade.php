<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat with Team Members') }}
        </h2>
    </x-slot>

    <div class="container">
        <h1>Chats</h1>
        
        <!-- List of Users for Chats -->
        <ul>
            @foreach($users as $chatUser)
                <li>
                    <button onclick="openChatModal({{ $chatUser->id }})">{{ $chatUser->name }}</button>
                </li>
            @endforeach
        </ul>
        
        <!-- Chat Modal -->
        <div id="chatModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeChatModal()">&times;</span>
                <div id="chatMessages">
                    <!-- Chat messages will be loaded here -->
                </div>
                <form id="chatForm">
                    @csrf
                    <textarea name="message" required></textarea>
                    <input type="hidden" name="sanemeja_id" id="sanemejaId">
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>

        <!-- Display Existing Chat Messages -->
        <div>
            <h2>Your Messages</h2>
            <ul>
                @foreach($zinas as $message)
                    <li>
                        <p><strong>{{ $message->sanemejs->name }}:</strong> {{ $message->zinas_saturs }}</p>
                        @if(auth()->id() == $message->sutitaja_id || auth()->id() == $message->sanemeja_id)
                            <form method="POST" action="{{ route('chat.destroy', $message->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>

<script>
function openChatModal(userId) {
    document.getElementById('sanemejaId').value = userId;
    document.getElementById('chatModal').style.display = 'block';

    // Fetch chat messages and display
    fetch(`/chat/${userId}`)
        .then(response => response.json())
        .then(data => {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = data.messages.map(msg => `
                <p><strong>${msg.sutitajs.name}:</strong> ${msg.message}</p>
            `).join('');
        });
}

function closeChatModal() {
    document.getElementById('chatModal').style.display = 'none';
}

document.getElementById('chatForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    
    fetch('/chat/store', {
        method: 'POST',
        body: formData,
    }).then(() => {
        // Optionally, refresh chat messages
        openChatModal(formData.get('sanemeja_id'));
    });

    this.reset();
});
</script>