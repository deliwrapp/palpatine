import ButtonToggle from "./ButtonToggle";
import BlockTabs from "./BlockTabs";
import Sidebar from './Sidebar';
import SidebarOut from './SidebarOut';
import Toast from './Toast';
import SlidePanel from './SlidePanel';
import MegaSearch from './MegaSearch';


new BlockTabs();
new MegaSearch('[data-btn="search"]', '[data-behaviour="sortable"] .table--item');

Array.from(document.getElementsByClassName("toast")).forEach(
  el => {
    new Toast(el);
  }
);
Array.from(document.getElementsByClassName("nav-side-btn")).forEach(
  el => {
    new Sidebar(el);
  }
);
Array.from(document.getElementsByClassName("content")).forEach(
  el => {
    new SidebarOut(el);
  }
);
Array.from(document.querySelectorAll('[data-class="btn-search"]')).forEach(
  el => {
    new ButtonToggle(el, "btn-search--active", '[data-class="btn-search"]');
  }
);
Array.from(document.querySelectorAll('[data-elem="nav-toggle"]')).forEach(
  el => {
    new ButtonToggle(el, "nav__list__group--active",'[data-elem="nav-toggle"]');
  }
);
Array.from(document.getElementsByClassName("slide_panel_toggle")).forEach(
  el => {
    new SlidePanel(el);
  }
);
