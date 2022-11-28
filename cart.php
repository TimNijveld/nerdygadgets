<?php
include "header.php";
include "CartFuncties.php";

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Winkelmand</title>
</head>

<body>
    <?php
    $cart = getCart();
    $ReturnableResult = null;
    //gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
    //totaal prijs berekenen
    //mooi weergeven in html
    //etc.
    $keys = "(";

    foreach ($cart as $key => $value) {
        $keys .= $key . ",";
    }
    $keys .= "-1)";

    $Query = "SELECT DISTINCT SI.StockItemID, SI.StockItemName, TaxRate, RecommendedRetailPrice,
                (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath
                FROM stockitems SI
                JOIN stockitemholdings SIH USING(stockitemid)
                JOIN stockitemstockgroups USING(StockItemID)
                JOIN stockgroups ON stockitemstockgroups.StockGroupID = stockgroups.StockGroupID
                WHERE SI.StockItemID in " . $keys;

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);

    function berekenVerkoopPrijs($adviesPrijs, $btw)
    {
        return $btw * $adviesPrijs / 100 + $adviesPrijs;
    }

    ?>
    <form method="post">
        <div class="cartContainer">
            <div class="pageTitle">
                <h1><span style="color: white">Jouw winkelmand</h1>
            </div>
            <div id="ResultsArea">
                <?php
                $totaal = 0;
                if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
                    foreach ($ReturnableResult as $row) {
                        $totaal += round(berekenVerkoopPrijs($row["RecommendedRetailPrice"], $row["TaxRate"]) * $cart[$row["StockItemID"]], 2);
                ?>
                        <!--  coderegel 1 van User story: bekijken producten  -->
                        <a class="ListItem"'>
                <!-- einde coderegel 1 van User story: bekijken producten   -->
                    <div id="ProductFrame">
                        <?php
                        if (isset($row['ImagePath'])) { ?>
                            <div class="ImgFrame"
                                 style="background-image: url(' <?php print "Public/StockItemIMG/" . $row['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;">
            </div>
        <?php } else if (isset($row['BackupImagePath'])) { ?>
            <div class="ImgFrame" style="background-image: url('<?php print "Public/StockGroupIMG/" . $row['BackupImagePath'] ?>'); background-size: cover;"></div>
        <?php } ?>
        <div id="StockItemFrameRight">
            <div class="CenterPriceLeftChild">
                <h1 class="StockItemPriceText">€<?php print sprintf(" %0.2f", berekenVerkoopPrijs($row["RecommendedRetailPrice"], $row["TaxRate"])); ?></h1>
                <h6>Inclusief BTW </h6>
                <input style="width: 30px; height: 30px; background-color: darkgray" type="submit" name="minus<?php print($row["StockItemID"]) ?>" value="-">
                <input style="width: 150px; background-color: darkgray" type="number" name="quantity<?php print($row["StockItemID"]) ?>" value="<?php print($cart[$row["StockItemID"]]) ?>" min="1">
                <input style="width: 30px; height: 30px; margin-right: 5px; background-color: darkgray" type="submit" name="plus<?php print($row["StockItemID"]) ?>" value="+">
                <input class="button" style="width: 150px; background-color: darkgray" type="submit" name="delete<?php print($row["StockItemID"]) ?>" value="Verwijderen">
            </div>
        </div>
        <h1 class="StockItemID">Artikelnummer: <?php print $row["StockItemID"]; ?></h1>
        <p class="StockItemName"><?php print $row["StockItemName"]; ?></p>
        </div>
        <!--  coderegel 2 van User story: bekijken producten  -->
        </a>
        <!--  einde coderegel 2 van User story: bekijken producten  -->
    <?php } ?>
<?php } ?>
</div>
    </form>
    <br>
    <div class="CenterPriceLeft">
        <h6>Totaal bedrag: </h6>
        <h1>€<?php print($totaal) ?></h1>
        <input class="button" style="width: 150px; background-color: darkgray" type="button" name="verderWinkelen" value="Verder winkelen" onClick="location.href ='./'">
        <input class="button" style="width: 150px; background-color: darkgray" type="button" name="checkout" value="Afrekenen" onClick="location.href='./checkout.php'">
        <form method="post">
            <input class="button" style="width: 150px; background-color: darkgray" type="submit" name="legen" value="Winkelmand legen">
        </form>
    </div>
    <?php
    if (isset($_POST["legen"])) {
        $cart = array();
        saveCart($cart);
        echo "<meta http-equiv='refresh' content='0'>";
    }
    if (isset($_POST["verderWinkelen"])) {
        echo "<a href='./'></a>";
    }
    foreach ($cart as $key => $value) {
        if (isset($_POST["delete" . $key])) {
            unset($cart[$key]);
            saveCart($cart);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        if (isset($_POST["minus" . $key])) {
            if ($cart[$key] == 1) {
                unset($cart[$key]);
                saveCart($cart);
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                $cart[$key] = $value - 1;
                saveCart($cart);
                echo "<meta http-equiv='refresh' content='0'>";
            }
        }
        if (isset($_POST["plus" . $key])) {
            $cart[$key] = $value + 1;
            saveCart($cart);
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }
    ?>


</html>