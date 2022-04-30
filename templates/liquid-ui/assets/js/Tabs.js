import Utils from "./Utils";

class Tabs {
  constructor() {
    this.head = Array.from(
      document.querySelectorAll('.\-tab-item')
    );
    this.content = Array.from(
      document.querySelectorAll('.\-tab-section')
    );
    this.initEvents();
  }

  initEvents() {
    this.head.forEach(el => {
      el.addEventListener("click", this.onClick.bind(this));
    });
  }

  removeActive(list) {
    list.forEach(el => {
      Utils.removeClass(el, "-active");
    });
  }

  onClick(event) {
    event.preventDefault();
    this.removeActive(this.head);
    this.removeActive(this.content);
    const target = event.currentTarget;
    const order = Utils.elementIndex(this.head, target);
    Utils.addClass(target, "-active");
    Utils.addClass(this.content[order], "-active");
  }
}

export default Tabs;
