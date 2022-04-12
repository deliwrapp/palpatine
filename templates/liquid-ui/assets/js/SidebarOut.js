import Utils from "./Utils";

class SidebarOut {
  constructor(el) {
    this.el = el;
    this.btnSide = document.getElementsByClassName("nav-side-btn")[0];
    this.navbar = document.getElementsByClassName("navbars__area")[0];
    this.window = window;

    this.initEvents();
  }

  initEvents() {
    this.el.addEventListener("click", this.onClick.bind(this));
  }

  onClick() {
    if (this.window.innerWidth < 1200 && !this.navbar.classList.contains("nav--reduce")) {
      Utils.toggleClass(this.btnSide, "nav-side-btn__active");
      Utils.toggleClass(this.navbar, "nav--reduce");
    }
  }
}

export default SidebarOut;
