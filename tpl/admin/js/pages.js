document.addEventListener('DOMContentLoaded', function (){
    renderPagesTable();
    // renderPageForm();
});

function renderPagesTable(){
    let xhr = new XMLHttpRequest();

    while (tinymce.editors.length > 0) {
        tinymce.remove(tinymce.editors[0]);
    }

    $('.content').html(`
        <div class="w-100 d-flex justify-content-center">
            <div class="spinner-border text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    `);

    xhr.open('GET', '/api/pages/?action=getPages');
    xhr.send();
    xhr.onloadend = function(){
        console.log('Получение списка страниц', xhr);
        let pages = JSON.parse(xhr.responseText);

        let html = `
            <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th scope="col">Имя</th>
                    <th scope="col">Ссылка</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Ключевые слова</th>
                    <td><a href="#" onclick="event.preventDefault();renderPageForm();">создать</a></td>
                </tr>
            </thead>
            <tbody>
        `;

        for (page of pages){
            html += `
                <tr>
                    <td>${page.name}</td>
                    <td><a href="${page.url}">${page.url}</a></td>
                    <td>${page.description}</td>
                    <td>${page.keywords}</td>
                    <td><a href="" onclick="event.preventDefault();renderPageForm(${page.id})">ред.</a></td>
                </tr>
            `;
        }

        html += `</tbody></table>`;
        $('.content').html(html);
        console.log('Массив страниц', pages);
    }
}

function renderPageForm(id = ''){
    let xhr = new XMLHttpRequest();

    if(id){
        $('.content').html(`
        <div class="w-100 d-flex justify-content-center">
            <div class="spinner-border text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    `);

        xhr.open('GET', `/api/pages?action=getPageVariables&id=${id}`);
        xhr.send();
        xhr.onloadend = function (){
            console.log('Получение значений страницы', xhr);
            let page = JSON.parse(xhr.responseText);
            console.log('Значения страницы', page);
            $('.content').html(pageForm(page));
            tinymce.init({
                selector: '#editor',
                width: '100%',
                height: '1000px',
                resize: false,
                plugins: "image,paste",
                paste_data_images: true
            });
        }
    }else{
        $('.content').html(pageForm());
        tinymce.init({
            selector: '#editor',
            width: '100%',
            height: '1000px',
            resize: false,
            plugins: "image,paste",
            paste_data_images: true
        });
    }

}

function pageForm(page = {}){
    let html = `
        <h6 class="d-flex justify-content-between">${page.id ? 'Редактирование страницы №'+page.id : 'Новая страница'} <div><button class="btn btn-primary btn-sm" onclick="renderPagesTable();">Назад</button>&nbsp;<button class="btn btn-success btn-sm" onclick="pageFormSave(${page.id ? page.id : ''})">Сохранить</button></div></h6>
        <br>
        <div class="form-group row">
            <div class="col-4"><label>Имя страницы</label>
                <input class="form-control form-control-sm" oninput="onchangeNameTranslit()" name="name" value="${page.name ? page.name : ''}">
                <small class="form-text text-muted">Пример: "Главная", "О нас" и.т.д</small>
            </div>
            <div class="col-4">
                <label>Заголовок</label>
                <input class="form-control form-control-sm" name="title" value="${page.title ? page.title : ''}">
            </div>
            <div class="col-4">
                <label>Ссылка</label>
                <input class="form-control form-control-sm" name="url" readonly value="${page.url ? page.url : ''}">
            </div>
            
        </div>
        <div class="form-group">
            <label>Описание</label>
            <input class="form-control form-control-sm" name="description" value="${page.description ? page.description : ''}">
        </div>
        <div class="form-group">
            <label>Теги</label>
            <input class="form-control form-control-sm" name="keywords" value="${page.keywords ? page.keywords : ''}">
        </div>
        <div class="form-group">
            <label>Содержание</label>
            <span id="editor">${page.content ? page.content : ''}</span>
        </div>
    `;
    return html;
}

function pageFormSave(id = ''){
    let xhr = new XMLHttpRequest();
    let form = new FormData();

    $('.content [name="name"]').attr('disabled', true);
    $('.content [name="title"]').attr('disabled', true);
    $('.content [name="url"]').attr('disabled', true);
    $('.content [name="description"]').attr('disabled', true);
    $('.content [name="keywords"]').attr('disabled', true);
    tinymce.activeEditor.mode.set("readonly");



    form.append('id', id);
    form.append('name', $('.content [name="name"]').val());
    form.append('title', $('.content [name="title"]').val());
    form.append('url', $('.content [name="url"]').val());
    form.append('description', $('.content [name="description"]').val());
    form.append('keywords', $('.content [name="keywords"]').val());
    form.append('content', tinymce.activeEditor.getContent());

    xhr.open('POST', '/api/pages/?action=setPageVariables');
    xhr.send(form);
    xhr.onloadend = function (){
        console.log('Сохранение значений страницы', xhr);
        renderPagesTable();
        tinymce.activeEditor.destroy();
    }
}

function onchangeNameTranslit(){
    let xhr = new XMLHttpRequest();
    let form = new FormData();
    let text = $('.content [name="name"]').val();

    form.append('text', text);

    xhr.open('POST', '/api/getTranslitUrl/');
    xhr.send(form);
    xhr.onloadend = function (){
        $('.content [name="url"]').val(xhr.responseText);

    }
    $('.content [name="title"]').val(text);
}