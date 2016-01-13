document.addEventListener('DOMContentLoaded', function(){
    var names = [];
    var finishedArr = [];

    chrome.runtime.sendMessage({greeting: "get database"}, function(response) {
        if (response.populate != null) {
            names.push(response.populate);
            var modded = toObject(names[0]);
            
            for (var i = 0; i < Object.keys(modded).length; i++) {
                var j = toObject(modded[i]);
                var j = toObject(modded[i]);
                finishedArr.push(j);  
            }
            
            var i;
            for(i = 0; i < finishedArr.length; i++){
                finishedArr[i]['firstName'] = finishedArr[i]['0'];
                delete finishedArr[i]['0'];

                finishedArr[i]['lastName'] = finishedArr[i]['1'];
                delete finishedArr[i]['1'];

                finishedArr[i]['quote'] = finishedArr[i]['2'];
                delete finishedArr[i]['2'];
            }
            modifyNames(document.documentElement, finishedArr);
        }
    });

    function toObject(arr) {
      var rv = {};
      for (var i = 0; i < arr.length; ++i)
        rv[i] = arr[i];
      return rv;
    }

    function modifyElement(element, firstTarget, secondTarget, entry) {
        var nodes = element.childNodes;
       
        for (var i = 0, l = nodes.length; i < l; i++) {
            if (nodes[i].nodeType === 3) {
                var target = firstTarget + " " + secondTarget;
                //put words BETWEEN first and second word
                if (nodes[i].data.toLowerCase().indexOf(target.toLowerCase()) != -1){
                    nodes[i].data = nodes[i].data.replace(RegExp(target, "ig"), firstTarget + " '" + entry[Math.floor(Math.random() * entry.length)] + "' " + secondTarget);
                }
            } else if (nodes[i].childNodes.length > 0) {
                modifyElement(nodes[i], firstTarget, secondTarget, entry);
            }
        }
    };

    function modifyNames(element, n){
        for (i = 0; i < n.length; i++){
            var quoteArr = [];

            for (var j = 0; j < n.length; j++) {
                if (n[i].firstName === n[j].firstName && n[i].lastName === n[j].lastName) {
                    quoteArr.push(n[j].quote); 
                }    
            }
            modifyElement(element, n[i].firstName, n[i].lastName, quoteArr); 
        }
    };

    //Checks and for page updates and modifies any new names
    var insertedNodes = [];
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            for (var i = 0; i < mutation.addedNodes.length; i++){
                insertedNodes.push(mutation.addedNodes[i]);
                modifyNames(insertedNodes[insertedNodes.length-1], finishedArr);
            }
        });
    });

    observer.observe(document.documentElement, {
        childList: true,
        attributes: true,
        subtree: true,
        characterData: true
    });
});