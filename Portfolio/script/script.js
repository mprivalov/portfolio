const toggleButton = document.querySelector('.toggle-button')
const toolbar = document.querySelector('.toolbar')

toggleButton.addEventListener('click',() => {
    toolbar.classList.toggle('active')
});