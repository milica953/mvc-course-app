document.addEventListener('DOMContentLoaded', () => {
const submenu = document.querySelector('.submenu');
const mainLink = submenu.querySelector('a');


mainLink.addEventListener('click', (e) => {
e.preventDefault();
submenu.classList.toggle('open');
});
});