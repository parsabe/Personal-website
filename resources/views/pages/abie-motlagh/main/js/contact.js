const form2 = document.querySelector(".php-email-form"),
name = form2.querySelector(".contact-name"),
email = form2.querySelector(".contact-email"),
subject = form2.querySelector(".contact-subject"),
msg = form2.querySelector(".contact-msg"),
submit = form2.querySelector(".contact-submit"),
error = form2.querySelector(".error-msg"),
success = form2.querySelector(".success-msg");

form2.onsubmit = (e)=>{
    e.preventDefault();
}

submit.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/main/php/contact.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                success.style.display = "block";
                success.textContent = data;
              } else{
                error.style.display = "block";
                error.textContent = data;
                
              }
          }
      }
    }
    let formdata = new FormData(form2);
    xhr.send(formdata);
}