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
        $provinceCode = $_GET['province_code'] ?? null;
        if (!$provinceCode) $this->json([]);
        $districts = $this->districtModel->byProvince($provinceCode);
        $this->json($districts);
    }

    public function communes() {
        $districtCode = $_GET['district_code'] ?? null;
        if (!$districtCode) $this->json([]);
        $communes = $this->communeModel->byDistrict($districtCode);
        $this->json($communes);
    }

    public function villages() {
        $communeCode = $_GET['commune_code'] ?? null;
        if (!$communeCode) $this->json([]);
        $villages = $this->villageModel->byCommune($communeCode);
        $this->json($villages);
    }
}
