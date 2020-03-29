sortAlbumData.forEach(element => {
    let titles = ``
    let images = ``

    element.items.map(item => {
        titles += `  
        <span class="year-info-event-link" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="getPopUp('${item.data}')">
        <span class="event-link-text">
        <span class="event-link-text-short" data-role="event-link-short"
         data-event-id="291">${item.name}</span>
        </span>
        </span>`

        console.log(item.data);

        images += ` <div class="thumb-block thumb-block--image has-title" data-role="thumb"
        data-behaviour="modal-iframe-trigger">
        <div class="thumb-image lazy-loading" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="getPopUp('${item.data}')"
        data-lazy-background-image="${item.media[0].image}">
        </div>
        <div class="thumb-text c-text"></div>
        </div>`
    })

    overview.innerHTML += `<div class="timeline-year" style="display: block;" data-role="timeline-year">
    <div class="year-inner">
        <div class="year-images bg-background-light">
            <div type="button" class="year-images-inner">
                <div class="images-list  nice-scroll-bar">
                   ${images}
                </div>
            </div>
        </div>
        <div class="year-info bg-background-light" data-role="year-event-list"
            data-year-id="209">
            <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="209">
                ${element.year} </div>
            <div class="year-event-list nice-scroll-bar">
              ${titles}
            </div>
           
        </div>
    </div>
</div>`
})



sortAlbumData.forEach(element => {
    let titles = ``
    let images = ``

    element.items.map(item => {
        titles += `  
        <span class="year-info-event-link" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="getPopUp('${item.data}')">
        <span class="event-link-text">
        <span class="event-link-text-short" data-role="event-link-short"
         data-event-id="291">${item.name}</span>
        </span>
        </span>`

        console.log(item.data);

        images += ` <div class="thumb-block thumb-block--image has-title" data-role="thumb"
        data-behaviour="modal-iframe-trigger">
        <div class="thumb-image lazy-loading" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="getPopUp('${item.data}')"
        data-lazy-background-image="${item.media[0].image}">
        </div>
        <div class="thumb-text c-text"></div>
        </div>`
    })

    overview.innerHTML += `<div class="timeline-year" style="display: block;" data-role="timeline-year">
    <div class="year-inner">
        <div class="year-images bg-background-light">
            <div type="button" class="year-images-inner">
                <div class="images-list  nice-scroll-bar">
                   ${images}
                </div>
            </div>
        </div>
        <div class="year-info bg-background-light" data-role="year-event-list"
            data-year-id="209">
            <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="209">
                ${element.year} </div>
            <div class="year-event-list nice-scroll-bar">
              ${titles}
            </div>
           
        </div>
    </div>
</div>`
})

sortAlbumData.forEach(element => {
    let titles = ``
    let images = ``

    element.items.map(item => {
        titles += `  
        <span class="year-info-event-link" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="getPopUp('${item.data}')">
        <span class="event-link-text">
        <span class="event-link-text-short" data-role="event-link-short"
         data-event-id="291">${item.name}</span>
        </span>
        </span>`

        console.log(item.data);

        images += ` <div class="thumb-block thumb-block--image has-title" data-role="thumb"
        data-behaviour="modal-iframe-trigger">
        <div class="thumb-image lazy-loading" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="getPopUp('${item.data}')"
        data-lazy-background-image="${item.media[0].image}">
        </div>
        <div class="thumb-text c-text"></div>
        </div>`
    })

    overview.innerHTML += `<div class="timeline-year" style="display: block;" data-role="timeline-year">
    <div class="year-inner">
        <div class="year-images bg-background-light">
            <div type="button" class="year-images-inner">
                <div class="images-list  nice-scroll-bar">
                   ${images}
                </div>
            </div>
        </div>
        <div class="year-info bg-background-light" data-role="year-event-list"
            data-year-id="209">
            <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="209">
                ${element.year} </div>
            <div class="year-event-list nice-scroll-bar">
              ${titles}
            </div>
           
        </div>
    </div>
</div>`
})

const getPopUp = (value) => {
    dataCount = 0; 
    const imageListTemplate = imageList(value);
    template.innerHTML = `
    <!-- Container for the image gallery -->
    <div class="conainer" style="width:90%;margin:auto;padding:20px;">
    
      <!-- Full-width images with number text -->
    
    ${imageListTemplate[0]}


    
    
      <!-- Image text -->
      <div class="caption-container" id="title">
      <h1 >${pickedAlbum.year}</h1>
      <h1 >${pickedAlbum.name}</h1>
      </div>
      <div class="row" style="padding:0 20px">

      ${imageListTemplate[1]}

      </div>
    </div>`

    showSlides(slideIndex);
}

const imageList = (value) => {
    list = [``,``]; 
    
    sortAlbumData.map(sortItem => {
        sortItem.items.forEach(element => {
            if(value == element.data) { 
                pickedAlbum = { 
                    media : element.media,
                    name : element.name,
                    year : element.data,
                }
            }
        })
    })

    pickedAlbum.media.forEach((item,i) => {
        
    if(item.video){
        const videoLink = item.video.replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/');
            list[0] += `<div class="mySlides">
            <iframe width="100%" height="${window.innerHeight / 2.5}" src="${videoLink}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <h1 style="text-align:right; font-size: 3vw;
            font-weight: 900;
            text-align: right;">${item.title ? item.title : ''}</h1>
          </div>
        `
            list[1] += `<div class="column">
            <img class="demo cursor" src="${item.image}" style="width:100%" onclick="currentSlide(${i + 1})">
            </div>
        `
        }
        else { 
        list[0] += `<div class="mySlides">
          <img src="${item.image}" style="width:100%">
          <h1 style="text-align:right; font-size: 3vw;
            font-weight: 900;
            text-align: right;">${item.title ? item.title : ''}</h1>
      </div>
    `
        list[1] += `<div class="column">
        <img class="demo cursor" src="${item.image}" style="width:100%" onclick="currentSlide(${i + 1})">
     
      </div>
    `
        }
    })
    

    return list;
}

var slideIndex = 0;


// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}
