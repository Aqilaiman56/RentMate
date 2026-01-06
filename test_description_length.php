<?php

// Test description length
$itemName = "Canon EOS 5D Mark IV DSLR Camera";
$startDate = "6 Jan";
$endDate = "10 Jan 2026";

// Current logic
if (strlen($itemName) > 40) {
    $itemName = substr($itemName, 0, 37) . '...';
}

$billDescription = 'Deposit for ' . $itemName . ' (' . $startDate . '-' . $endDate . ')';
$finalDescription = substr($billDescription, 0, 100);

echo "Item Name: $itemName\n";
echo "Bill Description: $billDescription\n";
echo "Final Description: $finalDescription\n";
echo "Length: " . strlen($finalDescription) . " characters\n";
echo "\n";

// Test with long item name
$longItemName = "Sony Alpha a7R IV Mirrorless Digital Camera with Professional Lens Kit";
if (strlen($longItemName) > 40) {
    $longItemName = substr($longItemName, 0, 37) . '...';
}

$billDescription2 = 'Deposit for ' . $longItemName . ' (' . $startDate . '-' . $endDate . ')';
$finalDescription2 = substr($billDescription2, 0, 100);

echo "Long Item Name: $longItemName\n";
echo "Bill Description: $billDescription2\n";
echo "Final Description: $finalDescription2\n";
echo "Length: " . strlen($finalDescription2) . " characters\n";
