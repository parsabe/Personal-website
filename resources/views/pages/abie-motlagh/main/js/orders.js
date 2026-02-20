const order = document.querySelector(".order"),
continueBtn = order.querySelector(".apply"),
eror = order.querySelector('.error-message');

order.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/main/php/order.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                    alert("سفارش شما با موفقیت انجام شد . و در حال پردازش میباشد.");
            }
              else{
                eror.style.display = "block";
                eror.textContent = data;
              }
          }
      }
    }
    let orderdata = new FormData(order);
    xhr.send(orderdata);
}