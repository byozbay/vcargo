<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseModel.php';

class TripModel extends BaseModel
{
    protected string $table      = 'trips';
    protected string $primaryKey = 'trip_id';

    /** Get trips list with company name and city names */
    public function getList(int $branchId = 0, string $status = '', int $limit = 20, int $offset = 0): array
    {
        $sql    = "SELECT t.*, bc.name AS company_name, bc.commission_rate,
                          oc.name AS origin_city, dc.name AS dest_city
                   FROM trips t
                   LEFT JOIN bus_companies bc ON t.company_id = bc.company_id
                   LEFT JOIN cities oc ON t.origin_city_id = oc.city_id
                   LEFT JOIN cities dc ON t.destination_city_id = dc.city_id
                   WHERE t.is_active = 1";
        $params = [];

        if ($branchId > 0) {
            $sql    .= " AND t.branch_id = ?";
            $params[] = $branchId;
        }
        if ($status) {
            $sql    .= " AND t.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY t.departure_time DESC LIMIT {$limit} OFFSET {$offset}";
        return $this->query($sql, $params);
    }

    /** Create trip with auto commission calculation */
    public function createTrip(array $data): int
    {
        // Get company commission rate
        $company = $this->query(
            "SELECT commission_rate FROM bus_companies WHERE company_id = ?",
            [$data['company_id']]
        );
        $rate = (float) ($company[0]['commission_rate'] ?? 15);

        $totalFee   = (float) ($data['total_cargo_fee'] ?? 0);
        $commission  = $totalFee * ($rate / 100);
        $netPayment  = $totalFee - $commission;

        $data['commission_amount'] = $commission;
        $data['net_payment']       = $netPayment;
        $data['status']            = $data['status'] ?? 'planned';

        return $this->create($data);
    }

    /** Assign shipment to trip and recalculate totals */
    public function addShipmentToTrip(int $tripId, int $shipmentId): bool
    {
        // Update shipment
        $this->execute(
            "UPDATE shipments SET trip_id = ?, status = 'dispatched' WHERE shipment_id = ?",
            [$tripId, $shipmentId]
        );

        // Recalculate trip totals
        return $this->recalculate($tripId);
    }

    /** Recalculate total_cargo_fee, commission_amount, net_payment */
    public function recalculate(int $tripId): bool
    {
        $trip = $this->find($tripId);
        if (!$trip) return false;

        $rows = $this->query(
            "SELECT COALESCE(SUM(total_fee), 0) AS total FROM shipments WHERE trip_id = ? AND is_active = 1",
            [$tripId]
        );
        $totalFee = (float) ($rows[0]['total'] ?? 0);

        $company    = $this->query("SELECT commission_rate FROM bus_companies WHERE company_id = ?", [$trip['company_id']]);
        $rate       = (float) ($company[0]['commission_rate'] ?? 15);
        $commission = $totalFee * ($rate / 100);
        $net        = $totalFee - $commission;

        return $this->update($tripId, [
            'total_cargo_fee'   => $totalFee,
            'commission_amount' => $commission,
            'net_payment'       => $net,
        ]);
    }
}
