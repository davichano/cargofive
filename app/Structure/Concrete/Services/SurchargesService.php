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

/**
 * @class SurchargesService
 * @package App\Structure\Concrete\Services
 * @description This service provides methods for managing surcharges. Here I will write every logic, I can reuse this code in a lot of controllers
 */
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
     * @description Constructor with parameters for the dependency injection
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

    /**
     * @description Get all surcharges. First step is get all surcharges models from the repository,
     * then I use the mapper to return a list of viewModels of this data.
     * @return array
     */
    public function getAll(): array
    {
        return SurchargeMapper::listModelToListViewModel($this->surchargesRepository->getAll());
    }

    /**
     * @description Get all surcharges joined. I call father a surcharge whose name is the representation of a group of names.
     * The repository return a list of Surcharges, then I use the Mapper class and return to the user a list if viewModels.
     * @return array
     */
    public function getAllFathers(): array
    {
        return SurchargeMapper::listModelToListViewModel($this->surchargesRepository->getAllFathers());
    }

    /**
     * @description Group surcharges by name, choose a surcharge like the father of the group.
     * I use the Jaro and Jaro-Winkler similarity algorithm.
     * This function is called by de command php artisan surcharge:group
     * @return array
     */
    public function group(): void
    {
        // Get all surcharges from the repository.
        $list = $this->surchargesRepository->getAllUngrouped();
        //Elloquent Collection to Array
        $data = [];
        foreach ($list as $item) $data[] = $item;
        // Group the surcharges using a similarity algorithm.
        $jjk = new JJKData();
        $groupedData = $jjk->groupSimilarData($data, $this->threshold);
        // Save the grouped surcharges in the database
        foreach ($groupedData as $group) {
            // Choose the father
            $father = $group[0];
            $father->isGrouped = true;
            $this->surchargesRepository->save($father);
            for ($i = 1; $i < count($group); $i++) {
                // Set the others as sons
                $group[$i]->idFather = $father->id;
                $group[$i]->isGrouped = true;
                $this->surchargesRepository->save($group[$i]);
            }
        }
    }


    /**
     * @param $exel
     * @return bool
     * @description Use this function to Upload data from MS Excel files,
     */
    public function uploadDataFromExcel($exel): bool
    {
        try {
            //Read the Excel File using PhpSpreadsheet\IOFactory
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($exel);
            $data = $spreadsheet->getActiveSheet()->toArray();
            //The data includes empty cells, we don't need these cells
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
            //Call to the fathers to include the new items in their groups
            $fatherList = $this->surchargesRepository->getAllFathers();
            //For each item in the Excel File
            foreach ($groupedData as $list) {
                //I am using a flag to check if the item founds a group to join
                $haveFather = false;
                //Create a new Surcharge object if we don't found a group to join
                $selectedSurcharge = new Surcharge();
                foreach ($fatherList as $surcharge) {
                    //Check if the new item's name is similar with the fathers name
                    $similarity = StringDistance::jaroWinkler($list[0]['surcharge'], $surcharge->name);
                    if ($similarity >= $this->threshold) {
                        //Update the flag
                        $haveFather = true;
                        //change the empty surcharge
                        $selectedSurcharge = $surcharge;
                        break;
                    }
                }
                //If the algorithm doesn't found a father
                if (!$haveFather) {
                    //Register a new Father, I make this separately to prevent set like son at the same surcharge
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
                    //Remove this element from the list
                    array_shift($list);
                }
                //Insert the remaining items from the Excel file
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

    /**
     * @param int $idGroupA
     * @param int $idGroupB
     * @return bool
     * @description If the threshold value is too high, maybe some surcharges aren't will be grouped, use this functions
     * to join two surcharges, I call them groups because each item can be a father of others surcharges, in this case
     * the function will move all the sons and rates
     */
    public function joinGroups(int $idGroupA, int $idGroupB): bool
    {
        try {
            //check if the provided ids are right
            if ($idGroupB == 0 || $idGroupA == 0) return false;
            //Get the surcharges
            $groupA = $this->surchargesRepository->getById($idGroupA);
            $groupB = $this->surchargesRepository->getById($idGroupB);
            //check which have the biggest sons number
            if (count($groupB->sons) > count($groupA->sons)) {
                $temp = $groupA;
                $groupA = $groupB;
                $groupB = $temp;
            }
            //the smallest group is now a son
            $groupB->idFather = $groupA->id;
            $this->surchargesRepository->save($groupB);
            //migrate also the rates
            foreach ($groupB->rates as $rate) {
                $rate->surcharge_id = $groupA->id;
                $this->rateService->save(RateMapper::modelToViewModel($rate));
            }
            //check if the new son have sons
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
