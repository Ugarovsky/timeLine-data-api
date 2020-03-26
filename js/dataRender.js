const overview = document.getElementById('overview');
const data = Object.values(apiData).map(item => {
    return {
        albumDate: item.albumDate,
        items: Object.values(item.items).map(elem => {
            return {
                title: elem.title,
                image: elem.image
            }
        })
    }
})

data.forEach(element => {
    let date = element.albumDate;
    let titles = ``
    let images = ``

    element.items.map(item => {
        titles += `  
        <span class="year-info-event-link">
        <span class="event-link-text">
        <span class="event-link-text-short" data-role="event-link-short"
         data-event-id="291">${item.title}</span>
        </span>
        </span>`

        images += ` <div class="thumb-block thumb-block--image has-title" data-role="thumb"
        data-behaviour="modal-iframe-trigger">
        <div class="thumb-image lazy-loading"
        data-lazy-background-image="${item.image}">
        </div>
        <div class="thumb-text c-text">גרעין גזית (לימי...</div>
        </div>`
    })

    overview.innerHTML += `<div class="timeline-year" style="display: block;" data-role="timeline-year">
    <div class="year-inner">
        <div class="year-images bg-background-light ">
            <div class="year-images-inner">
                <div class="images-list  nice-scroll-bar ">
                   ${images}
                </div>
            </div>
        </div>
        <div class="year-info bg-background-light" data-role="year-event-list"
            data-year-id="209">
            <div class="year-title c-alt" data-event="requestSearchYear" data-event-data="209">
                ${date} </div>
            <div class="year-event-list nice-scroll-bar">
              ${titles}
            </div>
            <div class="mobile-overlay" data-event="requestSearchYear" data-event-type="click"
                data-event-data="209"></div>
        </div>
    </div>

</div>`

    console.log(images);
})



















