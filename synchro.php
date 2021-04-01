<?php
//SCRIPT envoyant une requete GET  à autoscan central pour executer sauvegarder les nouveaux véhicules du centre
$mysqli = new mysqli("127.0.0.1","root","","autoscan");
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
$sql = 'SELECT * FROM vehicule WHERE synchro IS NULL OR synchro = 0 limit 1';
$lien = 'http://localhost/autoscan/central.php?';
$res = $mysqli->query($sql);
for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    $row = $res->fetch_assoc();
    $lien .= 'id='.urlencode($row['id'])
            .'&modele_id='.urlencode($row['modele_id'])
            .'&proprietaire_id='.urlencode($row['proprietaire_id'])
            .'&chassis='.urlencode($row['chassis'])
            .'&ptac='.urlencode($row['ptac'])
            .'&place='.urlencode($row['place'])
            .'&puissance='.urlencode($row['puissance'])
            .'&kilometrage='.urlencode($row['kilometrage'])
            .'&couleur='.urlencode($row['couleur'])
            .'&cree_par='.urlencode($row['cree_par'])
            .'&modifier_par='.urlencode($row['modifier_par'])
            .'&deletedAt='.urlencode($row['deletedAt'])
            .'&date_creation='.urlencode($row['date_creation'])
            .'&date_modification='.urlencode($row['date_modification'])
            .'&type_vehicule_id='.urlencode($row['type_vehicule_id'])
            .'&format_immatriculation_id='.urlencode($row['format_immatriculation_id'])
            .'&immatriculation='.urlencode($row['immatriculation'])
            .'&commentaire='.urlencode($row['commentaire'])
            .'&path='.urlencode($row['path'])
            .'&energie='.urlencode($row['energie'])
            .'&pv='.urlencode($row['pv'])
            .'&cu='.urlencode($row['cu'])
            .'&puissance_reelle='.urlencode($row['puissance_reelle'])
            .'&capacite='.urlencode($row['capacite'])
            .'&moteur='.urlencode($row['moteur'])
            .'&type_carte_grise_id='.urlencode($row['type_carte_grise_id'])
            .'&carte_grise='.urlencode($row['carte_grise'])
            .'&date_carte_grise='.urlencode($row['date_carte_grise'])
            .'&date_validite='.urlencode($row['date_validite'])
            .'&date_prochaine_visite='.urlencode($row['date_prochaine_visite'])
            .'&type_chassis='.urlencode($row['type_chassis'])
            .'&immatriculation_precedent='.urlencode($row['immatriculation_precedent'])
            .'&date_immatriculation_precedent='.urlencode($row['date_immatriculation_precedent'])
            .'&date_mise_circulation='.urlencode($row['date_mise_circulation'])
            .'&compteur_revisitepv='.urlencode($row['compteur_revisitepv'])
            .'&alimentation='.urlencode($row['alimentation'])
            .'&potCatalytique='.urlencode($row['potCatalytique'])            
            ;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $lien);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($curl);
    $code = curl_getinfo($curl);
    curl_close($curl);
    echo $return;
    if($return === true && $code['http_code'] >= 200 && $code['http_code'] <300){
        mysqli_query('UPDATE vehicule SET synchro = 1, date_synchro = NOW()');
        echo 'succes';
    }else{
        echo 'echec';
    }
    $mysqli->close();
}

?>