const forgotpass = document.querySelector(".forgotpass"),
submission = forgotpass.querySelector(".sender"),
ere = forgotpass.querySelector('.error-message');

forgotpass.onsubmit = (e)=>{
    e.preventDefault();
}

submission.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/main/php/pass.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                alert("رمز عبور شما با موفقیت تغییر کرد!");
            }
              else{
                ere.style.display = "block";
                ere.textContent = data;
              }
          }
      }
    }
    let signupdata = new FormData(forgotpass);
    xhr.send(signupdata);
}