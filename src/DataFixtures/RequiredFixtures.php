<?php

namespace App\DataFixtures;

use App\Egf\Ancient\AbstractFixture;
use App\Entity\SimpleShop\OrderStatus;

/**
 * Class RequiredFixtures
 */
class RequiredFixtures extends AbstractFixture {
	
	/**
	 * Load required data.
	 */
	public function loadData() {
		$orderStatuses = [
			'new'        => 'Új megrendelés',
			'processing' => 'Feldolgozás alatt',
			'done'       => 'Teljesítve',
			'deleted'    => 'Törölve',
		];
		foreach ($orderStatuses as $code => $label) {
			$this->newEntity(OrderStatus::class, ['code' => $code, 'label' => $label]);
		}
	}
}