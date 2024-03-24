import { fetchData } from "./_main.js"

export const login = ({ username, password }) => {
    return fetchData({
        type: "login",
        exchange: "authenticationExchange",
        queue: "authenticationQueue",
        username: username,
        password: password,
    });
};

export const register = ({ username, password, email }) => {
    return fetchData({
        type: "register",
        exchange: "authenticationExchange",
        queue: "authenticationQueue",
        username: username,
        password: password,
        email: email,
    });
}

export const validateSession = ({ sessionId }) => {
    return fetchData({
        type: "validate_session",
        exchange: "authenticationExchange",
        queue: "authenticationQueue",
        sessionId: sessionId
    });
}

export const getUser = ({sessionId}) => {
    return fetchData({
        type: "get_user",
        exchange: "authenticationExchange",
        queue: "authenticationQueue",
        sessionId: sessionId
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