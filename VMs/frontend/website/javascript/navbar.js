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

const addServerChanger = (container) => {
    let currentServer = getCookies(SERVER_COOKIE_NAME)
    
    // If the server cookie is set, then it's safe to show the user the cookie changer.
    // If it isn't set, then they don't need to change servers
    if (currentServer){
        const serverChangerLink = document.createElement("a")
        serverChangerLink.textContent = currentServer
        serverChangerLink.href = ""
        serverChangerLink.addEventListener("click", (e) => {
            e.preventDefault()
            // It takes the index of currentServer, adds 1, then gets the value at this new index
            // If this is undefined (i.e. new index is too much), it's going to default to the first server on the list.
            const newServer = SERVERS[SERVERS.indexOf(currentServer) + 1] ?? SERVERS[0] 
    
            setCookies(SERVER_COOKIE_NAME, newServer)
        })
        
        container.appendChild(serverChangerLink)
    }

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
        addPath("Search","/search",center)
        addPath("Library","/library",center)
        addPath("Recommendations", "/recommendations", center)
        addPath("Email", "/email", center)

        const logout = addPath("Log Out", "/logout", right)
        logout.addEventListener("click", (e) => {  logOut(e) })
    }

    addServerChanger(right)

    // Add areas to nav bar
    nav.appendChild(left)
    nav.appendChild(center)
    nav.appendChild(right)
})