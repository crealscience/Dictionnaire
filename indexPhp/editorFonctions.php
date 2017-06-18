<!-- Xavier-Laurent Salvador -->
<script>
function getInnerHtml() {
        var content = document.getElementById("content").innerHTML;
        document.getElementById("controle").innerHTML = content;
        document.getElementById("txtdef").value = content;
}

function validifier() {
        var s = document.getElementById('txtdef').value;
        document.getElementById('txttest').value = s;
        document.forms['valid'].submit();
        copyContent('renvoidtd','renvoi');
}

function toggle_width(id) {
       var e = document.getElementById(id);
       if(e.style.width == '100%')
          e.style.width = '45%';
       else
          e.style.width = '100%';

}

function mute_width(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'none')
          e.style.display = 'block';
       else
          e.style.display = 'none';

}

function Sauver() {
        document.forms['formulaire'].submit();  
}
</script>
