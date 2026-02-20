const swiper1 =  new Swiper('.testimonials-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 40
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },

      1200: {
        slidesPerView: 3,
        spaceBetween: 40
      }
    }
  });

  const swiper2 =  new Swiper('.testimonials-slider2', {
    speed: 600,
    loop:true,
    effect: 'coverflow',
    coverflowEffect: {
      rotate: 30,
      slideShadows: false,
    },
    autoplay: {
        delay: 2000,
        disableOnInteraction: false
      },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 40
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },

      1200: {
        slidesPerView: 3,
        spaceBetween: 40
      }
    }
  });
  const swiper3 =  new Swiper('.footer', {
    speed: 600,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
      },
    autoplay: {
      delay: 2000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',

    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 40
      },
 
      1200: {
        slidesPerView: 3,
        spaceBetween: 40
      }
    }
  });

