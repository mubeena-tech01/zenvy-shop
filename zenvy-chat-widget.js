(function () {

  /* 🔑 ADD YOUR GEMINI API KEY */
  const GEMINI_API_KEY = 'AQ.Ab8RN6IHvlNBa3lP3yIHDasErDzRJj9ovcUyFIVhaaVfOFUl7w';
  const LOGO_SRC = 'logozenvy.jpeg';

  const GEMINI_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${GEMINI_API_KEY}`;

  const SYSTEM_INSTRUCTION = `You are Zenvy Assistant, a friendly and elegant support agent.
Help with orders, returns, payments, and account issues. Keep replies short and helpful.`;

  /* ───────── STYLES ───────── */
  const style = document.createElement('style');
  style.textContent = `
    body { margin: 0; }

    #znv-chat {
      position: fixed;
      inset: 0;
      background: #fdf8f3;
      z-index: 99999;
      display: none;
      flex-direction: column;
      font-family: 'DM Sans', sans-serif;
    }

    #znv-chat.open {
      display: flex;
    }

    #znv-header {
      background: linear-gradient(135deg, #edd5b0, #d4a882);
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .znv-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .znv-logo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
    }

    .znv-logo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .znv-title {
      font-size: 15px;
      font-weight: 600;
      color: #2c1a0e;
    }

    #znv-close {
      font-size: 20px;
      cursor: pointer;
      background: none;
      border: none;
    }

    #znv-messages {
      flex: 1;
      overflow-y: auto;
      padding: 18px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .msg {
      max-width: 75%;
      padding: 10px 14px;
      border-radius: 14px;
      font-size: 14px;
      line-height: 1.5;
    }

    .bot {
      background: white;
      border: 1px solid #e0c9b4;
      color: #2c1a0e;
    }

    .user {
      background: #b8906a;
      color: white;
      margin-left: auto;
    }

    #znv-input-area {
      padding: 12px;
      border-top: 1px solid #e0c9b4;
      display: flex;
      gap: 8px;
    }

    #znv-input {
      flex: 1;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    #znv-send {
      background: #b8906a;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 10px;
      cursor: pointer;
    }
  `;
  document.head.appendChild(style);

  /* ───────── HTML ───────── */
  document.body.insertAdjacentHTML('beforeend', `
    <div id="znv-chat">
      
      <div id="znv-header">
        <div class="znv-left">
          <div class="znv-logo">
            <img src="${LOGO_SRC}" />
          </div>
          <div class="znv-title">Zenvy Assistant</div>
        </div>
        <button id="znv-close">✕</button>
      </div>

      <div id="znv-messages"></div>

      <div id="znv-input-area">
        <input id="znv-input" placeholder="Ask about your order..." />
        <button id="znv-send">Send</button>
      </div>

    </div>
  `);

  const chat = document.getElementById('znv-chat');
  const messages = document.getElementById('znv-messages');
  const input = document.getElementById('znv-input');
  const send = document.getElementById('znv-send');
  const closeBtn = document.getElementById('znv-close');

  const history = [];
  let welcomed = false;

  function addMessage(text, role) {
    const div = document.createElement('div');
    div.className = `msg ${role}`;
    div.textContent = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  async function fetchReply(text) {
  try {
    const res = await fetch(GEMINI_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        // ✅ FIX 1: Add system instruction so the bot behaves correctly
        system_instruction: {
          parts: [{ text: SYSTEM_INSTRUCTION }]
        },
        contents: [
          // ✅ FIX 2: Include full conversation history for multi-turn chat
          ...history,
          { role: "user", parts: [{ text: text }] }
        ]
      })
    });

    const data = await res.json();

    // ✅ FIX 3: Show specific error reason (quota, invalid key, etc.)
    if (!res.ok) {
      const reason = data?.error?.status || "";
      if (reason === "RESOURCE_EXHAUSTED") {
        return "⚠️ API quota exceeded. Please check your Gemini plan or try again tomorrow.";
      } else if (reason === "API_KEY_INVALID") {
        return "⚠️ Your API key is invalid. Please generate a new one at aistudio.google.com";
      }
      throw new Error(data?.error?.message || "API failed");
    }

    const reply = data?.candidates?.[0]?.content?.parts?.[0]?.text;
    if (!reply) throw new Error("Empty reply from API");

    // ✅ FIX 4: Save history so conversation has memory
    history.push({ role: "user", parts: [{ text: text }] });
    history.push({ role: "model", parts: [{ text: reply }] });

    return reply;

  } catch (err) {
    console.error("FETCH ERROR:", err);
    return "⚠️ Error: " + err.message;
  }
}

  async function sendMsg() {
    const text = input.value.trim();
    if (!text) return;

    input.value = "";
    addMessage(text, "user");

    const reply = await fetchReply(text);
    addMessage(reply, "bot");
  }

  send.addEventListener("click", sendMsg);

  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") sendMsg();
  });

  closeBtn.onclick = () => chat.classList.remove("open");

  function openChat() {
    chat.classList.add("open");

    if (!welcomed) {
      welcomed = true;
      addMessage(
        "Hey there! 👋 Welcome to Zenvy Shop.\n\nI’m your assistant. I can help with orders, returns, and more.\n\nHow can I help you today?",
        "bot"
      );
    }
  }

  window.ZenvyChat = {
    open: openChat,
    close: () => chat.classList.remove("open")
  };

})();