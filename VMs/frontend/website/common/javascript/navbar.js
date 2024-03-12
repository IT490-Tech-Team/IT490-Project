

const addPath = (title, path, parent) => {
    const element = document.createElement("a")
    element.innerHTML = title
    element.href = path

    parent.appendChild(element)
}

document.addEventListener("DOMContentLoaded", () => {
    const nav = document.querySelector("nav")

    const left = document.createElement("div");
    left.setAttribute("id", "left");
    
    const center = document.createElement("div");
    center.setAttribute("id", "center");
    
    const right = document.createElement("div");
    right.setAttribute("id", "right");

    addPath("BookQuest","/",left)
    addPath("Search","/search",center)
    addPath("Login","/login",right)
    addPath("Register","/register",right)
    
    nav.appendChild(left)
    nav.appendChild(center)
    nav.appendChild(right)
})