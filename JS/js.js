

function target_popup(form) {
	var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
	var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
	var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
	var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
	var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	if (!isiPad){
	windowHandle = window.open('', 'formpopup', 'width=900,height=400,resizeable = false,scrollbars');
    	windowHandle.focus();
	form.target = 'formpopup';
	}
}

  function openInParent(url) {
    window.opener.location.href = url;
    window.close();
  }

function CancelText(div){
	var coucou = document.getElementById(div).innerHTML;
	var target = "<span style='display:none'>" + coucou + "</span>";
	document.getElementById(div).innerHTML = target ;
}

function ShowText(div){
	var x=document.getElementById(div).innerHTML.replace(/none/,'inline').replace(/ShowText/,'CancelText').replace(/Lire la suite.../,'');
	document.getElementById(div).innerHTML = x;
}

function montrerDiv(div){
	document.getElementById(div).setAttribute('style','display:inline;')
}

function controleDiv(div){
	var coucou = document.getElementById(div).innerHTML;
	var target = document.getElementById('txtdef').value;
	var regexp1 = /&lt;/g;
	var regexp2 = /&gt;/g;
	target = target.replace(regexp1,"<");
	target = target.replace(regexp2,">");
	document.getElementById(div).innerHTML = target ;
}

function controleDivC(div){
        var coucou = document.getElementById(div).innerHTML;
        var target = document.getElementById('txtdef').innerHTML;
        var regexp1 = /&lt;/g;
        var regexp2 = /&gt;/g;
        target = target.replace(regexp1,"<");
        target = target.replace(regexp2,">");
        document.getElementById(div).innerHTML = target ;
}

function controleDivEdit(div){
	var coucou = document.getElementById(div).innerHTML;
	var target = document.getElementById('txttest').value;
	var regexp1 = /&lt;/g;
	var regexp2 = /&gt;/g;
	target = target.replace(regexp1,"<");
	target = target.replace(regexp2,">");
	document.getElementById(div).innerHTML = target ;
}

function getXML(div){
	var target = document.getElementById(div).innerHTML;
	alert(target);
}

function notEditable(){
	document.getElementById('editEntry').setAttribute('contentEditable','false');
}

function copyContent(s,d){
	var target = document.getElementById(s).innerHTML;
	var pre1 = /<div id=.attbalisessense.>[^>]*<\/div>/g;
	var pre2 = /(<a href=[^>]*>)([^>]*)(<\/a>)/g;
	var pre3 = /<br>/g;
	var pre4 = /<br\/>/g;
	target = target.replace(pre1,"");
	target = target.replace(pre2,"");
	target = target.replace(pre3,"");
	target = target.replace(pre4,"");
	document.getElementById(d).value = target ;	
}

function messagedroite(t){
		var e = document.getElementById('renvoidroite');
		var target = "display:block; color:#00FF00; font-size:13px;";	
		var texte1 = '<img src="images/icone_voyant_vert.gif" width="20" height="20" />  En attente de données XML.';
		var texte2 = '<img src="images/attenzione_rotondo_archi_01.png" width="20" height="20" />   Saisie de texte:"entrée" pour valider ou "esc".';
		if (t==1){
		e.setAttribute('style',target);
		e.innerHTML = texte2;}
		else {e.setAttribute('style',target);
		e.innerHTML = texte1;};
}

