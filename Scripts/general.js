let loadComplete = false;

function loadHtmlContent(path, targetElementId, onLoaded) {
    loadComplete = false;

    fetch(path)
    .then(response => {
        if (response.ok) {
            return response.text();
        }
        
    })
    .then(html => {
        const targetElement = document.getElementById(targetElementId);
        if (targetElement) {
            targetElement.innerHTML = html;
            onLoaded();
        }
    })
}

function loadAsideData(id) {
    let jsonFile = 'Data/aside.json';

    fetch(jsonFile)
        .then(response => response.json()) // Return response.json() om het JSON-bestand te parsen
        .then(json => {
            fillAsideData(id, json);
        })
}

function fillAsideData(id, json) {
    let pages = json.pages;
    let items = pages.find(page => page.id === id).items;

    let html = items.map(listItem => {
        return `
            <li class="aside-content-list-item">
                <a href="#${listItem.elementId}">
                    <div class="aside-content-list-item-icon">
                        ${listItem.icon} 
                    </div>
                    <div class="aside-content-list-item-text">
                        ${listItem.caption}
                    </div>
                </a>
            </li>
        `;
    }).join('');

    document.getElementById('aside-content-list').innerHTML = html;
}

