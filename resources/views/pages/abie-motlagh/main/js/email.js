const form = document.querySelector(".form"); 
email = form.querySelector(".email"),
submit = form.querySelector(".submit"),
error = form.querySelector(".error-mail");

form.onsubmit = (e)=>{
    e.preventDefault();
}

submit.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/main/php/news.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                alert("success");
              }
              else{
                error.style.display = "block";
                error.textContent = data;
                
              }
          }
      }
    }
    let formdata = new FormData(form);
    xhr.send(formdata);
}