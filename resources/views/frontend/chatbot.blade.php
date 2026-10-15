{{-- Apollo AI Chatbot Widget (tawk.to style with AI assistant) --}}
<style>
  /* ===== APOLLO CHATBOT STYLES ===== */
  #apollo-chatbot-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: var(--text, #1e293b);
  }

  /* Teaser / Greeting & Pills Container */
  #apollo-teaser-container {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
    margin-bottom: 14px;
    pointer-events: auto;
    transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1), transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.35s;
    animation: apolloTeaserPop 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  }

  #apollo-teaser-container.apollo-hidden {
    opacity: 0;
    visibility: hidden;
    transform: translateY(16px) scale(0.95);
    pointer-events: none;
  }

  @keyframes apolloTeaserPop {
    0% {
      opacity: 0;
      transform: translateY(20px) scale(0.9);
    }
    100% {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  /* Greeting Card */
  .apollo-greeting-card {
    background: #ffffff;
    color: #1e293b;
    border-radius: 14px;
    padding: 14px 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.07);
    max-width: 290px;
    position: relative;
    font-size: 0.92rem;
    line-height: 1.45;
    font-weight: 500;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  [data-theme="dark"] .apollo-greeting-card {
    background: #14161c;
    color: #f1f5f9;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.55), 0 0 1px rgba(255, 255, 255, 0.15);
  }

  .apollo-greeting-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 34px rgba(0, 0, 0, 0.18);
  }

  .apollo-greeting-close {
    position: absolute;
    top: 6px;
    right: 8px;
    background: transparent;
    border: none;
    color: #94a3b8;
    font-size: 16px;
    line-height: 1;
    cursor: pointer;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.2s, color 0.2s;
  }

  .apollo-greeting-close:hover {
    background: rgba(0, 0, 0, 0.07);
    color: #ef4444;
  }

  [data-theme="dark"] .apollo-greeting-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #f87171;
  }

  /* Suggestion Pills */
  .apollo-pills-wrap {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
  }

  .apollo-prompt-pill {
    background: #ffffff;
    color: #03a84e;
    border: 1.5px solid #00b67a;
    border-radius: 9999px;
    padding: 8px 18px;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    outline: none;
    user-select: none;
  }

  [data-theme="dark"] .apollo-prompt-pill {
    background: #14161c;
    color: #4ade80;
    border: 1.5px solid #22c55e;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
  }

  .apollo-prompt-pill:hover {
    background: #00b67a;
    color: #ffffff;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0, 182, 122, 0.3);
  }

  [data-theme="dark"] .apollo-prompt-pill:hover {
    background: #22c55e;
    color: #0a0b0e;
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.35);
  }

  /* Launcher Button */
  .apollo-launcher {
    display: flex;
    justify-content: flex-end;
    position: relative;
  }

  .apollo-launcher-btn {
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00c988 0%, #00a463 100%);
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(0, 182, 122, 0.45), 0 2px 8px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s;
    outline: none;
  }

  .apollo-launcher-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 30px rgba(0, 182, 122, 0.6);
  }

  .apollo-launcher-btn:active {
    transform: scale(0.96);
  }

  .apollo-launcher-icon {
    width: 30px;
    height: 30px;
    fill: #ffffff;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s;
  }

  .apollo-close-icon {
    position: absolute;
    width: 26px;
    height: 26px;
    fill: #ffffff;
    opacity: 0;
    transform: rotate(-90deg) scale(0.5);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s;
  }

  #apollo-chatbot-widget.apollo-open .apollo-launcher-icon {
    opacity: 0;
    transform: rotate(90deg) scale(0.5);
  }

  #apollo-chatbot-widget.apollo-open .apollo-close-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
  }

  /* Notification Badge */
  .apollo-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #ef4444;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
    animation: apolloBadgePulse 2.4s infinite;
  }

  [data-theme="dark"] .apollo-badge {
    border-color: #0a0b0e;
  }

  @keyframes apolloBadgePulse {
    0%, 100% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.15);
    }
  }

  /* Chat Window Dialog */
  #apollo-chat-window {
    position: fixed;
    bottom: 98px;
    right: 24px;
    width: 390px;
    max-width: calc(100vw - 32px);
    height: 600px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.22), 0 0 1px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(24px) scale(0.94);
    transform-origin: bottom right;
    transition: opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1), transform 0.32s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.32s;
    border: 1px solid rgba(0, 0, 0, 0.08);
  }

  [data-theme="dark"] #apollo-chat-window {
    background: #111319;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.7), 0 0 1px rgba(255, 255, 255, 0.2);
  }

  #apollo-chatbot-widget.apollo-open #apollo-chat-window {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
  }

  /* Chat Header */
  .apollo-chat-header {
    background: linear-gradient(135deg, #00b67a 0%, #008751 100%);
    color: #ffffff;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 14px rgba(0, 182, 122, 0.25);
    position: relative;
    flex-shrink: 0;
  }

  .apollo-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .apollo-avatar-wrap {
    position: relative;
    width: 42px;
    height: 42px;
  }

  .apollo-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.35);
  }

  .apollo-avatar svg {
    width: 24px;
    height: 24px;
    fill: #ffffff;
  }

  .apollo-status-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #22c55e;
    border: 2px solid #ffffff;
    box-shadow: 0 0 6px #22c55e;
  }

  .apollo-header-info h4 {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
    color: #ffffff;
  }

  .apollo-ai-badge {
    background: rgba(255, 255, 255, 0.24);
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 6px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  .apollo-header-info p {
    margin: 2px 0 0 0;
    font-size: 0.75rem;
    opacity: 0.9;
    color: #e6f9f0;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .apollo-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .apollo-header-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s, transform 0.15s;
  }

  .apollo-header-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
  }

  /* Chat Messages Container */
  .apollo-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    scroll-behavior: smooth;
    background: #f8fafc;
  }

  [data-theme="dark"] .apollo-chat-messages {
    background: #0d0f14;
  }

  .apollo-chat-messages::-webkit-scrollbar {
    width: 6px;
  }

  .apollo-chat-messages::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 3px;
  }

  [data-theme="dark"] .apollo-chat-messages::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.15);
  }

  /* Message Bubble */
  .apollo-msg {
    display: flex;
    flex-direction: column;
    max-width: 86%;
    animation: apolloMsgFade 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }

  @keyframes apolloMsgFade {
    0% {
      opacity: 0;
      transform: translateY(8px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .apollo-msg.bot {
    align-self: flex-start;
  }

  .apollo-msg.user {
    align-self: flex-end;
  }

  .apollo-msg-bubble {
    padding: 12px 15px;
    border-radius: 16px;
    font-size: 0.9rem;
    line-height: 1.5;
    position: relative;
    word-break: break-word;
  }

  .apollo-msg.bot .apollo-msg-bubble {
    background: #ffffff;
    color: #1e293b;
    border-bottom-left-radius: 4px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  [data-theme="dark"] .apollo-msg.bot .apollo-msg-bubble {
    background: #181b24;
    color: #e2e8f0;
    border: 1px solid rgba(255, 255, 255, 0.07);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
  }

  .apollo-msg.user .apollo-msg-bubble {
    background: linear-gradient(135deg, #00b67a 0%, #00965d 100%);
    color: #ffffff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 3px 10px rgba(0, 182, 122, 0.3);
  }

  .apollo-msg-time {
    font-size: 0.68rem;
    color: #94a3b8;
    margin-top: 4px;
    padding: 0 4px;
  }

  .apollo-msg.user .apollo-msg-time {
    align-self: flex-end;
  }

  /* Rich Formatting in Bot Messages */
  .apollo-msg-bubble strong {
    font-weight: 700;
    color: #0f172a;
  }

  [data-theme="dark"] .apollo-msg-bubble strong {
    color: #f8fafc;
  }

  .apollo-msg.user .apollo-msg-bubble strong {
    color: #ffffff;
  }

  .apollo-msg-bubble ul {
    margin: 6px 0;
    padding-left: 18px;
    list-style: disc;
  }

  .apollo-msg-bubble li {
    margin-bottom: 4px;
  }

  .apollo-msg-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
  }

  .apollo-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(0, 182, 122, 0.08);
    color: #00965d;
    border: 1px solid rgba(0, 182, 122, 0.3);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
  }

  [data-theme="dark"] .apollo-action-btn {
    background: rgba(34, 197, 94, 0.12);
    color: #4ade80;
    border-color: rgba(34, 197, 94, 0.35);
  }

  .apollo-action-btn:hover {
    background: #00b67a;
    color: #ffffff;
    border-color: #00b67a;
    transform: translateY(-1px);
  }

  /* Typing Indicator */
  .apollo-typing {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 12px 16px;
    background: #ffffff;
    border-radius: 16px;
    border-bottom-left-radius: 4px;
    width: fit-content;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.06);
  }

  [data-theme="dark"] .apollo-typing {
    background: #181b24;
    border-color: rgba(255, 255, 255, 0.07);
  }

  .apollo-typing-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #00b67a;
    animation: apolloBounce 1.4s infinite ease-in-out;
  }

  .apollo-typing-dot:nth-child(1) { animation-delay: -0.32s; }
  .apollo-typing-dot:nth-child(2) { animation-delay: -0.16s; }

  @keyframes apolloBounce {
    0%, 80%, 100% {
      transform: scale(0.6);
      opacity: 0.4;
    }
    40% {
      transform: scale(1);
      opacity: 1;
    }
  }

  /* Quick Chips Carousel Inside Chat */
  .apollo-chat-chips {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    padding: 8px 16px;
    background: #ffffff;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    scrollbar-width: none;
    flex-shrink: 0;
  }

  .apollo-chat-chips::-webkit-scrollbar {
    display: none;
  }

  [data-theme="dark"] .apollo-chat-chips {
    background: #111319;
    border-top: 1px solid rgba(255, 255, 255, 0.07);
  }

  .apollo-chip {
    background: rgba(0, 182, 122, 0.06);
    color: #03a84e;
    border: 1px solid rgba(0, 182, 122, 0.25);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    outline: none;
  }

  [data-theme="dark"] .apollo-chip {
    background: rgba(34, 197, 94, 0.09);
    color: #4ade80;
    border-color: rgba(34, 197, 94, 0.25);
  }

  .apollo-chip:hover {
    background: #00b67a;
    color: #ffffff;
    border-color: #00b67a;
  }

  /* Input Footer Area */
  .apollo-chat-footer {
    padding: 10px 14px 12px;
    background: #ffffff;
    border-top: 1px solid rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex-shrink: 0;
  }

  [data-theme="dark"] .apollo-chat-footer {
    background: #111319;
    border-top: 1px solid rgba(255, 255, 255, 0.07);
  }

  .apollo-input-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f1f5f9;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 24px;
    padding: 4px 6px 4px 16px;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  [data-theme="dark"] .apollo-input-wrap {
    background: #1a1e28;
    border-color: rgba(255, 255, 255, 0.1);
  }

  .apollo-input-wrap:focus-within {
    border-color: #00b67a;
    box-shadow: 0 0 0 3px rgba(0, 182, 122, 0.18);
  }

  .apollo-chat-input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.9rem;
    color: #0f172a;
    padding: 6px 0;
  }

  [data-theme="dark"] .apollo-chat-input {
    color: #f8fafc;
  }

  .apollo-chat-input::placeholder {
    color: #94a3b8;
  }

  .apollo-send-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #00b67a;
    border: none;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s, background 0.2s, opacity 0.2s;
    outline: none;
    flex-shrink: 0;
  }

  .apollo-send-btn:hover {
    background: #00965d;
    transform: scale(1.05);
  }

  .apollo-send-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
  }

  .apollo-send-btn svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
    margin-left: 2px;
  }

  .apollo-footer-brand {
    text-align: center;
    font-size: 0.68rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
  }

  .apollo-footer-brand b {
    color: #00b67a;
  }

  /* Responsive styles for mobile */
  @media (max-width: 480px) {
    #apollo-chatbot-widget {
      bottom: 16px;
      right: 16px;
    }

    .apollo-greeting-card {
      max-width: 250px;
      font-size: 0.86rem;
      padding: 12px 14px;
    }

    .apollo-prompt-pill {
      font-size: 0.82rem;
      padding: 7px 14px;
    }

    .apollo-launcher-btn {
      width: 56px;
      height: 56px;
    }

    #apollo-chat-window {
      right: 12px;
      left: 12px;
      bottom: 84px;
      width: auto;
      max-width: none;
      height: 82vh;
    }
  }
