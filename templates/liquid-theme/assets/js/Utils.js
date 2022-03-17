class Utils {
    static addClass(elt, className) {
      if (typeof elt.classList !== "undefined") {
        elt.classList.add(className);
        return true;
      }
      return false;
    }
  
    static hasClass(elt, className) {
      if (typeof elt.classList !== "undefined") {
        return elt.classList.contains(className);
      }
      return false;
    }
  
    static removeClass(elt, className) {
      if (typeof elt.classList !== "undefined") {
        elt.classList.remove(className);
        return true;
      }
      return false;
    }
  
    static toggleClass(elt, className, force = undefined) {
      if (typeof elt.classList !== "undefined") {
        if (typeof force === "undefined") {
          elt.classList.toggle(className);
        } else {
          elt.classList.toggle(className, force);
        }
        return true;
      }
      return false;
    }
  
    static camelToKebabCase(str) {
      return str.replace(/([a-z0-9]|(?=[A-Z]))([A-Z])/g, "$1-$2").toLowerCase();
    }
  
    static elementIndex(nodeList, searchElt) {
      return Array.from(nodeList).findIndex(el => el === searchElt);
    }
  
    static disableBodyScroll(el) {
      disableBodyScroll(el);
    }
  
    static enableBodyScroll(el) {
      enableBodyScroll(el);
    }
  
    static unwrap(wrapper) {
      const parent = wrapper.parentNode;
      while (wrapper.firstChild) {
        parent.insertBefore(wrapper.firstChild, wrapper);
      }
      parent.removeChild(wrapper);
    }
  
    static wrap(el, wrapper) {
      el.parentNode.insertBefore(wrapper, el);
      wrapper.appendChild(el);
    }
  
    static ieVersion(uaString) {
      uaString = uaString || navigator.userAgent;
      var match = /\b(MSIE |Trident.*?rv:|Edge\/)(\d+)/.exec(uaString);
      if (match) return parseInt(match[2]);
    }
  }
  
  export default Utils;
  