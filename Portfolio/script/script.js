const toggleButton = document.querySelector('.toggle-button')
const toolbar = document.querySelector('.toolbar')

toggleButton.addEventListener('click',() => {
    toolbar.classList.toggle('active')
});

const homeButton = document.querySelector('.home-button')

window.addEventListener('scroll', () => {
    if(window.scrollY > 700){
        homeButton.style.display = 'block';
    } else {
        homeButton.style.display = 'none';
    }
});