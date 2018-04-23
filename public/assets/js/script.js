"use strict"; // start of use strict


/**/
/* fix mobile hover */
/**/


$(window).ready(function() {
    init_rev_slider();
})


function init_rev_slider () {
  $('.tp-banner, .tp-banner-slider').on("revolution.slide.onloaded",function (e) {
    $('.tp-banner, .tp-banner-slider').css("opacity","1");
  });
  if ($('.tp-banner').length) {
    $('.tp-banner').revolution({
      dottedOverlay:"custom",
      delay:8000,
      startwidth:1170,
      startheight:700,
      lazyLoad:"on",
      responsiveLevels:[4096,1025,778,480],
      
      hideThumbs: 1000,
      thumbWidth:100,
      thumbHeight:50,
      thumbAmount:5,
      navigation: {
          arrows:{
            enable:true,
            left : {
              container:"slider",
              h_align:"left",
              v_align:"center",
              h_offset:0,
              v_offset:0,
            },
            right : {
              container:"slider",
              h_align:"right",
              v_align:"center",
              h_offset:0,
              v_offset:0
            }
          }        
      },
      touchenabled:"on",
      onHoverStop:"on",
      
      swipe_velocity: 0.7,
      swipe_min_touches: 1,
      swipe_max_touches: 1,
      drag_block_vertical: false,
                  
      keyboardNavigation:"off",    
      shadow:0,
      fullWidth:"off",
      fullScreen:"on",

      spinner:"off",
      parallax: {
        type:"mouse",
        origo:"slidercenter",
        speed:2000,
        levels:[2,3,4,5,6,7,12,16,10,50,47,48,49,50,51,55],
      },
      
      stopLoop:"off",
      stopAfterLoops:-1,
      stopAtSlide:-1,

      shuffle:"off",
      
      autoHeight:"off",           
      forceFullWidth:"off",                 
                  
      hideThumbsOnMobile:"off",
      hideNavDelayOnMobile:1500,            
      hideBulletsOnMobile:"off",
      hideArrowsOnMobile:"off",
      hideThumbsUnderResolution:0,
      
      startWithSlide:0,
      disableProgressBar: "on"
    })
  }
  if ($('.tp-banner-slider').length) {
    $('.tp-banner-slider').revolution({
      dottedOverlay:"custom",
      delay:8000,
      startwidth:1170,
      startheight:700,
      lazyLoad:"on",
      responsiveLevels:[4096,1025,778,480],
      
      hideThumbs: 1000,
      thumbWidth:100,
      thumbHeight:50,
      thumbAmount:5,
      parallax: {
        type:"mouse",
        origo:"slidercenter",
        speed:2000,
        levels:[2,3,4,5,6,7,12,16,10,50,47,48,49,50,51,55],
      },
      navigation: {
          arrows:{
            enable:true,
            left : {
              container:"slider",
              h_align:"left",
              v_align:"center",
              h_offset:0,
              v_offset:0,
            },
            right : {
              container:"slider",
              h_align:"right",
              v_align:"center",
              h_offset:0,
              v_offset:0
            }
          }        
      },
      touchenabled:"on",
      onHoverStop:"on",
      
      swipe_velocity: 0.7,
      swipe_min_touches: 1,
      swipe_max_touches: 1,
      drag_block_vertical: false,
                  
      keyboardNavigation:"off",
          
      shadow:0,
      fullWidth:"on",

      spinner:"off",
      
      stopLoop:"off",
      stopAfterLoops:-1,
      stopAtSlide:-1,

      shuffle:"off",
      
      autoHeight:"off",           
      forceFullWidth:"off",                 
                  
      hideThumbsOnMobile:"off",
      hideNavDelayOnMobile:1500,            
      hideBulletsOnMobile:"off",
      hideArrowsOnMobile:"off",
      hideThumbsUnderResolution:0,
      
      startWithSlide:0,
      disableProgressBar: "on"
    })
  }
}
