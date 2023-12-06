const toggleButton = document.getElementsByClassName('toggle-button')[0]
const toolbar = document.getElementsByClassName('toolbar')[0]

toggleButton.addEventListener('click',() => {
    toolbar.classList.toggle('active')
});

toolbar.addEventListener('click',() => {
    toolbar.classList.tool('hide')
})