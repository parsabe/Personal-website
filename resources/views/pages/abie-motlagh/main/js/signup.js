const signup = document.querySelector(".signup"),
continueBtn = signup.querySelector(".signup-submit"),
errorr = signup.querySelector('.error-signup');

signup.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/main/php/signup.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                location.href = "/portal/";
              }
              else{
                errorr.style.display = "block";
                errorr.textContent = data;
              }
          }
      }
    }
    let signupdata = new FormData(signup);
    xhr.send(signupdata);
}