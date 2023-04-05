function openBottombar(html, height = "450") {
    let body = document.querySelector("body");
    let sidebar = document.getElementById("bottom-bar");
    let sidebarBody = document.querySelector("#bottom-bar .bottom-bar-wrap");

    sidebar.style.height = `${height}px`;
    sidebar.classList.add('open');
    body.classList.add('darken-opacity');

    sidebarBody.style.opacity = '1';
    sidebarBody.innerHTML = html;
}

function closeBottombar() {
    let body = document.querySelector("body");
    let sidebar = document.getElementById("bottom-bar");
    let sidebarBody = document.querySelector("#bottom-bar .bottom-bar-wrap");

    sidebar.style.height = "0";
    sidebar.classList.remove('open');
    body.classList.remove('darken-opacity');

    sidebarBody.style.opacity = '0';
    sidebarBody.innerHTML = '';
}