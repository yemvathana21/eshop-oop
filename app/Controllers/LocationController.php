<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Village;

class LocationController extends Controller {
    private $provinceModel;
    private $districtModel;
    private $communeModel;
    private $villageModel;

    public function __construct() {
        $this->provinceModel = new Province();
        $this->districtModel = new District();
        $this->communeModel = new Commune();
        $this->villageModel = new Village();
    }

    public function provinces() {
        $provinces = $this->provinceModel->all();
        $this->json($provinces);
    }

    public function districts() {
        try {
            // បិទការបង្ហាញ Error ជា HTML ដើម្បីកុំឲ្យខូច JSON
            ini_set('display_errors', 0);

            $provinceCode = $_GET['province_code'] ?? null;
            if (!$provinceCode) $this->json([]);

            $districts = $this->districtModel->byProvince($provinceCode);
            $this->json($districts);
        } catch (\Throwable $e) {
            header('Content-Type: application/json', true, 500);
            echo json_encode(['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            exit;
        }
    }

    public function communes() {
        try {
            $districtCode = $_GET['district_code'] ?? null;
            if (!$districtCode) $this->json([]);
            $communes = $this->communeModel->byDistrict($districtCode);
            $this->json($communes);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            $this->json(['error' => $e->getMessage()]);
        }
    }

    public function villages() {
        try {
            $communeCode = $_GET['commune_code'] ?? null;
            if (!$communeCode) $this->json([]);
            $villages = $this->villageModel->byCommune($communeCode);
            $this->json($villages);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            $this->json(['error' => $e->getMessage()]);
        }
    }
}
