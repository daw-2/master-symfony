$(function () {
    $('input[type="file"]').on('change', function () {
        // C:\Fakepath\image.jpg
        // => ['C:', 'Fakepath', 'image.jpg']
        // => image.jpg
        var filename = $(this).val().split('\\').pop();
        console.log(filename);

        // Le next() est le label après le input
        $(this).next().text(filename);
    });    
});
