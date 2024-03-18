import { setCookies, fetchData } from "/common/javascript/helpers.js";
import { SESSION_ID_COOKIE_NAME } from "../common/javascript/defaults.js";

const main = () => {
    let form = document.getElementById("login")
    form.addEventListener("submit", (e) => {formSubmission(e)})
} 

const formSubmission = (event) => {
    event.preventDefault()

    // Formats data for rabbitmq
    const form = event.target
    const data = Object.fromEntries(new FormData(form))
    data.type = event.target.getAttribute("data-auth")

    fetchData("/authenticate/main.php", data)
    .then(data => { success(data) })
    .catch(error => { failure(error) })
}

const success = (data) => {
    setCookies(SESSION_ID_COOKIE_NAME, data.sessionId, data.expired_at)
    let result = confirm("Login Successful, would you like to go to the website?");

    if (result){
        location.assign("/")
    }
}

const failure = (error) => {
    console.log(error)
    alert("Try Again.")
}

main()