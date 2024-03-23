export const fetchData = async (data) => {
    const options = {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(data)
    };

    const response = await fetch("/RabbitMQClient/main.php", options);
    const [ok, responseData] = await Promise.all([response.ok, response.json()]);

    if (!ok) {
        throw new Error(responseData.message || "An error occurred while processing your request.");
    }

    return responseData;
};

export const login = ({ username, password }) => {
    return fetchData({
        type: "login",
        exchange: "authenticationExchange",
        queue: "authenticationQueue",
        username: username,
        password: password,
    });
};

export const register = ({username, password, email}) => {
        return fetchData({
        type: "register",
        exchange: "authenticationExchange",
        queue: "authenticationQueue",
        username: username, 
        password: password, 
        email: email, 
    });
}

export const validateSession = ({sessionId}) => {
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
    });
}
