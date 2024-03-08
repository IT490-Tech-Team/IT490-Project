let form = document.getElementById("registration")

const sendRegisterRequest = (user, password) => {
    fetch("/utils/authenticate/main.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            type: "register",
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
            alert("Try Again.")
            break;
    
        default:
            let result = confirm("Registration Successful, would you like to login?");
            
            if (result){
                location.assign("/login")
            }

            break;
    }
}



form.addEventListener("submit", (event) => {
	event.preventDefault()

	const username = document.getElementById("username").value
	const password = document.getElementById("password").value

	sendRegisterRequest(username, password)
})