export function getPics() {
    const imgs = document.querySelectorAll('.card-img-top');
    const fullPage = document.querySelector('#fullpage');
    console.log(imgs);

    imgs.forEach(img => {
        img.addEventListener('click', function() {
            fullPage.style.backgroundImage = 'url(' + img.src + ')';
            fullPage.style.display = 'block';
        });
    });
}
