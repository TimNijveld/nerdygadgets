<?php
function getVoorraadTekst($actueleVoorraad) {
    if ($actueleVoorraad >= 1000) {
        return "Ruime voorraad beschikbaar.";
    } else {
        return "Voorraad: $actueleVoorraad";
    }
}

print(getVoorraadTekst(800). "\n");
print(getVoorraadTekst(999). "\n");
print(getVoorraadTekst(1000). "\n");
print(getVoorraadTekst(1001). "\n");
print(getVoorraadTekst(1200). "\n");
print(getVoorraadTekst(0). "\n");
print(getVoorraadTekst(-1000). "\n");