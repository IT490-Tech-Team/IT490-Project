let form = document.getElementById("login")

const sendLoginRequest = (user, password) => {
    fetch("/utils/authenticate/main.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            type: "login",
            username: user,
            password: password
        })
    })
    .then((response) => {
        response.text().then((response_text) => {
            const response = JSON.parse(response_text)
            handleResponse(response)
        })
    })
    .catch(err => {
        console.log("ERROR?", err)
    })
}

const handleResponse = (response) => {
    console.log(response)
    switch (response.returnCode) {
        case "500":
        case "400":
            console.log("try again")
            break;
    
        default:
            createSessionCookie(response)
            console.log("ready to use the website")
            break;
    }
}

function createSessionCookie(response) {
    // Extract sessionId and expired_at from the response
    var sessionId = response.sessionId;
    var expiredAt = new Date(response.expired_at);
  
    // Convert the expiredAt date to UTC string format
    var expires = expiredAt.toUTCString();
  
    // Set the cookie with name "sessionId" and value sessionId
    document.cookie = `sessionId=${sessionId};expires=${expires};path=/`;
  
    console.log("Session cookie created successfully!");
}

form.addEventListener("submit", (event) => {
	event.preventDefault()

	const username = document.getElementById("username").value
	const password = document.getElementById("password").value

	sendLoginRequest(username, password)
})

