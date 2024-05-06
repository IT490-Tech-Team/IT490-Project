import { fetchData } from "./_main.js"
import { addLog } from "./log.js"

export const getFilters = () => {
    return fetchData({
        type: "get_filters",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
    })
    .then(data => {return {genres: data.genres, languages: data.languages}})
    .catch(error => {
        addLog('Error', 'Error getting database filters : '+error.message, "/IT490-Project/VMs/frontend/website/api/search_db.js");
        throw error;
    });
}

export const getGenreFilters = () => {
    return getFilters()
    .then(data => data.genres)
    .catch(error => {
        addLog('Error', 'Error getting genre filters : '+error.message, "/IT490-Project/VMs/frontend/website/api/search_db.js");
        throw error;
    });
}

export const getLanguageFilters = () => {
    return getFilters()
    .then(data => data.languages)
    .catch(error => {
        addLog('Error', 'Error getting language filters : '+error.message, "/IT490-Project/VMs/frontend/website/api/search_db.js");
        throw error;
    });
}

export const searchDatabaseBooks = (data) => {
	addLog('Info', 'Searching database for books', "frontend/website/api/search_db.js");
    return fetchData({
        type: "search",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        ...data
    })
    .then(data => {
        console.log("HELLLLOOOOO")
        console.log(data)
        return data.books
    })
    .catch(error => {
        addLog('Error', 'Error searching database for books: '+error.message, "/IT490-Project/VMs/frontend/website/api/search_db.js");
        console.log(error)
        throw error;
    });
}

export const addBookToUserLibrary = ({book_id, user_id}) => {
	addLog('Info', 'Adding book to lirary where user_id='+user_id+' and book_id='+book_id, "/IT490-Project/VMs/frontend/website/api/search_db.js");
    return fetchData({
        type: "add_to_library",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        user_id: user_id, 
        book_id: book_id 
    })
    .then(data => { return {}})
    .catch(error => {
        addLog('Error', 'Error adding book to library where bookId='+bookId+' and userId='+userId+': '+error.message, "frontend/website/api/search_db.js");
        console.log(error)
        throw error;
    });
}
 
export const addBooksToDatabase = ({books}) => {
	addLog('Info', 'Adding '+Object.keys(books).length+' books to database', "frontend/website/api/search_db.js");
    return fetchData({
        type: "add",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        books: JSON.stringify(books)
    })
    .then(data => data.books)
    .catch(error => {
        addLog('Error', 'Error adding '+Object.keys(books).length+' books to database: '+error.message, "frontend/website/api/search_db.js");
        console.log(error)
        throw error;
    });
}

export const getBooks = ({bookIds}) => {
    return fetchData({
        type: "get_books",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        book_ids: JSON.stringify(bookIds)
    })
    .then(data => data.books)
    .catch(error => {
        addLog('Error', 'Error retrieving books from database: '+error.message, "frontend/website/api/search_db.js");
        console.log(error)
        throw error;
    });
}

export const removeBookFromUserLibrary = ({book_id, user_id}) => {
	addLog('Info', 'Removing book from lirary where user_id='+user_id+' and book_id='+book_id, "frontend/website/api/search_db.js");
    return fetchData({
        type: "remove_from_library",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        user_id: user_id, 
        book_id: book_id 
    })
    .then(data => { return })
    .catch(error => {
        addLog('Error', 'Error removing books from database: '+error.message, "frontend/website/api/search_db.js");
        console.log(error)
        throw error;
    });
}
