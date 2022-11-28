<?php

function berekenVerkoopPrijs($adviesPrijs, $btw)
{
    return $btw * $adviesPrijs / 100 + $adviesPrijs;
}

print(berekenVerkoopPrijs(10, 21) . "\n");
print(berekenVerkoopPrijs(10, 0) . "\n");
print(berekenVerkoopPrijs(0, 21) . "\n");
print(berekenVerkoopPrijs(0, 0) . "\n");
print(berekenVerkoopPrijs(999999, 21) . "\n");
print(berekenVerkoopPrijs(9, -21) . "\n");
print(berekenVerkoopPrijs(-999, 21) . "\n");
print(berekenVerkoopPrijs(9223372036854775807, 21) . "\n");