function scanTouche2() //Avec le dernier caractère d'une chaîne
{
	var e = document.getElementById('txtdef');
	var red = "display:block; color:red; font-size:13px;";
	var blue = "display:block; color:blue; font-size:13px;";
	var green = "display:block; color:#00FF00; font-size:13px;";
	var last = e.value.length-1;
	var char = e.value.charAt(last)
        if(char.search(/</)!=-1){e.setAttribute('style',red);}
		if(char.search(/>/)!=-1){e.setAttribute('style',blue);}
		if(char.search(/"/)!=-1){e.setAttribute('style',green);}
}

function scanTouche() // on teste le nombre de balises ouvrantes et fermantes
					   // on change de couleur si il y a une différence	
{
	var e = document.getElementById('txtdef');
	var gris = "display:block; color:#BFBFBF; font-size:13px;";
	var blue = "display:block; color:blue; font-size:13px;";
	var green = "display:block; color:#00FF00; font-size:13px;";
	var last = e.value.length-1;
	var char = e.value.charAt(last) // le dernier caractère
	var compte1 = e.value.split("<" ).length; // on compte le nombre de <
	var compte2 = e.value.split(">" ).length; // on compte le nombre de >
        if(compte1 != compte2)
        {
			e.setAttribute('style',gris);
			var target = '<img src="images/attenzione_rotondo_archi_01.png" width="20" height="20" />   Balise ouverte !';
			document.getElementById('renvoi').setAttribute('style','color:red;')
			document.getElementById('renvoi').innerHTML = target
        }
        else {
			e.setAttribute('style',blue);
			var target = '<img src="images/icone_voyant_vert.gif" width="20" height="20" />   Saisie de texte...';
			document.getElementById('renvoi').setAttribute('style','color:#00FF00;')
			document.getElementById('renvoi').innerHTML = target
		}	
}

function scanToucheC() // on teste le nombre de balises ouvrantes et fermantes
                                           // on change de couleur si il y a une différence     
{
	var f = document.getElementById('txtdef');
        var ee = f.innerHTML;
	var regexp2 = /([^>])\&lt\;/;
	f.innerHTML = ee.replace(regexp2,"$1<span style='color:blue;'>&lt;</span>")
	cursorManager.setEndOfContenteditable(f);
}

function insertion(repdeb, repfin) {//fonction des touches bleues
  var input = document.forms['formulaire'].elements['txtdef'];
  input.focus();
  /* pour l'Explorer Internet */
  if(typeof document.selection != 'undefined') {
    /* Insertion du code de formatage */
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = repdeb + insText + repfin;
    /* Ajustement de la position du curseur */
    range = document.selection.createRange();
    if (insText.length == 0) {
      range.move('character', -repfin.length);
    } else {
      range.moveStart('character', repdeb.length + insText.length + repfin.length);
    }
    range.select();
    document.getElementById('txtdef').focus();
  }
  /* pour navigateurs plus récents basés sur Gecko*/
  else if(typeof input.selectionStart != 'undefined')
  {
    /* Insertion du code de formatage */
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);
    /* Ajustement de la position du curseur */
    var pos;
    if (insText.length == 0) {
      pos = start + repdeb.length;
    } else {
      pos = start + repdeb.length + insText.length + repfin.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
    document.getElementById('txtdef').focus();
  }
  /* pour les autres navigateurs */
  else
  {
    /* requête de la position d'insertion */
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Insertion à la position (0.." + input.value.length + "):", "0");
    }
    if(pos > input.value.length) {
      pos = input.value.length;
    }
    /* Insertion du code de formatage */
    var insText = prompt("Veuillez entrer le texte à formater:");
    input.value = input.value.substr(0, pos) + repdeb + insText + repfin + input.value.substr(pos);
    document.getElementById('txtdef').focus();
  }
}

document.addEventListener('keydown', function (event) {
  var esc = event.which == 27,
      nl = event.which == 13,
      el = event.target,
      input = el.nodeName != 'INPUT' && el.nodeName != 'TEXTAREA',
      data = {}; 

  if (input) {
    if (esc) {
      // restore state
      document.execCommand('undo');
      el.blur();
    } else if (nl) {
      // save
      data = el.innerHTML;

      log(JSON.stringify(data));

      el.blur();
      event.preventDefault();
    }
  }
}, true);

function log(s) {
  target = '<img src="images/dagobert83_apply.png" width="20" height="20" />   Texte récupéré de la zone de contrôle...';
  document.getElementById('renvoi').setAttribute('style','color:#00FF00;');
  document.getElementById('renvoi').innerHTML = target
  var c1 = /\\n/g;
  var c2 = /\\t/g;
  var c3 = /"/;  
  var c4 = />"/;
  var c5 = /\\"/g;
  var pre1 = /<div id=.attbalisessense.>[^>]*<\/div>/g;
  var pre2 = /<a href=[^>]*>[^>]*<\/a>/g;
  var pre3 = /<br>/g;
  var pre4 = /<br\/>/g;
  s = s.replace(c1,"\r");  
  s = s.replace(c2,"");
  s = s.replace(c3,"");
  s = s.replace(c4,">");
  s = s.replace(c5,"'");
  s = s.replace(pre1,"");
  s = s.replace(pre2,"");
  s = s.replace(pre3,"");
  s = s.replace(pre4,"");
  document.getElementById('txtdef').value = s;
}

function actuTextarea() {
  s=document.getElementById('controle').innerHTML;
  target = '<img src="images/dagobert83_apply.png" width="20" height="20" />   Texte récupéré de la zone de contrôle...';
  document.getElementById('renvoi').setAttribute('style','color:#00FF00;');
  document.getElementById('renvoi').innerHTML = target
  var c1 = /\\n/g;
  var c2 = /\\t/g;
  var c3 = /"/;  
  var c4 = />"/;
  var c5 = /\\"/g;
  var pre1 = /<div id=.attbalisessense.>[^>]*<\/div>/g;
  var pre2 = /<a href=[^>]*>[^>]*<\/a>/g;
  var pre3 = /<br>/g;
  var pre4 = /<br\/>/g;
  s = s.replace(c1,"\r");  
  s = s.replace(c2,"");
  s = s.replace(c3,"");
  s = s.replace(c4,">");
  s = s.replace(c5,"'");
  s = s.replace(pre1,"");
  s = s.replace(pre2,"");
  s = s.replace(pre3,"");
  s = s.replace(pre4,"");
  document.getElementById('txtdef').value = s;
}



 (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();

var tinyMCEmode = false;

function toggle(id) {
if (!tinyMCE.getInstanceById(id)) {
tinyMCE.execCommand('mceAddControl', false, id);
createCookie("tinyMCE" + id,"true");
}
else {
tinyMCE.execCommand('mceRemoveControl', false, id);
createCookie("tinyMCE" + id,"false");
}
}


//Namespace management idea from http://enterprisejquery.com/2010/10/how-good-c-habits-can-encourage-bad-javascript-habits-part-1/
(function( cursorManager ) {

    //From: http://www.w3.org/TR/html-markup/syntax.html#syntax-elements
    var voidNodeTags = ['AREA', 'BASE', 'BR', 'COL', 'EMBED', 'HR', 'IMG', 'INPUT', 'KEYGEN', 'LINK', 'MENUITEM', 'META', 'PARAM', 'SOURCE', 'TRACK', 'WBR', 'BASEFONT', 'BGSOUND', 'FRAME', 'ISINDEX'];

    //From: http://stackoverflow.com/questions/237104/array-containsobj-in-javascript
    Array.prototype.contains = function(obj) {
        var i = this.length;
        while (i--) {
            if (this[i] === obj) {
                return true;
            }
        }
        return false;
    }

    //Basic idea from: http://stackoverflow.com/questions/19790442/test-if-an-element-can-contain-text
    function canContainText(node) {
        if(node.nodeType == 1) { //is an element node
            return !voidNodeTags.contains(node.nodeName);
        } else { //is not an element node
            return false;
        }
    };

    function getLastChildElement(el){
        var lc = el.lastChild;
        while(lc && lc.nodeType != 1) {
            if(lc.previousSibling)
                lc = lc.previousSibling;
            else
                break;
        }
        return lc;
    }

    //Based on Nico Burns's answer
    cursorManager.setEndOfContenteditable = function(contentEditableElement)
    {

        while(getLastChildElement(contentEditableElement) &&
              canContainText(getLastChildElement(contentEditableElement))) {
            contentEditableElement = getLastChildElement(contentEditableElement);
        }

        var range,selection;
        if(document.createRange)//Firefox, Chrome, Opera, Safari, IE 9+
        {    
            range = document.createRange();//Create a range (a range is a like the selection but invisible)
            range.selectNodeContents(contentEditableElement);//Select the entire contents of the element with the range
            range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
            selection = window.getSelection();//get the selection object (allows you to change selection)
            selection.removeAllRanges();//remove any selections already made
            selection.addRange(range);//make the range you have just created the visible selection
        }
        else if(document.selection)//IE 8 and lower
        { 
            range = document.body.createTextRange();//Create a range (a range is a like the selection but invisible)
            range.moveToElementText(contentEditableElement);//Select the entire contents of the element with the range
            range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
            range.select();//Select the range (make it the visible selection
        }
    }

}( window.cursorManager = window.cursorManager || {}));

