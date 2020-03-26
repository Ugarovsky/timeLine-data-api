const overview = document.getElementById('overview');
const data = Object.values(apiData).map(item => {
    return {
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
                        data: element.albumDate,
                        media: element.items
                    });
            }
        })
    })
    return albumByYear;
}

const sortAlbumData = concatAlbumData();
console.log(sortAlbumData);