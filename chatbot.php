<?php
// chatbot.php - Include this file
?>
<style>
/* Chatbot Widget Styles */
.chat-widget-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: transform 0.3s;
}

.chat-widget-btn:hover {
    transform: scale(1.1);
}

.chat-widget-btn svg {
    width: 32px;
    height: 32px;
    fill: white;
}

.chat-window {
    position: fixed;
    bottom: 95px;
    right: 25px;
    width: 380px;
    height: 550px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    display: none;
    flex-direction: column;
    z-index: 9998;
    overflow: hidden;
}

.chat-window.active {
    display: flex;
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.chat-header-info {
    font-size: 12px;
    opacity: 0.9;
    margin-top: 4px;
}

.close-chat-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 8px;
    transition: background 0.2s;
}

.close-chat-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.chat-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8f9fa;
}

.chat-message {
    margin-bottom: 16px;
    display: flex;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-message.bot {
    justify-content: flex-start;
}

.chat-message.user {
    justify-content: flex-end;
}

.message-bubble {
    max-width: 75%;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
}

.chat-message.bot .message-bubble {
    background: white;
    color: #333;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.chat-message.user .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.quick-actions {
    padding: 12px 20px;
    background: white;
    border-top: 1px solid #e9ecef;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.quick-btn {
    padding: 8px 14px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
    color: #495057;
}

.quick-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.chat-input-container {
    padding: 16px 20px;
    background: white;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
}

.chat-input-container input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #dee2e6;
    border-radius: 24px;
    outline: none;
    font-size: 14px;
    transition: border-color 0.2s;
}

.chat-input-container input:focus {
    border-color: #667eea;
}

.send-btn {
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 24px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: transform 0.2s;
}

.send-btn:hover {
    transform: translateY(-2px);
}

.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-body::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

@media (max-width: 480px) {
    .chat-window {
        width: calc(100vw - 20px);
        height: calc(100vh - 100px);
        right: 10px;
    }
}
</style>

<!-- Chat Widget Button -->
<div class="chat-widget-btn" onclick="toggleChatWidget()">
    <svg viewBox="0 0 24 24">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
        <circle cx="9" cy="10" r="1.5"/>
        <circle cx="15" cy="10" r="1.5"/>
    </svg>
</div>

<!-- Chat Window -->
<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <div>
            <h3>🎉 Event Assistant</h3>
            <div class="chat-header-info">Online • Ready to help</div>
        </div>
        <button class="close-chat-btn" onclick="toggleChatWidget()">×</button>
    </div>
    
    <div class="chat-body" id="chatBody">
        <div class="chat-message bot">
            <div class="message-bubble">
                Hi there! 👋 Welcome to Event Management System.<br><br>
                I'm here to help you with:
                <br>• Event bookings
                <br>• Gallery & venues
                <!-- <br>• Pricing details -->
                <br>• General inquiries
            </div>
        </div>
    </div>
    
    <div class="quick-actions">
        <button class="quick-btn" onclick="sendQuickMessage('book')">📅 Book Event</button>
        <button class="quick-btn" onclick="sendQuickMessage('gallery')">🖼️ Gallery</button>
        <button class="quick-btn" onclick="sendQuickMessage('events')">👀 Events</button>
        <button class="quick-btn" onclick="sendQuickMessage('contact')">📞 Contact</button>
        <!-- <button class="quick-btn" onclick="sendQuickMessage('price')">💰 Pricing</button> -->
    </div>
    
    <div class="chat-input-container">
        <input type="text" id="chatMessageInput" placeholder="Type your message..." onkeypress="handleChatEnter(event)">
        <button class="send-btn" onclick="sendChatMessage()">Send</button>
    </div>
</div>

<script>
function toggleChatWidget() {
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.classList.toggle('active');
    
    if (chatWindow.classList.contains('active')) {
        document.getElementById('chatMessageInput').focus();
    }
}

