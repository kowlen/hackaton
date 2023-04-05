document.addEventListener('DOMContentLoaded', function (){
    renderTable();
});

function renderTable(){
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

    xhr.open('GET', '/api/navigation/?action=getList');
    xhr.send();
    xhr.onloadend = function(){
        console.log('Получение списка элементов навигации', xhr);
        let navigations = JSON.parse(xhr.responseText);

        let html = `
            <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th scope="col">Имя</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Ссылка</th>
                    <th scope="col">Ссылка на иконку</th>
                    <td><a href="#" onclick="event.preventDefault();renderForm();">создать</a></td>
                </tr>
            </thead>
            <tbody>
        `;

        for (item of navigations){
            html += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.type == 1 ? 'Верхнее меню': item.type == 2 ? 'Нижнее меню' : '(не заполнено)'}</td>
                    <td><a href="${item.url}">${item.url}</a></td>
                    <td>${item.icon}</td>
                    <td><a href="" onclick="event.preventDefault();renderForm(${item.id})">ред.</a></td>
                </tr>
            `;
        }

        html += `</tbody></table>`;
        $('.content').html(html);
        console.log('Массив элементов', navigations);
    }
}

function renderForm(id = ''){
    let xhr = new XMLHttpRequest();

    if(id){
        $('.content').html(`
        <div class="w-100 d-flex justify-content-center">
            <div class="spinner-border text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    `);

        xhr.open('GET', `/api/navigation?action=getVariables&id=${id}`);
        xhr.send();
        xhr.onloadend = function (){
            console.log('Получение значений элемента навигации', xhr);
            let item = JSON.parse(xhr.responseText);
            console.log('Значения элемента', item);
            $('.content').html(form(item));
        }
    }else{
        $('.content').html(form());

    }

}

function form(item = {}){
    let html = `
        <h6 class="d-flex justify-content-between">${item.id ? 'Редактирование пункта №'+item.id : 'Новый пункт'} <div><button class="btn btn-primary btn-sm" onclick="renderTable();">Назад</button>&nbsp;<button class="btn btn-success btn-sm" onclick="formSave(${item.id ? item.id : ''})">Сохранить</button></div></h6>
        <br>
        <div class="form-group row">
            <div class="col-4"><label>Имя страницы</label>
                <input class="form-control form-control-sm" name="name" value="${item.name ? item.name : ''}">
            </div>
            <div class="col-4">
                <label>Ссылка</label>
                <input class="form-control form-control-sm" name="url" value="${item.url ? item.url : ''}">
            </div>
            <div class="col-4">
                <label>Ссылка на иконку</label>
                <input class="form-control form-control-sm" name="icon" value="${item.icon ? item.icon : ''}">
            </div>
            <div class="col-4">
                <label>Тип</label>
                <select class="form-control form-control-sm" name="type">
                    <option value="1" ${item.type == 1 ? 'selected' : ''}>Верхнее меню</option>
                    <option value="2" ${item.type == 2 ? 'selected' : ''}>Нижнее меню</option>
                </select>
            </div>
        </div>
    `;
    return html;
}

function formSave(id = ''){
    let xhr = new XMLHttpRequest();
    let form = new FormData();

    $('.content [name="name"]').attr('disabled', true);
    $('.content [name="url"]').attr('disabled', true);
    $('.content [name="icon"]').attr('disabled', true);
    $('.content [name="type"]').attr('disabled', true);

    form.append('id', id);
    form.append('name', $('.content [name="name"]').val());
    form.append('url', $('.content [name="url"]').val());
    form.append('icon', $('.content [name="icon"]').val());
    form.append('type', $('.content [name="type"]').val());

    xhr.open('POST', '/api/navigation/?action=setVariables');
    xhr.send(form);
    xhr.onloadend = function (){
        console.log('Сохранение значений', xhr);
        renderTable();
    }
}