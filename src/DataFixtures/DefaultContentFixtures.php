<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Egf\Ancient\AbstractFixture;
use App\Entity\User\User,
	App\Entity\Content\Text,
	App\Entity\SimpleShop\Category,
	App\Entity\SimpleShop\Product;

/**
 * Class DefaultContentFixtures
 */
class DefaultContentFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	/**
	 * Load the default content data.
	 */
	public function loadData() {
		$this
			->loadUsers()
			->loadTexts()
			->loadPages()
			->loadSimpleShop();
	}
	
	/**
	 * Load default users.
	 * @return $this
	 */
	protected function loadUsers() {
		$this->newEntity(User::class, [
			'email'    => 'admin@admin.ad',
			'password' => '$2a$08$jHZj/wJfcVKlIwr5AvR78euJxYK7Ku5kURNhNx.7.CSIJ3Pq6LEPC',
			'role'     => 'ROLE_ADMIN',
			'active'   => TRUE,
		]);
		
		return $this;
	}
	
	/**
	 * Load default texts.
	 * @return $this
	 */
	protected function loadTexts() {
		$this
			// Registration
			->newTextContent('registration-form-before', NULL, 'Regisztrációs form.')
			->newTextContent('registration-form-after', NULL, 'Kérjük töltse ki.')
			->newTextContent('registration-confirm-email-sent', NULL, 'Regisztrációs email kiküldve.')
			// New order
			->newTextContent('new-order-select-products-before', NULL, 'Válassza ki a termékeket.')
			->newTextContent('new-order-select-products-after', NULL, 'Majd kattintson a tovább gombra.')
			->newTextContent('new-order-select-addresses-before', NULL, 'Válassza ki a címeket.')
			->newTextContent('new-order-select-addresses-after', NULL, 'Majd kattintson a tovább gombra.')
			->newTextContent('new-order-confirm-before', NULL, 'Kérjük ellenőrizze a megadott adatokat.')
			->newTextContent('new-order-confirm-after', NULL, 'Ha minden rendben, adja fel a megrendelését.')
			->newTextContent('new-order-submit-confirmed', NULL, 'Megrendelését rögzítettük. Köszönjük a vásárlást.');
		
		return $this;
	}
	
	
	/**
	 * Load default pages.
	 * @return $this
	 */
	protected function loadPages() {
		$this
			->newTextContent('index', 'kezdo oldal', '<p>kezdo oldal</p>')
			->newTextContent('fustolt-furjtojas', 'fustolt furjtoji', '<p>fustolt toji</p>')
			// ->newTextContent('', '', '<p></p>')
			// ->newTextContent('', '', '')
			// ->newTextContent('', '', '')
			// ->newTextContent('', '', '')
		;
		
		return $this;
	}
	
	/**
	 * Create a new text content entity.
	 * @param string $code  Identifier code.
	 * @param string $title Needed only when page content is created.
	 * @param string $text  Content.
	 * @return $this
	 */
	protected function newTextContent($code, $title, $text) {
		$this->newEntity(Text::class, [
			'code'  => $code,
			'title' => $title,
			'text'  => $text,
		]);
		
		return $this;
	}
	
	/**
	 * Load default SimpleShop categories and products.
	 * @return $this
	 */
	protected function loadSimpleShop() {
		$shopData = [[
			             'label'    => 'Füstölt fürjtojás',
			             'products' => [[
				                            'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 10 darabos',
				                            'description' => '10 darabos kiszerelés',
				                            'price'       => 1221,
			                            ], [
				                            'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 10 darabos',
				                            'description' => '10 db. / kiszerelés SZŐLŐMAGOLAJBAN',
				                            'price'       => 1551,
			                            ], [
				                            'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 50 darabos',
				                            'description' => '50 darabos kiszerelés',
				                            'price'       => 5115,
			                            ], [
				                            'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 50 darabos',
				                            'description' => '50 db. / kiszerelés SZŐLŐMAGOLAJBAN',
				                            'price'       => 5775,
			                            ]],
		             ], [
			             'label'    => 'Főtt fürjtojás',
			             'products' => [[
				                            'label'       => 'Natúr főtt fürjtojás sólében (Himalája sóval)',
				                            'description' => '50 db. / kiszerelés',
				                            'price'       => 3883,
			                            ], [
				                            'label'       => 'Főtt fürjtojás konzerv - 10 darabos',
				                            'description' => '10 darab tojás, sós lében 	',
				                            'price'       => 1001,
			                            ], [
				                            'label'       => 'Főtt fürjtojás konzerv - 35 darabos',
				                            'description' => '35 darab tojás, sós lében',
				                            'price'       => 3113,
			                            ]],
		             ], [
			             'label'    => 'Nyers fürjtojás',
			             'products' => [[
				                            'label'       => 'Nyers fürjtojás - 15 darabos',
				                            'description' => '15 darabos kiszerelés',
				                            'price'       => 474,
			                            ], [
				                            'label'       => 'Fürj tenyésztojás - 15 darabos',
				                            'description' => '15 darabos kiszerelés',
				                            'price'       => 1331,
			                            ]],
		             ]];
		
		foreach ($shopData as $categoryData) {
			$category = $this->newEntity(Category::class, [
				'label'  => $categoryData['label'],
				'active' => TRUE,
			]);
			
			foreach ($categoryData['products'] as $productData) {
				$this->newEntity(Product::class, [
					'category'    => $category,
					'label'       => $productData['label'],
					'description' => $productData['description'],
					'price'       => $productData['price'],
					'active'      => TRUE,
				]);
			}
		}
		
		return $this;
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