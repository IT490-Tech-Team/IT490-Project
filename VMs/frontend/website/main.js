import { validateSession } from "/common/javascript/helpers.js"

document.addEventListener("DOMContentLoaded", () => {
    validateSession()
    .then((data) => {
        document.querySelector("#welcome-banner").innerHTML = "Welcome back, user!"
    })
    .catch((error) => {
        document.querySelector("#welcome-banner").innerHTML = "Hello, new user!"
    })
})
