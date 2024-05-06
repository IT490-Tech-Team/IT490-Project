import { bookPopUp } from "/javascript/bookPopup.js"
import { addBookToUserLibrary, removeBookFromUserLibrary } from "/api/search_db.js"


export const bookToTableRow = (book, userDetails, userLibrary) => {
    // Arrays to coordinate the column with the data object
    const columnNames = ["cover", "title", "authors"]
    const bookKeys = ["cover_image_url", "title", "authors"]

    const row = document.createElement("tr")

    row.addEventListener("click", async () => {
        const bookPopUpElement = bookPopUp(book, userDetails, userLibrary)
        document.querySelector("body").appendChild(bookPopUpElement)
    })

    // iterates through name and index
    // uses index to get the corresponding book data
    columnNames.forEach((name, index) => {
        const data = book[bookKeys[index]]

        row.appendChild(
            bookDataToTableCell(name, data, book, userDetails)
        )
    })

	const userLibraryBookIds = userLibrary.map(library => library.book_id)

    console.log(userLibrary, userLibraryBookIds)
    // If book no in library, create a "add to library" button
    // if (userLibrary && !userLibraryBookIds.includes(book.id)){
    //     row.appendChild(
    //         bookDataToTableCell("add button", null, book, userDetails)
    //     )
    // }
    // else {
    //     row.appendChild(
    //         bookDataToTableCell("remove button", null, book, userDetails)
    //     )
    // }

    return row
}

const bookDataToTableCell = (name, data, bookData, userData) => {

    const cell = document.createElement("td")
    cell.className = name

    switch (name) {
        case "add button":
            const addToLibraryButton = document.createElement("button");
            addToLibraryButton.textContent = "Add To Library";
            
            addToLibraryButton.addEventListener("click", () => { addToLibrary(cell, bookData.id, userData.id)})

            cell.appendChild(addToLibraryButton);
            break
        case "remove button":
            const removeFromLibraryButton = document.createElement("button")
            removeFromLibraryButton.textContent = "Remove From Library"

            removeFromLibraryButton.addEventListener("click", () => { removeFromLibrary(cell, bookData.id, userData.id) })
            
            cell.appendChild(removeFromLibraryButton)
            break
        case "cover":
            const coverElement = document.createElement("img")
            coverElement.src = data

            cell.appendChild(coverElement)
            break;

        case "genres":
        case "authors":
            if (typeof data === "string") {
                data = JSON.parse(data)
            }
            
            if (data) {
                // Join the array with and (i.e. A and B )
                cell.textContent = data.join(" and ")
            }
            break;
        default:
            cell.textContent = data
            break;
    }
    
    return cell
}

const addToLibrary = async (parentElement, book_id, user_id) => {
    addBookToUserLibrary({book_id, user_id})
    .then((data) => {
        parentElement.innerHTML = "";
    })
}

const removeFromLibrary = async (parentElement, book_id, user_id) => {
    removeBookFromUserLibrary({book_id, user_id})
    .then((data) => {
        console.log(parentElement)
        parentElement.innerHTML = ""
    })
}