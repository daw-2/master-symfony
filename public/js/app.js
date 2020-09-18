$(function () {
    $('input[type="file"]').on('change', function () {
        // C:\Fakepath\image.jpg
        // => ['C:', 'Fakepath', 'image.jpg']
        // => image.jpg
        var filename = $(this).val().split('\\').pop();

        // Le next() est le label après le input
        $(this).next().text(filename);

        $('#product img').remove(); // On clean les anciennes
        // images
        var img = $('<img class="img-fluid" width="250" />');

        // On ajoute l'image dans la div qui contient le input file
        $(this).parent().parent().append(img);

        // On récupère un objet File en JS
        let file = this.files[0];
        // Avec un filereader, on peut lire un fichier en JS
        let reader = new FileReader();

        reader.addEventListener('load', function (file) {
            // Une fois qu'on a chargé l'image, on l'affiche dans le src
            // de la balise img
            img.attr('src', file.target.result);
        });

        reader.readAsDataURL(file);
    });
});
