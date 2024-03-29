import { SESSION_ID_COOKIE_NAME } from "./defaults.js";
import { getCookies, setCookies } from "./helpers.js";

const main = () => {
    
}

const addPath = (title, path, parent) => {
    const element = document.createElement("a")
    element.id = path.replace("/","")
    element.innerHTML = title
    element.href = path

    parent.appendChild(element)
    return element
}

const logOut = (e) => {
    e.preventDefault()

    setCookies(SESSION_ID_COOKIE_NAME, "", new Date())
    location.reload()
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
    
    if(getCookies(SESSION_ID_COOKIE_NAME)){
        const search = addPath("Search","/search",center)
        const library = addPath("Library","/library",center)
        const recommendations = addPath("Recommendations", "/recommendations", center)
        const email = addPath("Email", "/email", center)


        const logout = addPath("Log Out", "/logout", right)
        logout.addEventListener("click", (e) => {  logOut(e) })
    }

    // Add areas to nav bar
    nav.appendChild(left)
    nav.appendChild(center)
    nav.appendChild(right)
})