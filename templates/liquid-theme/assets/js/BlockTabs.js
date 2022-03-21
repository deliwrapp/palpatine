import Utils from "./Utils";

class BlockTabs {
  constructor() {
    this.head = Array.from(
      document.querySelectorAll('[data-behaviour="blocktabs-button"]')
    );
    this.content = Array.from(
      document.querySelectorAll('[data-behaviour="blocktabs-content"]')
    );
    this.initEvents();
  }

  initEvents() {
    this.head.forEach(el => {
      el.addEventListener("click", this.onClick.bind(this));
    });
  }

  removeActive(list, prefix) {
    list.forEach(el => {
      Utils.removeClass(el, prefix + "--active");
    });
  }

  onClick(event) {
    this.removeActive(this.head, "block-tabs__title");
    this.removeActive(this.content, "block-tabs__content");

    const target = event.currentTarget;
    const order = Utils.elementIndex(this.head, target);
    Utils.addClass(target, "block-tabs__title--active");
    Utils.addClass(this.content[order], "block-tabs__content--active");
  }
}

export default BlockTabs;
