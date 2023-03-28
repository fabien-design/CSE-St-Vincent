// Code Modal suppression d'un partenaire

var modal = document.getElementById("modalSupprPartenaire");
var span = document.getElementsByClassName("close")[0];
var btnNon = document.getElementsByClassName("formSupprNon")[0];
var btnOui = document.getElementsByClassName("formSupprOui")[0];
// cacher modal au click de la croix ou du btn non
span.onclick = function() {
  modal.style.display = "none";
  history.pushState(null, null, window.location.href.split("&")[0]);
}
btnNon.onclick = function() {
    modal.style.display = "none";
    history.pushState(null, null, window.location.href.split("&")[0]);
}
btnOui.onclick = function() {
    history.pushState(null, null, window.location.href.split("&")[0]);
    setTimeout(modal.style.display = "none", 2000);
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    history.pushState(null, null, window.location.href.split("&")[0]);
  }
}

// Code Jquery en AJAX pour la suppression d'un partenaire

$(document).ready(function(){
    $("#formSupprPartenaire").submit(function(e){
      e.preventDefault();
      var formData = $("#formSupprPartenaire").serialize();
      $.ajax({
        type: "POST",
        url: "supprPartenaire.php",
        data: formData,
        success: function(response){
          alert(response);
          setTimeout(location.reload(true) , 3000);
        }
      });
    });
});
