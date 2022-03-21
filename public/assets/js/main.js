init()

function init() {
    convertToPreviewCode()
    initSwipeMenu()
    activeModal()
}

function convertToPreviewCode() {
    try {
        let codeBlocks = document.querySelectorAll('.code-preview')
        console.log(codeBlocks)
        codeBlocks.forEach(el => {
            let previewCode = el.innerHTML
            el.innerHTML = null
            el.textContent = process(previewCode)

            function process(str) {
                let div = document.createElement('div')
                div.innerHTML = str.trim()
                return format(div, 0).innerHTML
            }
              
            function format(node, level) {
                let indentBefore = new Array(level++ + 1).join('  '),
                indentAfter = new Array(level - 1).join('  '),
                textNode;
            
                for (let i = 0; i < node.children.length; i++) {
                    node.children[i].innerHTML = node.children[i].innerHTML.trim()
                    textNode = document.createTextNode('\n' + indentBefore)
                    node.insertBefore(textNode, node.children[i])
                    
                    format(node.children[i], level)
                
                    if (node.lastElementChild == node.children[i]) {
                        textNode = document.createTextNode('\n' + indentAfter)
                        node.appendChild(textNode)
                    }
                }
                //node.innerHTML = node.innerHTML.trim()
                return node;
            }
        }); 
    } catch (err) {
        console.log(err)
    }
    
}

function initSwipeMenu() {
    init();

    function init() {
        let menusTriggers = document.querySelectorAll(".swipe-m-trigger");
        let clickedEvent = "click";

        window.addEventListener('touchstart', function detectTouch() {
            clickedEvent = "touchstart"; // Transforme l'événement en "touchstart"
            window.removeEventListener('touchstart', detectTouch, false);
        }, false);

        menusTriggers.forEach(trigger => {
            try {
                let panelId = trigger.getAttribute('data-panel');
                let openState = trigger.getAttribute('data-open-panel') ?
                    trigger.getAttribute('data-open-panel'):
                    "-open"
                ;
                let closeState = trigger.getAttribute('data-close-panel') ?
                    trigger.getAttribute('data-close-panel') :
                    "-close"
                ;
                let panel = document.getElementById(panelId);

                trigger.addEventListener(clickedEvent, function(evt) {
                    console.log(clickedEvent)
                    // Modification du menu trigger
                    this.classList.contains("clicked") ? 
                        this.classList.remove("clicked") : 
                        this.classList.add("clicked");
                    // Créé l'effet pour le menu slide
                    if(panel.classList.contains(openState)) {
                        panel.classList.remove(openState)
                        panel.classList.add(closeState)
                    } else {
                        panel.classList.remove(closeState)
                        panel.classList.add(openState)
                    }


                    /*===============================*/
                    /*=== Swipe avec Touch Events ===*/
                    /*===============================*/
                    /* let startX = 0; // Position de départ
                    let distance = 100; // 100 px de swipe pour afficher le menu

                    // Au premier point de contact
                    window.addEventListener("touchstart", function(evt) {
                        // Récupère les "touches" effectuées
                        var touches = evt.changedTouches[0];
                        startX = touches.pageX;
                        between = 0;
                    }, false);

                    // Quand les points de contact sont en mouvement
                    window.addEventListener("touchmove", function(evt) {
                        // Limite les effets de bord avec le tactile...
                        evt.preventDefault();
                        evt.stopPropagation();
                    }, false);

                    // Quand le contact s'arrête
                    window.addEventListener("touchend", function(evt) {
                        let touches = evt.changedTouches[0];
                        let between = touches.pageX - startX;

                        // Détection de la direction
                        if(between > 0) {
                            let orientation = "ltr";
                        } else {
                            let orientation = "rtl";
                        }

                        // Modification du menu trigger
                        if(Math.abs(between) >= distance && orientation == "ltr" && !menu.classList.contains("visible")) {
                            trigger.classList.add("clicked");
                        }
                        if(Math.abs(between) >= distance && orientation == "rtl" && !menu.classList.contains("invisible")) {
                            trigger.classList.remove("clicked");
                        }

                        // Créé l'effet pour le menu slide (compatible partout)
                        if(Math.abs(between) >= distance && orientation == "ltr" && !menu.classList.contains("visible")) {
                            menu.classList.remove("invisible");
                            menu.classList.add("visible");
                        }
                        if(Math.abs(between) >= distance && orientation == "rtl" && !menu.classList.contains("invisible")) {
                            menu.classList.remove("visible");
                            menu.classList.add("invisible");
                        }
                    }, false); */
                }, false);
            } catch (err) {
                console.log(err) 
            }
            

        });

        
    
        
    }
    function reset() {
        init()
    }
    function destroy() {

    }
}

