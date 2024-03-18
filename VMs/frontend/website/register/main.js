import { fetchData } from "/common/javascript/helpers.js";

const main = () => {
    let form = document.getElementById("registration")
    form.addEventListener("submit", (e) => {formSubmission(e)})
}

const formSubmission = (event) => {
    event.preventDefault()

    const form = event.target
    const data = Object.fromEntries(new FormData(form))
    data.type = event.target.getAttribute("data-auth")

    fetchData("/authenticate/main.php", data)
    .then(data => { success(data) })
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