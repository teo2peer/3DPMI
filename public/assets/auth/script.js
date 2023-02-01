$(document).ready(function() {
  checkWindowHash();
})


//check Hash 


function checkWindowHash() {
  console.log(window.location.hash)
  
  //reset pages
  /*$("#logo").removeClass("animate");
  $("#login-inputs").removeClass("animate");*/
  
  //reset event listeners 
  $("#email").off("keyup");
  $("#password").off("keyup");
  
  //show and init pages 
  if(window.location.hash == "#resetPassword") {
    $("#back-button > a").attr("href", "#");
    $("#password-div").slideUp(500);
    $("#login-btn-container").slideUp(500);
    $("#alt-login-options").slideUp(500);
    $("#forgot-password").slideUp(500);
    $("#reset-password-submit").slideDown(500);
    $("#loader").slideUp(500)
    $("#back-button").show(500);          
    $("#notification").slideUp(500);
    $("#email-div").slideDown(500);
  } else if (window.location.hash == "#notification"){
    //Shows if there is a major response from the server after an action.  Like a failed password reset email
    //back Button 
    $("#back-button > a").attr("href", "#");
    $("#back-button").show(500);  
    //containers 
    $("#logo").removeClass("load").addClass("animate");
    $("#login-inputs").removeClass("load").addClass("animate");
    //elements
    $("#password-div").slideUp(500);
    $("#login-btn-container").slideUp(500);
    $("#alt-login-options").slideUp(500);
    $("#forgot-password").slideUp(500);
    $("#reset-password-submit").slideUp(500)
    $("#email-div").slideUp(500);
    $("#loader").slideUp(500);
    //shown elements
    $("#notification").slideDown(500);
  } else if(window.location.hash == "#processing") {
    $("#loader").slideDown(500);
    //containers 
    $("#logo").removeClass("animate").addClass("load");
    $("#login-inputs").removeClass("animate").addClass("load");
  } else {
    $("#email").on( "keyup", function(){checkFormFill();})
    $("#password").on( "keyup", function(){checkFormFill();})
    
    $("#logo").removeClass("load").addClass("animate");
    $("#login-inputs").removeClass("load").addClass("animate");
    
    $("#password-div").slideUp(500);
    $("#forgot-password").slideDown(500);
    $("#reset-password-submit").slideUp(500);
    $("#email-div").slideDown(500);
    $("#back-button").hide(500);
    $("#loader").slideUp(500)
    $("#notification").slideUp(500);
    checkFormFill();
  }
}

$(window).on('hashchange', function() {
  checkWindowHash()
});

function checkFormFill() {
  console.log($("#email").val())
  if($("#email").val() == "" && $("#password").val() == "") {
    $("#login-btn-container").slideUp(500);
    $("#alt-login-options").slideDown(500);
    $("#password-div").slideUp(500);
  } else {
    $("#login-btn-container").slideDown(500);
    $("#alt-login-options").slideUp(500);
    $("#password-div").slideDown(500);
  }
}

function showMessage(title, message) {
  $("#notification > h5").html(title);
  $("#notification > p").html(message);
  window.location.hash = "#notification";
}



function sudoSendResetEmail() {
  location.hash = "#processing"
  
  setTimeout(function() {
    //on success 
    showMessage("Email Sent!", "Please check your inbox and follow the link.")
    
  }, 2000)
}





function sudoLogin() {
   location.hash = "#processing"
  setTimeout(function() {
    location.hash = "#"
  }, 2000)
}