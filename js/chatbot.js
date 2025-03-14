function toggleChatbot() {
    let chatbotContainer = document.getElementById("chatbot-container");
    if (chatbotContainer.classList.contains("active")) {
        chatbotContainer.classList.remove("active");
        setTimeout(() => chatbotContainer.style.display = "none", 300);
    } else {
        chatbotContainer.style.display = "flex";
        setTimeout(() => chatbotContainer.classList.add("active"), 10);
    }
}

function handleKeyPress(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}

function sendPredefinedMessage(message) {
    document.getElementById("chatbot-input").value = message;
    sendMessage();
}

function sendMessage() {
    const inputField = document.getElementById("chatbot-input");
    const userMessage = inputField.value.trim();
    if (userMessage === "") return;

    displayMessage(userMessage, "user");
    inputField.value = "";

    setTimeout(() => {
        const botResponse = getAIResponse(userMessage);
        displayMessage(botResponse, "bot");
    }, 500);
}

function displayMessage(message, sender) {
    const chatbox = document.getElementById("chatbot-messages");
    const messageElement = document.createElement("div");
    messageElement.classList.add("message", sender);
    messageElement.innerText = message;
    chatbox.appendChild(messageElement);
    chatbox.scrollTop = chatbox.scrollHeight; 
}

function getAIResponse(message) {
    const lowerMessage = message.toLowerCase();

    const responses = [
        {
            keywords: ["best seller", "top item", "popular"],
            response: "Our best sellers are Brioche, Croissant, and Pain au Chocolat!"
        },
        {
            keywords: ["store hours", "open time", "closing time", "schedule"],
            response: "We are open from 8 AM to 8 PM every day!"
        },
        {
            keywords: ["location", "where are you", "store address"],
            response: "We are located at Silang, Cavite."
        },
        {
            keywords: ["track order", "order status", "my delivery", "order"],
            response: "To track your order, go to your profile and check 'Order Status'."
        },
        {
            keywords: ["palbites", "about palbites", "who are you"],
            response: "Palbites is a bakeshop dedicated to crafting fresh, high-quality pastries, bread, and cakes for every occasion."
        },
        {
            keywords: ["custom cakes", "make a cake", "order cake", "birthday cake", "wedding cake"],
            response: "Sorry we are not selling cakes"
        },
        {
            keywords: ["delivery", "do you deliver", "shipping", "home delivery"],
            response: "Yes, we offer delivery! You can order online through our website"
        },
        {
            keywords: ["payment methods", "pay", "accepted payments", "payment options"],
            response: "Unfortunately, we only accept cash"
        },
        {
            keywords: ["menu", "what do you sell", "products", "food list"],
            response: "You can check it out on the 'Products' page!"
        },
        {
            keywords: ["discount", "promo", "special offer", "sale"],
            response: "Yes! We offer discounts on bulk orders and seasonal promotions. Check our social media for updates!"
        },
        {
            keywords: ["ingredients", "what's inside", "food content"],
            response: "We use only the finest natural ingredients, with no artificial preservatives."
        },
        {
            keywords: ["gluten-free", "gluten", "no gluten"],
            response: "Unfortunately, we don't offer that."
        },
        {
            keywords: ["vegan", "plant-based", "no animal products"],
            response: "We do offer vegan-friendly options! Look for the vegan symbol on our menu."
        },
        {
            keywords: ["coffee", "do you serve coffee", "cafe"],
            response: "We only offer tasty pastries!"
        },
        {
            keywords: ["refund policy", "return order", "money back"],
            response: "If there's an issue with your order, please contact us within 24 hours, and we’ll be happy to help!"
        },
        {
            keywords: ["how to order", "buy", "purchase"],
            response: "You can order online through our website, by calling us, or by visiting our store!"
        },
        {
            keywords: ["shelf life", "expiration", "how long does it last"],
            response: "Most of our baked goods last 2-3 days when stored properly in a cool, dry place."
        },
        {
            keywords: ["allergy", "nut allergy", "food allergy"],
            response: "If you have any allergies, please contact us before ordering."
        },
        {
            keywords: ["franchise", "business opportunity", "partner with you"],
            response: "We're excited that you're interested in franchising! Please visit our social media page or contact us for more for details."
        },
        {
            keywords: ["gift card", "voucher", "gift certificate"],
            response: "We only offer vouchers."
        },
        {
            keywords: ["customer support", "help", "support"],
            response: "You can reach our customer support team via email at contact@palbites.com or call us at 09979041929."
        },
        {
            keywords: ["recommend", "suggest", "what should I try"],
            response: "If you're new here, try our classic croissants or our signature brownies!"
        },
        {
            keywords: ["offer"],
            response: "We offer freshly baked bread, delicious pastries, and cookies made with love. Our products are perfect for everyday treats or special celebrations."
        },
        {
            keywords: ["story"],
            response: "What started as a small home bakery grew into a beloved shop known for its handcrafted treats. We continue to bake with passion, using the finest ingredients and time-tested recipes."
        },
    ];

    for (const item of responses) {
        if (item.keywords.some(keyword => lowerMessage.includes(keyword))) {
            return item.response;
        }
    }

    return "I'm not sure about that. Can you ask something else?";
}










