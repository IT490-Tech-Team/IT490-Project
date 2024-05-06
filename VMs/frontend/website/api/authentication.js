import { fetchData } from "./_main.js"
import { addLog } from "./log.js"

const exchange = "authenticationExchange"
const queue = "authenticationQueue"

export const login = ({ username, password }) => {
	addLog('Info', 'Logging in '+username, "frontend/website/api/authentication.js");
    return fetchData({
        type: "login",
        exchange: exchange,
        queue: queue,
        username: username,
        password: password,
    })
    .then(data => {
        return {
            sessionId: data.sessionId,
            expired_at: data.expired_at
        }
    })
    .catch(error => {
        addLog('Error', 'Error logging in '+username+': '+error.message, "frontend/website/api/authentication.js");
        throw error;
    });
};

export const register = ({ username, password, email }) => {
	addLog('Info', 'Registering '+username, "frontend/website/api/authentication.js");
    return fetchData({
        type: "register",
        exchange: exchange,
        queue: queue,
        username: username,
        password: password,
        email: email,
    })
    .then(data => { return {}})
    .catch(error => {
        addLog('Error', 'Error registering '+username+': '+error.message, "frontend/website/api/authentication.js");
        throw error;
    });
}

export const validateSession = ({ sessionId }) => {
	addLog('Info', 'Validating '+username, "frontend/website/api/authentication.js");
    return fetchData({
        type: "validate_session",
        exchange: exchange,
        queue: queue,
        sessionId: sessionId
    })
    .then(data => { return {}})
    .catch(error => {
        addLog('Error', 'Error validating '+username+': '+error.message, "frontend/website/api/authentication.js");
        throw error;
    });
}

export const getUser = ({sessionId}) => {
    return fetchData({
        type: "get_user",
        exchange: exchange,
        queue: queue,
        sessionId: sessionId
    })
    .then(data => {
        return {
            userDetails: data.userDetails,
            userLibrary: data.userLibrary
        }
    })
    .catch(error => {
        addLog('Error', 'Error fetching user data with sessionId='+sessionId+': '+error.message, "frontend/website/api/authentication.js");
        throw error;
    });
}

export const getUserDetails = ({ sessionId }) => {
    return getUser({sessionId})
    .then(data => data.userDetails)
    .catch(error => {
        addLog('Error', 'Error getting user details with sessionId='+sessionId+': '+error.message, "frontend/website/api/authentication.js");
        throw error;
    });
}

export const getUserLibrary = ({ sessionId }) => {
    return getUser({sessionId})
    .then(data => data.userLibrary)
    .catch(error => {
        addLog('Error', 'Error getting user library with sessionId='+sessionId+': '+error.message, "frontend/website/api/authentication.js");
        throw error;
    });
}
