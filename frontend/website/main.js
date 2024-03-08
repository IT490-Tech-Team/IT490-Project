
function getSessionIdCookie() {
    const cookies = document.cookie.split('; ');
    for (const cookie of cookies) {
        const [name, value] = cookie.split('=');
        if (name === 'sessionId') {
            return value;
        }
    }
    return null;
}

const sendValidateRequest = (sessionId) => {
    fetch("/utils/authenticate/main.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            type: "validate_session",
            sessionId: sessionId
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
            document.querySelector("#welcome-banner").innerHTML = "Hello, new user!"
            console.log("try again")
            break;
    
        default:
            document.querySelector("#welcome-banner").innerHTML = "Welcome back, user!"
            console.log("ready to use the website")
            break;
    }
}

const sessionId = getSessionIdCookie();

sendValidateRequest(sessionId)