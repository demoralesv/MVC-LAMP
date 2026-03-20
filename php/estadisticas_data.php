<?php
require_once "functions.php";

header("Content-Type: application/json; charset=utf-8");

$stats = json_decode(getStats($conn), true);

if (!is_array($stats)) {
    $stats = [];
}

$itemsPerPage = 5;
$totalUrls = count($stats);
$totalPages = max(1, ceil($totalUrls / $itemsPerPage));

$currentPage = isset($_GET["page"]) ? (int) $_GET["page"] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$startIndex = ($currentPage - 1) * $itemsPerPage;
$pagedStats = array_slice($stats, $startIndex, $itemsPerPage);

$selectedId = isset($_GET["id"]) && $_GET["id"] !== ""
    ? (int) $_GET["id"]
    : null;

$selectedDetail = null;

if (!empty($stats) && $selectedId !== null) {
    foreach ($stats as $item) {
        if ((int) $item["id"] === $selectedId) {
            $selectedDetail = $item;
            break;
        }
    }
}

$dateLabels = [];
$dateFrequencies = [];
$countryLabels = [];
$countryFrequencies = [];

if ($selectedDetail) {
    if (!empty($selectedDetail["dates"])) {
        foreach ($selectedDetail["dates"] as $dateItem) {
            $dateLabels[] = $dateItem["date"];
            $dateFrequencies[] = (int) $dateItem["frequency"];
        }
    }

    if (!empty($selectedDetail["countries"])) {
        foreach ($selectedDetail["countries"] as $countryItem) {
            $countryLabels[] = $countryItem["name"];
            $countryFrequencies[] = (int) $countryItem["frequency"];
        }
    }
}

echo json_encode([
    "currentPage" => $currentPage,
    "totalPages" => $totalPages,
    "selectedId" => $selectedId,
    "pagedStats" => $pagedStats,
    "selectedDetail" => $selectedDetail,
    "charts" => [
        "dateLabels" => $dateLabels,
        "dateFrequencies" => $dateFrequencies,
        "countryLabels" => $countryLabels,
        "countryFrequencies" => $countryFrequencies
    ]
], JSON_UNESCAPED_UNICODE);
