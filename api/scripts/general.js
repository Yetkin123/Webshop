let loadComplete = false;

function loadHtmlContent(path, targetElementId, onLoaded) {
    loadComplete = false;

    console.log('[loadHtmlContent] fetching:', path);
    fetch(path, { cache: 'no-store' })
        .then(response => {
            console.log('[loadHtmlContent] response for', path, 'status=', response.status);
            if (!response.ok) throw new Error(`Failed to fetch ${path}: ${response.status}`);
            return response.text();
        })
        .then(html => {
            const targetElement = document.getElementById(targetElementId);
            if (!targetElement) {
                console.warn('Target element not found:', targetElementId);
                return;
            }

            // inject HTML
            targetElement.innerHTML = html;

            // execute inline and external scripts found in the injected HTML
            const scripts = Array.from(targetElement.querySelectorAll('script'));
            const promises = scripts.map(oldScript => {
                return new Promise(resolve => {
                    const newScript = document.createElement('script');
                    // copy attributes
                    for (let i = 0; i < oldScript.attributes.length; i++) {
                        const attr = oldScript.attributes[i];
                        newScript.setAttribute(attr.name, attr.value);
                    }

                    if (oldScript.src) {
                        newScript.src = oldScript.src;
                        newScript.onload = () => resolve();
                        newScript.onerror = () => {
                            console.error('Failed to load script', oldScript.src);
                            resolve(); // resolve so a broken script doesn't block everything
                        };
                        document.head.appendChild(newScript);
                    } else {
                        newScript.textContent = oldScript.textContent;
                        document.head.appendChild(newScript);
                        resolve();
                    }
                });
            });

            Promise.all(promises).then(() => {
                loadComplete = true;
                if (typeof onLoaded === 'function') {
                    try { onLoaded(); } catch (e) { console.error('onLoaded error', e); }
                }
            });
        })
        .catch(err => {
            console.error('[loadHtmlContent] error fetching', path, err);
        });
}

function loadAsideData(id) {
    let jsonFile = '/api/data/aside.json';

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

