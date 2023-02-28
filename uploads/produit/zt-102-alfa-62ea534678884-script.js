let showPassword = document.querySelector('#showPassword');

showPassword.addEventListener("mousedown", () => {
    let inputPass = document.querySelector("#password");

    if (inputPass.getAttribute('type') === 'password') {
        inputPass.setAttribute('type', "text");
        this.setAttribute('class', 'fas fa-eye text-primary');
    }
})

showPassword.addEventListener("mouseup", () => {
    let inputPass = document.querySelector("#password");

    if (inputPass.getAttribute('type') === 'text') {
        inputPass.setAttribute('type', "password");
        this.setAttribute('class', 'fas fa-eye-slash text-primary');
    }
})