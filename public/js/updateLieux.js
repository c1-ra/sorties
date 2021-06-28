// construction du tableau avec les infos des lieux de la ville
// pour les afficher dans le cas où l'utilisateur modifie seulement le lieu mais pas la ville
$(document).ready( function() {
    let villeSelector = $("#app_sortie_ville");
    $.ajax({
        url: baseURL + "/api/lieux",
        type: "GET",
        dataType: "JSON",
        data: {
            ville: villeSelector.val()
        },
        success: function (lieux) {
            var lieuSelect = $("#app_sortie_lieu");

            //Ajout dans un tableau
            lieuxTab = [];
            $.each(lieux, function (key, lieu) {
                lieuxTab[lieu.id] = lieu;
            });
        },
        error: function (err) {
            alert("Erreur de lecture des données");
        }
    });
});

//Variables globales
let rue = $("#rue");
let codePostal = $("#codePostal");
let latitude = $("#latitude");
let longitude = $("#longitude");

$('#app_sortie_ville').change(function () {
    var villeSelector = $(this);
    $.ajax({
        url: baseURL + "/api/lieux",
        type: "GET",
        dataType: "JSON",
        data: {
            ville: villeSelector.val()
        },
        success: function (lieux) {
            var lieuSelect = $("#app_sortie_lieu");

            // Retirer les options et les valeurs relatives au lieu et à la ville)
            lieuSelect.html('');
            rue.html('');
            codePostal.html('');
            latitude.html('');
            longitude.html('');

            lieuSelect.append('<option value>Sélectionnez un lieu</option>');

            //Ajout dans un tableau
            lieuxTab = [];
            $.each(lieux, function (key, lieu) {
                lieuSelect.append('<option value="' + lieu.id + '">' + lieu.nom + '</option>');
                lieuxTab[lieu.id] = lieu;
            });
        },

        error: function (err) {
            alert("Erreur de lecture des données");
        }
    });
});

$('#app_sortie_lieu').change(function () {
    let id = $(this).val();
    rue.html('');
    rue.append(lieuxTab[id].rue);

    codePostal.html('');
    codePostal.append(lieuxTab[id].codePostal);

    latitude.html('');
    latitude.append(lieuxTab[id].latitude);

    longitude.html('');
    longitude.append(lieuxTab[id].longitude);
});