function sendChatMessage() {
    const input = document.getElementById('chatMessageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    addChatMessage(message, 'user');
    input.value = '';
    
    setTimeout(() => {
        const response = getChatbotResponse(message.toLowerCase());
        addChatMessage(response, 'bot');
    }, 600);
}

function addChatMessage(text, sender) {
    const chatBody = document.getElementById('chatBody');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${sender}`;
    messageDiv.innerHTML = `<div class="message-bubble">${text}</div>`;
    chatBody.appendChild(messageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
}

function getChatbotResponse(message) {
    const baseUrl = 'http://localhost:8888/Event-Management-System-master/';
    
    // Book event / Gallery
    if (message.includes('book') || message.includes('booking') || message.includes('reserve') || message.includes('gallery')) {
        return `Great choice! 🎉<br><br>
                <strong>Event Booking:</strong><br>
                1. Browse our gallery<br>
                2. Choose your event type<br>
                3. Select preferred date<br>
                4. Fill in guest details<br>
                5. Confirm your booking<br><br>
                <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600; text-decoration:underline;">📸 View Gallery & Book Event →</a>`;
    }
    
    // View events
    if (message.includes('event') || message.includes('show') || message.includes('view') || message.includes('available') || message.includes('types')) {
        return `📅 <strong>Our Event Services:</strong><br><br>
                🎂 <strong>Birthday Parties</strong><br>
                Perfect celebrations for all ages with custom themes & decorations<br><br>
                💑 <strong>Anniversary Events</strong><br>
                Romantic celebrations for your special milestones<br><br>
                🎪 <strong>Other Events</strong><br>
                Graduations, baby showers & custom celebrations<br><br>
                <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">View Gallery →</a>`;
    }
    
    // Birthday specific
    if (message.includes('birthday') || message.includes('bday') || message.includes('birth day')) {
        return `🎂 <strong>Birthday Party Packages:</strong><br><br>
                <strong>Kids Birthday (Ages 1-12)</strong><br>
                • Themed decorations<br>
                • Games & entertainment<br>
                • Cake & snacks<br>
                • Party favors<br>
                Starting at $500<br><br>
                <strong>Teen Birthday (Ages 13-17)</strong><br>
                • DJ & music<br>
                • Photo booth<br>
                • Catering<br>
                Starting at $700<br><br>
                <strong>Adult Birthday</strong><br>
                • Custom venue setup<br>
                • Full catering<br>
                • Bar service (optional)<br>
                Starting at $900<br><br>
                <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">Book Now →</a>`;
    }
    
    // Anniversary specific
    if (message.includes('anniversary') || message.includes('wedding anniversary')) {
        return `💑 <strong>Anniversary Celebration Packages:</strong><br><br>
                <strong>Romantic Dinner</strong><br>
                • Candlelit setup<br>
                • 3-course meal<br>
                • Live music<br>
                Starting at $800<br><br>
                <strong>Party Celebration</strong><br>
                • Venue decoration<br>
                • DJ/Live band<br>
                • Full catering<br>
                • Photography<br>
                Starting at $1500<br><br>
                <strong>Vow Renewal Ceremony</strong><br>
                • Complete setup<br>
                • Catering for guests<br>
                • Photo & video<br>
                Custom pricing<br><br>
                <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">View & Book →</a>`;
    }
    
    // Contact
    if (message.includes('contact') || message.includes('phone') || message.includes('email') || message.includes('reach') || message.includes('call')) {
        return `📞 <strong>Contact Information:</strong><br><br>
                📧 <strong>Email:</strong> moinabid007@gmail.com<br>
                📱 <strong>Phone:</strong> +44 314 5046418<br>
                💬 <strong>WhatsApp:</strong> +44 314 5046418<br><br>
                🕒 <strong>Office Hours:</strong><br>
                Monday - Friday: 9:00 AM - 6:00 PM<br>
                Saturday: 10:00 AM - 4:00 PM<br>
                Sunday: By appointment<br><br>
                📍 <strong>Location:</strong> United Kingdom<br><br>
                Feel free to reach out anytime!`;
    }
    
    // Pricing
    // if (message.includes('price') || message.includes('cost') || message.includes('fee') || message.includes('charge') || message.includes('budget')) {
    //     return `💰 <strong>Pricing Guide:</strong><br><br>
    //             <strong>Birthday Parties:</strong><br>
    //             Kids (25-50 guests): $500 - $800<br>
    //             Teens (30-60 guests): $700 - $1,200<br>
    //             Adults (40-100 guests): $900 - $2,000<br><br>
    //             <strong>Anniversary Events:</strong><br>
    //             Small (20-40 guests): $800 - $1,500<br>
    //             Medium (50-80 guests): $1,500 - $3,000<br>
    //             Large (100+ guests): $3,000+<br><br>
    //             <strong>Corporate Events:</strong><br>
    //             Small meeting (10-30): $600 - $1,200<br>
    //             Conference (50-100): $2,000 - $5,000<br>
    //             Large event (200+): Custom quote<br><br>
    //             <strong>Entertainment Services:</strong><br>
    //             • DJ Services: $300-$800<br>
    //             • Photography: $500-$1,500<br>
    //             • Catering: $20-$50/person<br><br>
    //             <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">Book Your Event →</a>`;
    // }
    
    // Gallery specific
    if (message.includes('photo') || message.includes('picture') || message.includes('image')) {
        return `📸 <strong>Event Gallery:</strong><br><br>
                Browse our portfolio of successful events:<br><br>
                • Birthday celebrations 🎂<br>
                • Anniversary parties 💑<br>
                • Special occasions 🎉<br><br>
                Get inspiration for your event!<br><br>
                <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">View Full Gallery →</a>`;
    }
    
    // Availability
    if (message.includes('available') || message.includes('availability') || message.includes('date') || message.includes('when')) {
        return `📆 <strong>Check Availability:</strong><br><br>
                To check if your preferred date is available:<br><br>
                <strong>Please provide:</strong><br>
                1. Event type (Birthday/Anniversary/Corporate)<br>
                2. Preferred date<br>
                3. Expected number of guests<br>
                4. Time preference<br><br>
                Or visit our gallery to check the calendar directly!<br><br>
                <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">Check Calendar →</a>`;
    }
    
    // Catering/Food
    if (message.includes('food') || message.includes('catering') || message.includes('menu') || message.includes('eat')) {
        return `🍽️ <strong>Catering Services:</strong><br><br>
                <strong>Menu Options:</strong><br><br>
                🥗 <strong>Appetizers & Starters</strong><br>
                • Fresh salads<br>
                • Finger foods<br>
                • Cheese platters<br><br>
                🍗 <strong>Main Course</strong><br>
                • International cuisine<br>
                • BBQ options<br>
                • Vegetarian/Vegan<br><br>
                🍰 <strong>Desserts</strong><br>
                • Custom cakes<br>
                • Dessert bars<br>
                • Ice cream<br><br>
                <strong>Dietary Options:</strong><br>
                ✓ Vegetarian<br>
                ✓ Vegan<br>
                ✓ Gluten-free<br>
                ✓ Halal<br><br>
                Cost: $20-$50 per person<br><br>
                📞 Contact us for detailed menu!`;
    }
    
    // Entertainment
    // if (message.includes('entertainment') || message.includes('music') || message.includes('dj') || message.includes('fun')) {
    //     return `🎵 <strong>Entertainment Services:</strong><br><br>
    //             <strong>Music & DJ:</strong><br>
    //             🎧 Professional DJ: $300-$800<br>
    //             🎸 Live Band: $800-$2,000<br>
    //             🎹 Solo Artist: $200-$500<br><br>
    //             <strong>For Kids:</strong><br>
    //             🤡 Clowns & Magicians: $250-$500<br>
    //             🎨 Face Painting: $150-$300<br>
    //             🎪 Bouncy Castle: $300-$600<br><br>
    //             <strong>Special Features:</strong><br>
    //             📸 Photo Booth: $300-$600<br>
    //             🎤 Karaoke: $200-$400<br><br>
    //             Let us make your event unforgettable!`;
    // }
    
    // Corporate events
    // if (message.includes('corporate') || message.includes('company') || message.includes('business') || message.includes('conference')) {
    //     return `💼 <strong>Corporate Event Solutions:</strong><br><br>
    //             <strong>Event Types:</strong><br><br>
    //             📊 <strong>Conferences & Seminars</strong><br>
    //             • Full AV equipment<br>
    //             • Presentation screens<br>
    //             • High-speed Wi-Fi<br><br>
    //             🤝 <strong>Team Building</strong><br>
    //             • Activities & games<br>
    //             • Professional facilitators<br>
    //             • Catering included<br><br>
    //             🎉 <strong>Office Parties</strong><br>
    //             • Holiday celebrations<br>
    //             • Award ceremonies<br>
    //             • Retirement events<br><br>
    //             <strong>Corporate Packages Include:</strong><br>
    //             ✓ Professional setup<br>
    //             ✓ AV equipment<br>
    //             ✓ Wi-Fi & parking<br>
    //             ✓ Refreshments<br><br>
    //             Starting at $2,000<br><br>
    //             <a href="${baseUrl}gallery.php" style="color:#667eea; font-weight:600;">Book Corporate Event →</a>`;
    // }
    
    // Help
    if (message.includes('help') || message.includes('assist')) {
        return `🤝 <strong>How Can I Help?</strong><br><br>
                I can provide information about:<br><br>
                📅 <strong>Bookings:</strong> Event reservations<br>
                🖼️ <strong>Gallery:</strong> View past events<br>
                💰 <strong>Pricing:</strong> Package details<br>
                📞 <strong>Contact:</strong> Get in touch<br>
                🎨 <strong>Services:</strong> What we offer<br><br>
                Just ask me anything or use the quick buttons below!`;
    }
    
    // Greetings
    if (message.includes('hi') || message.includes('hello') || message.includes('hey') || message.includes('salam') || message.includes('assalam')) {
        return `Assalam-o-Alaikum! السلام علیکم<br>Hello! 👋<br><br>
                Welcome to Event Management System! 🎉<br><br>
                I'm here to help you plan the perfect event!<br><br>
                <strong>Quick Links:</strong><br>
                • 📸 View Gallery<br>
                • 📅 Book an event<br>
                • 💰 Check pricing<br>
                • 📞 Contact us<br><br>
                What can I help you with?`;
    }
    
    // Thanks
    if (message.includes('thank') || message.includes('thanks') || message.includes('shukriya')) {
        return `You're very welcome! 😊<br>
                Shukriya! شکریہ<br><br>
                It's my pleasure to help you!<br><br>
                Is there anything else you'd like to know?<br><br>
                <strong>Remember:</strong><br>
                📞 Call us: +92 314 5046418<br>
                📧 Email: moinabid007@gmail.com`;
    }
    
    // Goodbye
    if (message.includes('bye') || message.includes('goodbye') || message.includes('allah hafiz')) {
        return `Allah Hafiz! اللہ حافظ<br>
                Thank you for chatting! 👋<br><br>
                Have a wonderful day! 🌟<br><br>
                <strong>Contact Info:</strong><br>
                📧 moinabid007@gmail.com<br>
                📱 +92 314 5046418<br><br>
                We look forward to making your event special! 🎉`;
    }
    
    // Default response
    return `I'd be happy to help! 😊<br><br>
            <strong>You can ask me about:</strong><br>
            • "Book an event" - Start booking<br>
            • "Show gallery" - View photos<br>
            • "Contact" - Get in touch<br>
            • "Birthday party" - Birthday packages<br>
            • "Anniversary" - Anniversary events<br>
            • "Entertainment" - Music & fun<br>
            Or type your question!`;
}

function sendQuickMessage(type) {
    let message = '';
    
    switch(type) {
        case 'book':
            message = 'I want to book an event';
            break;
        case 'gallery':
            message = 'Show me the gallery';
            break;
        case 'events':
            message = 'Show me available events';
            break;
        case 'contact':
            message = 'How can I contact you?';
            break;
        case 'price':
            message = 'What are your prices?';
            break;
    }
    
    addChatMessage(message, 'user');
    
    setTimeout(() => {
        const response = getChatbotResponse(message.toLowerCase());
        addChatMessage(response, 'bot');
    }, 600);
}

function handleChatEnter(event) {
    if (event.key === 'Enter') {
        sendChatMessage();
    }
}

// Welcome message after page load
window.addEventListener('load', function() {
    setTimeout(() => {
        if (!document.getElementById('chatWindow').classList.contains('active')) {
            console.log('Chatbot ready!');
        }
    }, 2000);
});
</script>