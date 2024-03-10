import { setCookies } from "/common/javascript/helpers.js";
import { authenticate } from "/common/javascript/authenticate.js"
import { SESSION_ID_COOKIE_NAME } from "../common/javascript/defaults.js";

let form = document.getElementById("login")

form.addEventListener("submit", (event) => {
	event.preventDefault()

    const data = Object.fromEntries(new FormData(form))
    data.type = event.target.getAttribute("data-auth")

    authenticate(data)
    .then(data => {
        setCookies(SESSION_ID_COOKIE_NAME, data.sessionId, data.expired_at)
        let result = confirm("Login Successful, would you like to go to the website?");

        if (result){
            location.assign("/")
        }
    })
    .catch(error => {
        console.log("ERROR??",error.message.split(":"))
        alert("Try Again.")
    })
})

