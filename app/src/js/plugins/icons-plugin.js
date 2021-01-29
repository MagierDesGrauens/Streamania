let elems = document.querySelectorAll('[class*="bi-"]');

elems.forEach(elem => {
    let iconClass = '',
        otherClasses = [];

    elem.classList.forEach(elClass => {
        if (elClass.startsWith('bi-')) {
            iconClass = elClass.substr(3, elClass.length - 3);
        } else {
            otherClasses.push(elClass);
        }
    });

    $(elem).replaceWith(`<svg
        class="bi svg ${otherClasses.join(' ')}"
        fill="currentColor"
    >
        <use xlink:href="svg/bootstrap-icons.svg#${iconClass}"/>
    </svg>`);
});
