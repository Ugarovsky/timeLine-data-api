const overview = document.getElementById('overview');
const data = Object.values(apiData).map((item,i) => {
    return {
        name: Object.keys(apiData)[i],
        albumDate: item.albumDate,
        albumYear: item.albumDate.slice(0, 4),
        items: Object.values(item.items).map(elem => {
            return {
                title: elem.title,
                image: elem.image
            }
        })
    }
})

const albumArray = () => {
    let albumArray = []

    data.forEach(element => {
        isDouble = 0;

        if (!albumArray.length) {
            albumArray.push({
                year: element.albumYear,
                items: [],
            })
        }
        else {
            data.map(item => {
                isDouble += item.albumYear == element.albumYear ? 1 : 0;
            })

            if (isDouble < 2) {
                albumArray.push({
                    year: element.albumYear,
                    items: [],
                })
            }
        }
    })
    return albumArray;
}

const concatAlbumData = () => {
    const albumByYear = albumArray();

    albumByYear.forEach(item => {
        data.map(element => {
            if (item.year == element.albumYear) {
                item.items.push(
                    {
                        name: element.name,
                        data: element.albumDate,
                        media: element.items
                    });
            }
        })
    })
    return albumByYear;
}

const sortAlbumData = concatAlbumData();

//////////////////////////////////////////////////////////
// sortAlbumData.forEach(element => {
//     let titles = ``
//     let images = ``

//     element.items.map(item => {
//         titles += `  
//         <span class="year-info-event-link">
//         <span class="event-link-text">
//         <span class="event-link-text-short" data-role="event-link-short"
//          data-event-id="291">${item.name}</span>
//         </span>
//         </span>`

//         images += ` <div class="thumb-block thumb-block--image has-title" data-role="thumb"
//         data-behaviour="modal-iframe-trigger">
//         <div class="thumb-image lazy-loading"
//         data-lazy-background-image="${item.media[0].image}">
//         </div>
//         <div class="thumb-text c-text"></div>
//         </div>`
//     })

//     overview.innerHTML += `<div class="timeline-year" style="display: block;" data-role="timeline-year">
//     <div class="year-inner">
//         <div class="year-images bg-background-light" onclick="getPopUp(${element.year})">
//             <div class="year-images-inner">
//                 <div class="images-list  nice-scroll-bar ">
//                    ${images}
//                 </div>
//             </div>
//         </div>
//         <div class="year-info bg-background-light" data-role="year-event-list"
//             data-year-id="209">
//             <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="209">
//                 ${element.year} </div>
//             <div class="year-event-list nice-scroll-bar">
//               ${titles}
//             </div>
//             <div class="mobile-overlay" data-event="requestSearchYear" data-event-type="click"
//                 data-event-data="209"></div>
//         </div>
//     </div>
// </div>`

// })

// sortAlbumData.forEach(element => {
//     let titles = ``
//     let images = ``

//     element.items.map(item => {
//         titles += `  
//         <span class="year-info-event-link">
//         <span class="event-link-text">
//         <span class="event-link-text-short" data-role="event-link-short"
//          data-event-id="291">${item.name}</span>
//         </span>
//         </span>`

//         images += ` <div class="thumb-block thumb-block--image has-title" data-role="thumb"
//         data-behaviour="modal-iframe-trigger">
//         <div class="thumb-image lazy-loading"
//         data-lazy-background-image="${item.media[0].image}">
//         </div>
//         <div class="thumb-text c-text"></div>
//         </div>`
//     })

//     overview.innerHTML += `<div class="timeline-year" style="display: block;" data-role="timeline-year">
//     <div class="year-inner">
//         <div class="year-images bg-background-light" onclick="getPopUp(${element.year})">
//             <div class="year-images-inner">
//                 <div class="images-list  nice-scroll-bar ">
//                    ${images}
//                 </div>
//             </div>
//         </div>
//         <div class="year-info bg-background-light" data-role="year-event-list"
//             data-year-id="209">
//             <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="209">
//                 ${element.year} </div>
//             <div class="year-event-list nice-scroll-bar">
//               ${titles}
//             </div>
//             <div class="mobile-overlay" data-event="requestSearchYear" data-event-type="click"
//                 data-event-data="209"></div>
//         </div>
//     </div>
// </div>`

// })

sortAlbumData.forEach(element => {
    let titles = ``
    let images = ``

    element.items.map(item => {
        titles += `  
        <span class="year-info-event-link">
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
            <div class="mobile-overlay" data-event="requestSearchYear" data-event-type="click"
                data-event-data="209"></div>
        </div>
    </div>
</div>`
})

const getPopUp = (value) => { 
    const template = document.getElementById('popup-content');
    template.innerHTML = `<div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:960px;height:480px;overflow:hidden;visibility:hidden;background-color:#24262e;">
    <!-- Loading Screen -->
    <div data-u="slides" style="cursor:default;position:relative;top:0px;left:240px;width:720px;height:480px;overflow:hidden;">
        <div>
            <img data-u="image" src="img/004-s99x66.jpg" />
            <img data-u="thumb" src="img/004-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/005-s99x66.jpg" />
            <img data-u="thumb" src="img/005-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/006-s99x66.jpg" />
            <img data-u="thumb" src="img/006-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/007-s99x66.jpg" />
            <img data-u="thumb" src="img/007-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/008-s99x66.jpg" />
            <img data-u="thumb" src="img/008-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/009-s99x66.jpg" />
            <img data-u="thumb" src="img/009-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/010-s99x66.jpg" />
            <img data-u="thumb" src="img/010-s99x66.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/015.jpg" />
            <img data-u="thumb" src="img/015.jpg" />
        </div>
        <div>
            <img data-u="image" src="img/015.jpg" />
            <img data-u="thumb" src="img/015.jpg" />
        </div>

    </div><a data-scale="0" href="https://www.jssor.com" style="display:none;position:absolute;">web animation composer</a>
    <!-- Thumbnail Navigator -->
    <div data-u="thumbnavigator" class="jssort101" style="position:absolute;left:0px;top:0px;width:240px;height:480px;background-color:#000;" data-autocenter="2" data-scale-left="0.75">
        <div data-u="slides">
            <div data-u="prototype" class="p" style="width:99px;height:66px;">
                <div data-u="thumbnailtemplate" class="t"></div>
                <svg viewbox="0 0 16000 16000" class="cv">
                    <circle class="a" cx="8000" cy="8000" r="3238.1"></circle>
                    <line class="a" x1="6190.5" y1="8000" x2="9809.5" y2="8000"></line>
                    <line class="a" x1="8000" y1="9809.5" x2="8000" y2="6190.5"></line>
                </svg>
            </div>
        </div>
    </div>
    <!-- Arrow Navigator -->
    <div data-u="arrowleft" class="jssora093" style="width:50px;height:50px;top:0px;left:270px;" data-autocenter="2">
        <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
            <circle class="c" cx="8000" cy="8000" r="5920"></circle>
            <polyline class="a" points="7777.8,6080 5857.8,8000 7777.8,9920 "></polyline>
            <line class="a" x1="10142.2" y1="8000" x2="5857.8" y2="8000"></line>
        </svg>
    </div>
    <div data-u="arrowright" class="jssora093" style="width:50px;height:50px;top:0px;right:30px;" data-autocenter="2">
        <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
            <circle class="c" cx="8000" cy="8000" r="5920"></circle>
            <polyline class="a" points="8222.2,6080 10142.2,8000 8222.2,9920 "></polyline>
            <line class="a" x1="5857.8" y1="8000" x2="10142.2" y2="8000"></line>
        </svg>
    </div>
</div>`

jssor_1_slider_init();

}