import { fetchData } from "./_main.js"

const exchange = "authenticationExchange"
const queue = "authenticationQueue"

export const login = ({ username, password }) => {
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
    });
};

export const register = ({ username, password, email }) => {
    return fetchData({
        type: "register",
        exchange: exchange,
        queue: queue,
        username: username,
        password: password,
        email: email,
    })
    .then(data => { return {}});
}

export const validateSession = ({ sessionId }) => {
    return fetchData({
        type: "validate_session",
        exchange: exchange,
        queue: queue,
        sessionId: sessionId
    })
    .then(data => { return {}});
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
}

export const getUserDetails = ({ sessionId }) => {
    return getUser({sessionId})
    .then(data => data.userDetails)
}

export const getUserLibrary = ({ sessionId }) => {
    return getUser({sessionId})
    .then(data => data.userLibrary)
}