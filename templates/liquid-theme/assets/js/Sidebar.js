import Utils from "./Utils";

class Sidebar {
  constructor(el) {
    this.el = el;
    this.navbar = document.getElementsByClassName("navbars__area")[0];
    this.window = window;

    this.initEvents();
  }

  initEvents() {
    this.el.addEventListener("click", this.onClick.bind(this));
    this.window.addEventListener("load", this.onEvent.bind(this));
    this.window.addEventListener("resize", this.onEvent.bind(this));
  }

  onEvent(){
    if (this.window.innerWidth < 1200 && !this.navbar.classList.contains("nav--reduce")) {
      Utils.toggleClass(this.el, "nav-side-btn__active");
      Utils.toggleClass(this.navbar, "nav--reduce");
    }
  }

  onClick() {
    Utils.toggleClass(this.el, "nav-side-btn__active");
    Utils.toggleClass(this.navbar, "nav--reduce");
  }
}

export default Sidebar;
