<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Roommate Finder</title>
   
    <style>
        /* Centering content */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:white; /* Aesthetic light blue background */
        }

        .container {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Zoom effect animation */
        .welcome-message {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            animation: zoomIn 1s ease-in-out;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Styled login button */
        .login-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            font-size: 18px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        /* Chatbot container */
        .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: none;
            flex-direction: column;
        }

        .chat-header {
            background: #007BFF;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
        }

        .chat-body {
    height: 250px;
    padding: 10px;
    overflow-y: auto;
    font-size: 14px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    color: black; /* Ensure text is visible */
}

.chat-body p {
    color: black; /* Ensures both user and bot messages are visible */
}


        .chat-input {
            width: 100%;
            padding: 10px;
            border: none;
            outline: none;
        }

        .chat-footer {
            display: flex;
            border-top: 1px solid #ddd;
        }

        .chat-send-btn {
            width: 50px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Floating Chatbot Button */
        .chatbot-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            border: none;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="welcome-message">Welcome, Roomie!</h1>
        <a href="login.php" class="login-btn">Login</a>
    </div>

    <!-- Chatbot Button -->
    <button class="chatbot-toggle" onclick="toggleChatbot()">💬</button>

    <!-- Chatbot Container -->
    <div class="chatbot-container" id="chatbot">
        <div class="chat-header" onclick="toggleChatbot()">Roomie Chatbot</div>
        <div class="chat-body" id="chat-body">
            <p><strong>Bot:</strong> Hi! Ask me anything about roommate matching.</p>
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input" class="chat-input" placeholder="Type a question...">
            <button class="chat-send-btn" onclick="sendMessage()">➤</button>
        </div>
    </div>

    <script>
        function toggleChatbot() {
            const chatbot = document.getElementById("chatbot");
            chatbot.style.display = chatbot.style.display === "block" ? "none" : "block";
        }

        function sendMessage() {
            const inputField = document.getElementById("chat-input");
            const message = inputField.value.trim();
            const chatBody = document.getElementById("chat-body");

            if (message === "") return;

            // Append user message
            chatBody.innerHTML += `<p><strong>You:</strong> ${message}</p>`;

            // Clear input field
            inputField.value = "";

            // Process chatbot response
            let response = getBotResponse(message);

            // Append bot response with delay for better UX
            setTimeout(() => {
                chatBody.innerHTML += `<p><strong>Bot:</strong> ${response}</p>`;
                chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll
            }, 1000);
        }

        function getBotResponse(input) {
            input = input.toLowerCase();
            if (input.includes("what is this app")) {
                return "This is a roommate matching web app that helps you find compatible roommates!";
            } else if (input.includes("how does it work")) {
                return "You create an account, enter your preferences, and get matched with potential roommates.";
            } else if (input.includes("is this free")) {
                return "Yes! This service is completely free to use.";
            } else if (input.includes("how do i sign up")) {
                return "Click on the 'Login' button, then select 'Sign Up' to create an account.";
            } else {
                return "I'm not sure about that, but I can help you with roommate-related questions!";
            }
        }
    </script>
</body>
</html>