</style>

<div id="apollo-chatbot-widget">
  <!-- 1. Floating Teaser: Greeting Bubble & Suggestion Pills (Tawk.to Apollo AI Style) -->
  <div id="apollo-teaser-container">
    <!-- Speech / Greeting Card -->
    <div class="apollo-greeting-card" id="apolloGreetingCard" onclick="apolloOpenChat()">
      <button class="apollo-greeting-close" id="apolloDismissTeaser" title="Close" onclick="apolloDismissTeaser(event)">✕</button>
      <span>👋 Hi! I'm <strong>Apollo</strong>, an AI Assistant. Ask me anything about <strong>{{ config('detailsApp.name', 'Sync-Trade') }}</strong>!</span>
    </div>

    <!-- Suggestion Pills (Clicking any opens chat and asks that question) -->
    <div class="apollo-pills-wrap">
      <button class="apollo-prompt-pill" onclick="apolloAskPrompt('What is {{ config('detailsApp.name', 'Sync-Trade') }}?')">
        What is {{ config('detailsApp.name', 'Sync-Trade') }}?
      </button>
      <button class="apollo-prompt-pill" onclick="apolloAskPrompt('What are the investment plans?')">
        What are the investment plans?
      </button>
      <button class="apollo-prompt-pill" onclick="apolloAskPrompt('How do I deposit & withdraw?')">
        How do I deposit & withdraw?
      </button>
      <button class="apollo-prompt-pill" onclick="apolloAskPrompt('How do I connect wallet & register?')">
        How do I connect wallet & register?
      </button>
      <button class="apollo-prompt-pill" onclick="apolloAskPrompt('I have a different question')">
        I have a different question
      </button>
    </div>
  </div>

  <!-- 2. Circular Green Launcher Button with Badge '1' -->
  <div class="apollo-launcher">
    <button class="apollo-launcher-btn" id="apolloLauncherBtn" onclick="apolloToggleChat()" title="Chat with Apollo AI">
      <!-- Chat Speech Bubble SVG -->
      <svg class="apollo-launcher-icon" viewBox="0 0 24 24">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
      </svg>
      <!-- Close 'X' SVG -->
      <svg class="apollo-close-icon" viewBox="0 0 24 24">
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
      </svg>
      <!-- Notification Badge '1' -->
      <span class="apollo-badge" id="apolloNotificationBadge">1</span>
    </button>
  </div>

  <!-- 3. Full Interactive Apollo AI Chat Window -->
  <div id="apollo-chat-window" role="dialog" aria-label="Apollo AI Assistant">
    <!-- Header -->
    <div class="apollo-chat-header">
      <div class="apollo-header-left">
        <div class="apollo-avatar-wrap">
          <div class="apollo-avatar">
            <!-- Apollo Robot / Assistant Icon -->
            <svg viewBox="0 0 24 24">
              <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.38-1 1.72V7h4a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-8a3 3 0 0 1 3-3h4V5.72c-.6-.34-1-.98-1-1.72a2 2 0 0 1 2-2m-3 8a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m6 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m-6 5v1.5h6V15H9z"/>
            </svg>
          </div>
          <span class="apollo-status-dot"></span>
        </div>
        <div class="apollo-header-info">
          <h4>Apollo <span class="apollo-ai-badge">AI BOT</span></h4>
          <p>Online · Instant answers</p>
        </div>
      </div>
      <div class="apollo-header-actions">
        <button class="apollo-header-btn" onclick="apolloResetChat()" title="Restart Conversation">↺</button>
        <button class="apollo-header-btn" onclick="apolloCloseChat()" title="Close Chat">✕</button>
      </div>
    </div>

    <!-- Messages Container -->
    <div class="apollo-chat-messages" id="apolloChatMessages">
      <!-- Bot Welcome message injected by JS -->
    </div>

    <!-- Quick Chips Carousel inside chat -->
    <div class="apollo-chat-chips">
      <button class="apollo-chip" onclick="apolloAskPrompt('What are the investment plans?')">💎 Packages</button>
      <button class="apollo-chip" onclick="apolloAskPrompt('How do I deposit & withdraw?')">💳 Deposit/Withdraw</button>
      <button class="apollo-chip" onclick="apolloAskPrompt('How do I connect wallet & register?')">🚀 Connect Wallet</button>
      <button class="apollo-chip" onclick="apolloAskPrompt('What is the referral & level income?')">📈 Team Income</button>
      <button class="apollo-chip" onclick="apolloAskPrompt('How can I contact support?')">💬 Human Support</button>
    </div>

    <!-- Footer Input -->
    <div class="apollo-chat-footer">
      <div class="apollo-input-wrap">
        <input 
          type="text" 
          id="apolloChatInput" 
          class="apollo-chat-input" 
          placeholder="Ask Apollo anything..." 
          autocomplete="off"
          onkeydown="if(event.key==='Enter') apolloSendUserMessage();"
        />
        <button class="apollo-send-btn" id="apolloSendBtn" onclick="apolloSendUserMessage()" title="Send">
          <svg viewBox="0 0 24 24">
            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
          </svg>
        </button>
      </div>
      <div class="apollo-footer-brand">
        ⚡ <span>Powered by <b>{{ config('detailsApp.name', 'Sync-Trade') }} AI</b></span>
      </div>
    </div>
  </div>
