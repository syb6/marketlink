<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eGreen MarketLink Chatbot</title>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --bg-light: #fcfbf9;
            --text-dark: #1c1917;
            --border-color: #e5e7eb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f3f4f6;
        }

        /* Floating Widget Container */
        .ai-widget-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        /* Floating Launcher Button */
        .ai-launcher {
            position: relative;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--emerald-600);
            color: #ffffff;
            font-size: 24px;
            display: grid;
            place-items: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, background-color 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .ai-launcher:hover {
            transform: scale(1.06);
            background: var(--emerald-700);
        }

        .ai-pulse {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 50%;
            border: 2px solid var(--emerald-500);
            animation: aiPulse 2.2s infinite cubic-bezier(0.4, 0, 0.2, 1);
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

        /* Chat Window Container */
        .ai-chat-window {
            width: 350px;
            height: 500px;
            max-height: calc(100vh - 110px);
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: opacity 0.25s ease, transform 0.25s ease;
            transform-origin: bottom right;
        }

        .ai-chat-window.d-none {
            display: none !important;
        }

        /* Chat Header */
        .ai-chat-header {
            background: var(--emerald-600);
            color: #ffffff;
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ai-chat-header>div {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 600;
        }

        .ai-close-btn {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.85;
            cursor: pointer;
            border-radius: 4px;
            transition: opacity 0.2s ease;
        }

        .ai-close-btn:hover {
            opacity: 1;
        }

        /* Chat Messages Body */
        .ai-chat-body {
            padding: 16px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #ffffff;
        }

        .ai-bubble {
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13.5px;
            line-height: 1.45;
            max-width: 85%;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .ai-response {
            background: var(--emerald-50);
            color: var(--text-dark);
            border-bottom-left-radius: 4px;
            align-self: flex-start;
            border: 1px solid var(--emerald-100);
        }

        .ai-user {
            background: var(--emerald-600);
            color: #ffffff;
            border-bottom-right-radius: 4px;
            align-self: flex-end;
        }

        /* Quick Suggestion Chips */
        .ai-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: auto;
            padding-top: 8px;
        }

        .ai-chip {
            background: var(--bg-light);
            border: 1px solid var(--emerald-100);
            color: var(--emerald-700);
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 100px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .ai-chip:hover {
            background: var(--emerald-50);
            border-color: var(--emerald-500);
        }

        /* Chat Footer / Input Bar */
        .ai-chat-footer {
            padding: 12px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-light);
        }

        .ai-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 14px;
            color: var(--text-dark);
            padding: 6px 4px;
        }

        .ai-send-btn {
            background: none;
            border: none;
            color: var(--emerald-600);
            font-size: 20px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
            cursor: pointer;
        }

        .ai-send-btn:hover {
            color: var(--emerald-700);
        }

        /* Responsive Mobile Layout */
        @media (max-width: 480px) {
            .ai-widget-container {
                bottom: 16px;
                right: 16px;
                left: 16px;
            }

            .ai-chat-window {
                width: 100%;
                height: min(520px, calc(100vh - 90px));
                border-radius: 12px;
            }

            .ai-launcher {
                width: 52px;
                height: 52px;
                font-size: 22px;
            }

            .ai-input {
                font-size: 15px;
                /* Prevents auto-zoom on iOS safari */
            }
        }
    </style>
</head>

<body>

    <!-- Chatbot Widget HTML Container -->
    <div class="ai-widget-container" id="ai-widget">

        <!-- Chat Window -->
        <div class="ai-chat-window d-none" id="ai-chat-window">
            <div class="ai-chat-header">
                <div>
                    <i class="bi bi-robot"></i>
                    <span>eGreen Assistant</span>
                </div>
                <button type="button" class="ai-close-btn" id="ai-close-trigger" aria-label="Close Chat">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="ai-chat-body" id="ai-chat-body">
                <div class="ai-bubble ai-response">
                    Hi! I'm your MarketLink Assistant. How can I help you source fresh produce today?
                </div>

                <div class="ai-chips" id="ai-chips">
                    <button type="button" class="ai-chip">Find organic apples nearby</button>
                    <button type="button" class="ai-chip">Market pickup hours today</button>
                    <button type="button" class="ai-chip">Recommend seasonal veg</button>
                </div>
            </div>

            <div class="ai-chat-footer">
                <input type="text" placeholder="Ask something..." class="ai-input" id="ai-input">
                <button type="button" class="ai-send-btn" id="ai-send-btn" aria-label="Send Message">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>

        <!-- Floating Launcher -->
        <button type="button" class="ai-launcher" id="ai-launcher-trigger" aria-label="Open Chat">
            <i class="bi bi-stars"></i>
            <span class="ai-pulse"></span>
        </button>
    </div>

    <!-- JavaScript Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatWindow = document.getElementById('ai-chat-window');
            const launcherBtn = document.getElementById('ai-launcher-trigger');
            const closeBtn = document.getElementById('ai-close-trigger');
            const chatInput = document.getElementById('ai-input');
            const sendBtn = document.getElementById('ai-send-btn');
            const chatBody = document.getElementById('ai-chat-body');
            const chipsContainer = document.getElementById('ai-chips');

            // Replace with your Google Gemini API Key
            const API_KEY = "YOUR_GEMINI_API_KEY";
            const systemPrompt =
                "You are the MarketLink AI assistant for eGreen. Rules:\n- Payment: Pre-orders are strictly paid IN PERSON at pickup. No online payment.\n- Delivery: Pickup ONLY at farmer market stalls. No home delivery.\n- Keep answers friendly, helpful, and under 3 sentences.";

            // Toggle Chat Window
            launcherBtn.addEventListener('click', () => {
                chatWindow.classList.toggle('d-none');
                if (!chatWindow.classList.contains('d-none')) {
                    chatInput.focus();
                }
            });

            closeBtn.addEventListener('click', () => {
                chatWindow.classList.add('d-none');
            });

            // Offline rule fallback engine
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

                // Remove quick suggestion chips on first action
                if (chipsContainer) {
                    chipsContainer.remove();
                }

                // Add user bubble
                const userMsg = document.createElement('div');
                userMsg.className = 'ai-bubble ai-user';
                userMsg.textContent = text;
                chatBody.appendChild(userMsg);

                chatInput.value = '';
                chatBody.scrollTop = chatBody.scrollHeight;

                // Add thinking bubble
                const botMsg = document.createElement('div');
                botMsg.className = 'ai-bubble ai-response';
                botMsg.innerHTML =
                    '<span style="opacity: 0.6;"><i class="bi bi-three-dots"></i> Thinking...</span>';
                chatBody.appendChild(botMsg);
                chatBody.scrollTop = chatBody.scrollHeight;

                if (API_KEY && API_KEY !== "AQ.Ab8RN6KDYndmV-2On6upz0EPb0rFfjCm7uarslfJH5AMW16G6AY") {
                    try {
                        // Check inside your sendMessage() function
                        const response = await fetch('/ai-chat', { // or your API endpoint URL
                            method: "POST", // 👈 Make sure this is explicitly set to POST
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    ?.content
                            },
                            body: JSON.stringify({
                                message: text
                            }) // 👈 Body is only allowed with POST/PUT/PATCH
                        });

                        if (!response.ok) {
                            throw new Error(`API error HTTP ${response.status}`);
                        }

                        const data = await response.json();
                        const responseText = data?.candidates?.[0]?.content?.parts?.[0]?.text;

                        if (responseText) {
                            botMsg.innerHTML = responseText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        } else {
                            botMsg.innerHTML = getMarketLinkReply(text).replace(/\*\*(.*?)\*\*/g,
                                '<strong>$1</strong>');
                        }
                    } catch (err) {
                        console.warn("API Call Failed or unconfigured. Falling back to offline response:", err);
                        botMsg.innerHTML = getMarketLinkReply(text).replace(/\*\*(.*?)\*\*/g,
                            '<strong>$1</strong>');
                    }
                } else {
                    // Default offline response when API Key is missing
                    setTimeout(() => {
                        botMsg.innerHTML = getMarketLinkReply(text).replace(/\*\*(.*?)\*\*/g,
                            '<strong>$1</strong>');
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 400);
                }

                chatBody.scrollTop = chatBody.scrollHeight;
            }

            // Event Listeners
            sendBtn.addEventListener('click', () => sendMessage(chatInput.value.trim()));

            chatInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage(chatInput.value.trim());
                }
            });

            // Delegation for suggestion chips
            document.addEventListener('click', (e) => {
                if (e.target && e.target.classList.contains('ai-chip')) {
                    sendMessage(e.target.textContent.trim());
                }
            });
        });
    </script>
</body>

</html>
