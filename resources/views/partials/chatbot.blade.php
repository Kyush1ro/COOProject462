<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050;">
    <!-- Chat Toggle Button -->
    <button class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
            style="width: 60px; height: 60px;"
            type="button" 
            data-coreui-toggle="collapse" 
            data-coreui-target="#chatbotCard" 
            aria-expanded="false" 
            aria-controls="chatbotCard">
        <i class="fas fa-robot fa-lg"></i>
    </button>

    <!-- Chat Card -->
    <div class="collapse mt-3" id="chatbotCard" style="width: 350px;">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-robot"></i>
                    <span class="fw-semibold">LMS Assistant</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-coreui-toggle="collapse" data-coreui-target="#chatbotCard" aria-label="Close"></button>
            </div>
            
            <div class="card-body p-0">
                <div id="chat-messages" class="p-3" style="height: 300px; overflow-y: auto;">
                    <!-- Welcome Message -->
                    <div class="d-flex mb-3">
                        <div class="bg-body-secondary border rounded p-2 shadow-sm" style="max-width: 80%;">
                            <small class="text-muted d-block mb-1">Assistant</small>
                            Hello! I can help you check your <b>courses</b>, <b>assignments</b>, or <b>grades</b>. What do you need?
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer p-2">
                <form id="chat-form" class="d-flex gap-2">
                    <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." autocomplete="off">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');

    function appendMessage(sender, text, isUser = false) {
        const alignClass = isUser ? 'justify-content-end' : '';
        const bgClass = isUser ? 'bg-primary text-white' : 'bg-body-secondary border text-body';
        const senderName = isUser ? 'You' : 'Assistant';
        
        const html = `
            <div class="d-flex mb-3 ${alignClass}">
                <div class="${bgClass} rounded p-2 shadow-sm" style="max-width: 80%;">
                    <small class="${isUser ? 'text-white-50' : 'text-muted'} d-block mb-1">${senderName}</small>
                    ${text}
                </div>
            </div>
        `;
        
        chatMessages.insertAdjacentHTML('beforeend', html);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message
        appendMessage('You', message, true);
        chatInput.value = '';

        // Show typing indicator (optional, simplified here)
        
        try {
            const response = await fetch('{{ route("chatbot.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            appendMessage('Assistant', data.reply);
        } catch (error) {
            console.error('Error:', error);
            appendMessage('Assistant', 'Sorry, I encountered an error. Please try again.');
        }
    });
});
</script>