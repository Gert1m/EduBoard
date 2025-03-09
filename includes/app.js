if (/Mobi|Android/i.test(navigator.userAgent)) {
    document.body.classList.add('mobile');
} else {
    document.body.classList.add('desktop');
}
