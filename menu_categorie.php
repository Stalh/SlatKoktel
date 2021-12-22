<?php
session_start();
// On réinitialise les choix.
for ($i = 1; $i < 9; ++$i) {
    $_SESSION["choix" . $i] = NULL;
}
if (isset($_GET["dernier_choix"])) {
    $dernier_choix = $_GET["dernier_choix"];
}
else {
    $dernier_choix = 0;
}
// On récupère les choix précédents.
if (!empty($_POST["aliment1"]) && ($dernier_choix >= 1)) {
    $_SESSION["choix1"] = $_POST["aliment1"];
    if (!empty($_POST["aliment2"]) && ($dernier_choix >= 2)) {
        $_SESSION["choix2"] = $_POST["aliment2"];
        if (!empty($_POST["aliment3"]) && ($dernier_choix >= 3)) {
            $_SESSION["choix3"] = $_POST["aliment3"];
            if (!empty($_POST["aliment4"]) && ($dernier_choix >= 4)) {
                $_SESSION["choix4"] = $_POST["aliment4"];
                if (!empty($_POST["aliment5"]) && ($dernier_choix >= 5)) {
                    $_SESSION["choix5"] = $_POST["aliment5"];
                    if (!empty($_POST["aliment6"]) && ($dernier_choix >= 6)) {
                        $_SESSION["choix6"] = $_POST["aliment6"];
                        if (!empty($_POST["aliment7"]) && ($dernier_choix >= 7)) {
                            $_SESSION["choix7"] = $_POST["aliment7"];
                            if (!empty($_POST["aliment8"]) && ($dernier_choix >=8)) {
                                $_SESSION["choix8"] = $_POST["aliment8"];
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

<!-- On prépare une première liste avec tous les aliments de base-->
<form name="listes">
    <select name="aliment1" onchange="change_aliment(1)">
        <option value="" selected></option>
        <option value="7" <?php if ($_SESSION["choix1"] == 7) {
                                echo "selected";
                            } ?>>Fruit</option>
        <option value="9" <?php if ($_SESSION["choix1"] == 9) {
                                echo "selected";
                            } ?>>Assaisonnement</option>
        <option value="10" <?php if ($_SESSION["choix1"] == 10) {
                                echo "selected";
                            } ?>>Légume</option>
        <option value="13" <?php if ($_SESSION["choix1"] == 13) {
                                echo "selected";
                            } ?>>Liquide</option>
        <option value="19" <?php if ($_SESSION["choix1"] == 19) {
                                echo "selected";
                            } ?>>Noix et graine oléagineuse</option>
        <option value="21" <?php if ($_SESSION["choix1"] == 21) {
                                echo "selected";
                            } ?>>Oeuf</option>
        <option value="33" <?php if ($_SESSION["choix1"] == 33) {
                                echo "selected";
                            } ?>>Aliments divers</option>
        <option value="88" <?php if ($_SESSION["choix1"] == 88) {
                                echo "selected";
                            } ?>>Produit laitier</option>
    </select>

    <?php
    // Pour chaque liste, on génère la suivante si le besoin est, on s'arrête à 8 car dans la hiérarchie impossible de descendre en dessous de 8 sous-aliments.
    for ($i = 0; $i < 8; ++$i) {

        echo ("<br><br>");

        // On se connecte à la BDD.
        $bdd = new PDO('mysql:host=localhost;dbname=SlatKoktel;charset=utf8;', 'slatkoktel', 'root2');

        // On récupère l'id et le nom de l'aliment sélectionné.
        $sql = "SELECT a.al_idAliment, a.al_nomAliment FROM Aliments a JOIN SuperCategorie sp ON sp.spc_idAliment = a.al_idAliment WHERE sp.spc_idAlimentSuperCategorie = :choix";

        $test = $bdd->prepare($sql);

        // On sélectione l'aliment en fonction du menu où l'on se situe.
        if (!$test->execute(['choix' => $_SESSION["choix" . ($i + 1)]])) {
            print_r($test->errorInfo());
        }

        // Si on a rempli le choix actuel et qu'il possède des sous catégories, on les affiche dans le menu suivant.
        if ((strlen($_SESSION["choix" . ($i + 1)]) > 0) && ($test->fetch() != null)) {
            // On rééxecute la requête pour se remettre au début.

            $test->execute(['choix' => $_SESSION["choix" . ($i + 1)]]);
            echo '<select name="aliment';
            echo ($i + 2);
            echo '" onchange="change_aliment(' . ($i + 2) . ')"><option value=""></option>';
            // Pour chaque sous catégorie on crée une option.
            while ($row = $test->fetch()) {
                if ($_SESSION["choix" . ($i + 2)] == $row['al_idAliment']) {
                    $selection = "selected";
                } else {
                    $selection = "";
                }
                echo '<option value="' . $row['al_idAliment'] . '" ' . $selection . '>' . $row['al_nomAliment'] . '</option>';
            }
            echo '</select>';
        }
    }
    ?>

</form>