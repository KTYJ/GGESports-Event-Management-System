// For PC img

const images = document.querySelectorAll('.PC');

    images.forEach(image => {
        image.addEventListener('mouseover', function() {
            images.forEach(otherImage => {
                if (otherImage !== image) {
                    otherImage.style.filter = 'brightness(25%) contrast(100%)';
                } else {
                    otherImage.style.filter = 'brightness(100%) contrast(100%)';
                }
            });
        });

        image.addEventListener('mouseout', function() {
            images.forEach(otherImage => {
                otherImage.style.filter = 'brightness(100%) contrast(100%)';
            });
        });
    });

// For Mobile IMG

const imagesM = document.querySelectorAll('.mbG');

    imagesM.forEach(image => {
        image.addEventListener('mouseover', function() {
            imagesM.forEach(otherImage => {
                if (otherImage !== image) {
                    otherImage.style.filter = 'brightness(25%) contrast(100%)';
                } else {
                    otherImage.style.filter = 'brightness(100%) contrast(100%)';
                }
            });
        });

        image.addEventListener('mouseout', function() {
            imagesM.forEach(otherImage => {
                otherImage.style.filter = 'brightness(100%) contrast(100%)';
            });
        });
    });


// Content for PC and Moblie

function showText(textId) {
    var text = document.getElementById(textId);
    text.style.opacity = 1; // Set opacity to 1 to make the text visible
}

function hideText(textId) {
    var text = document.getElementById(textId);
    text.style.opacity = 0; // Set opacity to 0 to hide the text
}

// Scrolling Behaviours for Images

document.getElementById('csgo').addEventListener('click', function() {
    document.getElementById('desc-media1').scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('lol').addEventListener('click', function() {
    document.getElementById('desc-media2').scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('apex').addEventListener('click', function() {
    document.getElementById('desc-media3').scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('penguin').addEventListener('click', function() {
    document.getElementById('desc-media4').scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('pubg').addEventListener('click', function() {
    document.getElementById('desc-media5').scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('wangZ').addEventListener('click', function() {
    document.getElementById('desc-media6').scrollIntoView({ behavior: 'smooth' });
});
