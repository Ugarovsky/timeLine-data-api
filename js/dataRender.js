const overview = document.getElementById('overview');
const data = Object.values(apiData).map(item => {
    return {
        albumDate : item.albumDate,
        items : item.items
    }
})

// const titles = data.items.map(item => { 
//     return ` <span class="year-info-event-link" data-behaviour="modal-iframe-trigger">
//     <span class="event-link-text">
//         <span class="event-link-text-short" data-role="event-link-short"
//             data-event-id="276">גרעין מיוצאי גרמני...</span>
//                 <span class="event-link-text-full bg-background c-alt"
//                 data-role="event-link-full" data-event-id="276">
//                 ${item.title}
//                 </span>
//     </span>
// </span>
// `
// })

data.forEach(item => {
    overview.innerHTML += `<div class="timeline-year" id="${item.albumDate}" style="display: block;" data-role="timeline-year">
    <div class="year-inner">
        <div class="year-images bg-background-light ">
            <div class="year-images-inner">
                <div class="images-list  nice-scroll-bar ">
    
                    <div class="thumb-block thumb-block--front-page thumb-block--dummy">
                        <div class="thumb-image thumb-image--dummy  thumb-image--dummy--add bg-background"
                            data-modal-trigger="home-add-content">
                            <i class="fas fa-plus c-background-light"></i>
                        </div>
                    </div>
                    <div class="thumb-block thumb-block--front-page thumb-block--dummy">
                        <div class="thumb-image thumb-image--dummy  thumb-image--dummy--add bg-background"
                            data-modal-trigger="home-add-content">
                            <i class="fas fa-plus c-background-light"></i>
                        </div>
                    </div>
    
    
                    <div class="thumb-block thumb-block--front-page thumb-block--dummy">
                        <div class="thumb-image thumb-image--dummy  thumb-image--dummy--add bg-background"
                            data-modal-trigger="home-add-content">
                            <i class="fas fa-plus c-background-light"></i>
                        </div>
                    </div>
                    <div class="thumb-block thumb-block--front-page thumb-block--dummy">
                        <div class="thumb-image thumb-image--dummy  thumb-image--dummy--add bg-background"
                            data-modal-trigger="home-add-content">
                            <i class="fas fa-plus c-background-light"></i>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
        <div class="year-info bg-background-light" data-role="year-event-list"
            data-year-id="211">
            <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="211">
               ${item.albumDate}</div>
            <div class="year-event-list nice-scroll-bar" id=${item.albumDate}-title>
              </div>
            <div class="mobile-overlay" data-event="requestSearchYear" data-event-type="click"
                data-event-data="211"></div>
        </div>
    </div>
    </div>`    
});
