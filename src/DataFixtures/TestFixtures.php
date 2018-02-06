<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Egf\Ancient\AbstractFixture;

/**
 * Class TestFixtures
 */
class TestFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	/**
	 * Load some test data.
	 */
	public function loadData() {
	}
	
	/**
	 * Required dependency fixtures.
	 * @return array
	 */
	public function getDependencies() {
		return [
			RequiredFixtures::class,
		];
	}
}