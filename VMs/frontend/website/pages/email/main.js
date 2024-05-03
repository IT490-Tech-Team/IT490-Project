import { getUserDetails } from "/api/authentication.js"
import { emailSignUp } from "/api/email_signup.js"
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js"
import { getCookies } from "/javascript/helpers.js"

const main = async () => {
const form = document.querySelector("form")

form.addEventListener("submit", async (event) => {
    event.preventDefault()

    const userDetails = await getUser({
        sessionId: getCookies(SESSION_ID_COOKIE_NAME)
    })

    const form = event.target
    const data = Object.fromEntries(new FormData(form))
    data.user_email = userDetails.email

    console.log(data)
    
    emailSignUp(data)
    .then(data => {
        console.log(data)
    })
})
}
main()