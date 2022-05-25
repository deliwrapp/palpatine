import Utils from "./Utils";

class Sidebar {
  constructor(el) {
    this.el = el;
    this.navbar = document.getElementById(this.el.getAttribute('data-panel'));
    console.log(this.el.getAttribute('data-panel'))
    this.openPanelClass = this.el.getAttribute('data-open-panel');
    this.closePanelClass = this.el.getAttribute('data-close-panel');
    this.window = window;

    this.initEvents();
  }

  initEvents() {
    this.el.addEventListener("click", this.onClick.bind(this));
    this.window.addEventListener("load", this.onEvent.bind(this));
    this.window.addEventListener("resize", this.onEvent.bind(this));
  }

  onEvent(){
    if (this.window.innerWidth < 1200 && !this.navbar.classList.contains(this.closePanelClass)) {
      Utils.toggleClass(this.el, "-active");
      Utils.toggleClass(this.el, "clicked");
      Utils.toggleClass(this.navbar, this.closePanelClass);
    }
  }

  onClick() {
    Utils.toggleClass(this.el, "-active");
    Utils.toggleClass(this.el, "clicked");
    Utils.toggleClass(this.navbar, this.closePanelClass);
    Utils.toggleClass(this.navbar, this.openPanelClass);
  }
}

export default Sidebar;
