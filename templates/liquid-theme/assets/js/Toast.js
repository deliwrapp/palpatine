import Utils from "./Utils";

class Toast {
  constructor(el) {
    this.el = el;
    this.initEvents();
  }

  initEvents() {
    this.el.addEventListener("click", this.onClick.bind(this));
  }

  onClick() {
    Utils.addClass(this.el, "-d-none");
  }
}

export default Toast;
