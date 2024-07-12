function toggleDropdown() {
    console.log("Toggle Dropdown");
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function (event) {
    console.log("Window Clicked");
    if (!event.target.matches('.dropbtn') && !event.target.matches('.profile-icon')) {
        console.log("Closing Dropdown");
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
