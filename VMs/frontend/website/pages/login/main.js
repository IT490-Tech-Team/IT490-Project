import { getCookies, setCookies } from "/javascript/helpers.js";
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js";
import { login, validateSession } from "/api/authentication.js"

const main = () => {
    let form = document.getElementById("login")
    form.addEventListener("submit", (e) => {formSubmission(e)})
} 

const formSubmission = (event) => {
    event.preventDefault()

    // Formats data for rabbitmq
    const form = event.target
    const data = Object.fromEntries(new FormData(form))
    login(data)
    .then(response => success(response))
    .catch(response => failure(response))
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