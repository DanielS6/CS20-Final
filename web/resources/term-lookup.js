document.addEventListener( 'DOMContentLoaded', function () {
	// Code that runs after the DOM loads
	console.log( 'Page content is loaded' );

    const defDisplay = document.getElementById('er-def');
    const textInput = document.getElementById('er-text');
    const searchBtn = document.getElementById('er-search');
    const priorTermsBar = document.getElementById('er-search-history');

    const getCurrentSelection = () => {
        const selectedText = textInput.value.substring(
            textInput.selectionStart,
            textInput.selectionEnd
        );
        return (selectedText === '' ? false : selectedText);
    };
    const setDisplayUnknown = (term) => {
        defDisplay.innerText = 'Wikipedia had no article about: \'' + term + '\'';
    };
    const setDisplayKnown = (response) => {
        response.json().then(
            json => {
                console.log(json);
                defDisplay.innerHTML = json.extract_html;
            }
        );
    }
    const enWikiRest = 'https://en.wikipedia.org/api/rest_v1/page/summary/';
    const doLookup = (term) => {
        defDisplay.innerText = 'Loading...';
        // Special case for '..' because that means to go up a level
        if (term === '..') {
            setDisplayUnknown('..');
            return;
        }
        let encodedTerm = term.replace(' ', '_');
        encodedTerm = encodeURIComponent(encodedTerm);
        fetch(enWikiRest + encodedTerm)
            .then(
                response => {
                    if (response.ok === false) {
                        setDisplayUnknown(term);
                    } else {
                        setDisplayKnown(response);
                    }
                }
            )
    };

    let currentTerm = '';
    const maybeDoLookup = (term) => {
        if (term !== currentTerm) {
            currentTerm = term;
            addTermToHistory(term);
            doLookup(term);
        }
    };

    const addTermToHistory = (term) => {
        const wrapper = document.createElement('div');
        const link = document.createElement('a');
        link.innerText = term;
        link.addEventListener(
            'click',
            () => maybeDoLookup(term)
        );
        wrapper.append(link);
        priorTermsBar.prepend(wrapper);
    };

    searchBtn.addEventListener(
        'click',
        () => {
            const searchTerm = getCurrentSelection();
            if (searchTerm !== false) {
                maybeDoLookup(searchTerm);
            }
        }
    );
} );