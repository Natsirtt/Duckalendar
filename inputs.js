var defaultInput = ["login", "password"];

$(document).ready(
  function() {
    $(".round").click(
      function (){
        var input = $(this);
        if (defaultInput.indexOf(input.val()) !== -1) {
          input.val("");
        }
      }
    )
  }
);