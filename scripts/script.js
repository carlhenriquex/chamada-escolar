window.addEventListener("scroll", function(){

    let header = document.querySelector("#header")
    header.classList.toggle("rolagem", window.scrollY > 120)
})


function menuShow() {
    const menuMobile = document.querySelector(".mobile-menu");
    menuMobile.classList.toggle("open");

    if (menuMobile.classList.contains("open")) {
        const menuItems = menuMobile.querySelectorAll(".item-mobile");
        menuItems.forEach(item => {
            item.addEventListener("click", () => {
                menuMobile.classList.remove("open");
            });
        });
    }
}
