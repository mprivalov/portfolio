const toggleButton = document.querySelector('.toggle-button')
const toolbar = document.querySelector('.toolbar')

toggleButton.addEventListener('click',() => {
    toolbar.classList.toggle('active')
});

const homeButton = document.querySelector('.home-button')

window.addEventListener('scroll', () => {
    if(this.scrollY > 200){
        homeButton.style.display = 'flex';
    } else {
        homeButton.style.display = 'none';
    }
});