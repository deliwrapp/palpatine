import Utils from "./Utils";

class ButtonToggle {
  constructor(el, classToggle, navItem) {
    this.el = el;
    this.class = classToggle;
    this.navItem = document.querySelectorAll(navItem);

    this.initEvents();
  }

  initEvents() {
    this.el.addEventListener("click", this.onButtonClick.bind(this));
  }

  onButtonClick() {

    if (this.navItem.length > 1){
      for(var i=0; i < this.navItem.length; i= i + 1){
        if (this.el.classList.contains(this.class)) {
          Utils.removeClass(this.navItem[i], this.class);
          Utils.removeClass(this.el, this.class);
        }else{
          Utils.removeClass(this.navItem[i], this.class);
          Utils.addClass(this.el, this.class);
        }
      }
    }
    Utils.toggleClass(this.el, this.class);

  }
}

export default ButtonToggle;