</div>

<script>
  /* ===== APOLLO CHATBOT ENGINE ===== */
  const APOLLO_CONFIG = {
    brandName: "{{ config('detailsApp.name', 'Sync-Trade') }}",
    dashboardUrl: "{{ url('member/dashboard') }}",
    registerUrl: "{{ url('/member/register/1') }}",
    loginUrl: "{{ url('/admin') }}",
    packagesAnchor: "#packages",
    aboutAnchor: "#about",
    contactWa: "https://wa.me/?text=" + encodeURIComponent("Hello {{ config('detailsApp.name', 'Sync-Trade') }} Support, I need assistance with the platform.")
  };

  let apolloIsOpen = false;
  let apolloHasOpened = false;

  // Sound chime using Web Audio API (no external asset needed)
  function apolloPlayChime() {
    try {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (!AudioCtx) return;
      const ctx = new AudioCtx();
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.type = 'sine';
      osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
      osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12); // A5
      gain.gain.setValueAtTime(0.08, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.start();
      osc.stop(ctx.currentTime + 0.36);
    } catch (e) {
      // Audio playback restrictions fallback gracefully
    }
  }

  function apolloToggleChat() {
    if (apolloIsOpen) {
      apolloCloseChat();
    } else {
      apolloOpenChat();
    }
  }

  function apolloOpenChat() {
    const widget = document.getElementById('apollo-chatbot-widget');
    const teaser = document.getElementById('apollo-teaser-container');
    const badge = document.getElementById('apolloNotificationBadge');
    const input = document.getElementById('apolloChatInput');

    widget.classList.add('apollo-open');
    teaser.classList.add('apollo-hidden');
    if (badge) badge.style.display = 'none';

    apolloIsOpen = true;

    if (!apolloHasOpened) {
      apolloHasOpened = true;
      apolloInitConversation();
      apolloPlayChime();
    }

    setTimeout(() => {
      if (input) input.focus();
    }, 250);
  }

  function apolloCloseChat() {
    const widget = document.getElementById('apollo-chatbot-widget');
    widget.classList.remove('apollo-open');
    apolloIsOpen = false;
  }

  function apolloDismissTeaser(event) {
    if (event) event.stopPropagation();
    const teaser = document.getElementById('apollo-teaser-container');
    teaser.classList.add('apollo-hidden');
    try {
      sessionStorage.setItem('apollo_teaser_dismissed', 'true');
    } catch (e) {}
  }

  // Restore teaser dismissal state
  (function() {
    try {
      if (sessionStorage.getItem('apollo_teaser_dismissed') === 'true') {
        const teaser = document.getElementById('apollo-teaser-container');
        if (teaser) teaser.classList.add('apollo-hidden');
      }
    } catch (e) {}
  })();

  function apolloGetTime() {
    const now = new Date();
    let hours = now.getHours();
    let mins = now.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    mins = mins < 10 ? '0' + mins : mins;
    return hours + ':' + mins + ' ' + ampm;
  }

  function apolloInitConversation() {
    const messages = document.getElementById('apolloChatMessages');
    messages.innerHTML = '';

    const welcomeHtml = `
      <div class="apollo-msg-bubble">
        👋 Hi there! I'm <strong>Apollo</strong>, your 24/7 AI Assistant for <strong>${APOLLO_CONFIG.brandName}</strong>.<br><br>
        I can answer your questions about our <strong>algorithmic copy trading, investment plans, wallet connection, deposits, and income structure</strong>.<br><br>
        How can I help you today?
      </div>
      <div class="apollo-msg-actions">
        <button class="apollo-action-btn" onclick="apolloAskPrompt('What are the investment plans?')">💎 View Packages</button>
        <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I connect wallet & register?')">🚀 Connect Wallet</button>
        <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I deposit & withdraw?')">💳 Deposit & Withdraw</button>
      </div>
      <span class="apollo-msg-time">${apolloGetTime()}</span>
    `;

    const msgDiv = document.createElement('div');
    msgDiv.className = 'apollo-msg bot';
    msgDiv.innerHTML = welcomeHtml;
    messages.appendChild(msgDiv);
    apolloScrollToBottom();
  }

  function apolloResetChat() {
    apolloInitConversation();
    apolloPlayChime();
  }

  function apolloScrollToBottom() {
    const messages = document.getElementById('apolloChatMessages');
    if (messages) {
      setTimeout(() => {
        messages.scrollTop = messages.scrollHeight;
      }, 50);
    }
  }

  function apolloAskPrompt(question) {
    if (!apolloIsOpen) {
      apolloOpenChat();
    }
    apolloProcessQuery(question);
  }

  function apolloSendUserMessage() {
    const input = document.getElementById('apolloChatInput');
    const query = input.value.trim();
    if (!query) return;
    input.value = '';
    apolloProcessQuery(query);
  }

  function apolloProcessQuery(query) {
    const messages = document.getElementById('apolloChatMessages');

    // 1. Append User Message
    const userMsg = document.createElement('div');
    userMsg.className = 'apollo-msg user';
    userMsg.innerHTML = `
      <div class="apollo-msg-bubble">${apolloEscapeHtml(query)}</div>
      <span class="apollo-msg-time">${apolloGetTime()}</span>
    `;
    messages.appendChild(userMsg);
    apolloScrollToBottom();

    // 2. Append Typing Indicator
    const typingId = 'apollo-typing-' + Date.now();
    const typingDiv = document.createElement('div');
    typingDiv.className = 'apollo-msg bot';
    typingDiv.id = typingId;
    typingDiv.innerHTML = `
      <div class="apollo-typing">
        <span class="apollo-typing-dot"></span>
        <span class="apollo-typing-dot"></span>
        <span class="apollo-typing-dot"></span>
      </div>
    `;
    messages.appendChild(typingDiv);
    apolloScrollToBottom();

    // 3. Resolve Answer with dynamic response
    const delay = Math.min(1000, Math.max(500, query.length * 15));
    setTimeout(() => {
      const typingEl = document.getElementById(typingId);
      if (typingEl) typingEl.remove();

      const response = apolloGenerateAnswer(query);

      const botMsg = document.createElement('div');
      botMsg.className = 'apollo-msg bot';
      botMsg.innerHTML = `
        <div class="apollo-msg-bubble">${response.text}</div>
        ${response.actionsHtml || ''}
        <span class="apollo-msg-time">${apolloGetTime()}</span>
      `;
      messages.appendChild(botMsg);
      apolloScrollToBottom();
      apolloPlayChime();
    }, delay);
  }

  function apolloEscapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
  }

  /* ===== KNOWLEDGE BASE / AI RESPONSE GENERATOR ===== */
  function apolloGenerateAnswer(query) {
    const q = query.toLowerCase();

    // 1. What is Sync-Trade / SwissBull / About
    if (q.includes('what is') || q.includes('about') || q.includes('who are you') || q.includes('how does it work') || q.includes('sync-trade') || q.includes('swissbull')) {
      return {
        text: `<strong>${APOLLO_CONFIG.brandName}</strong> is a high-performance, technology-driven forex & algorithmic copy trading platform.<br><br>
        Key Highlights:
        <ul>
          <li><strong>Automated Algorithmic Trading</strong>: Precision trade execution with strict risk protocols.</li>
          <li><strong>Copy Trading</strong>: Seamlessly mirror profitable strategies.</li>
          <li><strong>Consistent Returns</strong>: Target monthly returns up to 10%.</li>
          <li><strong>Decentralized & Transparent</strong>: Built on BNB Smart Chain (BEP-20) with automated payouts.</li>
        </ul>`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <a href="${APOLLO_CONFIG.aboutAnchor}" class="apollo-action-btn" onclick="apolloCloseChat()">📖 Read More</a>
            <button class="apollo-action-btn" onclick="apolloAskPrompt('What are the investment plans?')">💎 Check Packages</button>
          </div>
        `
      };
    }

    // 2. Investment Packages / ROI / Profit / Plans
    if (q.includes('package') || q.includes('plan') || q.includes('invest') || q.includes('roi') || q.includes('profit') || q.includes('return') || q.includes('earn')) {
      return {
        text: `Our investment packages are designed for every investor level, offering <strong>up to 10% monthly returns</strong> backed by real algorithmic trading:
        <ul>
          <li><strong>Minimum Investment</strong>: Starts from just <strong>$50</strong>.</li>
          <li><strong>ROI Distribution</strong>: Daily trading profits credited directly to your trading wallet.</li>
          <li><strong>Trading Days</strong>: 24×5 active forex and cryptocurrency market sessions.</li>
          <li><strong>Capital Protection</strong>: Strict risk management & stop-loss rules on every trade.</li>
        </ul>
        Click below to inspect all tier packages directly on the site:`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <a href="${APOLLO_CONFIG.packagesAnchor}" class="apollo-action-btn" onclick="apolloCloseChat()">💎 View All Packages</a>
            <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I deposit & withdraw?')">💳 Deposit Methods</button>
          </div>
        `
      };
    }

    // 3. Deposit & Withdrawal
    if (q.includes('deposit') || q.includes('withdraw') || q.includes('fund') || q.includes('payment') || q.includes('usdt') || q.includes('bep20') || q.includes('trc20') || q.includes('crypto')) {
      return {
        text: `Here is how deposits and withdrawals work on <strong>${APOLLO_CONFIG.brandName}</strong>:
        <ul>
          <li><strong>Supported Currency</strong>: USDT (BNB Smart Chain BEP-20 & TRC-20).</li>
          <li><strong>Deposits</strong>: Instant automated blockchain confirmations with zero hidden fees.</li>
          <li><strong>Withdrawals</strong>: Request withdrawals anytime 24/7 directly to your verified Web3 wallet address.</li>
          <li><strong>Processing Speed</strong>: Fast automated on-chain processing.</li>
        </ul>`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <button class="apollo-action-btn" onclick="if(typeof connectWallet==='function') connectWallet(); apolloCloseChat();">🔗 Connect Wallet to Deposit</button>
            <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I connect wallet & register?')">🚀 Registration Steps</button>
          </div>
        `
      };
    }

    // 4. Wallet Connection & Registration
    if (q.includes('register') || q.includes('connect') || q.includes('wallet') || q.includes('metamask') || q.includes('trust wallet') || q.includes('start') || q.includes('join') || q.includes('login') || q.includes('signup')) {
      return {
        text: `Getting started is quick and seamless with Web3!
        <ul>
          <li><strong>Step 1</strong>: Ensure you have MetaMask or Trust Wallet configured on <strong>BNB Smart Chain (BSC)</strong>.</li>
          <li><strong>Step 2</strong>: Click <strong>"Connect Wallet"</strong> at the top right of the page.</li>
          <li><strong>Step 3</strong>: If you are new, you'll be redirected to complete your 1-minute registration with your sponsor ID!</li>
          <li><strong>Step 4</strong>: Deposit USDT and activate your desired package.</li>
        </ul>`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <button class="apollo-action-btn" onclick="if(typeof connectWallet==='function') connectWallet(); apolloCloseChat();">🔗 Connect Web3 Wallet</button>
            <a href="${APOLLO_CONFIG.registerUrl}" class="apollo-action-btn">📝 Register Account</a>
          </div>
        `
      };
    }

    // 5. Team Income / Referral / Level Income / Affiliate
    if (q.includes('referral') || q.includes('level') || q.includes('income') || q.includes('affiliate') || q.includes('team') || q.includes('commission') || q.includes('bonus')) {
      return {
        text: `<strong>${APOLLO_CONFIG.brandName}</strong> features a multi-stream earning ecosystem:
        <ul>
          <li><strong>Direct Referral Bonus</strong>: Earn 10% instant commission on direct referrals.</li>
          <li><strong>10-Level Team Trading Profit</strong>: Earn daily passive override rewards from trades across 10 generations of your team:
            <br>• Level 1 & 2: <strong>5%</strong>
            <br>• Level 3 & 4: <strong>4%</strong>
            <br>• Level 5 & 6: <strong>3%</strong>
            <br>• Level 7 & 8: <strong>2%</strong>
            <br>• Level 9 & 10: <strong>1%</strong>
          </li>
          <li><strong>Team Performance Bonuses & Leadership Ranks</strong> for top volume achievers.</li>
        </ul>`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <a href="#income" class="apollo-action-btn" onclick="apolloCloseChat()">📊 View Income Breakdown</a>
            <a href="#ranks" class="apollo-action-btn" onclick="apolloCloseChat()">🏆 Partnership Ranks</a>
          </div>
        `
      };
    }

    // 6. Security / Safe / Legit / Trust
    if (q.includes('safe') || q.includes('secure') || q.includes('legit') || q.includes('trust') || q.includes('scam') || q.includes('security') || q.includes('audit')) {
      return {
        text: `Security and transparency are the foundational pillars of <strong>${APOLLO_CONFIG.brandName}</strong>:
        <ul>
          <li><strong>Non-Custodial Web3 Access</strong>: You log in with your cryptographic wallet. No passwords to compromise.</li>
          <li><strong>Automated Smart Contracts</strong>: Transparent on-chain transactions verifiable on BscScan.</li>
          <li><strong>Risk Diversification</strong>: Strict stop-loss mechanisms and real algorithmic liquidity integration.</li>
        </ul>`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <button class="apollo-action-btn" onclick="apolloAskPrompt('What are the investment plans?')">💎 View Packages</button>
            <button class="apollo-action-btn" onclick="apolloAskPrompt('How can I contact support?')">💬 Talk to Support</button>
          </div>
        `
      };
    }

    // 7. Human Support / WhatsApp / Telegram / Contact
    if (q.includes('contact') || q.includes('support') || q.includes('help') || q.includes('human') || q.includes('whatsapp') || q.includes('telegram') || q.includes('phone') || q.includes('email') || q.includes('different question')) {
      return {
        text: `Our official support desk is available to assist you 24/7! You can chat directly with our representative via WhatsApp, access our community, or review official tutorials:`,
        actionsHtml: `
          <div class="apollo-msg-actions">
            <a href="${APOLLO_CONFIG.contactWa}" target="_blank" class="apollo-action-btn">💬 WhatsApp Support</a>
            <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I connect wallet & register?')">🚀 Connect Wallet</button>
            <button class="apollo-action-btn" onclick="apolloAskPrompt('What is ${APOLLO_CONFIG.brandName}?')">ℹ️ About Us</button>
          </div>
        `
      };
    }

    // 8. General fallback
    return {
      text: `Thanks for your question! As an AI assistant for <strong>${APOLLO_CONFIG.brandName}</strong>, I can assist you with our trading platform, investment packages, deposits/withdrawals, or registration.<br><br>
      Would you like to explore one of these popular topics?`,
      actionsHtml: `
        <div class="apollo-msg-actions">
          <button class="apollo-action-btn" onclick="apolloAskPrompt('What are the investment plans?')">💎 Packages</button>
          <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I connect wallet & register?')">🚀 How to Start</button>
          <button class="apollo-action-btn" onclick="apolloAskPrompt('How do I deposit & withdraw?')">💳 Deposit/Withdraw</button>
          <a href="${APOLLO_CONFIG.contactWa}" target="_blank" class="apollo-action-btn">💬 Chat on WhatsApp</a>
        </div>
      `
    };
  }
</script>
