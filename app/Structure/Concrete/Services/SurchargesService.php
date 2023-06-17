<?php

namespace App\Structure\Concrete\Services;

use App\Helpers\JJKData;
use App\Models\Rate;
use App\Models\Surcharge;
use App\Structure\Abstract\Repositories\SurchargesRepositoryInterface;
use App\Structure\Abstract\Services\CarrierServiceInterface;
use App\Structure\Abstract\Services\RateServiceInterface;
use App\Structure\Abstract\Services\SurchargesServiceInterface;
use App\ViewModels\Mappers\RateMapper;
use App\ViewModels\Mappers\SurchargeMapper;
use App\ViewModels\RateViewModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use webd\language\StringDistance;

class SurchargesService implements SurchargesServiceInterface
{
    protected SurchargesRepositoryInterface $surchargesRepository;
    protected CarrierServiceInterface $carrierService;
    protected RateServiceInterface $rateService;
    protected float $threshold;


    /**
     * @param SurchargesRepositoryInterface $surchargesRepository
     * @param CarrierServiceInterface $carrierService
     * @param RateServiceInterface $rateService
     */
    public function __construct(
        SurchargesRepositoryInterface $surchargesRepository,
        CarrierServiceInterface       $carrierService,
        RateServiceInterface          $rateService
    )
    {
        $this->threshold = 0.85;
        $this->surchargesRepository = $surchargesRepository;
        $this->carrierService = $carrierService;
        $this->rateService = $rateService;
    }

    public function getAll(): array
    {
        return SurchargeMapper::listModelToListViewModel($this->surchargesRepository->getAll());
    }

    public function getAllFathers(): array
    {
        return SurchargeMapper::listModelToListViewModel($this->surchargesRepository->getAllFathers());
    }

    public function group(): array
    {
        $list = $this->surchargesRepository->getAllUngrouped();
        //Elloquent Collection to Array
        $data = [];
        foreach ($list as $item) $data[] = $item;
        //Grouping items
        $jjk = new JJKData();
        $groupedData = $jjk->groupSimilarData($data, $this->threshold);
        foreach ($groupedData as $group) {
            $father = $group[0];
            $father->isGrouped = true;
            $this->surchargesRepository->save($father);
            for ($i = 1; $i < count($group); $i++) {
                $group[$i]->idFather = $father->id;
                $group[$i]->isGrouped = true;
                $this->surchargesRepository->save($group[$i]);
            }
        }
        return $this->getAllFathers();
    }

    /**
     * @throws Exception
     */
    public function uploadDataFromExcel($exel): bool
    {
        try {
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($exel);
            $data = $spreadsheet->getActiveSheet()->toArray();
            $i = 0;
            $list = [];
            while ($data[$i][0] !== null) {
                $item = [
                    'surcharge' => $data[$i][0],
                    'carrier' => $data[$i][1],
                    'amount' => $data[$i][2],
                    'currency' => $data[$i][3],
                    'apply_to' => $data[$i][4],
                ];
                $list[] = $item;
                $i++;
            }
            //Removing the MsExcel Header
            array_shift($list);
            //Grouping items
            $jjk = new JJKData();
            $groupedData = $jjk->groupSimilarData($list, $this->threshold);
            $fatherList = $this->surchargesRepository->getAllFathers();
            foreach ($groupedData as $list) {
                $haveFather = false;
                $selectedSurcharge = new Surcharge();
                foreach ($fatherList as $surcharge) {
                    $similarity = StringDistance::jaroWinkler($list[0]['surcharge'], $surcharge->name);
                    if ($similarity >= $this->threshold) {
                        $haveFather = true;
                        $selectedSurcharge = $surcharge;
                        break;
                    }
                }
                if (!$haveFather) {
                    $selectedSurcharge = SurchargeMapper::excelDataToModel($list[0], $selectedSurcharge);
                    $selectedSurcharge = $this->surchargesRepository->save($selectedSurcharge);
                    //Get the carrier, if the carrier doesn't exist, the service will create a new one
                    $carrier = $this->carrierService->getByName($list[0]['carrier']);
                    #Create Rate
                    {
                        $rate = new RateViewModel();
                        $rate->surcharge_id = $selectedSurcharge->id;
                        $rate->carrier_id = $carrier->id;
                        $rate->amount = $list[0]['amount'];
                        $rate->currency = $list[0]['currency'];
                        $rate->apply_to = $list[0]['apply_to'];
                        $this->rateService->save($rate);
                    }
                    array_shift($list);
                }
                foreach ($list as $item) {
                    #Save the items in the group, I'm not sure if it's necessary
                    {
                        $newSon = SurchargeMapper::excelDataToModel($item, new Surcharge());
                        $newSon->idFather = $selectedSurcharge->id;
                        $this->surchargesRepository->save($newSon);
                    }
                    //Get the carrier, if the carrier doesn't exist, the service will create a new one
                    $carrier = $this->carrierService->getByName($item['carrier']);
                    #Create Rate
                    {
                        $rate = new RateViewModel();
                        $rate->surcharge_id = $selectedSurcharge->id;
                        $rate->carrier_id = $carrier->id;
                        $rate->amount = $item['amount'];
                        $rate->currency = $item['currency'];
                        $rate->apply_to = $item['apply_to'];
                        $this->rateService->save($rate);
                    }
                }
            }
            return true;
        } catch (\Exception $exception) {
            //Do something special with the exception, I don't know if Cargofive have any log file or something
            return false;
        }
    }

    public function joinGroups(int $idGroupA, int $idGroupB): bool
    {
        try {
            if ($idGroupB == 0 || $idGroupA == 0) return false;
            $groupA = $this->surchargesRepository->getById($idGroupA);
            $groupB = $this->surchargesRepository->getById($idGroupB);
            if (count($groupB->sons) > count($groupA->sons)) {
                $temp = $groupA;
                $groupA = $groupB;
                $groupB = $temp;
            }
            $groupB->idFather = $groupA->id;
            $this->surchargesRepository->save($groupB);
            foreach ($groupB->rates as $rate) {
                $rate->surcharge_id = $groupA->id;
                $this->rateService->save(RateMapper::modelToViewModel($rate));
            }
            foreach ($groupB->sons as $son) {
                $son->idFather = $groupA->id;
                $this->surchargesRepository->save($son);
            }
            return true;
        } catch (\Exception $exception) {
            //Do something special with the exception, I don't know if Cargofive have any log file or something
            return false;
        }
    }
}
