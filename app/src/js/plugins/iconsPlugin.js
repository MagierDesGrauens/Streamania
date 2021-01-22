let elems = document.querySelectorAll('[class^="bi-"]');

elems.forEach(elem => {
    elem.classList.forEach(classes => {
        if (classes.startsWith('bi-')) {
            iconClass = classes.split('-')[1];
        }
    });
    $(elem).replaceWith(`
    <svg class="bi" width="32" height="32" fill="currentColor">
        <use xlink:href="svg/bootstrap-icons.svg#${iconClass}"/>
    </svg>
    `);
});
