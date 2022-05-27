class ModalHandler {
  constructor(modalClass = '.modal', classToggle = '-active', modalOpenTriggerClass = '.\-modal-open', modalCloseTriggerClass = '.\-modal-close') {
    this.modalClass = modalClass;
    this.class = classToggle;
    this.modalOpenTriggers = document.querySelectorAll(modalOpenTriggerClass);
    this.modalCloseTriggers = document.querySelectorAll(modalCloseTriggerClass);
    this.initEvents();
  }

  initEvents() {
    this.modalOpenTriggers.forEach(el => {
        el.addEventListener('click', (e) => {
            let defaultMode = el.getAttribute('data-modal-default')
            console.log(defaultMode)
            if (defaultMode != "true" || null == defaultMode || !defaultMode || defaultMode == "false" ) { 
              e.preventDefault()
              console.log('preventDefault')
            }
            let modalId = el.getAttribute('data-modal-id')
            let modal = document.getElementById(modalId)
            if (modal) {
                modal.classList.toggle(this.class)
            }
        })
          
    })
    this.modalCloseTriggers.forEach(el => {
        el.addEventListener('click', (e) => {
            e.preventDefault()
            let modal = el.closest(this.modalClass)
            if (modal && modal.classList.contains(this.class)) {
                modal.classList.remove(this.class)
            }
        })
          
    })
  }
}

export default ModalHandler;
