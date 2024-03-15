import { authenticate }  from "/common/javascript/authenticate.js"

let form = document.getElementById("registration")

form.addEventListener("submit", (event) => {
	event.preventDefault()
    
    const data = Object.fromEntries(new FormData(form))
    data.type = event.target.getAttribute("data-auth")

    authenticate(data)
    .then(data => {
        let result = confirm("Registration Successful, would you like to login?");
            
        if (result){
            location.assign("/login")
        }
    })
    .catch(error => {
        console.log("ERROR??",error.message.split(":"))
        alert("Try Again.")
    })
})