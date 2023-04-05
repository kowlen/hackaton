function openSidebar(html, width = "450") {
    let body = document.querySelector("body");
    let sidebar = document.getElementById("sidebar");
    let sidebarBody = document.querySelector("#sidebar .sidenav-wrap");

    sidebar.style.width = `${width}px`;
    sidebar.classList.add('open');
    body.classList.add('darken-opacity');

    sidebarBody.style.opacity = '1';
    sidebarBody.innerHTML = html;
}

function closeSidebar() {
    let body = document.querySelector("body");
    let sidebar = document.getElementById("sidebar");
    let sidebarBody = document.querySelector("#sidebar .sidenav-wrap");

    sidebar.style.width = "0";
    sidebar.classList.remove('open');
    body.classList.remove('darken-opacity');

    sidebarBody.style.opacity = '0';
    sidebarBody.innerHTML = '';
}