import { authenticate } from "./authenticate.js";
import { SESSION_ID_COOKIE_NAME } from "./defaults.js";
import { getCookies, setCookies } from "./helpers.js";


const addPath = (title, path, parent) => {
    const element = document.createElement("a")
    element.id = path.replace("/","")
    element.innerHTML = title
    element.href = path

    parent.appendChild(element)
}

document.addEventListener("DOMContentLoaded", () => {
    const nav = document.querySelector("nav")

    // Sets areas
    const left = document.createElement("div");
    left.setAttribute("id", "left");
    
    const center = document.createElement("div");
    center.setAttribute("id", "center");
    
    const right = document.createElement("div");
    right.setAttribute("id", "right");

    // Adds Set Paths
    addPath("BookQuest","/",left)
    
    addPath("Login","/login",right)
    addPath("Register","/register",right)
    
    // Add optional paths
    authenticate({ type: "get_user", sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
    .then((data) => {
        addPath("Search","/search",center)
        addPath("Library","/library",center)
        addPath("Log Out", "/logout", right)
        addPath("Recommendations", "/recommendations", center)
        document.querySelector("#logout").addEventListener("click", (e) => {  logOut(e) })
    })
    // Silences error
    .catch(() => {})

    // Add areas to nav bar
    nav.appendChild(left)
    nav.appendChild(center)
    nav.appendChild(right)
})

const logOut = (e) => {
    e.preventDefault()

    setCookies(SESSION_ID_COOKIE_NAME, "", new Date())
    location.reload()
}