import { SERVER_COOKIE_NAME, SESSION_ID_COOKIE_NAME, SERVERS } from "./defaults.js";
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

    // Add Server Changer
    const serverChangerLink = document.createElement("a")
    let currentServer = getCookies(SERVER_COOKIE_NAME)

    // If currentServer is undefined or null, it defaults to the last element in servers array
    if (!currentServer){
        currentServer = SERVERS[0]
    }
    serverChangerLink.textContent = currentServer
    serverChangerLink.href = ""
    
    serverChangerLink.addEventListener("click", () => {
        e.preventDefault()
        // It takes the index of currentServer, adds 1, then gets the value at this new index
        // If this is undefined (i.e. new index is too much), it's going to default to the first server on the list.

        const newServer = SERVERS[SERVERS.indexOf(currentServer) + 1] ?? SERVERS[0] 

        setCookies(SERVER_COOKIE_NAME, newServer)
    })
    
    right.appendChild(serverChangerLink)


    // Add areas to nav bar
    nav.appendChild(left)
    nav.appendChild(center)
    nav.appendChild(right)
})