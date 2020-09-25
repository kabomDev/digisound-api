document.querySelectorAll('button[data-action="delete"]').forEach(function (button) {
    button.addEventListener('click', function () {
        this.parentNode.remove();
    })
});

document.querySelector('#add-artist').addEventListener('click', function () {
    const prototype = document.querySelector('#event_artists').getAttribute("data-prototype");

    const index = document.querySelectorAll('button[data-action="delete"]').length;

    const html = prototype.replace(/__name__/g, index);

    document.querySelector('#event_artists').insertAdjacentHTML('beforeend', html);

    const button = document.querySelector('#event_artists > div:last-child button');

    button.addEventListener('click', function () {
        this.parentNode.remove();
    })

});