const wrapper=document.querySelector('.wrapper');
const loginLink=document.querySelector('.login-link');
const registerLink=document.querySelector('.register-link');

registerLink.addEventListener('click', ()=> {
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', ()=> {
    wrapper.classList.remove('active');
});


const TermsChkBox = document.getElementById("Terms");
const RegisterBtn = document.getElementById("RegBtn");

TermsChkBox.addEventListener
("change", 
    function () 
    {
        RegisterBtn.disabled = !this.checked; 
        // Enable if Terms & Conditions CheckBox checked || Disable if Not
    }
);