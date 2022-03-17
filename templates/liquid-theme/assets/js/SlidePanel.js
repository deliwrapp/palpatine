import Utils from "./Utils";

class SlidePanel {
  constructor(el) {
    this.el = el;
    this.slidePanelArea = document.getElementsByClassName("slide_panel_area")[0];
    this.window = window;

    this.initEvents();
  }

  initEvents() {
    this.el.addEventListener("click", this.onClick.bind(this));
    this.window.addEventListener("load", this.onEvent.bind(this));
    this.window.addEventListener("resize", this.onEvent.bind(this));
  }

  onEvent(){
    if (this.window.innerWidth < 1690 && !this.slidePanelArea.classList.contains("slide_panel--reduce")) {
      Utils.toggleClass(this.el, "slide_panel__active");
      Utils.toggleClass(this.slidePanelArea, "slide_panel--reduce");
    }
  }

  onClick() {
    Utils.toggleClass(this.el, "slide_panel__active");
    Utils.toggleClass(this.slidePanelArea, "slide_panel--reduce");
  }
}

export default SlidePanel;
