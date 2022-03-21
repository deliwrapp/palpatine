import Utils from "./Utils";

class MegaSearch {
  constructor(el, items) {
    this.el = document.querySelectorAll(el);
    this.items = document.querySelectorAll(items);
    this.initEvents();
  }

  initEvents() {
    this.el.forEach(el => {
      el.addEventListener("keyup", this.onkeyup.bind(this));
    });
  }

  onkeyup() {
    var rex = new RegExp(this.el[0].value, 'i');

    this.items.forEach(el => {
      Utils.addClass(el, "hidden");
    });
    
    Array.prototype.filter.call(this.items, function(e) {
      if (rex.test(e.innerText) == true){
        Utils.removeClass(e, "hidden");
      }
    });
  }
}

export default MegaSearch;
