<?php


namespace App\Services;


use App\Repository\AccountsRepository;
use App\Repository\PurchaseRepository;
use App\Repository\SaleRepository;
use Carbon\Carbon;

class DailyReportService
{
    /**
     * @var SaleRepository
     */
    private $saleRepository;
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;
    /**
     * @var AccountsRepository
     */
    private $accountsRepository;

    /**
     * DailyReportService constructor.
     * @param SaleRepository $saleRepository
     * @param PurchaseRepository $purchaseRepository
     * @param AccountsRepository $accountsRepository
     */
    public function __construct(SaleRepository $saleRepository, PurchaseRepository $purchaseRepository, AccountsRepository $accountsRepository)
    {

        $this->saleRepository = $saleRepository;
        $this->purchaseRepository = $purchaseRepository;
        $this->accountsRepository = $accountsRepository;
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getSaleByDay($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        return $this->saleRepository->findByDay($date);
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getPurchaseByDay($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        return $this->purchaseRepository->findByDay($date);
    }

    public function getAccountsReportByDay($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        return $this->accountsRepository->findByDay($date);
    }


}
