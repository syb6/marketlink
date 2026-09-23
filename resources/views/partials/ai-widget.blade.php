<!-- Bootstrap Icons CDN (Required for chat icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Chatbot Widget HTML -->
<div class="ai-widget-container" id="ai-widget">
    <!-- Chat Window -->
    <div class="ai-chat-window d-none" id="ai-chat-window">
        <div class="ai-chat-header">
            <div>
                <i class="bi bi-robot"></i>
                <strong>eGreen Assistant</strong>
            </div>
            <button type="button" class="ai-close-btn"
                onclick="document.getElementById('ai-chat-window').classList.add('d-none')">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <div class="ai-chat-body">
            <div class="ai-bubble ai-response">
                Hi! I'm your MarketLink Assistant. How can I help you source fresh produce today?
            </div>

            <div class="ai-chips">
                <button type="button" class="ai-chip">Find organic apples nearby</button>
                <button type="button" class="ai-chip">Market pickup hours today</button>
                <button type="button" class="ai-chip">Recommend seasonal veg</button>
            </div>
        </div>

        <div class="ai-chat-footer">
            <input type="text" placeholder="Ask something..." class="ai-input">
            <button type="button" class="ai-send-btn"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>

    <!-- Floating Launcher -->
    <button type="button" class="ai-launcher"
        onclick="document.getElementById('ai-chat-window').classList.toggle('d-none')">
        <i class="bi bi-stars"></i>
        <span class="ai-pulse"></span>
    </button>
</div>

<!-- Widget Styles -->
<style>
    .ai-widget-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 16px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .ai-launcher {
        position: relative;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--emerald-600, #059669);
        color: white;
        font-size: 24px;
        display: grid;
        place-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease, background 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .ai-launcher:hover {
        transform: scale(1.05);
        background: var(--emerald-700, #047857);
    }

    .ai-pulse {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 50%;
        border: 2px solid var(--emerald-500, #10b981);
        animation: aiPulse 2s infinite cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    @keyframes aiPulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    .ai-chat-window {
        width: 330px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .ai-chat-window.d-none {
        display: none !important;
    }

    .ai-chat-header {
        background: var(--emerald-600, #059669);
        color: white;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ai-chat-header>div {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .ai-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        padding: 0;
        opacity: 0.8;
        cursor: pointer;
    }

    .ai-close-btn:hover {
        opacity: 1;
    }

    .ai-chat-body {
        padding: 16px;
        height: 260px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ai-bubble {
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.4;
        max-width: 85%;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .ai-response {
        background: #ecfdf5;
        color: #1c1917;
        border-bottom-left-radius: 4px;
        align-self: flex-start;
    }

    .ai-user {
        background: var(--emerald-600, #059669);
        color: white;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
    }

    .ai-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: auto;
    }

    .ai-chip {
        background: #fcfbf9;
        border: 1px solid #d1fae5;
        color: #047857;
        font-size: 11px;
        padding: 6px 10px;
        border-radius: 100px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .ai-chip:hover {
        background: #ecfdf5;
        border-color: #10b981;
    }

    .ai-chat-footer {
        padding: 12px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 8px;
        background: #fcfbf9;
    }

    .ai-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        font-size: 13px;
        color: #1c1917;
    }

    .ai-send-btn {
        background: none;
        border: none;
        color: #059669;
        font-size: 18px;
        padding: 4px;
        transition: color 0.2s;
        cursor: pointer;
    }

    .ai-send-btn:hover {
        color: #047857;
    }

    /* Responsive Mobile Adjustments */
    @media (max-width: 480px) {
        .ai-widget-container {
            bottom: 16px;
            right: 16px;
            left: 16px;
            align-items: flex-end;
        }

        .ai-chat-window {
            width: 100%;
            height: calc(100vh - 100px);
            max-height: 500px;
        }

        .ai-chat-body {
            flex-grow: 1;
            height: auto;
        }
    }
</style>

<!-- Widget Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatInput = document.querySelector('.ai-input');
        const sendBtn = document.querySelector('.ai-send-btn');
        const chatBody = document.querySelector('.ai-chat-body');
        const chips = document.querySelectorAll('.ai-chip');

        // Local offline fallback rules
        function getMarketLinkReply(query) {
            const q = query.toLowerCase();
            if (q.includes('pay') || q.includes('card') || q.includes('money')) {
                return "Pre-orders are paid **IN PERSON at pickup** directly to the farmer! No online payments are needed.";
            }
            if (q.includes('deliver') || q.includes('shipping') || q.includes('home')) {
                return "MarketLink is **Pickup Only**! You collect your reserved items directly from the farmer's market stall.";
            }
            if (q.includes('time') || q.includes('hour') || q.includes('when')) {
                return "Market hours depend on the location. Most operate on Saturdays from 8:00 AM – 12:00 PM. Check the Map view for exact stall windows!";
            }
            if (q.includes('order') || q.includes('cancel') || q.includes('change')) {
                return "You can place pre-orders on the product cards and modify/cancel them prior to the farmer's set cutoff time.";
            }
            if (q.includes('hi') || q.includes('hello') || q.includes('hey')) {
                return "Hello! How can I help you with your market pre-orders today?";
            }
            return "I can help with market hours, pre-orders, and pickup locations. What would you like to know?";
        }

        async function sendMessage(text) {
            if (!text) return;

            // Remove suggestion chips on first message
            const chipsContainer = document.querySelector('.ai-chips');
            if (chipsContainer) {
                chipsContainer.remove();
            }

            // Append User Message
            const userMsg = document.createElement('div');
            userMsg.className = 'ai-bubble ai-user';
            userMsg.textContent = text;
            chatBody.appendChild(userMsg);

            chatInput.value = '';
            chatBody.scrollTop = chatBody.scrollHeight;

            // Append Thinking State
            const botMsg = document.createElement('div');
            botMsg.className = 'ai-bubble ai-response';
            botMsg.innerHTML =
                '<span style="opacity: 0.6;"><i class="bi bi-three-dots"></i> Thinking...</span>';
            chatBody.appendChild(botMsg);
            chatBody.scrollTop = chatBody.scrollHeight;
            const API_KEY = "{{ env('GEMINI_API_KEY') }}";
            const systemPrompt =
                "You are the MarketLink AI assistant for eGreen. Rules:\n- Payment: Pre-orders are strictly paid IN PERSON at pickup. No online payment.\n- Delivery: Pickup ONLY at farmer market stalls. No home delivery.\n- Keep answers friendly, helpful, and under 3 sentences.";

            try {
                // Official Gemini Endpoint using gemini-2.5-flash
                const response = await fetch(
                    `https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=${API_KEY}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            systemInstruction: {
                                parts: [{
                                    text: systemPrompt
                                }]
                            },
                            contents: [{
                                parts: [{
                                    text: text
                                }]
                            }]
                        })
                    });

                if (!response.ok) {
                    const errDetails = await response.json().catch(() => ({}));
                    console.error("Gemini API Error Details:", errDetails);
                    throw new Error(`API error HTTP ${response.status}`);
                }

                const data = await response.json();

                // Safe Optional Chaining
                const responseText = data?.candidates?.[0]?.content?.parts?.[0]?.text;

                if (responseText) {
                    botMsg.innerHTML = responseText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                } else {
                    const localReply = getMarketLinkReply(text);
                    botMsg.innerHTML = localReply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                }
            } catch (err) {
                console.warn("API Call Failed. Falling back to local offline response:", err);
                const localReply = getMarketLinkReply(text);
                botMsg.innerHTML = localReply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            }

            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // Event Listeners
        sendBtn.addEventListener('click', () => sendMessage(chatInput.value.trim()));

        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage(chatInput.value.trim());
            }
        });

        chips.forEach(chip => {
            chip.addEventListener('click', function() {
                sendMessage(this.textContent.trim());
            });
        });
    });
</script>
