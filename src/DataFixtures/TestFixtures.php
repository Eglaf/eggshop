<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Egf\Ancient\AbstractFixture;
use App\Entity\User\User,
	App\Entity\User\Address,
	App\Entity\SimpleShop as ShopEntity;

/**
 * Class TestFixtures
 */
class TestFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	/**
	 * Load some test data.
	 */
	public function loadData() {
		$u1 = $this->newEntity(User::class, [
			'name'     => 'teszt juzer',
			'email'    => 'teszt@teszt.ts',
			'password' => '$2a$08$jHZj/wJfcVKlIwr5AvR78euJxYK7Ku5kURNhNx.7.CSIJ3Pq6LEPC',
			'role'     => 'ROLE_USER',
			'active'   => TRUE,
		]);
		
		$a1 = $this->newEntity(Address::class, [
			'user'        => $u1,
			'title'       => 'cim1',
			'city'        => 'bp',
			'zipCode'     => 1234,
			'street'      => 'ize u.',
			'houseNumber' => '1',
		]);
		$a2 = $this->newEntity(Address::class, [
			'user'        => $u1,
			'title'       => 'cim2',
			'city'        => 'bp',
			'zipCode'     => 2345,
			'street'      => 'vmi ut',
			'houseNumber' => '2',
		]);
		
		$o1 = $this->newEntity(ShopEntity\Order::class, [
			'user'            => $u1,
			'shippingAddress' => $a1,
			'billingAddress'  => $a2,
			'status'          => $this->om->find(ShopEntity\OrderStatus::class, 1),
			'comment'         => 'Ez csak egy teszt rendeles',
		]);
		$this->newEntity(ShopEntity\OrderItem::class, [
			'order'   => $o1,
			'product' => $this->om->find(ShopEntity\Product::class, mt_rand(1, 4)),
			'price'   => mt_rand(100, 200),
			'count'   => mt_rand(3, 10),
		]);
		$this->newEntity(ShopEntity\OrderItem::class, [
			'order'   => $o1,
			'product' => $this->om->find(ShopEntity\Product::class, mt_rand(5, 9)),
			'price'   => mt_rand(200, 300),
			'count'   => mt_rand(3, 10),
		]);
		
		
		$o2 = $this->newEntity(ShopEntity\Order::class, [
			'user'            => $u1,
			'shippingAddress' => $a1,
			'status'          => $this->om->find(ShopEntity\OrderStatus::class, 1),
			'comment'         => 'Masik teszt rendeles',
		]);
		$this->newEntity(ShopEntity\OrderItem::class, [
			'order'   => $o2,
			'product' => $this->om->find(ShopEntity\Product::class, mt_rand(1, 5)),
			'price'   => mt_rand(100, 200),
			'count'   => mt_rand(3, 10),
		]);
		$this->newEntity(ShopEntity\OrderItem::class, [
			'order'   => $o2,
			'product' => $this->om->find(ShopEntity\Product::class, mt_rand(6, 9)),
			'price'   => mt_rand(200, 300),
			'count'   => mt_rand(3, 10),
		]);
		
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
