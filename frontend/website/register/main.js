let form = document.getElementById("registration")

const sendRegisterRequest = (user, password) => {
    fetch("./register.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            username: user,
            password: password
        })
    })
    .then((response) => {
        response.text().then((response2) => { console.log("HI", response2)})
    })
    .catch(err => {
        console.log("ERROR?", err)
    })
}

form.addEventListener("submit", (event) => {
	event.preventDefault()

	const username = document.getElementById("username").value
	const password = document.getElementById("password").value

	sendRegisterRequest(username, password)
})