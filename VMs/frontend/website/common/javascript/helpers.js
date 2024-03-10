import { authenticate } from "./authenticate.js";
import { SESSION_ID_COOKIE_NAME } from "./defaults.js";

export const getCookies = (cookieName) => {
    const cookies = document.cookie.split('; ');
    for (const cookie of cookies) {
        const [name, value] = cookie.split('=');
        if (name === cookieName) {
            return value;
        }
    }
    return null;
}

export const setCookies = (cookieName, cookieValue, expirationDate) => {
    const expiryDate = new Date(expirationDate);
    const cookieString = `${cookieName}=${cookieValue};expires=${expiryDate.toUTCString()};path=/`;
    
    document.cookie = cookieString;
}

export const validateSession = () => {
    let data = {}

    data.type = "validate_session"
    data.sessionId = getCookies(SESSION_ID_COOKIE_NAME)

    return authenticate(data)
}