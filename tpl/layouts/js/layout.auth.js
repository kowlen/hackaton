function auth(){
    let xhr = new XMLHttpRequest();
    let form = new FormData();
    let login = document.querySelector('#login');
    let password = document.querySelector('#password');
    let errorWrap = document.querySelector('#error-wrap');

    form.append('login', login.value);
    form.append('password', password.value);

    xhr.open('POST', '/access/authorization/');
    xhr.send(form);
    xhr.onloadend = function (){
        console.log('Авторизация',xhr.responseText)
        if(xhr.responseText === 'ok'){
            location.href = '/admin/pages/';
        }else{
            errorWrap.innerText = xhr.responseText;
        }
    }
}