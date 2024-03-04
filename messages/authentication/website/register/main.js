let form = document.getElementById("registration")

const sendRegisterRequest = (user, password) => {
    var request = new XMLHttpRequest();
    request.open("POST", "./register.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Send the request with form data
    request.send("username=" + encodeURIComponent(user) + "&password=" + encodeURIComponent(password));

    // Optional: Define a callback function to handle the response
    request.onload = function() {
        if (request.status >= 200 && request.status < 300) {
            // Success response
            console.log(request.responseText);
            // You can handle the response here (e.g., show success message)
        } else {
            // Error response
            console.error('Request failed with status:', request.status);
            // You can handle errors here (e.g., show error message)
        }
    };
}

form.addEventListener("submit", (event) => {
	event.preventDefault()

	const username = document.getElementById("username").value
	const password = document.getElementById("password").value

	sendRegisterRequest(username, password)
})