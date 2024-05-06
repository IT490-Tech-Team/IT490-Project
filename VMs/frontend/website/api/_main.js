import { addLog } from "./log.js"

export const fetchData = async (data) => {
	const options = {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(data)
    };

    const response = await fetch("/client/main.php", options);
    const [ok, responseData] = await Promise.all([response.ok, response.json()]);

    if (!ok) {
	    // do not call addLog(). will create an endless loop
        throw new Error(responseData.message || "An error occurred while processing your request.");
    }

    return responseData;
};
