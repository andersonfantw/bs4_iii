document.addEventListener("DOMContentLoaded", function () {
    var x = document.querySelector.bind(document);
    var text = x(".lnet-tag");
    var startValue = text.innerHTML;
    var blocked = false;
    var undo = x(".undo");
    var redo = x(".redo");
    var save = x(".save");
    var dirty = x(".dirty");
    var stack = new Undo.Stack();
    var newValue = "";

    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    var observer = new MutationObserver(function (mutations) {      
      if(blocked){
        blocked = false;
        return;
      }
      newValue = text.innerHTML;
      stack.execute(new EditCommand(text, startValue, newValue));
      startValue = newValue;       
    });

    observer.observe(text, {
        attributes: true,
        childList: true,
        characterData: true,
        characterDataOldValue: true,
        subtree: true
    });

    var EditCommand = Undo.Command.extend({
        constructor: function (textarea, oldValue, newValue) {
            this.textarea = textarea;
            this.oldValue = oldValue;
            this.newValue = newValue;
        },
        execute: function () {},
        undo: function () {
            blocked = true;
            this.textarea.innerHTML = this.oldValue;
        },

        redo: function () {
            blocked = true;
            this.textarea.innerHTML = this.newValue;
        }
    });


    function stackUI() {
        redo.disabled = !stack.canRedo();
        undo.disabled = !stack.canUndo();
        dirty.style.display = stack.dirty() ? 'inline-block' : 'none';

    }
    stackUI();

    undo.addEventListener("click", function () {
        stack.undo();
    });

    redo.addEventListener("click", function () {
        stack.redo();
    });

    save.addEventListener("click", function () {
        stack.save();
    });

    stack.changed = function () {
        stackUI();
    };

		$(document).keydown(function(event){
			switch(event.which){
				case 89: //redo
					if(stack.canRedo()) stack.redo();
					break;
				case 90: //undo
					if(stack.canUndo()) stack.undo();
					break;
			}
		});
});