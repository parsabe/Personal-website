const login = document.querySelector(".login"),
continuebtn = login.querySelector(".login-btn"),
errortxt = login.querySelector('.error-login');

login.onsubmit = (e)=>{
    e.preventDefault();
}

continuebtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/main/php/login.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                location.href = "/portal/";
              }
              else{
                errortxt.style.display = "block";
                errortxt.textContent = data;
              }
          }
      }
    }
    let logindata = new FormData(login);
    xhr.send(logindata);
}