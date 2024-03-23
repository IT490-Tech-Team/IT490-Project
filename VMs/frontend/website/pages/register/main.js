import { register } from "../../javascript/api.js"

const main = () => {
    let form = document.getElementById("registration")
    form.addEventListener("submit", (e) => {formSubmission(e)})
}

const formSubmission = (event) => {
    event.preventDefault()

    const form = event.target
    const data = Object.fromEntries(new FormData(form))

    register(data)
    .then(response => { success(response) })
    .catch(error => { failure(error) })
}

const success = (data) => {
    let result = confirm("Registration Successful, would you like to login?");
            
    if (result){
        location.assign("/login")
    }
}

const failure = (error) => {
    console.log("ERROR??",error.message.split(":"))
    alert("Try Again.")
}

main()