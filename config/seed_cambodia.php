<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = realpath(__DIR__ . '/../app') . DIRECTORY_SEPARATOR;
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

require_once __DIR__ . '/config.php';

use App\Core\Database;

$db = Database::getInstance()->getConnection();
$baseUrl = 'https://cambo-gazetteer.manethpak.dev/api/v1';

function fetchAll($url) {
    $all = [];
    $page = 1;
    $perPage = 20;
    do {
        $json = file_get_contents("$url?page=$page&perPage=$perPage");
        $data = json_decode($json, true);
        $items = $data['data'] ?? [];
        $all = array_merge($all, $items);
        $totalPages = $data['pagination']['totalPages'] ?? 1;
        echo "  page $page/$totalPages (" . count($items) . " items)\n";
        $page++;
    } while (($data['pagination']['hasNextPage'] ?? false) && $page <= ($data['pagination']['totalPages'] ?? 1));
    return $all;
}

echo "Seeding Cambodia administrative data...\n";

// 1. Provinces (only 25, API returns all at once)
echo "Fetching provinces...\n";
$provincesJson = file_get_contents("$baseUrl/provinces");
$provincesData = json_decode($provincesJson, true);
$provinces = $provincesData['data'] ?? [];

$provinceStmt = $db->prepare("INSERT IGNORE INTO provinces (code, name_en, name_km) VALUES (?, ?, ?)");
$count = 0;
foreach ($provinces as $p) {
    $code = str_pad($p['code'], 2, '0', STR_PAD_LEFT);
    $provinceStmt->execute([$code, $p['name_en'] ?? '', $p['name_km'] ?? '']);
    $count++;
}
echo "  $count provinces seeded.\n";

// 2. Districts (paginated)
echo "Fetching districts...\n";
$allDistricts = fetchAll("$baseUrl/districts");
$districtStmt = $db->prepare("INSERT IGNORE INTO districts (code, province_code, name_en, name_km, type) VALUES (?, ?, ?, ?, ?)");
$count = 0;
foreach ($allDistricts as $d) {
    $districtStmt->execute([$d['code'], $d['parentCode'], $d['name_en'] ?? '', $d['name_km'] ?? '', $d['type'] ?? null]);
    $count++;
}
echo "  $count districts seeded.\n";

// 3. Communes (paginated)
echo "Fetching communes...\n";
$allCommunes = fetchAll("$baseUrl/communes");
$communeStmt = $db->prepare("INSERT IGNORE INTO communes (code, province_code, district_code, name_en, name_km) VALUES (?, ?, ?, ?, ?)");
$count = 0;
foreach ($allCommunes as $c) {
    $provinceCode = substr($c['code'], 0, 2);
    $districtCode = substr($c['code'], 0, 4);
    $communeStmt->execute([$c['code'], $provinceCode, $districtCode, $c['name_en'] ?? '', $c['name_km'] ?? '']);
    $count++;
}
echo "  $count communes seeded.\n";

// 4. Villages (paginated, 14k+)
echo "Fetching villages (this may take a moment)...\n";
$allVillages = fetchAll("$baseUrl/villages");
$villageStmt = $db->prepare("INSERT IGNORE INTO villages (code, province_code, district_code, commune_code, name_en, name_km) VALUES (?, ?, ?, ?, ?, ?)");
$count = 0;
foreach ($allVillages as $v) {
    $provinceCode = substr($v['code'], 0, 2);
    $districtCode = substr($v['code'], 0, 4);
    $communeCode = substr($v['code'], 0, 6);
    $villageStmt->execute([$v['code'], $provinceCode, $districtCode, $communeCode, $v['name_en'] ?? '', $v['name_km'] ?? '']);
    $count++;
    if ($count % 2000 === 0) echo "  $count villages...\n";
}
echo "  $count villages seeded.\n";

echo "\nDone! Cambodia address data seeded successfully.\n";