function activeModal() {
    try {
        let modalBtns = document.querySelectorAll('.\-modal-open')
        let modalCloseTriggers = document.querySelectorAll('.\-modal-close')
        modalBtns.forEach(el => {
            el.addEventListener('click', (e) => {
                e.preventDefault()
                console.log('hhh')
                let modalId = el.getAttribute('data-modal-id')
                let modal = document.getElementById(modalId)
                if (modal) {
                    modal.classList.toggle('-active')
                }
            })
              
        })
        modalCloseTriggers.forEach(el => {
            el.addEventListener('click', (e) => {
                e.preventDefault()
                let modal = el.closest(".modal")
                console.log(modal)
                if (modal && modal.classList.contains('-active')) {
                    modal.classList.remove('-active')
                }
            })
              
        })
    } catch (err) {
        console.log(err)
    }
    
}

// Forms
[].forEach.call(document.querySelectorAll('.form-group input.form-field'), function(el) {
    el.addEventListener('focus', function() {
        el.parentNode.classList.add('focus');
    });
    el.addEventListener('blur', function() {
        el.parentNode.classList.remove('focus');
    });
});

[].forEach.call(document.querySelectorAll('.form-file-field input'), function(el) {
    el.addEventListener('change', function() {
        var filesCount = el.files.length;
        if(filesCount === 1) {
            el.parentNode.querySelectorAll('.file-msg')[0].textContent = el.value.split('\\').pop();
        } else {
            var textSelected = 'files selected';
            if(el.parentNode.querySelectorAll('.file-msg')[0].dataset.selected) {
                textSelected = el.parentNode.querySelectorAll('.file-msg')[0].dataset.selected;
            }
            el.parentNode.querySelectorAll('.file-msg')[0].textContent = filesCount + ' ' + textSelected;
        }
        el.parentNode.classList.add('active');
    });
});

[].forEach.call(document.querySelectorAll('.password .icon-view'), function(el) {
    el.addEventListener('click', function() {
        var input = el.parentNode.getElementsByTagName('input')[0];
        if(!el.classList.contains('active')) {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
        input.focus();
        el.classList.toggle('active');
    });
});

// Selects
[].forEach.call(document.querySelectorAll('select.form-select'), function(el) {
    if (!el.multiple) {
        let classes = el.classList.value,
        id = el.id,
        name = el.name,
        value = el.options[el.selectedIndex].textContent,
        i;

        let wrapper = document.createElement('div');
        let template = '<span>' + value + '</span>';
        template += '<div>';
        for(i = 0; i < el.options.length; i++) {
            let active = '';
            if(value == el.options[i].innerHTML) {
                active = 'active';
            }
            template += '<span class="' + el.options[i].classList.value + ' ' + active + '" data-value="' + el.options[i].value + '">' + el.options[i].innerHTML + '</span>';
        }
        template += '</div>';

        wrapper.className = classes;
        wrapper.innerHTML = template;

        el.style.display = 'none';
        el.parentNode.insertBefore(wrapper, el);
    }
});

[].forEach.call(document.querySelectorAll('.form-select:not(.disabled) > span'), function(el) {
    el.addEventListener('click', function(e) {
        document.addEventListener('click', function() {
            [].forEach.call(document.querySelectorAll('.form-select'), function(allFS) {
                allFS.classList.remove('open');
            });
        });
        el.parentNode.classList.toggle('open');
        e.stopPropagation();
    });
});

[].forEach.call(document.querySelectorAll('.form-select > div > span'), function(el) {
    el.addEventListener('click', function(e) {
        let div = el.parentNode.parentNode;
        let select = div.nextSibling;
        select.value = el.dataset.value;
        select.dispatchEvent(new Event('change'));
        let entries = el.parentNode.getElementsByTagName('span');
        for(i = 0; i < entries.length; i++) {
            entries[i].classList.remove('active');
        }
        setTimeout(function() {
            el.classList.add('active');
        }, 300);
        div.classList.remove('open');
        div.getElementsByTagName('span')[0].textContent = el.textContent;
    });
});

// classList Polyfill - Source: https://gist.github.com/k-gun/c2ea7c49edf7b757fe9561ba37cb19ca
;(function() {
    let regExp = function(name) {
        return new RegExp('(^| )'+ name +'( |$)');
    };
    let forEach = function(list, fn, scope) {
        for (var i = 0; i < list.length; i++) {
            fn.call(scope, list[i]);
        }
    };
    function ClassList(element) {
        this.element = element;
    }
    ClassList.prototype = {
        add: function() {
            forEach(arguments, function(name) {
                if (!this.contains(name)) {
                    this.element.className += ' '+ name;
                }
            }, this);
        },
        remove: function() {
            forEach(arguments, function(name) {
                this.element.className =
                    this.element.className.replace(regExp(name), '');
            }, this);
        },
        toggle: function(name) {
            return this.contains(name) ? (this.remove(name), false) : (this.add(name), true);
        },
        contains: function(name) {
            return regExp(name).test(this.element.className);
        },
        replace: function(oldName, newName) {
            this.remove(oldName), this.add(newName);
        }
    };
    if(!('classList' in Element.prototype)) {
        Object.defineProperty(Element.prototype, 'classList', {
            get: function() {
                return new ClassList(this);
            }
        });
    }
    if(window.DOMTokenList && DOMTokenList.prototype.replace == null) {
        DOMTokenList.prototype.replace = ClassList.prototype.replace;
    }
})();