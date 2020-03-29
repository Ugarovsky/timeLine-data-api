const overview = document.getElementById('overview');
const template = document.getElementById('popup-content');
const data = Object.values(apiData).map((item,i) => {
    return {
        name: Object.keys(apiData)[i],
        albumDate: item.albumDate,
        albumYear: item.albumDate.slice(0, 4),
        items: Object.values(item.items).map(elem => {
            return {
                title: elem.title,
                image: elem.image,
                video: elem.video
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
