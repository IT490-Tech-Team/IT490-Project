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