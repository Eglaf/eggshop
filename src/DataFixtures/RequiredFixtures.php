<?php

namespace App\DataFixtures;

use App\Egf\Ancient\AbstractFixture;
use App\Entity\SimpleShop\OrderStatus;
use App\Entity\Config;

/**
 * Class RequiredFixtures
 */
class RequiredFixtures extends AbstractFixture {
	
	/**
	 * Load required data.
	 */
	public function loadData() {
		foreach ($this->getConfigs() as $configData) {
			$this->newEntity(Config::class, $configData);
		}
		
		foreach ($this->getOrderStatuses() as $code => $label) {
			$this->newEntity(OrderStatus::class, ['code' => $code, 'label' => $label]);
		}
	}
	
	/**
	 * Used config rows.
	 * @return array
	 */
	protected function getConfigs() {
		return [[
			        'code'        => 'order-minimum-price',
			        'value'       => '10000',
			        'description' => 'Rendelés minimum összege, házhoz szállítas esetén. Szám legyen megadva.',
		        ], [
			        'code'        => 'order-delivery-price',
			        'value'       => '1000',
			        'description' => 'Házhoz szállítas ára. Szám legyen megadva.',
		        ], [
			        'code'        => 'order-no-delivery-price-above-sum',
			        'value'       => '15000',
			        'description' => 'Szállítási költség elhagyása, e felett az összeg felett. Szám legyen megadva.',
		        ], [
			        'code'        => 'admin-phone',
			        'value'       => '+36-30-655-8977',
			        'description' => 'Admin felhasználó telenszáma.',
		        ], [
			        'code'        => 'admin-email',
			        'value'       => 'info@furjtojas.eu',
			        'description' => 'Admin felhasználó email címe. Érvényes email cim legyen megadva.',
		        ], [
			        'code'        => 'sender-email',
			        'value'       => 'no-reply@furjtojas.eu',
			        'description' => 'Email küldésnél a feladó.',
		        ]];
	}
	
	/**
	 * Used order statuses.
	 * @return array
	 */
	protected function getOrderStatuses() {
		return [
			'new'        => 'Új megrendelés',
			'processing' => 'Feldolgozás alatt',
			'done'       => 'Teljesítve',
			'deleted'    => 'Törölve',
		];
	}
	
}
