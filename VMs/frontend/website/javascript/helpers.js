import { authenticate } from "./authenticate.js";
import { SESSION_ID_COOKIE_NAME, UTILS_PATH } from "./defaults.js";

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

// Function to fetch from php scripts in /common/utils/ 
export const fetchData = async (endpoint, data) => {
    try {
        const response = await fetch(UTILS_PATH + endpoint, {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        })
        .then(response => response.text())
        .then(response => JSON.parse(response))
        
        return response;
    } catch (error) {
        console.error("Error fetching data:", error);
        return { error: "Error fetching data" };
    }
};

export const getUserDetailsAndLibrary = async () => {
    try {
        const { userDetails, userLibraries } = await fetchData(
            "/authenticate/main.php",
            {
                type: "get_user",
                sessionId: getCookies(SESSION_ID_COOKIE_NAME)
            }
        );
        return [userDetails, userLibraries];
    } catch (error) {
        console.error("Error fetching user details and library:", error);
        return [null, null];
    }
}

export const arrayIncludesObject = (arr, obj) => {
    // Iterate through the array
    for (let item of arr) {
        // Convert objects to JSON strings for comparison
        const itemString = JSON.stringify(item);
        const objString = JSON.stringify(obj);
        // If the JSON strings match, return true
        if (itemString === objString) {
            return true;
        }
    }
    // If no match found, return false
    return false;
}