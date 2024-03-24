import { getFilters } from "/javascript/api.js"

const main = async () => {
	console.log("BRO")
	const form = document.querySelector("form")
	const tableBody = document.querySelector("tbody")

	// Fetch filters and populate the form select elements for genre and language async
	getFilters()
	.then(data => {
		const genres = data.genres
		const languages = data.languages

		const genreSelection = document.querySelector("select#genre")
		const languageSelection = document.querySelector("select#language")

		setSelection(genreSelection, genres)
		setSelection(languageSelection, languages)
	})
}

// Function to populate a select element with given entries array
const setSelection = (selectElement, entries) => {
    entries.forEach(entry => {
        const option = document.createElement("option")

        option.value = entry
        option.textContent = entry

        selectElement.appendChild(option)
    });
}

main()