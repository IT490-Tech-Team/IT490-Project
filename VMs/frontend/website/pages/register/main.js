import { register } from "/api/authentication.js"


function isValidEmail(email) {
    const emailR = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return emailR.test(email);
}


const main = () => {
    let form = document.getElementById("registration")
    form.addEventListener("submit", (e) => {formSubmission(e)})
}

const formSubmission = (event) => {
    event.preventDefault()

    const form = event.target
    const data = Object.fromEntries(new FormData(form))
    const email = data.email; // Assuming email is a field in your form
    
    if (!isValidEmail(email)) {
        alert("Please enter a valid email address.");
    } else {
        register(data)
        .then(response => { success(response) })
        .catch(error => { failure(error) })
}
}



const success = (data) => {
    let result = confirm("Registration Successful, would you like to login?");
    console.log(data)
    if (result){
        location.assign("/login")
    }
}

const failure = (error) => {
    console.log("ERROR??",error.message.split(":"))
    alert("Try Again.")
}

main()