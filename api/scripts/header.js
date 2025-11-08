function changeBackgroundOnScroll(){
    window.addEventListener('scroll', function(){
        var header = document.getElementById('header-container');

        if ((window.scrollY != 0) && (window.innerWidth > 700)){
            header.style.backgroundColor = '#3B3838';
        }
        else {
            header.style.backgroundColor = 'rgba(0 ,0, 0, 0)';
        }
    })
}