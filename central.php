<?php
    //récupération d'un nouveau vehicule
    $mysqli = new mysqli("127.0.0.1","root","","central");
    if ($mysqli -> connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
      exit();
    }
    
    $immat = isset($_REQUEST['immatriculation']) ? urldecode($_REQUEST['immatriculation']) : 0;
    $sql = 'SELECT id FROM vehicule WHERE immatriculation = '.$immat;
    $res = $mysqli->query($sql);
    if($res == null OR $res->num_rows == 0){
        $idOrigine = urldecode($_REQUEST["id"]);
        $proprietaireId = urldecode($_REQUEST["proprietaire_id"]);
        $modeleId = urldecode($_REQUEST["modele_id"]);
        $sql = "INSERT INTO vehicule (`id_origine`,`modele_id`, `proprietaire_id`, `chassis`, `ptac`, `place`, `puissance`, `kilometrage`, `couleur`, `cree_par`, `modifier_par`, `date_creation`, `date_modification`, `type_vehicule_id`, `format_immatriculation_id`, `immatriculation`, `commentaire`, `path`, `energie`, `pv`, `cu`, `puissance_reelle`, `capacite`, `moteur`, `type_carte_grise_id`, `carte_grise`, `date_carte_grise`, `date_validite`, `date_prochaine_visite`, `type_chassis`, `immatriculation_precedent`, `date_immatriculation_precedent`, `date_mise_circulation`, `compteur_revisitepv`, `alimentation`, `potCatalytique`, `synchro`) VALUES (".
                urldecode($_REQUEST["id"])
                .",".urldecode($_REQUEST["modele_id"])
                .",'".urldecode($_REQUEST["proprietaire_id"])
                .",'".urldecode($_REQUEST["chassis"])
                ."',".urldecode($_REQUEST["ptac"]).","
                .urldecode($_REQUEST["place"]).","
                .urldecode($_REQUEST["puissance"])
                .",".urldecode($_REQUEST["kilometrage"])
                .",'".urldecode($_REQUEST["couleur"])
                ."','".urldecode($_REQUEST["cree_par"])
                ."','".urldecode($_REQUEST["modifier_par"])
                ."','".urldecode($_REQUEST["date_creation"])."','"
                .urldecode($_REQUEST["date_modification"])
                ."',".urldecode($_REQUEST["type_vehicule_id"])
                .",'".urldecode($_REQUEST["format_immatriculation_id"])
                ."','".urldecode($_REQUEST["immatriculation"])."','"
                .urldecode($_REQUEST["commentaire"])
                ."','".urldecode($_REQUEST["path"])
                .",'".urldecode($_REQUEST["energie"])
                ."','".urldecode($_REQUEST["pv"])
                ."','".urldecode($_REQUEST["cu"])
                ."',".urldecode($_REQUEST["puissance_reelle"]).","
                .urldecode($_REQUEST["capacite"])
                .",'".urldecode($_REQUEST["moteur"])
                ."',".urldecode($_REQUEST["type_carte_grise_id"])
                .",'".urldecode($_REQUEST["carte_grise"])
                ."','".urldecode($_REQUEST["date_carte_grise"])
                ."','".urldecode($_REQUEST["date_validite"])
                ."','".urldecode($_REQUEST["date_prochaine_visite"])
                ."','".urldecode($_REQUEST["type_chassis"])
                ."','".urldecode($_REQUEST["immatriculation_precedent"])
                ."','".urldecode($_REQUEST["date_immatriculation_precedent"])
                ."','".urldecode($_REQUEST["date_mise_circulation"])
                ."','".urldecode($_REQUEST["compteur_revisitepv"])
                ."',".urldecode($_REQUEST["alimentation"])
                .",".urldecode($_REQUEST["potCatalytique"])
                .", NOW() )";
    }
    echo $sql;exit;
    if(!$mysqli->query($sql)){
        echo("Error description: " . $mysqli -> error);
    }
    $mysqli->close();
?>
