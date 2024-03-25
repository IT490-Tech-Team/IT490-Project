import { fetchData } from "./_main.js"

export const getFilters = () => {
    return fetchData({
        type: "get_filters",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
    })
    .then(data => {return {genres: data.genres, languages: data.languages}})
}

export const getGenreFilters = () => {
    return getFilters()
    .then(data => data.genres)
}

export const getLanguageFilters = () => {
    return getFilters()
    .then(data => data.languages)
}

export const searchDatabaseBooks = (data) => {
    return fetchData({
        type: "search",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        ...data
    })
    .then(data => data.books)
}

export const addBookToUserLibrary = ({book_id, user_id}) => {
    return fetchData({
        type: "add_to_library",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        user_id: user_id, 
        book_id: book_id 
    })
}

export const addBooksToDatabase = ({books}) => {
    return fetchData({
        type: "add",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        books: JSON.stringify(books)
    })
    .then(data => data.books)
}

export const getBooks = ({bookIds}) => {
    return fetchData({
        type: "get_books",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        book_ids: JSON.stringify(bookIds)
    })
    .then(data => data.books)
}

export const removeBookFromUserLibrary = ({book_id, user_id}) => {
    return fetchData({
        type: "remove_from_library",
        exchange: "searchDatabaseExchange",
        queue: "searchDatabaseQueue",
        user_id: user_id, 
        book_id: book_id 
    })
}