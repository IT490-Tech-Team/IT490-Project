import { getUserDetailsAndLibrary } from "../common/javascript/helpers.js";
import { fetchData } from "/common/javascript/helpers.js";

const form = document.querySelector("form")

form.addEventListener("submit", async (event) => {
    event.preventDefault()

    const [userDetails, userLibrary] = await getUserDetailsAndLibrary()

    const form = event.target
    const data = Object.fromEntries(new FormData(form))
    data.type = "add_update"
    data.user_email = userDetails.email
    
    fetchData("/email/main.php", data)
    .then((data) => {
        console.log(data)
    })
})