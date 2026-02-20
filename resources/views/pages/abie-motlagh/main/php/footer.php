<footer id="newsletter">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-heading mt-5">
                        <h4>عضویت در خبرنامه </h4>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-3">
                    <form id="search" class="form" action="#" method="GET">
                        <div class="row form-group">
                            <div class="col-lg-6 col-sm-6">
                                <fieldset>
                                    <input type="address" style="direction:rtl" name="address" class="email form-control" placeholder="ایمیل" autocomplete="on" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <fieldset>
                                    <button type="submit" class="main-button submit"> <i class="fa fa-angle-left"></i> عضویت</button>
                                </fieldset>
                            </div>
                            <div class="col error-mail"></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-widget mb-5">
                        <h4>ارتباط با ما</h4>
                        <p><a href="mailto:abie-motlagh@hounaar.com">abie-motlagh@hounaar.com</a></p>
                        <a href="https://t.me/abie_motlagh"><i class="icons bi bi-telegram"></i></a>
                        <a href="https://twitter.com/abie_motlagh"><i class="icons ml-5 bi bi-instagram"></i></a>
                        <a href="https://instagram.com/abie_motlagh"><i class="icons ml-5 bi bi-twitter"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="footer-widget mb-5">
                        <h4>مجوزات</h4>
                        <div class="logo">
                            <img src="/main/pic/logo/enamad.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="copyright-text">
                        <p>استفاده از هر گونه مطالب این فروشگاه فقط برای مقاصد غیر تجاری با ذکر منبع بلامانع است.کلیه حقوق متعلق به سامانه آبی مطلق است</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <script type="text/javascript">
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
                swal({
  title: "موفق",
  text: "ایمیل شما با موفقیت در خبرنامه ها ثبت شد",
  icon: "success",
});
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
    </script>