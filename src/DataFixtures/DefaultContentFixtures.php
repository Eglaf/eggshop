<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Egf\Ancient\AbstractFixture;
use App\Entity\User\User,
	App\Entity\Content\Text,
	App\Entity\Content\File,
	App\Entity\SimpleShop\Category,
	App\Entity\SimpleShop\Product;

/**
 * Class DefaultContentFixtures
 */
class DefaultContentFixtures extends AbstractFixture implements DependentFixtureInterface {
	
	/**
	 * Required dependency fixtures.
	 * @return array
	 */
	public function getDependencies() {
		return [
			RequiredFixtures::class,
		];
	}
	
	/**
	 * Load the default content data.
	 */
	public function loadData() {
		$this
			->loadUsers()
			->loadImages();
		
		$this->om->flush();
		
		$this
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
			'name'     => 'kovacs attila',
			'email'    => 'admin@admin.ad',
			'password' => '$2a$08$jHZj/wJfcVKlIwr5AvR78euJxYK7Ku5kURNhNx.7.CSIJ3Pq6LEPC',
			'role'     => 'ROLE_ADMIN',
			'active'   => TRUE,
		]);
		
		return $this;
	}
	
	/**
	 * Load default short texts.
	 * @return $this
	 */
	protected function loadTexts() {
		// Basic short texts.
		foreach ($this->getShortTextContents() as $code => $text) {
			$this->newEntity(Text::class, [
				'code'              => $code,
				'title'             => NULL,
				'text'              => $text,
				'enabledParameters' => NULL,
			]);
		}
		
		// Short texts with parameters.
		$this
			->newEntity(Text::class, [
				'code'  => 'new-order-select-products-before',
				'title' => NULL,
				'text'  => '
					<p>Cégünk 25 éve a fürjtojás specialistája és az egész Európai Unióban egyedül mi működtetünk engedéllyel fürjtojás feldolgozó üzemet. Weblapunkon étkezési fürjtojás és fürj tenyésztojás rendelésére nyílik lehetősége. Étkezési fürjtojás három fajtáját kínáljuk az érdeklődőknek:  nyers fürjtojás,  natúr konzerv fürjtojás (sós lében)   és füstölt fürjtojás étolajban, fóliatasakba,  díszdobozba csomagolva.</p>
					<p><span style="color:#ee2210;"><strong>Termékeink semmilyen tartósítószert nem tartalmaznak, ízesítéshez pedig Himalája sót használunk!</strong></span></p>
					<p>A rendelés nincs előzetes regisztrációhoz kötve, így szolgáltatásaink korlátozás nélkül minden látogató számára elérhetőek.</p>
					<h2>Tojás vásárlás menete</h2>
					<p>A rendelés menete a választott terméknek megfelelő formanyomtatvány értelemszerű kitöltése.  A formanyomtatvány kitöltése nem minősül online vásárlásnak, csak igényleadásnak, a termékkel kapcsolatos jövőbeli vásárlási szándék kifejezésének. Az eladó tojások iránti vásárlási szándékának megerősítésére minden esetben kapcsolatba lépünk Önnel, s az adás-vételi ügylet az áru kiszállításakor realizálódik.</p>
					<p>A tojás rendelési űrlap kitöltése után automatikus visszajelzést küldünk email címére, majd rövid időn belük telefonon is felvesszük a kapcsolatot Önnel a megrendelés kiszállításával vagy átvételével kapcsolatban. Rendelési adatait minden esetben telefonos egyeztetés során véglegesítjük. A megrendelés szóbeli, telefonos megerősítést követően minősül csak valós vásárlásnak.</p>
					<p>Igényleadáskor rögzített adatait bizalmasan kezeljük, harmadik félnek nem adjuk ki.</p>
					<p>Ha véletlenül hibásan adta le igénylőlapját, kérjük az <strong>{{ admin-email }}</strong> email címre „Hibás igénylőlap” tárggyal jelezze felénk vagy hívjon minket telefonszámunkon.</p>
					<p>A megrendelt tojások kifizetése házhozszállítás esetén a termékek futártól való átvételekor történik.</p>
					<p><strong><span style="color: #ff0000;">Futárral történő kiszállítást (házhozszállítást) csak minimum {{ order-minimum-price }} Ft értékű egyösszegű igénylés leadása esetén áll módunkban teljesíteni.</span>
					Amennyiben a rendelésének összege eléri a {{ order-no-delivery-price-above-sum }} Ft-ot,
					a szállítási díjat átvállaljuk Öntől, tehát nem számolunk fel házhozszállítási költséget ({{ order-delivery-price }} Ft)!</strong></p>
					<p><strong>
					A házhozszállítás futárszolgálattal történik, a kiszállítási díj országosan egységesen {{ order-delivery-price }} Ft.
					Az igénylőlap alján az összegben a házhozszállítás díja is benne foglaltatik, amennyiben személyesen szeretné átvenni a termékeket telephelyünkön (Tamago Kft. - 1161 Budapest, Albán utca 1.), kérjük a telefonos egyeztetés (rendelés véglegesítése) során jelezze.
					Személyes átvétel esetén a végösszeg {{ order-delivery-price }} Ft-al kevesebb lesz! </strong></p>
					<p>A {{ admin-phone }}  telefonszámon <span style="text-decoration: underline;"><em>munkanapokon 9-17 óra között</em></span> a rendelésekkel kapcsolatos kérdésekre az ügyfélszolgálat munkatársai válaszolnak.</p>
					',
			])
			// This one has to be out here. todo WTF?
			->setEnabledParameters(['admin-email', 'admin-phone', 'order-minimum-price', 'order-delivery-price', 'order-no-delivery-price-above-sum']);
		
		return $this;
	}
	
	/**
	 * Load content images.
	 * @return $this
	 */
	protected function loadImages() {
		foreach ($this->getImageContents() as $path => $label) {
			$this->newEntity(File::class, [
				'storageName' => $path,
				'label'       => $label,
				'description' => $label,
				'mimeType'    => 'image/jpeg',
				'active'      => TRUE,
			]);
		}
		
		return $this;
	}
	
	/**
	 * Load default pages.
	 * @return $this
	 */
	protected function loadPages() {
		foreach ($this->getPageContents() as $code => $page) {
			$this->newEntity(Text::class, [
				'code'  => $code,
				'title' => $page[0],
				'text'  => $page[1],
			]);
		}
		
		return $this;
	}
	
	/**
	 * Load default SimpleShop categories and products.
	 * @return $this
	 */
	protected function loadSimpleShop() {
		foreach ($this->getSimpleShopContents() as $categoryData) {
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
					'image'       => $this->om->getRepository(File::class)->findOneBy([
						'storageName' => $productData['image'],
					]),
				]);
			}
		}
		
		return $this;
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Get contents                                               **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Get the short text contents.
	 * @return array [code => text, code2 => text2]
	 */
	protected function getShortTextContents() {
		return ['registration-form-before'          => 'Kérjük töltse ki az adatokat.',
		        'registration-form-after'           => 'Gombra kattintás után egy emailt fog kapni az aktiváló linkkel.',
		        'registration-confirm-email-sent'   => 'Regisztrációs email kiküldtük.',
		        'new-order-select-products-after'   => '',
		        'new-order-select-addresses-before' => 'Amennyiben kér kiszállítást vagy számlázást, adja meg a címeket.',
		        'new-order-select-addresses-after'  => '',
		        'new-order-confirm-before'          => 'Kérjük ellenőrizze a megadott adatokat.',
		        'new-order-confirm-after'           => 'Ha mindent rendben talál, a gombra kattintva feladhatja megrendelését.',
		        'new-order-submit-confirmed'        => 'Rendelését rögzítettük. Köszönjük a vásárlást.',
		];
	}
	
	protected function getImageContents() {
		return [
			'image_1.jpg'                                 => 'Fürjtojás',
			'furjtojasok.jpg'                             => 'Japán fürj tojása',
			'fustolt-furjtojas.jpg'                       => 'Nyers fürjtojás',
			'fustolt-furjtojas-50db1.jpg'                 => 'Füstölt fürjtojás',
			'natur-konzerv-furjtojas-10db.jpg'            => 'Főtt füstölt fürjtojás',
			'zold-szojabab.jpg'                           => 'Zöld szójabab',
			'szojabab-edamame.jpg'                        => 'Szójabab edamame',
			'shiso-level.jpg'                             => 'Shiso levél',
			'friss-tojas.jpg'                             => 'Friss tojás',
			'tojas-etel.jpg'                              => 'Tojás étel',
			'furjtojas-recept.jpg'                        => 'Fürjtojás recept',
			'furj-recept.jpg'                             => 'Fürj recept',
			'japan-furj.jpg'                              => 'Japán fürj',
			'furjtojas_15_darabos.jpg'                    => 'Fürjtojás 15 darabos',
			'furjtojas-kura-300x200.jpg'                  => 'Fürjtojás kúra',
			'furjtojas.jpg'                               => 'Fürjtojás',
			'fustolt-furjtojas-10darabos-300x169.jpg'     => 'füstölt fürjtojás 10 darabos',
			'fustolt-furjtojas-50darabos-300x169.jpg'     => 'Főtt, füstölt fürjtojás 50 darabos',
			'natur-fott-furjtojas-300x169.jpg'            => 'Natúr főtt fürjtojás sólében (Himalája sóval)',
			'natur-konzerv-furjtojas-10db-260x300.jpg'    => 'Főtt fürjtojás konzerv - 10 darabos',
			'natur-konzerv-furjtojas-35db-205x300.jpg'    => 'Főtt fürjtojás konzerv - 35 darabos',
			'nyers-furjtojas-15db-265x300.jpg'            => 'Nyers fürjtojás - 15 darabos',
			'furj-tenyesztojas-300x210.jpg'               => 'Fürj tenyésztojás - 15 darabo',
			'TamagoKFT-furjtojas-feldolgozas-169x300.jpg' => 'Tamago Kft. Fürjtojás feldolgozás',
		];
	}
	
	/**
	 * Get some page content as an array. The first elem is the title, the second is the body content.
	 * @return array [PageCode => [Title, Content], PageCode2 => [Title2, Content2]]
	 */
	protected function getPageContents() {
		return [
			'index'                            => [NULL, '<h1>Fürjtojás</h1>
					<p>
					<img src="/uploads/image_1.jpg" style="float:left; height:463px; width:388px;" title="Fürjtojás" alt="Fürjtojás" >
					A fürjtojást, mint neve is mutatja a japán fürj madárnak köszönhetjük. A japán fürj egy éven belül általában kétszer kotlik. Tojásait a földön, mélyedésekbe rakja le, egyszerre maximum 8 tojást. A fürjtojás kinézetét tekintve apró, fehér alapon barna pettyes héjú, alakjában a normál tojással megegyező formájú tojás. A fürjtojások színárnyalata és rajzolata azonban mindig más, az egyedre jellemzően ugyanaz.</p>
					<p>A fürj tojása igazi ínyencségnek számít. Egy fürjtojás tömege átlagosan 10 gramm, energiatartalma 15 kcal. Bár a fürjtojás méretét tekintve igen apró, vitaminértéke jóval magasabb a többi szárnyas tojásáénál, s koleszterinszintje pedig a legalacsonyabb valamennyi között.</p>
					<p>Számos kultúrában a fürjtojás az egészséges táplálkozás alapanyaga, hisz hatszor több ásványi anyagot (foszfor, réz, vas, cink) és vitamint (pl. B vitamin) tartalmaz, mint a legáltalánosabb körökben fogyasztott tyúktojás. Elmondható, hogy a fürjtojás a legegészségesebb és legbiztonságosabb fogyasztású tojásféle.</p>
					<p>Annál inkább tápláló a japán fürj tojása, minél természetesebb körülmények között él maga a kotló madár. A fürj gyógyhatású tojásainak és egyszerű tartási követelményeinek köszönhetően egyre kedveltebb háziszárnyassá növi ki magát, már-már helytálló a „jövő baromfija” elnevezés használata rá. A fürjtojás és fürjhús kiemelt helyen szerepel az egészséges ételek körében.</p>
					<p>&nbsp;</p>
					<p>A tárolási előírások betartásával a fürjtojás szobahőmérsékleten 1 hónapig, hűtőben 3 hónapig tárolható, az Európai Uniós előírások szerint viszont csak 3 hétig. A fürtojás összetétele: tojásfehérje, tojássárgája, tojáshéj. Az alábbiakban lássuk ezeket részletesebben.</p>
					<h3>
					<img src="/uploads/furjtojasok.jpg" style="float:right; height:510px; width:491px" title="Japán fürj tojása" alt="Japán fürj tojása"></a>
					Tojásfehérje</h3>
					<p>A tojássárgáját körülvevő fehérje (albumin) elnevezése onnan ered, hogy sütéskor, főzéskor fehérré válik. Biológiai funkciója a tojássárgáját védi az ütődésektől, magas víztartalma fontos az embrió vízháztartásában és az ásványi anyagok forgalmában. Számos nélkülözhetetlen aminosavat tartalmaz, melyek a fejlődő fürj embrió fehérjeszintéziséhez elengedhetetlenek.</p>
					<h3>Tojássárgája</h3>
					<p>Az étkezési célból fogyasztott fürjtojás sárgájának három fontos tápértéke van: zsírsav, koleszterin, vitaminok és ásványi anyagok.</p>
					<p>Zsírsav: A tojás zsírsav energiaértéke igen magas. A telített és telítetlen zsírsav aránya a fürjtojás esetében nagyban függ a fürjek táplálkozásától. A többszörösen telítetlen zsírsavakat célirányos takarmányozással lehet feldúsítani, s szintén a fürjjel etetett táp milyenségével módosítható a telített zsírok háttérbe szorítás. A telítetlen zsírsavak élettanilag hasznos omega zsírsavakat tartalmaznak. Az emberi szervezet nem képes előállítani ezeket az omega-3 és omega-6 zsírsavakat, így a fürjtojás fogyasztása kitűnő lehetőség, hogy ezen hasznos esszenciális zsírsavak a szervezetünkbe jussanak. Ezzel szemben a telített zsírsav megnöveli a vér koleszterinszintjét, fokozva a szív- és érrendszeri betegségek kialakulását.</p>
					<p>Ahogy a telített zsírsav, úgy a fürjtojás vitaminmennyisége is a fürj takarmányának vitamintartalmával függ össze. A fürjtojás sárgája vitaminokban gazdag, A, D, E, B vitaminokat nagy mennyiségben tartalmaz, valamint jelentős a vastartalma is.</p>
					<h3>Tojáshéj</h3>
					<p>Az egészséges japán fürj tojásának héjvastagsága átlagosan 0,28 mm. A fürjtojáshéj védi az emberió fürjet és biztosít számos ásványi anyagot a fejlődő embrió számára, de számos baromfi számára lisztpor minőségűre zúzva hasznos étrendkiegészítő a fürjtojás héja. 90%-os kalcium-karbonát tartalmánál fogva az emberi szervezetre, a fejlődő gyermekek és csontritkulásban szenvedők részére is hasznos lehet, szintén lisztporrá őrölt formában.</p>
					<h3>Cégünkről röviden</h3>
					<p>A fürjtojás egészséges és ízletes ételként egyre inkább keresett termékké nőtte ki magát hazánkban is. Cégünk, a Tamago Kft. az egyetlen nagy múltú fürjtojás feldolgozó Magyarországon: 1989 óta szolgáljuk ki a vásárlók és érdeklődők igényeit. Többszörösen díjazott termékeink a füstölt fürjtojás és a konzerv fürjtojás, melyekre weboldalunkon leadhatja igényét fürj tenyésztojással és nyers fürjtojással egyetemben. Szezonálisan minden év májusában élő, 7 hetes fürjcsaládokat is értékesítünk, amennyiben igényét előre, márciusban a (06)-28-447-480 telefonszámon jelzi. Az élő fürjekkel megkímélheti magát a fürj keltetéssel járó nehézségktől és egyből már tojóképes egyedekkel rendelkezhet.</p>
					<h2>
					<img src="/uploads/fustolt-furjtojas.jpg" style="float:left; height:732px; width:490px;" title="Nyers fürjtojás" alt="Nyers fürjtojás">
					Füstölt fürjtojás
					</h2>
					<p>Az emberiség történetében az élelmiszerek hosszantartó fogyasztásra való alkalmassá tétele mindig is központi téma volt. De hogyan lehet a fürjtojás fogyaszthatósági idejét megnövelni?</p>
					<p>A fürjtojás nagy fehérjetartalma miatt romlandó termék, megfelelő kezelést és tárolást igényel, hogy a gyors romlástól megóvjuk. A fürjtojás tartósításának módszere a füstölés. A füstölés, mint tartósítási eljárás annyira a régmúltba nyúlik vissza, hogy régészeti ásatások igazolják, hogy a szárítás és sózás mellett ez volt a harmadik legősibb tartósító eljárás. Teljes bizonyossággal kijelenthetjük, hogy amióta az emberiség ismeri a tüzet, füstjét húsok, halak konzerválására is használta. A régmúlt korokban a füstölés hozzátartozott a mindennapi élethez, az ételek mindennapos, házi tartósításához. A húsok és sajtok füstölése mindenki számára ismert eljárás, de hogy a tojást is füstöljük, különösképpen a fürjtojást, ez lehet, hogy sokaknak újdonság.</p>
					<h2><strong>Fürjtojás füstölés folyamata</strong></h2>
					<p>A tojásokat alaposan megtisztítjuk, majd megfőzzük. A héját géppel maradéktalanul eltávolítjuk. Ezután pácoljuk, majd Maurer-féle kombinált hőkezelő szekrényben füstöljük a tojásokat. Felöntőként étolajat használunk.&nbsp;A fürjtojás a füstölés révén nemcsak hogy tartós élelmiszerré válik, hanem önmagában is nagyon ízletes, étvágygerjesztő ételt kapunk. A füstölt fürjtojásnak igen nagy szerepe van a vendéglátásban is. Kiválóan alkalmas szendvicsek, hidegtálak díszítésére, ízesítésére illetve sör és borkorcsolyának is kiváló.</p>
					<p>A füstölés a konzerválás és jobb ízvilág biztosítása mellett egyéb hasznos célt is szolgál az élelmiszerekkel kapcsolatban. A füstöléssel meggátoljuk a baktériumok szaporodását oly módon, hogy a fürjtojás nem veszít tápértékéből sem.</p>',
			],
			'fustolt-furjtojas'                => ['Füstölt fürjtojás', '<h1>A füstölt fürjtojás</h1>
					<p>Az emberiség történetében az élelmiszerek hosszantartó fogyasztásra való alkalmassá tétele mindig is központi téma volt. De hogyan lehet a fürjtojás fogyaszthatósági idejét megnövelni? Mielőtt ismertetnék a fürjtojás konzerválásának mibenlétét, ejtsünk néhány szót magáról a fürjtojásról.</p>
					<p>A fürjtojást, mint neve is mutatja a japán fürj madárnak köszönhetjük. A japán fürj egy éven belül általában kétszer kotlik. Tojásait a földön, mélyedésekbe rakja le, egyszerre maximum 8 tojást. A fürjtojás kinézetét tekintve apró, fehér alapon barna pettyes héjú, alakjában a normál tojással megegyező formájú tojás. A fürjtojások színárnyalata és rajzolata azonban mindig más.</p>
					<p>
					<img src="/uploads/image_1.jpg" style="float:left; height:463px; width:388px;" title="Fürjtojás" alt="Fürjtojás" >
					A fürj tojása igazi ínyencségnek számít. Egy fürjtojás tömege átlagosan 10 gramm, energiatartalma 15 kcal. Bár a fürjtojás méretét tekintve igen apró, vitaminértéke jóval magasabb a többi szárnyas tojásáénál, s koleszterinszintje pedig a legalacsonyabb valamennyi között.</p>
					<p>Számos kultúrában a fürjtojás az egészséges táplálkozás alapanyaga, hisz hatszor több ásványi anyagot (foszfor, réz, vas, cink) és vitamint (pl. B vitamin) tartalmaz, mint a legáltalánosabb körökben fogyasztott tyúktojás. Elmondható, hogy a fürjtojás a legegészségesebb és legbiztonságosabb fogyasztású tojásféle.</p>
					<p>Annál inkább tápláló a japán fürj tojása, minél természetesebb körülmények között él maga a kotló madár. A fürj gyógyhatású tojásainak és egyszerű tartási követelményeinek köszönhetően egyre kedveltebb háziszárnyassá növi ki magát, már-már helytálló a „jövő baromfija” elnevezés használata rá.</p>
					<p>A fürjtojás nagy fehérjetartalma miatt romlandó termék, megfelelő kezelést és tárolást igényel, hogy a gyors romlástól megóvjuk. A fürjtojás tartósításának módszere a füstölés. A füstölés, mint tartósítási eljárás annyira a régmúltba nyúlik vissza, hogy régészeti ásatások igazolják, hogy a szárítás és sózás mellett ez volt a harmadik legősibb tartósító eljárás. Teljes bizonyossággal kijelenthetjük, hogy amióta az emberiség ismeri a tüzet, füstjét húsok, halak konzerválására is használta. A régmúlt korokban a füstölés hozzátartozott a mindennapi élethez, az ételek mindennapos, házi tartósításához. A húsok és sajtok füstölése mindenki számára ismert eljárás, de hogy a tojást is füstöljük, különösképpen a fürjtojást, ez lehet, hogy sokaknak újdonság.</p>
					<p>A fürjtojás a füstölés révén nemcsak hogy tartós élelmiszerré válik, hanem önmagában is nagyon ízletes, étvágygerjesztő ételt kapunk. A füstölt fürjtojásnak igen nagy szerepe van a vendéglátásban is. Kiválóan alkalmas szendvicsek, hidegtálak díszítésére, ízesítésére.</p>
					<p>A füstölés a konzerválás és jobb ízvilág biztosítása mellett egyéb hasznos célt is szolgál az élelmiszerekkel kapcsolatban. A füstöléssel meggátoljuk a baktériumok szaporodását oly módon, hogy a fürjtojás nem veszít tápértékéből sem.</p>
					<h2>Hogyan készül a füstölt fürjtojás?</h2>
					<p>A 250 darabos dobozokban érkező nyers, friss fürjtojás feldolgozása, füstölése, ezáltal tartósítása a következő módon zajlik. Első lépés a mosás, ezt követi a szárítás és szennyezett tojások fertőtlenítése. A friss tojásokat 10 percig fertőtlenítő folyadékba helyezzük, hogy a fertőtlenítés megakadályozza vagy minimalizálja a légi úton történő szennyeződéseket. A fertőtlenített, feldolgozásra kész tojásokból úgy készül füstölt fürjtojás, hogy a füstölésre szánt tojásmennyiséget egy éjszakára sós vízbe helyezik. Maga a füstölés egy nagy fém kamrában zajlik, bükkfa segítségével. A kész füstölt tojás csomagolása többféle kiszerelésben (10,20 és 50 darabos) lehetséges, papírdobozos vagy hajlékony műanyag fóliatasakban, melyekben felöntőlé (étolaj) van a tojások körül és ez rugalmas csomagolást biztosít. A kész füstölt fürjtojás ezt követően áll vevőink rendelkezésére. Igény szerint, kereskedelmi célra, díszdobozba (színes papírkarton) tesszük.</p>
					<h3><b>Miért a fürjtojást tartjuk a legjobbnak?</b></h3>
					<p>A tojás az emberek legjelentősebb táplálékforrása. Ez így van napjainkban és így volt mindig is. A tojás mindig is egyszerűbben megszerezhető volt, mint például a hús, s mind tápértéke, mind fehérje- és zsírtartalma vetekszik a húséval. Számos szárnyas tojását fogyaszthatjuk, mégis az emberi étkezésben a tyúktojás fogyasztása a legelterjedtebb.</p>
					<p>
					<img src="/uploads/fustolt-furjtojas-50db1.jpg" style="float:left; height:279px; width:300px;" alt="Füstölt fürjtojás">
					A fürjtojás tojásfehérje tartalma az összes többi emberi fogyasztásra alkalmas madár tojásától nagyobb. Míg pl. a tyúktojás 55% fehérjét tartalmaz, a fürj tojása 60% tojásfehérje tartalmú. Az egészségtudatos életmódra törekvő emberek többsége felfedezte már a fürjtojás abszolút elsőbbségét, amiben nemcsak tojásfehérje koncentrációjának mennyisége, hanem számos egyéb vitamintartalma is kiemelhető. Napjainkban egyre inkább előtérbe helyeződik a fürjtojás, szemben a hagyományos tyúktojással, a japán fürjet a jövő baromfijaként emlegetik.</p>
					<p>Miért van szükségünk fehérjére, ezáltal miért hasznost tehát az emberi szervezet számára a tojásfehérje és a fürjtojás fehérjéje?</p>
					<p>A fehérje a legjobb aminosav. Fehérjékre a szervezet minden sejtjének szüksége van, mindenféle szövet, szerv, izom növekedése és az elpusztult sejtek pótlása fehérjét kíván.&nbsp; A tojásfehérje, más néven albumin élettani jelentősége abban emelhető ki leginkább, hogy a baromfi a kevésbé értékes növényi fehérjék fogyasztása révén nagy értékű tökéletes fehérjét állít elő, erre az emberi szervezet nem képes.</p>
					<p>A fürjtojás igazi kincs, a legmagasabb biológia értékkel rendelkező fehérje-forrás. Egyetlen fürjtojás fehérjéje közel 3 dkg húsnak felel meg. A tojásfehérje pozitívuma, hogy könnyen és maximálisan emészthető, különösen főtt állapotban, s átalakítás, azaz sütés-főzés közben sem veszít értékéből. A fürjtojás fogyasztható nyersen, főzve, sütve, aszpikba, feldolgozva és füstölve egyaránt.</p>
					<p>A fürjtojás tojásfehérje koncentrációja magasabb, mint a tyúktojásé, emellett beltartalma és magas vitaminértéke, valamint alacsony koleszterinszintje miatt is jóval hasznosabb az emberi szervezetnek, mint a hétköznapokban elterjedt tyúktojás. Kiemelendő tulajdonsága továbbá az eltarthatósága, hűtőben tárolva 90 napig, szobahőmérsékleten 30 napig őrzi meg szavatosságát, de az Európai Úniós szabályok szerint csak három hétig tárolható.</p>
					<p>Egy japán fürj éves tojáshozama megközelítőleg 250 darabra tehető.</p>
					<p><b>Füstölt fürjtojás kiszerelések</b></p>
					<p>A fürjtojás kiszerelési egysége kínálatunkban 10 és 50 darabos füstölt fürjtojást tartalmazó tojásdoboz (szavatossági ideje 70 nap), illetve natúr konzerv (8-10 darabos – 200 grammos illetve 30 darabos – 700 grammos) &nbsp;sós lében tárolt tisztított fürjtojás. A konzerv fürtjtojás szavatossági ideje 1 év.</p>
					<p><b>A fürjtojás eltarthatósága</b></p>
					<p>A fürjtojás kiemelendő tulajdonsága hosszan tartó eltarthatósága. A friss fürjtojás hűtőben tárolva 90 napig, szobahőmérsékelten 30 napig őrzi meg szavatosságát. Fontos előírás, hogy csak ép, sértetlen tojáshéjú, szárazon megtisztított tojást tároljunk. Az ideális tárolási hőmérsékelt 5-8 C fok közötti. A szobahőmérsékleten tárolt friss tojás egy nap alatt többet veszít frissességéből, mint hűtőben tárolva több napon keresztül.</p>
					<p>Mint minden tojás, a fürjtojás is az első héten igazán friss, biztonságosan készíthető belőle lágy tojás, buggyantott tojás egyaránt.</p>
					<h5>Hogyan ellenőrizhető egy tojás frissessége?</h5>
					<p>A legismertebb módszer a tojás vízbe helyezése. A friss tojás leül a víz aljára, az idősebb középen úszik, a legrégebbi pedig felmegy a víz tetejére a nagy légzsák miatt.</p>
					<p>A másik ismert módszer a rázás, az egynapos fürjtojás gömbölyű végénél levő légkamra ekkor még alig észlelhető, ez napról napra kissé nő. Egy egészen friss fürjtojást ha fülünkhöz emelve megrázzuk, nem hallunk semmit, de néhány nappal később már gyenge, tompa hangot hallat.</p>
					<p>A tojás eltarthatóságát minden esetben tartalmaznia kell a csomagolásnak. Annak az időpontnak a rábélyegzése, amikor a tyúk tojta, nem kötelező, de a tojás eltarthatósági idejét fel kell tüntetni.</p>
					<p>A friss tojás tárolására a legcélszerűbb a tojástartó műanyag doboz, un. tojásdoboz használata. A tojásdoboz tiszta, kifolyt tojástartalomtól mentes legyen illetve szellőzőnyílásokkal legyen ellátva. Csak ép, szárazon megtisztított tojást tároljunk, száraz, hűvös helyen, maximum 5-12 C -on. 5 C alatt méginkább eláll a tojás.</p>
					<p>Ha azt szeretnénk, hogy tovább friss maradjon a tojás, csúcsával felfelé tároljuk (ekkor viszont leereszkedik a sárgája a fenekébe). Akkor tegyük csúcsával lefelé a tojásdobozba, ha azt szeretnénk, hogy sárgája középen legyen – így viszont hamarabb romlik.</p>',
			],
			'fott-furjtojas-konzerv'           => ['Főtt fürjtojás konzerv', '<h1><b>Főtt fürjtojás konzerv</b></h1>
					<p>
					<img src="/uploads/natur-konzerv-furjtojas-10db.jpg" style="float:left; height:346px; width:300px; margin-right:15px;" alt="Főtt füstölt fürjtojás">
					A 250 darabos dobozokban érkező nyers, friss fürjtojás feldolgozása során egy részéből füstölt fürjtojás, másik részéből natúr konzerv fürjtojást készítünk.</p>
					<p>A fürjtojások fertőtlenítése két szakaszban történik, a fürjtojás mindkét alkalommal öblítésre is kerül. Így kerül át a feldogozó térbe.</p>
					<p>A mosás, szárítás és szennyezett tojások fertőtlenítése az első lépés. A friss tojásokat 10 percig fertőtlenítő folyadékba helyezzük, hogy a fertőtlenítés megakadályozza vagy minimalizálja a légi úton történő szennyeződéseket. A héjától megtisztított natúr tojásokat sólével felöntjük, és hőkezelő szekrényben tartósítjuk, ezzel jutunk el a konzerv tojás készítés végső fázisához. A natúr konzerv sós lében tárolt tisztított fürjtojás kínálatunkban ezt követően megvásárolhatóvá válik Vevőink számára.</p>
					<p>A konzerv fürjtojás használatát kifejezetten ünnepi levesekbe ajánljuk, a tojásokat elég csak a főzés végeztével a levesbe helyezni, hogy éppen csak átmelegedjen, főnie nem kell.</p>',
			],
			'szojabab'                         => ['Szójabab', '<h1>Szójabab</h1>
					<p>A szójabab a pillangósvirágúak családjába tartozik, Afrika, Ázsia trópusain honos lágyszárú növény.</p>
					<p>A szójababnak több fajtája ismert, igen kedvelt íze és közismert gyógyhatása miatt a zöld szójabab, amit Mungnak, Mungóbabnak is neveznek.</p>
					<p>Kinézete alapján a szója karószerű főgyökérrel rendelkezik, ez 1,5-2 m mélységig is lehatol a földbe. Ebből kiinduló oldalelágazásai és felszívó hajszálgyökerei a talaj felső részén találhatóak. Főhajtásának hossza eléri a 80-100 cm-t. Középzöld, szórt állású levelei vannak, melyek alakja a szójabab fajtáitól függően változó, a kerek tojásdadtól a keskeny lándzsásig terjedhet, éréskor leveleit lehullatja.</p>
					<p>
					<img src="http://www.furjtojas.eu/wp-content/uploads/2012/12/zold-szojabab.jpg" style="float:left;" height="400" width="400" alt="zöld szójabab">
					A zöld szójabab meleg, párás levegőt igényel, egyenletes 20-25 °C körüli hőmérsékletet, még az árnyékot sem tűri. A nagy hőingadozások nem kedveznek fejlődésének, de a száraz forróság sem. A zöld szójabab közepesen vízigényes, szárazabb időszakokban feltétlenül öntözzük, különben hozama visszaesik. Vízigényét jelzi, ha csúcsi levelei hervadó fonnyadást mutatnak. Nedves, laza vagy közép kötött, de jól levegőző talajon fejlődik a legjobban.</p>
					<p>Tápanyagigény tekintetében a zöld szójabab közepes tápanyagigényű.</p>
					<p>A zöld szójabab termesztése szabadföldi magvetéssel történik, akkor optimális a magvetést elkezdeni, ha a felső talajréteg eléri a 6-8 °C-os hőmérsékletet. A zöld szójabab tenyészideje alatt folyamatos többszöri saraboló kapálással tartsuk gyommentesen és morzsalékosan a talajt. Szedését levelei teljes lehullatása után kezdjük meg, ilyenkor már a csúcsi magok is beérnek. A magokat megszárítás után óvatosan kell kezelni, mert a maghéj könnyen reped és törik.</p>
					<div style="clear:both;"></div>
					<h2>
					<img src="/uploads/szojabab-edamame.jpg" style="float:left; height:400px; width:400px" title="Szójabab edamame" alt="Szójabab Edamame">
					Miért egészséges táplálék a zöld szójabab?</h2>
					<p style="text-align: left;">A zöld szójabab fehérjetartalma elérheti a 40%-ot, zsírtartalma pedig a 20%-ot, ebből kifolyólag rendkívül hasonlít az állati eredetű zsírokhoz és fehérjékhez, ezért a szója kiváló húspótló.</p>
					<p style="text-align: left;">A zöld szójabab Ázsiában igen nagy szerepet játszik az emberi táplálkozásban, Kínában már több évezrede termesztik. Európába még csak a XVIII. században jutott el, Amerikában pedig még később vált ismertté. Nagybani termesztése még csak kb. 20 éve kezdődött meg.</p>
					<p style="text-align: left;">A szója magőrleményeket tej- és sajtszerű készítményekké dolgozzák fel, emellett mártások, köretek készítésére is alkalmas. Az olajkinyerés után visszamaradó magrészek igen értékes takarmánydúsítók, a szója már legfontosabb takarmánynövényünkké nőtte ki magát.</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>A szóját az élelmiszeriparban margarin, édességek, hústöltelékek, pástétomok készítéséhez alkalmazzák, félig száradó olaját a festék- és gyógyszeripar is hasznosítja.</p>
					<p>100 g zöld szója tápérték adatai:</p>
					<ul>
					<li>Kalória: 147 kcal – 7,4%</li>
					<li>Fehérje:12,95g – 25,9%</li>
					<li>Szénhidrát: 11,05g – 4,1%</li>
					<li>Zsír: 6,8g – 9,7%</li>
					<li>Élelmi rost: 4,2g – 16,8%</li>
					<li>Nátrium: 0,015g – 0,6%</li>
					</ul>
					<p>A Mung, vagyis zöld szójabab rendkívüli módon igénytelen, nem kíván szakértelmet a kezelése és a termés gondozása, mondhatni ha egyszer már elvetettük, vadon nő.</p>
					<p>Gyógyhatása révén különösen vesebetegeknek ajánlott, de számos kísérleti tanulmány bizonyítja, hogy összetevői révén a szójából készült ételek fogyasztása a mellrák kockázatát is jelentősen csökkenti.</p>
					<p>Japánban és az angol nyelvterületeken Edamame néven ismerik. Szezonális, augusztus közepétől szeptember közepéig tudjuk a beérkező rendeléseket teljesíteni, de amennyiben ilyen terméket szeretne rendelni, kérjük mindenképpen érdeklődjön telefonszámainkon vagy emailben.</p>',
			],
			'shiso-level'                      => ['Shiso levél', '<h1>Shiso levél</h1>
					<p>A Shiso az ajakosvirágúak rendjébe tartozik. Őshazája Délkelet-Ázsia. Igénytelen növény, kellő mennyiségű csapadék és napfény mellett bárhol elél.</p>
					<p>
					<img src="/uploads/shiso-level.jpg" style="float:left; height:400px; width:400px; margin-right:15px;" alt="Shiso levél">
					Egynyári, lágyszárú növény, bolyhos szára vagy lila vagy zöld színű, ez alapján beszélünk vörös és zöld shiso-ról. Kinézetre a fodros bazsalikomhoz hasonlítható. Levelei apró szőrökkel fedettek, a levelek vállai tompák, de csúcsai kihegyesedők, szélük pedig fogazott.</p>
					<p>A shiso Ázsiában kulináris gyógynövény, kitűnő fűszer, úgy is hívják, hogy „a kínai bazsalikom”. A kínai konyha kedvelt és sokat használt fűszere, íze leginkább a fahéjra, ánizsra és az édesgyökérre hasonlít. Termesztik tehát aromás leveleiért, amit többnyire frissen használnak. Sziklevele is ízesítőszer.</p>
					<p>A vörös shiso akajiso, aka shio neveken ismert. Az umbeboshit és az eltett gyömbért is színezik vele. A vörös shiso-t néha kiveszik az umbeboshi mellől és megszárítva, porrá zúzva őrölt fűszersóként használják „shiso momiji” néven.</p>
					<p>A vörös shiso leveleit gyönyörű formájukért termesztik. Olajban süthető levelei fantasztikus dekorációk sült húsokhoz, köretekhez.</p>
					<div style="clear:both;"></div>
					<p>A zöld shiso, más néven aojiso, ao shiso fűszeresebb aromájú, mint a vörös shiso, ez részben magas festékanyagának is köszönhető, akár színező szerként is használhatjuk. A zöld shisot sashimi, saláták, sushi és tempura ízesítésére használják.</p>
					<h2>Mire használják a Shiso levelet?</h2>
					<p>A shiso felhasználás igen sokrétű, a távol-keleti országokban más-más fajtáját termesztik.</p>
					<p>Japánban látványos és aromás leveleit főképp friss fűszerként, ételszínezékként használják, de Japánban a shiso virágbimbóját is fogyasztják.</p>
					<p>Kínában a Perilla var. Frutescens fajtája a legismertebbe, melynek szárított magját fűszerként használják. A perillameg olajipari felhasználása a lenolajhoz hasonlítható.</p>
					<p>De más-más változata használt Koraában és Vietnámban is.</p>
					<p>A shiso teafőzetét egészségügyi hatásai miatt kedvelik, elsősorban gyulladáscsökkentő ereje miatt használják. A shiso leveleinek antiallergén hatást tulajdonítanak, a kínai gyógyászat allergiás tünetek enyhítésére, valamint viszkető bőrpanaszok, duzzanatok kezelésére alkalmazza.</p>
					<h3>A Japán SHISO levél szervezetünkre gyakorolt jótékony hatásai</h3>
					<ul>
					<li>Normalizálja az immunrendszert, így hat a bőrgyulladásra, szénanáthára és enyhíti az egyéb allergiás tüneketet. Erős antibakteriális, romlást gátló hatásai miatt nélkülözhetetlen a nyers halételekhez. Sashimiez ételízesítőként javasolt. Megelőzi az ételmérgezést, serkenti az emésztő enzimek kiválasztását, rendbe teszi a gyomrot.</li>
					<li>Béta karotin tartalma a sárgarépáéval azonos. A béta karotin A vitaminná való változtatása kapcsán segíti a látást, hallást, a nyálkahártyák és a bőr védelmét.</li>
					<li>Erősíti az ellenálló képességet, antioxidáns hatása által segít a rák megelőzősében, gátolja az öregedést.</li>
					<li>Sok benne a vas és a C-vitamin, vérszegénység megelőzésére is kitűnően alkalmas. Vegetáriánusok például szójaszószba keverve fogyasztják.</li>
					<li>Kevés benne a kalória, de magas a tápértéke, ezért nagyon ajánlott salátákba és egyéb diétás ételekbe keverni. Sok benne a kálium, mely segíti a vizelet kiválasztását, verejtékezést. Rendbe teszi és helyreállítja a felesleges vízvisszatartást.</li>
					<li>Magas kalcium tartalma miatt idegrendszerre nyugtató hatással van.</li>
					</ul>
					<p>&nbsp;</p>
					<h4>Népi használatban a következő esetekben alkalmazzák:</h4>
					<ul>
					<li>megfázáskor</li>
					<li>nyugtalanság</li>
					<li>alvászavar</li>
					<li>ételmérgezés</li>
					<li>hal, rák, kagyló okozta allergiás tünetek esetén</li>
					<li>vágott seb kezelésére</li>
					</ul>
					<p>Ez a termékünk szezonális, minden év júliusától október végéig rendelhető. Amennyiben szeretne rendelni, kérjük keressen minket telefonszámainkon.</p>',
			],
			'furj-tenyesztojas'                => ['A fürj tenyésztojás', '<h1>A fürj tenyésztojás</h1>
					<p>A fürj tenyésztojás iránti kereslet az utóbbi években jelentősen megugrott. Ez köszönhető a fürj ízletes húsának, gyógyhatású tojásainak, magas tojáshozamának, valamint annak a tényezőnek, hogy a fürjcsibe nagyon hamar (17. napon) kikel a tojásból és ideális körülmények között hamar, általában a hetedik héten belül elkezd tojni. Gazdaságos, egészséges dolog tehát a fürjtenyésztés. A fürj maximálisan esedékese a jövő baromfija címre.</p>
					<p>Ha valaki fürjet szeretne tartani, első lépés a megbízható minőségű fürj tenyésztojás beszerzése, amire kínálatunkban Önnek is lehetősége nyílik. A fürj tenyésztojás a kifejezetten tojástermelési céllal nevelt fürjek tojása. Az általunk kínált fürj tenyésztojás megbízható feltételek között jön létre, biztonsággal állítjuk, hogy maximális keltetési arányt nyújtanak tenyésztojásaink. Ez köszönhető a megfelelő tenyészállat célzott kiválasztásának és táplálásának.</p>
					<p>A fürj tenyésztéstojás keltetésének folyamatában a felkészülés első lépcsőfoka a megfelelő tenyészállatok kiválogatása. Célszerű minél több vérvonalból összeállítani a törzseket, főképpen a hím ivarú fürj legyen idegen vérvonalú, legalább minden második évben. A tenyésztés időszakára a fürjeket alaposan fel kell készíteni, vitaminokkal, nyomelemekkel, ásványi anyagokkal, megfelelő takarmánnyal. A tenyésztojások begyűjtése általában január végén kezdődik. Kiemelt figyelmet fordítsunk arra, hogy csak ép, egészséges, tiszta tojások kerüljenek keltetésre.</p>
					<p>A tenyésztojások gondos tárolást igényelnek, ami nem haladhatja meg a 8-10 napot, mert az a kelés-arány rovására mehet. A fürj tenyésztojás tárolása 8-10 C hőfokon történik, enyhén sötét helyen és naponta egyszer meg kell forgatni őket. A tojáskeltetés keltetőgéppel és fürjkotlós által is történhet. Keltetőgépből 17-18. napon, fürjkotlós által a 18-19. napon kelnek ki.</p>
					<p>Tudni kell, hogy egy kikelt fürj első tojásai csak étkezésre jók, keltetésre nem. A tojó ivarérettségekor, azaz a 7 hetes korától kezdődően tojik. Keltetés céljára csak 10 héttől tojt tojások alkalmasak. Természetes tartásban egy évben kb. 250 tojást tojik a japánfürj.</p>',
			],
			'furjtojas-keltetes'               => ['Fürjtojás keltetés', '<h1>Fürjtojás keltetés</h1>
					<p>A tojáskeltetés legfontosabb mozzanatait az alábbiakban foglaljuk össze, melyeket betartva 70%-os kelési arányt érhetünk el.</p>
					<p>A keltetés leggyakoribb formája a keltetőgép használata. Keltetőnek nevezzük azt a berendezést, melyben biztosítjuk a kelési hőmérsékletet és páratartalmat, s melyben a tojásokat forgatjuk. A keltetés során a tojásokat naponta forgatni kell, de az első három és utolsó három napon a tojásokat nem tanácsos a forgatni.</p>
					<p>A keltetés optimális hőmérséklete 37,2 – 37,6 C. Az optimális páratartalom 85-95%. Ez azt a célt szolgálja, hogy a tojások haja megpuhuljon.</p>
					<p>Nagyon fontos momentum a szellőztetés, mivel a fejlődő embrió is levegőzik. Száz vagy több tojás zárt térben hamar telítődik elhasznált vagy mérgezett gázzal, ezért a szellőztetés elsődleges. A tojásforgatás időszakában általában napi kétszer, a keltetés végén (kb. 15. naptól) napi háromszor szükséges.</p>
					<p>Minden keltetőgépben van egy ventilátor, melynek szerepe, hogy a beállított hőmérséklet és a párás levegő egyenlő részben szóródjon szét a beltérben, tehát a levegőztetés is elengedhetetlen.</p>
					<p>A fürjtojás keltetés fontos momentuma a hűtés, ami nem azonos a levegőztetéssel. Lényege a tojás felforrósodásának elkerülése. A tojás optimális hőfoka úgy állapítható meg, ha szemhéjunkra helyezzük és nem érezzük forrónak. Ha valamely szerkezetnél nincs hűtés funkció, gyakori szellőztetéssel vagy a fűtés időnkénti kikapcsolásával (15-20 percre) oldható meg a hűtés.</p>
					<p>A keltetés elejétől a végéig tiszta vízzel párásítsunk, ez is elmaradhatatlan.</p>
					<p>Az utolsó három napban a tojásokat permetezzük langyos ecetes vízzel, ez elősegíti a kemény fürjtojáshéj felpuhulását. A 14. napon a tojásokat meg kell lámpázni, s a fiatlan tojásokat el kell távolítani. Ekkor már hallani kopogásokat, halk csipogást. A csibék először kilukasztják a tojás héját, majd pihennek 6-12 órát és aztán repesztenek és kibújnak. Ha 12 óra elteltével sem sikerült a csibéknek kirepeszteni a tojáshéjat, akkor lehet rajtuk segíteni, különben befulladnak.</p>
					<p>A tojáskeltetés során a japán fürj csibéje tojásából&nbsp; a 17-18. napon bújik elő. Az új tartásmódnak köszönhetően nem ritka, hogy maga a fürjkotlós kelti ki a csibéit és azok a 18-19. napon ki is kelnek. A gépi keltetésnél a tojások száma a gép befogadó képességétől függ, kotlóssal történő keltetésnél ez kb. 7 tojásban maximálható.</p>
					<h2>Miután kikeltek a fürj csibék</h2>
					<p>A kikelt csibéket rögtön át kell helyezni a számukra előkészített neveldébe, ahol 38 fok van. megerősödés és száradás miatt – megfelelő hőmérséklet és páratartalom mellett -, majd áthelyezhetőek a számukra előkészített inkubációs készülékbe (melegedő), itt folytatják életük első fejlődési szakaszát. A kikelt csibék száradásukat követően azonnal önállóak. Célszerű kamillateát adni nekik, bélrendszerüket átmosandó, utána pedig Jolovit vitamin koncentrátumot.</p>
					<p>Fontos megjegyezni, hogy a fürj csibék kikelése után az első 24 óra a kritikus, akkor dől el, hogy életben maradnak-e (például a hőmérsékletváltozás miatt) tehát folyamatos felügyeletet igényelnek, állandóan mellettük kell lenni.</p>
					<p>Az inkubációs készülékben a tollasodásig maradnak a kis fürjek, mely teljes időtartama kb. 6 hét. Ez idő alatt a hőfok folyamatos csökkentésére is figyelmet kell fordítani. Napról napra kezdetben fél, majd egy fokkal kell csökkenteni a hőmérsékletet, emellett a páratartalmat is.</p>
					<p>A fürj csibék felneveléséhez a legjobb fácán indítótápok használata, amit egy hetes kortól főtt tojásos lágyeleséggel, apróra vágott zöldséggel célszerű kiegészíteni. A fürj várható élettartama leginkább táplálásától függ, szabad kifutóban sokáig elélhetnek.</p>
					<p>Amennyiben teljeskörű képet szeretne kapni, ajánljuk Dr. Bogenfürst Ferenc „Keltetés” című szakkönyvét illetve „A japánfürj és tenyésztése” (Czibulyás József – Tóth Sándor) könyvet.</p>',
			],
			'tenyesztojas-rendelesi-lehetoseg' => ['Fürj tenyésztojás igénylési lehetőség', '<h1>Fürj tenyésztojás igénylési lehetőség</h1>
					<p>Önnek lehetősége van eladó fürj tenyésztojás igénylésére regisztráció nélkül.</p>
					<p>Az eladó tenyésztojások igénylését a Tojás rendelés menüpont alatt tudja leadni. A igénylőlap kitöltése nem minősül online vásárlásnak, csak igényleadásnak, a termékkel kapcsolatos jövőbeli vásárlási szándék kifejezésének. Az eladó tenyésztojás iránti vásárlási szándékának megerősítésére minden esetben telefonos kapcsolatba lépünk Önnel, s az adás-vételi ügylet az áru kiszállításakor realizálódik.</p>
					<p>A tenyésztojás igénylólap kitöltése után automatikus visszajelzést küldünk email címére, majd rövid időn belül telefonon is felvesszük a kapcsolatot Önnel a megrendelés kiszállításával vagy átvételével kapcsolatban. Rendelési adatait minden esetben telefonos egyeztetés során véglegesítjük. A megrendelés szóbeli, telefonos megerősítést követően minősül csak valós vásárlásnak.</p>
					<p>Igényleadáskor rögzített adatait bizalmasan kezeljük, harmadik félnek nem adjuk ki.</p>
					<p>Ha véletlenül hibásan adta le megrendelését, kérjük az INFO [KUKAC] FURJTOJAS [PONT] EU&nbsp;email címre „Hibás igénylőlap” tárggyal jelezze felénk.</p>
					<p>A megrendelt &nbsp;tenyésztojások kifizetése házhozszállítás esetén átvételkor történik.</p>
					<p>Megrendelheti úgy is a terméket, hogy nem kér házhozszállítást, hanem személyes átvétellel a Csömörön, Szakura utca 5-7 alatt található átvevőhelyen tudja átvenni és kifizetni. Ez az átvételi pont csak tenyésztojások rendelése esetén működik, minden más termékünket központi telephelyünkön tudja személyesen átvenni.</p>
					<p><strong>Az egy darab, 15 darabos fürj tenyésztojás csomag ára 1125 Forint, az országos lefedettségű futárszolgálattal rendelhető minimum mennyiség 3 csomag (45 darab tenyésztojás) amely 3375 Ft. A futárszolgálat díja 990 Ft, tehát a minimum tenyésztojás rendelés 3375 Ft + 990 Ft = bruttó 4365 Ft . Amennyiben legalább 15.000 Ft összegben igényel fürj tenyésztojást, NEM KELL KISZÁLLÍTÁSI DÍJAT FIZETNIE! &nbsp;(990 Ft.) Természetesen nem szükséges csak tenyésztojást rendelnie, rendelhet késztermékeket is, &nbsp;például füstölt fürjtojást, főtt fürjtojás konzervet, nyers fürjtojást.</strong></p>',
			],
			'tojas-eltarthatosag'              => ['Tojás eltarthatóság', '<h1>Tojás eltarthatóság</h1>
					<p>A fürjtojás kiemelendő tulajdonsága hosszan tartó eltarthatósága. A friss fürjtojás hűtőben tárolva 90 napig, szobahőmérsékelten 30 napig őrzi meg szavatosságát. Fontos előírás, hogy csak ép, sértetlen tojáshéjú, szárazon megtisztított tojást tároljunk. Az ideális tárolási hőmérsékelt 5-8 C fok közötti. A szobahőmérsékleten tárolt friss tojás egy nap alatt többet veszít frissességéből, mint hűtőben tárolva több napon keresztül.</p>
					<p>Mint minden tojás, a fürjtojás is az első héten igazán friss, biztonságosan készíthető belőle lágy tojás, buggyantott tojás egyaránt.</p>
					<p>
					<img src="/uploads/friss-tojas.jpg" style="float:left; height:403px; width:403px; margin-right:15px;" title="Friss tojás" alt="Friss tojás">
					</p>
					<h2>Hogyan ellenőrizhető egy tojás frissessége?</h2>
					<p>Ismert módszer a rázás, az egynapos fürjtojás gömbölyű végénél levő légkamra ekkor még alig észlelhető, ez napról napra kissé nő. Egy egészen friss fürjtojást ha fülünkhöz emelve megrázzuk, nem hallunk semmit, de néhány nappal később már gyenge, tompa hangot hallat.</p>
					<p>A tojás eltarthatóságát minden esetben tartalmaznia kell a csomagolásnak. Annak az időpontnak a rábélyegzése, amikor a tyúk tojta, nem kötelező, de a tojás eltarthatósági idejét fel kell tüntetni.</p>
					<h3><b>A fürjtojás eltarthatósága</b></h3>
					<p>A fürjtojás kiemelendő tulajdonsága hosszan tartó eltarthatósága. A friss fürjtojás hűtőben tárolva 60 napig (EU-s előírás szerint 3 hétig), szobahőmérsékleten 30 napig őrzi meg szavatosságát. Fontos előírás, hogy csak ép, sértetlen tojáshéjú, szárazon megtisztított tojást tároljunk. Az ideális tárolási hőmérsékelt 5-8 C fok közötti. A szobahőmérsékleten tárolt friss tojás egy nap alatt többet veszít frissességéből, mint hűtőben tárolva több napon keresztül.</p>
					<p>&nbsp;</p>
					<p>Mint minden tojás, a fürjtojás is az első héten igazán friss, biztonságosan készíthető belőle lágy tojás, buggyantott tojás és sütemény egyaránt.</p>',
			],
			'egeszseges-etelek'                => ['Egészséges ételek', '<h1>Mi számít egészséges ételnek?</h1>
					<p>Az étkezés, táplálkozás az emberi élet elengedhetetlen része, hisz az emberi szervezet megfelelő és egészséges működéséhez táplálékot vesz magához, amit vérhálózat útján juttat el az egyes sejtekhez. A felvett táplálék egy része elhasznált anyagaink pótlását vagy éppen anyagaink gyarapítását szolgálja, más része pedig az élettevékenységekhez nélkülözhetetlen energiaszükséglet fedezésére szolgál.</p>
					<p>A táplálkozás mértékét az adott ember szervezetének szükséglete, illetve a felvett tápanyagok tápértéke határozza meg. Egy adott tápanyag, étel tápértékét energiaszolgáltatásának mértékével szokás jellemezni (kalória). Azonban nem közömbös az sem, hogy az egyes tápanyagokból milyen arányban részesül az emberi szervezet. Ételeinknek mindig tartalmaznia kell megfelelő mennyiségű és minőségű fehérjét, mert ebből vonja ki a szervezet azokat az aminosavakat, melyeket önállóan nem képes előállítani. Elengedhetetlen, hogy táplálékunk tartalmazzon un. járulékos tápanyagokat, vitaminokat, melyek nélkül súlyos zavarok léphetnek fel szerveink működésében.</p>
					<p>Egy felnőtt ember átlagos napi tápanyagigénye: 70 g fehérje, 50 g zsír és 500 g szénhidrát, mely átlagértékek a szervezet igénybevételével arányosan növekednek.&nbsp; Ez tehát az egészséges táplálkozás alapja. Az egészséges ételek összeválogatásának mérvadója&nbsp; különféle ételek, italok megfelelő arányban és mennyiségben megvalósuló rendszeres fogyasztása a fentiek figyelembevételével, s ezáltal számos betegség kockázata minimálisra csökkenthető. Tehát megfelelő mennyiségben kell étkezésünk során a szervezetünk számára energiát adó tápanyagokat (fehérjék, zsírok, szénhidrátok) és energiát nem adó tápanyagokat (vitaminok, ásványi anyagok, nyomelemek) biztosítani. Minél többféle nyersanyag felhasználásával, minél többszínű feldolgozási módszerrel ízletes és változatos étrendet állíthatunk össze a mindennapokban magunk és családunk számára, s biztosítjuk a szervezet optimális működéséhez minden szükséges tápanyagokat.</p>
					<p>Miért fontos az egészséges táplálkozás, az egészséges ételek fogyasztása? A betegségek kialakulásáért életmódunk mellett jelentős részben nem megfelelő táplálkozásunk a felelős. Mitöbb, a legsúlyosabb szív- és érrendszerbeli és egyéb betegségek 30-40%-a megelőzhető egészséges ételek fogyasztásával, az egészséges táplálkozással.</p>
					<h1>Egészséges ételek rövid összefoglalása:</h1>
					<ol>
					<li>Gabonafélék. Egészséges táplálkozásunk alapja a gabonafélék napi többszöri fogyasztása, főként teljes értékű gabonaipari termékek révén. Teljes értékű gabona: melynek feldolgozása során a teljes tápanyagtartalom megtartott. Ilyenek a teljes kiőrlésű lisztből készült termékek, korpával, olajos magvakból készített pékáruk, barna rizs stb. Jelentős szerepük van az energia- és szénhidrátszükséglet szavatolásában, valamint fehérje- és vitamintartalmuk sem elhanyagolható.</li>
					<li>Zöldségek, gyümölcsök, főzelékfélék. Ezen táplálékcsoport tartalmazza a legnagyobb mennyiségű vitamint, ásványi anyagot és élelmi rostot.</li>
					<li>Tej- és tejtermékek. Ajánlott napi mennyiség fél liter tej vagy annak megfelelő egység tejtermék fogyasztása. Hasznos kalcium és D-vitamin forrás, de a tejtermékek egyéb vitamintartalma is jelentős.</li>
					<li>Hús- és hústermékek, tojás. A húsok nagy mennyiségű teljes értékű fehérjét tartalmaznak, s egyebek mellett jó B-vitamin források. Húskészítmények nagy része 30-40% zsírt is tartalmaz, ezért napi fogyasztásra a soványabb húsok javasoltak. Belsőségek közül kiemelendő a máj tápértéke. Kedvező hatású a halhús, rendszeres fogyasztása kifejezetten ajánlott, főleg a hideg tengeri halaké, magas omega-3 zsírsav-tartalmúk révén.</li>
					</ol>
					<p>A tojás az emberek legjelentősebb táplálékforrása. Tápértéke a húsével vetekszik, mind fehérje, mind zsírtartalma tekintetében. A baromfitojások közül a tyúktojás fogyasztása a legáltalánosabb, holott a tyúktojás fehérjetartalmához képest a fürj tojása magasabb, 60% tojásfehérje tartalmú. A tojás fehérjekoncentrációja mellett számos egyéb vitamin forrása is.</p>
					<p>A szervezet normális életműködéséhez nélkülözhetetlen a folyadékpótlás biztosítása, elsősorban tisztított víz, un. pí víz vagy lúgos víz formájában. Napi 2-3 liter folyadékra van szükségünk, amibe szilárd táplálékaink víztartalma is beletartozik.</p>',
			],
			'tojas-etel'                       => ['Tojás étel', '<h1>Tojás étel</h1>
					<p>Évszázadokon keresztül a tojás az emberiség alaptáplálékaként szerepelt és szerepel napjainkban is. A tojás egyik legjelentősebb táplálékforrásunk, ez köszönhető magas tápértékének: fehérje- és&nbsp; zsírtartalmának. A tojás teljes biológiai értékű fehérjéket tartalmaz, azaz az összes esszenciális aminosavat tartalmazzák, melyeket az emberi szervezet önmagában nem képes előállítani. A tojásban ráadásul számos egyéb élelmiszerekben alig fellelhető vitaminok és a különféle biológiai folyamatokban nélkülözhetetlen ásványi sók is megtalálhatóak. Mindez biológiailag könnyen hasznosítható formában van jelen a tojás egyes alkotórészeiben.</p>
					<p>A tojás tápértékén túl tehát számos kedvező élettani hatás kifejtésére is képes, fehérjén kívül rendkívül gazdag vitaminokban (tulajdonképpen a C-vitamin kivételével valamennyi vitamin megtalálható benne), ideális zsírösszetételű, mindemellett számos betegség megelőzésére ill. az öregedésbeli folyamat lelassítására alkalmas.</p>
					<p>Többféle népesség étkezési szokásait tanulmányozva 383 kultúrkör fehérjeforrásul 94%-ban a tojást és a baromfihúst használja.</p>
					<p>A baromfitojások közül bár a tyúktojás fogyasztása a leghétköznapibb, azonban kiemelendő, hogy a fürj tojása jóval magasabb, 60% tojásfehérje tartalmú. A fürjtojás emellett számos egyéb hasznos vitamin forrása.</p>
					<p>
					<img src="/uploads/tojas-etel.jpg" style="height:315px; width:355px; float:left; margin-right:15px;" title="tojás étel" alt="tojás étel">
					</p>
					<p>A tojás étel legnagyobb előnye, hogy a legoptimálisabban működtetik a gyomor- és bélrendszer emésztésért és anyagcseréért felelős szakaszát. Mindezt a tojás ételek nagyon könnyen fogyasztható viszonylag kis mennyiségben váltják ki, míg más ételekből hasonló hatáshoz meglehetősen nagy adagok szükségesek.</p>
					<p>A fürjtojás tömege kb. 10 gramm, energiatartalma 15 kcal. Kis beltartalma ellenére egy fürjtojás jóval nagyobb vitaminértékkel és alacsonyabb koleszterinszinttel bír, mint egy tyúktojás. A fürjtojás még nyersen fogyasztva is a legegészségesebb és legbiztonságosabb tojás étel.</p>
					<p>A fürjtojás energiaértéke igen magas, zsírsav összetétele: 2/3 telítetlen, 1/3 telített zsírsav. Ez az arány szinte megegyezik azzal az aránnyal, ahogy a tenyésztők etetik a fürjeket. A telítetlen zsírsav jótékony az emberi szervezetre. Az omega-3 és omega-6 zsírsavak esszenciális zsírsavak, szervezetünk nem képes előállítani, csak táplálékkal tudjuk bejuttatni, pl. tojás étel formájában.</p>
					<p>&nbsp;</p>
					<p>A tojás tehát alapvető élelmiszer a konyhában, számtalan tojás étel és egyéb étel alapanyaga nyersen vagy sütve-főzve. Lágytojás, rántotta, tükörtojás, rántott ételek panírja és számos étel összetevője a tojás. Gyorsan és rövid időn belül készíthető tojás étel, mely ételek a tojás fent ismertetett jellemzőinél fogva hosszú ideig laktatóak. Reggelire különösen ajánlott a fehérjefogyasztás, bundáskenyér, lágytojás pirítóssal, hogy csak a legegyszerűbb ételeket említsük. Szájhagyomány szerint a séfek legalább 100 féle módját ismerik a tojás felhasználásának, így tojás étel készítésének.</p>
					<h2>Hasznos tanácsok tojás ételek készítéséhez</h2>
					<ul>
					<li>Tojásfőzéshez, krémekhez, majonézkészítéshez csak teljesen friss tojást használjunk!</li>
					<li>A feltört tojás tárolása tilos, mielőbb fel kell használni azt!</li>
					<li>A tojásos étel alapos átsütést, főzést igényel, hogy belseje is forró legyen (75 C felett), a sütés-főzés elpusztítja az esetleges baktériumokat, vírusokat!</li>
					<li>Tojás ételek maradékait se tároljuk, különösen nem szobahőmérsékleten, mert néhány órányi tárolás már veszélyes lehet!</li>
					<li>Hűtőben tárolt tojásos ételt újrafogyasztás előtt fel kell forrósítani (nem elég csak melegíteni), az ételmaradékban a baktériumok ugyanis felszaporodhatnak, mely csak újabb alapos átsütés-főzés által pusztul el!</li>
					</ul>'],
			'furjtojas-recept'                 => ['Fürjtojás recept', '<h1>Fürjtojás recept</h1>
					<p>
					<img src="/uploads/furjtojas-recept.jpg" style="float:left; height:265px; width:190px; margin-right:15px;" title="fürjtojás recept" alt="fürjtojás recept">
					Ez a kis fehér alapon barna pettyes, apró tojás főképp az exkluzív hidegkonyhai fogásokra specializálódott szakácsok kedvenc alapanyaga. Mind keményre főzve, mind miniatűr tükörtojássá sütve díszíthetünk vele szendvicseket, salátákat, kész ételeinket. Ugyanakkor jóval több egyszerű dísznél, nagyon finom falat akár héjában megfőzve és meghámozva, akár buggyantott tojásként, de sütve vagy édességé habosítva is ízletes. Hiába olyan pici a mérete (10 gramm, azaz kb. 5 fürjtojás tesz ki egyetlen tyúktojást), szinte minden tojásétel elkészíthető belőle. A fürjtojás több vágyfokozó, hangulatba hozó „bájital” alkotóeleme is.
					</p>
					<p>Az alábbiakban összegyűjtöttünk Önöknek ízletes, kedvelt fürjtojásból készült ételek receptjeit.</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<h2>Lazacos falatkák füstölt fürjtojással – Hiroe Shouji / Buddha Bar Szálloda Sushi Séfje</strong></h4>
					<p>A kifliket karikára szeleteljük. A szeletkéket kevés vajjal megkenjük, majd minden kiflit betakarunk egy vele azonos nagyságú füstölt lazac szeletkével. Ráhelyezzük a kisebb karika uborkát, végül a félbevágott füstölt fürjtojást. Az egészet fogvájóval felszúrjuk. Borkóstolókon, de egyéb vendégfogadásnál is egyszerűen elkészíthető újdonság.</p>
					<h2><strong>Palóc levés, ahogy a vendég szereti – füstölt fürjtojással&nbsp;</strong><strong>- Mészáros István / Abonyi Mészáros Vendéglő</strong></h2>
					<p>Nyersanyaggal soha ne spórolj, az étel, amit készítesz meghálálja. Tisztelet és szeretet vesz körül.</p>
					<p>Hozzávalók (10 adagra): 80 dkg hagyma, olaj, 2 db csontos birkahús, só, bors, csemegepaprika, babérlevél, 1 db citrom, 1 db burgonya, zöldbab, liszt, tárkony, lestyán, kapor, 2 dl tejfel, 10 db füstölt fürjtojás.</p>
					<p>Elkészítése: Ha jót akarsz, a hagymát csak szecskázd. Felhevített olajban a hagymát lepirítod. A birkahúst kicsontozva, apróra vágva rádobod a hagymára. A nagy csonttal együtt összepirítod, felöntöd pörköltnek, megfőzöd és a megmaradt anyagokat belerakod. Megfelelő mennyiségű vízzel felöntöd és készre főzöd.</p>
					<p>Citrom, kapor, tejföl és füstölt fürjtojás hozzáadásával és a füstölt olajával pikánsra beízesíted az isteni palóc levest.</p>
					<h2><strong>Zsázsás szójacsíra füstölt fürjtojással&nbsp;</strong><strong>- Kalla Kálmán / Gundel Étterem séfje</strong></h2>
					<p>A hagymát cikkekre vágjuk, a füstölt olajon dinszteljük. Hozzáadjuk a maréknyi szójacsírát, kevés julianre vágott sárgarépát, megkeverjük, borsozzuk. Rövid ideig pirítjuk, ráteszünk öt szem füstölt fürjtojást félbevágva. Megszórjuk aprított zsázsával és pirított szezámmaggal. A végén sózunk, vagy kevés szójaszósszal ízesítjük.</p>
					<p>Gazdagíthatjuk darált hússal vagy sonkakockákkal a húsevők részére. Főtt rizzsel, de pirítóssal is nagyon finom.</p>
					<h2><strong>Húspogácsa Kardosné módra – Mészáros István / Abonyi Mészáros Vendéglő</strong></h2>
					<p>Hozzávalók: 60 dkg darált marhahús, 2 db tojás, 5 db zsemle, liszt, 10 db füstölt fürjtojás, tej, olaj, csemegepaprika.</p>
					<p>Elkészítése: A nyersanyagot összegyúrjuk, beízesítjük, állagát beállítjuk fasírt minőségre.</p>
					<p>A fasírthúst elosztjuk kb. 10 felé. Kigömbölyítjük és a közepébe belenyomjuk a füstölt fürjtojást. Készre sütjük forró olajban. Tört burgonyával tálaljuk és fokhagymával töltött szilvát adunk mellé.</p>
					<h2><strong>Borjúszegy töltve Akihisa módra&nbsp;</strong><strong>&nbsp;- Mészáros István / Abonyi Mészáros Vendéglő</strong></h2>
					<p>Hozzávalók: 1 db borjú dagadó, hagyma töltelékbe lepirítva, gomba töltelékbe lepirítva, 4 db nyers tojás töltelékbe kötőanyag, 10 db zsemle tejbe áztatni, tej, petrezselyem, 10 db füstölt fürjtojás, morzsa, liszt, tengeri só, bors, lestyán, zöldborsó, kacsamáj aprítva.</p>
					<p>Elkészítése: A dagadót vékonyra kloffolva, besózva kiterítjük, ízletes töltelékkel megtöltjük, feltekerjük, mint máskor a bejglit. Hústűvel összetűzve, tepsibe helyezve, bacon szalonnával befedve, kevés olaj, víz hozzáadásával készre sütjük.</p>
					<p>Először forró sütőben, amikor a sütés beindult takarékra vesszük és jól átsütjük. Tanács: 2-2,5 óra, de igazából Ön dönti el. Héjában főtt burgonyát petrezselyemmel megfuttatjuk és a dagadót ezzel tálaljuk. Bőséges, friss salátát készítünk mellé.</p>
					<h2><strong>Borjú bécsi szelet Hiroe módra&nbsp;</strong><strong>&nbsp;- Mészáros István / Abonyi Mészáros Vendéglő</strong></h2>
					<p>Hozzávalók: 10 szelet borjúcomb kloffolva, hagyma, töltelékbe lepirítva, gomba, 2 db tojás, tej, petrezselyem, 10 db füstölt fürjtojás, 5 db zsemle, tengeri só, lestyán, olaj, morzsa, liszt, 12 db tojás, bors, zöldborsó, csirkemáj töltelékbe lekaparva, lepirítva, sajt brokkolira szórva.</p>
					<p>Elkészítése: a húst szép nagyra kloffolni, sózni. Minden szeletbe 1 db füstölt fürjtojást tenni. A töltelékeket kanállal szétosztani és belerakni, összehajtani és óvatosan bepanírozni. Készre sütni.</p>
					<p>Köret: brokkoli, tört burgonya olajjal nyakon öntve.</p>
					<h2><strong>Papírvékony nyers bélszínszeletek arany tamagochannal – Kalla Kálmán / Gundel Étterem séfje</strong></h2>
					<p>Hozzávalók: 250 gr tisztított bélszín, tengeri só, frissen őrölt feketebors.</p>
					<p>A szószhoz: 2 csapott evőkanál mustár, 2 evőkanál olaj a tojásról, 2 csapott teáskanál cukor, 8 db füstölt fürjtojás reszelve, 4 szem borókabogyó, só.</p>
					<p>A parajhoz: 100 g tisztított, megmosott parajlevél, 1 evőkanál olaj a tojásról, só, frissen őrölt feketebors, 12 db füstölt fürjtojás.</p>
					<p>Elkészítése: A húst mélyhűtőbe tesszük két órára.</p>
					<p>Egy tálban elkeverjük a mustárt, cukrot, reszelt tojásokat, majd az olajat állandó kevergetés mellett cseppenként hozzáadjuk. Sóval és az összetört borókával ízesítjük.</p>
					<p>Paraj: A parajleveleket vékony csíkokra vágjuk, sózzuk, borsozzuk és hozzákeverjük az olajat.</p>
					<p>Tálalás: A fagyott bélszínt szeletelőgéppel nagyon vékonyra szeleteljük. Egymás mellé téve a szeleteket beborítunk vele négy tányért, tengeri sóval megszórjuk és borsot őrlünk rá.</p>
					<p>A spenótot a hússzeletek közepébe halmozzuk, meglocsoljuk a szósszal és rátesszük az egész füstölt fürjtojásokat.</p>
					<h2><strong>Fürjtojás fantázia – Magyar Vendéglátóípari Iskola végzősei</strong></h2>
					<p>Hideg egresleves füstölt fürjtojással</p>
					<p>Az egrest hideg vízzel lemossuk, gondosan tisztítjuk, a szárvégeket eltávolítjuk. Feltesszük főni. A főzővízbe cukrot, kevés sót, szegfűszeget, fahéjat, szűrt citromlevet és citromhéjat puhára főzzük. Főzés közben pár szem friss áttört egrest teszünk bele. Elkészítjük a rántást. Szárazon lisztet pirítunk, hideg vízzel simára keverjük, kevés tejet adunk hozzá, majd a rántást átszűrve besűrítjük a levest. Montírozzuk, kevés fehérbort adunk hozzá. Előhűtött csészében tálaljuk, betétje az olajtól lemosott füstölt fürjtojás.</p>
					<p>Nyári napokon ajánljuk, savanykás ízével biztos enyhülést ígér a forróságban.</p>
					<h2><strong>Füstölt fürjtojás saláta gyümölccsel – Hotel Novotel séfje</strong></h2>
					<p>Majonéz alapot készítünk, amihez a füstölt olajból is adunk, 3:1 arányban. Az ételt keverőedénybe tesszük, a tojásszemeket félbevágjuk, hozzáadjuk a megmosott savanykás ízű körtét, almát, a tojás méretének megfelelő kockára vágva. Ízesítjük sóval, fűszerezzük fehér őrölt borssal cayane borssal.</p>
					<p>Előhűtött üvegtányérban tálaljuk, díszítése almacikk, legyezőformára vágott bébikörte és félbevágott füstölt fürjtojás.</p>
					<h2><strong>Füstölt fürjtojással töltött csirkecomb</strong></h2>
					<p>Hozzávalók: (4 személyre) 4 db csirkecomb, 20-25 db fürjtojás, só, bors, gyömbér, petrezselyem , tojás, áztatott zsemle.</p>
					<p>Előkészítése: a csirkecombok bőrét fellazítjuk, megsózzuk, állni hagyjuk és megtöltjük a következő töltelékkel: Az apróra vágott fürjtojást összekeverjük a párolt hagymával, áztatott zsemlével, tojással és fűszerekkel. Megtöltjük vele a csirkecombokat és sütőben megsütjük, tetején szalonnaszeletekkel.</p>
					<p>Köretként burgonyapürét ajánlunk fehér szőlőszemekkel, citromkarikával, főtt-füstölt fürjtojásokat félbevágva, petrezselyemmel díszítve.</p>
					<h2><strong>Vegyes salátaegyveleg Makk Marci módra / Makk Zoltán vendéglátóipari végzős</strong></h2>
					<p>a) Saláta: natúr zöldségek – saláta, póréhagyma, sárgarépa, kukorica, kevés káposzta, retek apróra, finomra vágva, kevés füstölt olajjal meghintve, a tetejére félbevágott füstölt fürjtojások, melyeknek &nbsp;sárgáját előzőleg kivájjuk és helyébe kétféle kaviárt töltünk, helyenként citromkarikákkal díszítjük.</p>
					<p>b) Saláta: a füstölt olajon hagymás pirítunk, majd hozzáadjuk a kockára vágott gombát. A következő&nbsp;fűszerekkel ízesítjük: babérlevél, szurokfű, kakukkfű, citrom leve, cukor, só, bors. Mikor a gomba félig puha, felöntjük kevés paradicsomlével és paradicsomos konzervhal levével, kis pikáns fehérborral&nbsp;ízesítve. ( Ha sűrű lett, kevés csontlével felönthetjük.) A paradicsomos konzervhalat karikára vágjuk, a füstölt fürjtojásokat is a kihűlt mártáshoz keverjük.</p>
					<p>Hozzávalók: ízlés szerinti mennyiségű salátalevél, póréhagyma, sárgarépa, kukorica, fehérkáposzta,&nbsp;retek, főtt-füstölt fürjtojás és olaja, piros és fekete kaviár, citrom, hagyma, gomba, fűszerek,&nbsp;paradicsomos halkonzerv, fehérbor, csontlé.</p>
					<p>A salátákat kínáló tálra helyezzük és dekoráljuk salátalevéllel, petrezselyemmel, reszelt hagymával, paradicsommal, citromkarikával.</p>
					<h2><strong>Töltött fürjpecsenye (4 személyre)</strong></h2>
					<p>Hozzávalók: 4 db pecsenyefürj, 20 db füstölt fürjtojás, 1 db kisebb vöröshagyma, 1 db sárgarépa, 1 db petrezselyem gyökér zölddel, só, bors,áfonya.</p>
					<p>Elkészítése: frissen vágott pecsenyefürjek bőrét fellazítjuk. Ujjunkkal a bőr alá kenjük a sót és a fűszereket. Kívülről is bekenjük füstölt olajjal, sózzuk, fűszerezzük, majd állni hagyjuk.</p>
					<p>Töltelék: a vöröshagymát a füstölt olajon megpirítjuk, hozzáadjuk a karikára vágott sárgarépát, a petrezselyemgyökeret, és puhára pároljuk. Sózzuk, borsozzuk. A fürjek máját, szívét és a füstölt fürjtojásokat apróra vágjuk. hozzáadjuk az apróra vágott petrezselyem zöldjét is. Jól összedolgozzuk és a fürjek bőre alá töltjük. Fogvájóval összetűzzük.</p>
					<p>Előmelegített sütőben füstölt olajjal párszor megkenve ropogósra sütjük.</p>
					<p>Párolt rizs, vagy gombás rizzsel kínáljuk, áfonyát adunk mellé. Kitűnő csemege, előétel.</p>
					<p>&nbsp;</p>
					<p><em><strong>Amennyiben további fürjtojás recepteket szeretne, hívjon minket vagy írjon bizalommal és mi elküldünk Önnek további 20 receptet!</strong></em></p>',
			],
			'furj-receptek'                    => ['Fürj receptek', '<h1>Fürj receptek</h1>
					<p>
					<img src="/uploads/furj-recept.jpg" style="float:left; height:266px; width:154px; margin-right:15px;" title="fürj recept" alt="fürj recept">
					A vadhúsok általánosságban egészséges, koleszterinben és zsírban szegény, vasban, vitaminokban viszont gazdag húsfélék. Ez igaz a fürj húsára is. A fürj melléből, combjából és szárnyából pörkölt, rántott hús, finom sültek készíthetőek, aprólékjából pedig nagyon ízletes leves főzhető. A fürj kis méretéből kifolyólag ezen ételekhez általában két felnőtt fürj szükséges. A fürjhús a csirkehússal összehasonlítva jóval omlósabb, aromásabb, sőt mellhúsa sem száraz. Vadíze miatt a fürj tökéletes vendégváró vagy ünnepi fogás, de pikán családi ebédnek is tökéletes és egészséges választás.</p>
					<p>Legtöbben csak fagyasztva jutnak fürjhúshoz, pedig a fürjek tenyésztése sem embert próbáló feladat. A japán fürj tenyésztése és tartása nem igényel különösebb felszereltséget, sem speciális körülményeket, de 10-15 darab fürj például egy négytagú család tojásszükségletét is maximálisan kiszolgálja.</p>
					<p>Az alábbiakban összegyűjtöttünk Önöknek ízletes, kedvelt fürjtojásból készült ételek receptjeit.</p>
					<p>&nbsp;</p>
					<h2>Fürj mozzarellával töltve</h2>
					<p>Hozzávalók (4 személyre): 8 konyhakész fürj, 2 mozzarellagolyó, 5 evőkanál tisztított napraforgómag, 2 evőkanál szárított&nbsp;majoránna, 2 tojás, 6 dkg vaj, 2 dl húsleves kockából.</p>
					<p>A körethez: 4 burgonya, 4 alma, 3 tojás, 5 dl tej, 10 dkg sajt, vaj a formához.</p>
					<p>Elkészítés:</p>
					<p>A fürjeket megmossuk, kívül-belül bedörzsöljük sóval és borssal. A mozzarellát lecsepegtetjük, kisebb darabokra vágjuk. Megpirítunk 4 evőkanál napraforgómagot, és azonnal tálba borítjuk.</p>
					<p>A napraforgót, a mozzarellát, a majoránnát, a tojásokat botmixerrel addig keverjük, amíg egynemű masszát kapunk. Sóval, borssal ízesítjük, és a fürjekbe töltjük. A nyílást fogvájóval betűzzük. A sütőt 200 C fokra előmelegítjük. A fürjeket mellükkel felfelé tepsire rakosgatjuk, és kisebb darabokban rászórjuk a vajat. A sütőben 50 percig sütjük, de 10 perc után aláöntjük a húslevest. Eztán 30 perc múlva rászórunk 1 evőkanál napraforgómagot, és befejezzük a sütést.</p>
					<p>Köretként almás krumplirakottast készítünk, ehhez meghámozzuk a burgonyát, és vékony szeletekre vágjuk. Megmossuk az almát, magházát kifúrjuk, és az almát szintén karikákra vágjuk. Kivajazunk egy kisebb tűzálló tálat, és cserépszerűen (kissé egymásra csúsztatva) felváltva belefektetjük a burgonyát és az almát. A tojást elkeverjük a tejjel, ráöntjük az almás burgonyára, megszórjuk a sajttal, és a fürj után a sütőben kb. 25 percig sütjük.</p>
					<p>&nbsp;</p>
					<h2>Zöldségekkel párolt fürj</h2>
					<p>Hozzávalók: 8 db fürj, 1 fej&nbsp;vöröshagyma, 15 dkg&nbsp;sárgarépa, 5 dkg&nbsp;zeller, 10 dkg fehérrépa, 2 dl vörösbor, 1 kávéskanál&nbsp;kakukkfű, 2 babérlevél, 8 szem&nbsp;borsó, olaj.</p>
					<p>Elkészítés:</p>
					<p>A megtisztított fürjet kívül-belül megsózzuk, és kevés olajban elősütjük. A sütésből visszamaradt zsiradékhoz adjuk a darabokra szelt vöröshagymát, a karikára vágott sárgarépát, a zellert és a fehérrépát. A zöldséget megpirítjuk, és felengedjük vörösborral. Sóval, kakukkfűvel, babérlevéllel, borssal ízesítjük és felforraljuk. Beletesszük a fürjet, és lassan puhára pároljuk. Vajban párolt zöldkörettel tálaljuk.</p>
					<h2>Fürj aszalt szilvával</h2>
					<p>Hozzávalók – fürjként: 4 db aszalt szilva (magtalan), likőr, konyak vagy rum; 2 db főtt vagy&nbsp;sült gesztenye, fél-fél tk aprított rozmaring és kakukkfű, 3 csík füstölt szalonna, só, bors.</p>
					<p>Elkészítés:</p>
					<p>Az aszalt szilvát az alkoholban beáztatjuk. A sütőt előmelegítjük 200 fokra.</p>
					<p>Közben 1 csík szalonnát apró kockákra vágunk, a gesztenyéket durván felaprítjuk. Egy serpenyőben, kevés olajban, a szalonna darabokat megpirítjuk, hozzáadjuk a zöldfűszereket és a gesztenyét, 2-3 percig sütjük. Közben 2 db aszalt szilvát durva darabokra vágunk, hozzáadjuk a szalonna darabokhoz. A másik kettőt hagyjuk az alkoholban.</p>
					<p>A fürj üregét megtöltjük az aszalt szilvás töltelékkel, majd betekerjük 2 szalonna csíkkal. Ízlés szerint sózzuk, borsozzuk. Kb 30 percig szép barnára sütjük, a szalonna legyen ropogós. Ne süssük túl!</p>
					<h2>Egyszerű fürjpecsenye</h2>
					<p>Hozzávalók: 8 fürj, 20 dkg angolszalonna, só, bors, 1 kk. borókabogyó, 2 dl olaj.</p>
					<p>Elkészítés:</p>
					<p>A borókabogyót összetörjük, majd a sóval, és borssal együtt a fürjekhez adjuk. Vékony szalonnaszeletekkel beborítjuk, majd tepsibe tesszük. Sütőben fél órán át sütjük, közben többször meglocsoljuk az olajjal. Amikor kész, a fürjeket kettévágjuk, a levét leszűrjük, és meglocsoljuk vele. mentás almakocsonyát és apró leveles tésztát adhatunk hozzá.</p>
					<h2>Jóllakott fürj</h2>
					<p>Hozzávalók: 4 fürj, 10 dkg angolszalonna, 5 dkg sárgarépa, 5 dkg kukorica, 5 dkg zöldborsó, 1 zsemle, 1 dl tej, 3 tojás, 5 dkg gombasó, bors, 1 kk. bazsalikom, 2 dl olaj.</p>
					<p>Elkészítés:</p>
					<p>A madarak hasüregét kinyitjuk, és csontjait kiszedjük. A szalonnát felkockázzuk, és zsírját kisütjük. A gombát és a sárgarépát felszeleteljük, majd a zöldborsóval és kukoricával együtt a szalonnához adjuk. Negyed órát pároljuk. A zsemlét beáztatjuk a tejbe, majd kicsavarjuk, és a zöldségekhez keverjük. Fűszerezzük, ráütjük a tojásokat, és jól elkeverjük. Utána a tölteléket a madarak hasába helyezzük, és sütőben 25 percig sütjük. borsmártással és burgonyakrokettel kínáljuk.</p>
					<h2>Töltött fürj</h2>
					<p>Hozzávalók: 4 fürj, 10 dkg vaj, 3 cl konyak, 1 zsemle, 2 dl tej, 10 dkg szárnyasmáj, só, bors, 2 dl barnamártás, 5 dl tejszín, 1 kk. majoránna.</p>
					<p>Elkészítés:</p>
					<p>A májat felaprítjuk, majd forró vajon megsütjük. A zsemlét beáztatjuk a tejbe, majd kicsavarjuk, és összedolgozzuk a májjal. Megfűszerezzük, és a kicsontozott madarakba töltjük. A nyílást bezárjuk, majd sózzuk, borsozzuk, és vajon 35 percig sütjük. Közben többször meglocsoljuk. Amikor kész, rálocsoljuk a konyakot, a mártást és a tejszínt, és még pár percig pároljuk. Burgonyakrokettel tálaljuk.</p>
					<h2>Fürj gombával töltve</h2>
					<p>Hozzávalók (4 személyre): 4 fürj, 20 dkg&nbsp;gomba, 20 dkg borjúhús, 1 vöröshagyma, 2 dl&nbsp;tejszín, 4 ek. tokaji szamorodni, 1 szikkadt zsemle, 2 ek. Rama&nbsp;margarin, 1 ek. olaj,&nbsp; 1 csokor turbulya, reszelt&nbsp;szerecsendió,&nbsp; fehér&nbsp;bors, só.</p>
					<p>Elkészítés:</p>
					<p>A madarak szárnyát leszedjük, a törzsét kifilézzük, majd&nbsp;sóval és&nbsp;hagymával ízesítjük. A borjúhúst finomra daráljuk, és&nbsp;sózzuk,&nbsp;borsozzuk. A&nbsp;gombát megtisztítjuk, felaprítjuk, majd felengedjük a borral. A zsemlét kockákra vágjuk, és 2 evőkanál&nbsp;tejszínt öntünk rá, majd a húshoz adjuk a leszűrt gombával és a felaprított&nbsp;hagymával és turbulyával együtt. Jól összekeverjük, majd megtöltjük vele a fürjeket, felcsavarjuk, és megtűzzük. Az olajhoz hozzáadjuk a Rama&nbsp;margarint, felforrósítjuk, majd körbepirítjuk benne a tekercseket. Utána tepsibe rakjuk, meglocsoljuk a&nbsp;tejszín,&nbsp;&nbsp;bor és szaft keverékével, majd fél órát sütjük. Ezután 5 percig állni hagyjuk a sütőben, majd tálaljuk.</p>
					<h2>Fürj szalonnaköpenyben recept</h2>
					<p>Hozzávalók (4 személyre): 4 fürj, só, 4 vékony szelet&nbsp;szalonna, 4&nbsp;zsályalevél, 1-2 ek. olvasztott&nbsp;vaj, 1 dl csirkehúsleves.</p>
					<p>Elkészítése:</p>
					<p>A fürjeket besózzuk, hasüregükbe egy-egy&nbsp;zsályát teszünk, majd&nbsp;szalonnába göngyöljük, és megkötözzük őket. Vajjal kikent tepsibe tesszük, majd magas fokozaton kb. 15-20 percig sütjük. Ezután a pecsenyelevet összekeverjük a levessel, felforraljuk, majd ezzel meglocsolva kínáljuk a fürjeket.</p>
					<p>&nbsp;</p>
					<h2>Aromás fürjek</h2>
					<p>Hozzávalók (4 személyre): 4 fürj, 30 dkg piros ribizli, 3 cl&nbsp;konyak, 5 dl vad-alaplé, 5 dkg&nbsp;vaj, só,&nbsp;bors.</p>
					<p>Elkészítés:</p>
					<p>A fürjeket bepácoljuk, majd a&nbsp;vajat felforrósítjuk, és átsütjük rajta. Ezután tepsibe tesszük őket, és negyed órán át sütőben sütjük. Utána hozzáadjuk a többi hozzávalót, megfűszerezzük, és még 10 percre visszatesszük a sütőbe. Végül a húst félbevágjuk, majd a mártással és&nbsp;vajas galuskával tálaljuk.</p>
					<p>&nbsp;</p>
					<h2>Fürj burgonyafészekben</h2>
					<p>Hozzávalók (4 személyre): 8 db fürj, 4 db nagyobb burgonya, 1 csokor petrezselyem, ízlés szerint apróra vágott friss rozmaringlevél, ízlés szerint bors.</p>
					<p>Elkészítés:</p>
					<p>A fürjeket alaposan megtisztítjuk, besózzuk és fél órát állni hagyjuk. A krumplit megmossuk, leszárítjuk és hosszában kettévágjuk, belsejét kivájjuk. A burgonya kivájt belsejét megsózzuk. 1-2 szál petrezselymet beleteszünk a fürjek hasüregébe, és begöngyöljük őket két szelet szalonnába. A burgonyafészekbe tesszük a fürjeket és megborsozzuk. Egy tűzálló tálat kikenünk olajjal, beletesszük a burgonyafészkes fürjeket, közé szórjuk a krumpli kikapart belsejét. Alufóliával letakarjuk, és 200 fokra előmelegített sütőben kb. 20 percig pároljuk, majd levesszük a fóliát, tetejét megszórjuk apróra vágott friss rozmaringgal, és megsütjük. Párolt vöröskáposztával kínáljuk.</p>',
			],
			'potencianovelo-etel'              => ['Potencianövelő étel', '<h1>Potencianövelő étel</h1>
					<p>Azokat az anyagokat, melyek a nemi vágyat és aktivitást növelik, Afrodité, a szerelem görög istennője után afrodiziákumoknak nevezzük. Ilyen hatású némely ételünk is, ezek az un. potencianövelő ételek. Nem újkeletű megfigyelés, hogy bizonyos ételek és növények serkentik vagy felkeltik a nemi vágyat, valamint támogatják a férfierőt. Szinte minden kornak és népnek megvolt a maga afrodiziákuma. A gránátalma vágykeltő hatásáról a Káma-Szútra is említést tesz, a maják és az egyiptomiak a mézet alkalmazták előszeretettel vágykeltőnek, az indiánok fahéjjal kenték be péniszüket, a borsmenta főzetét Hippokratész „szerelmi játékra ösztönző ital”-nak nevezte, a népi gyógyászatban a merevedés idejének meghosszabbítására a petrezselyem szárának rágcsálását javasolták, de a mustármagnak is potencianövelő hatást tulajdonítottak már az ősidőktől fogva, ezért a szerzeteseknek tilos volt fogyasztaniuk.</p>
					<h2>Melyek ezek a vágykeltő, vágyfokozó potencianövelő étel típusok?</h2>
					<ol>
					<li>Először is a fűszernövények. A fűszernövények ősidők óta az ember érzékeinek szolgálatában állnak: különleges aromát kölcsönöznek ételeinknek, emellett gyógyítanak, illatosítják környezetünket. A legismertebb afrodiziákumként számon tartott fűszereink: ánizs, bazsalikom, chili, fahéj, gyömbér, szegfűszeg és még sorolhatnánk. A fűszerek egy részében megtalálhatók olyan anyagok, melyek erősítik a húgyúti és ivar-szerveket, vagy izgatják a központi idegrendszer szexualitásért felelős régióit.</li>
					<li>A vágy és gyönyör gyümölcsei, mint datolya, füge, görögdinnye. A datolyáról az arabok úgy tartják, hogy növeli a férfiasságot, a görögdinnye pedig a legújabb kutatások szerint természetes potencianövelőként hat, ez a benne található citrullin nevű aminosavnak köszönhető, mely hat a vérerek tágulására és ernyedésére.</li>
					<li>Növelhető a szexuális vonzerő aromás teákkal, mint bojtorján, ginzeng, édesgyökér, orbáncfű, citromfű, borsmenta tea stb. Ez utóbbi kimondottan a merevedést támogatja.</li>
					<li>Tenger gyümölcsei, mint kagyló, osztriga, avokádó, kaviár. A kaviárt a tehetős emberek Viagrájának tartják, mindig is az előkelőség, gazdagság és szexualitás szimbóluma volt. Jótékony hatása révén megszünteti a szexuális fáradtságot. A kagylót és osztrigát nemcsak az erekció meghosszabbítására, hanem az impotencia és frigiditás megszüntetésére is használják.</li>
					<li>Zöldségek, mint zeller, petrezselyem, fokhagyma. A petrezselyem régi ajzószer, erősíti a merevedési reflexet és meghosszabbítja az erekciót.</li>
					<li>Csokoládé. Fenil-etilamin tartalmának köszönhető vágykeltő hatása. Ezt a vegyületet az agy akkor termeli, amikor szerelmesek vagyunk. A csokoládé fokozza az agyban a szerotonin szintet, amit boldogsághormonnak is neveznek, s eufórikus állapotot produkál. Vidám, boldog ember pedig sokkal nagyobb kedvvel ugrik az ágyba.</li>
					<li>Szarvasgomba. „A nőket gyengédebbé, a férfiakat vállalkozóbbá teszi” – írta róla egy híres gasztronómus 1825-ben. Mindezt nyilvánvalóan tartalmának köszönheti, mert a benne lévő anyagok közel állnak a tesztoszteronhoz.</li>
					<li>Tojás. A hagyomány szerint az egyik legismertebb vágyfokozó. Olcsó, könnyen hozzájuthatunk és feltűnéstől és mellékhatásoktól is mentes potencianövelő étel. Aminosavakban igen gazdag, stabilizálja a vérnyomást és segíti a vér akadálytalan áramlását, ezáltal növeli a szexuális vágyat. Tojások között kiemelendő a fürjtojás, mely valóságos életelixír, férfiasságot serkentő hatása pedig a laikusok számára is ismert.</li>
					</ol>',
			],
			'immunerosito-etel'                => ['Immunerősítő étel', '<h1>Immunerősítő étel</h1>
					<p>Immunrendszerünk, azaz szervezetünk védekező mechanizmusának működése az immunitásban nyilvánul meg. Az immunitás jelentése védettség, mentesség. Immunrendszerünk fő feladata a testidegen anyagok eltávolítása, megsemmisítése.</p>
					<p>Immunrendszerünk legyengülhet egy-egy betegség vagy fizikai, lelki megterhelés hatására, diéta, tápanyaghiány esetén is, egyensúlyának fenntartása vagy helyreállítása immunerősítő étel illetve ételek fogyasztása által segíthető.</p>
					<p>Ha szervezetünk tápanyagkészlete megcsappan, azaz az aminosavak, vitaminok és ásványi anyagok szintje lecsökken, romló egészségi állapothoz, különböző betegségekhez vezethet. Szervezetünk felerősítéséhez immunerősítő étel fogyasztása ajánlott. A nagy mennyiségű természetes vitaminok, ásványi anyagok, esszenciális aminosavak fogyasztása hozzájárul az immunrendszer javításához, a különböző betegségek megszüntetéséhez.</p>
					<p>A fürjtojás nagyon jó aminosav forrás, fogyasztása kiemelten ajánlott. A fürjtojás normalizálja az immunitást, segíti az emésztőrendszer, idegrendszer és szív-érrendszer tevékenységét. Immunerősítő tulajdonsága mellett az akut légzőrendszeri fertőzéseket is megszünteti (nátha, influenza), jó hatással van a cukorbetegségre, sikeresen kezelhető fürjtojással a krónikus epehólyag-gyulladás.</p>
					<p>A fürtojás fogyasztása olyannyira biztonságos, hogy mind gyermekek, mind felnőttek, de idősek és betegek is nyugodtan ehetik.</p>
					<p>A fürjtojás vitaminösszetétele a következő: A-vitamin átlagosan 200-400 NE, D-vitamin 20-30 NE, E-vitamin és számos B-vitamin található a fürjtojás sárgájában. Vitaminok mellett ásványi anyagokban is gazdag a fürjtojás, pl. foszforban, káliumban, cinkben, vasban. A fürjtojás vastartalma kb. 2 gramm, mely mennyiség kizárólag a sárgájában található. Ez elég nagy mennyiség, 3-4 fürjtojással egy fejlődésben levő 20 kg testsúlyú gyermek vasszükséglete fedezhető. Koleszterintartalma viszont kisebb, mint a tyúktojásnak, nem hiába tartják a fürjtojást a legegészségesebb, immunerősítő ételek egyikének.</p>
					<p>A fürjtojás immunrendszert erősítő hatása nagyban köszönhető lizozim nevű enzimjének, melyet a szervezet természetes antibiotikumának is neveznek. A lizozim bizonyos mikroorganizmusok ellen hat, azok sejtfalában található fehérje szétroncsolásával. Lizozim az emberi szervezetben a könnyben, nyálban lelhető fel és természetesen növelhetjük fürj tojásfehérje fogyasztásával. A lizozim az immunerősítő étel alapja.</p>
					<p>A fürjtojás fogyasztása kúraszerűen idegkimerültség, általános gyengeség esetén immunerősítőnek, műtétek után roborálásra, légúti fertőzések, gyulladások, asztma, allergia, emésztőrendszeri és egyéb betegségek kiegészítő kezelésére maximálisan ajánlott.</p>',
			],
			'magas-vernyomas-kezeles'          => ['Magas vérnyomás kezelés', '<h1>Magas vérnyomás kezelés</h1>
					<p>A magas vérnyomás, szakszóval hipertónia egy olyan kóros állapot, melyben emelkedik a vérnyomás az artériákban. Ezen emelkedés a szívtől több, erőteljesebb munkát igényel a vér erekben való keringetéséhez. A normál vérnyomás nyugalmi állapotban 100-140/60-90 Hgmm értéktartományon belül van, magas vérnyomás esetén a felső érték meghaladja a 140, az alsó a 90 Hgmm értéket. A kezeletlen magas vérnyomás veszélyt jelent az érrendszer, a szív- és vesebetegségek kialakulására nézve, valamint az egyik legjelentősebb kockázati tényező a stroke és szívinfarktus tekintetében. A hipertónia tünetmentes, így sajnos sokkal több a beteg, mint aki tud is róla, hogy érintett.</p>
					<p>A magas vérnyomás étrend- és életmódbeli változásokkal javítható, ezáltal csökken a kapcsolódó egészségügyi szövődmények kockázata is. Sokaknál ez nem elegendő és gyógyszeres magas vérnyomás kezelés szükséges. Azonban tapasztalat, hogy az eredményes életmódbeli változtatások a vérnyomást csökkenthetik ugyanolyan mértékben, mint a vérnyomáscsökkentő gyógyszerek, azaz étrend- és életmódbeli változtatásokkal is normalizálható a vérnyomás.</p>
					<p>A magas vérnyomás kezelés tekintetében az életmódbeli változtatások az alábbiakat jelentik: étrendi változtatások, testmozgás, testsúlycsökkentés, dohányzás elhagyás, kávé (koffein) fogyasztás mérséklése. Ezek betartása sokaknál meghozza az eredményt és a gyógyszeres kezelés is elmaradhat.</p>
					<p>Az étrendbeli változtatáson belül első helyen az alacsony nátriumtartalmú étrend kialakítása áll, de csökkenti a vérnyomást a gyümölcsökben, zöldségekben, alacsony zsírtartalmú tejtermékekben gazdag étrend is. Valamint elmaradhatatlan az alkoholfogyasztás korlátozása.</p>
					<p>A fizikai aktivitás értágító, és az erek tágulékonyságát célzó hatása szintén jót tesz a vérnyomás normalizálásának. Aerob mozgás, mint gyaloglás, futás, kerékpározás, úszás legalább heti három alkalommal, de még jobb, ha minden nap 30-60 perces időtartamban megtörténik.</p>
					<p>Amennyiben a magas vérnyomás nem áll helyre étrend- és életmódbeli változtatások hatására, szükségessé válik vérnyomáscsökkentő gyógyszerek alkalmazása.</p>
					<p>A magas vérnyomás kezelése terén több természetgyógyász is előszeretettel ajánlja a fürjtojás kúraszerű alkalmazását. A fürjtojás kúra lényege, hogy a tojásokat napi szinten, éhgyomorra és nyersen kell fogyasztani, mert jótékony hatása csak így érvényesül. Aki idegenkedik a nyers tojás ízétől, egy kis mézzel vagy cukorral is kikeverheti. Egy alkalommal felnőtteknek 4-5, gyerekeknek 2-3 darab fürjtojás elfogyasztása ajánlott. A fürjtojás kúra időtartama az adott betegségtől, problémától függ, mert nem csak a magas vérnyomás, számos egyéb betegség kezelésére alkalmas, pl. gyomorfekély, emésztési zavarok, csalánkiütés, szamárköhögés, máj betegségei, cukorbaj, allergiák, asztma, vérszegénység, potenciazavar, köszvény, migrén, vesebántalmak stb. Egy kúra általában több hetet vesz igénybe, s néhány hónapos szünettel célszerű megismételni.</p>',
			],
			'japan-furj'                       => ['Japán fürj', '<h1>Japán fürj</h1>
					<p>A japán fürj a madarak osztályába tartozó faj. A madarak osztályán belül a tyúkalakúak rendjébe és a fácánfélék családjába tartozik. Rovarokkal és más gerinctelenekkel, valamint magvakkal táplálkozik.</p>
					<p>A japán fürj kelet-ázsiai madár, a füves területek és a gabonaföldek jellemző faja, kerüli a vizenyős és erdős területet. Háziasítása a XI-XII. századra tehető, Kínában, Koreában és Japánban háziasították először.</p>
					<p>Természetes körülmények közötti szaporodása évi kétszeri költést jelent, sűrű növényzetbe rejti fészkét és egyszerre 7-8 tojást rak. Tojásai 18 napi kotlás után kelnek ki, fiókái fészekhagyók.</p>
					<p>A japán fürj húsa igencsak ízletes, az első írásos feljegyzés a XII. századból származik, mely szerint a japán császár fürjhúst fogyasztott, hogy a tuberkulózist elkerülje. 1910-re nemzeti eledellé vált Japánban a fürjhús. Európában az 1900-as évek elején kezdték tenyészteni a japán fürj madarat húsáért.</p>
					<p>
					<img src="/uploads/japan-furj.jpg" style="float:left; height:256px; width:400px; margin-right:15px;" title="japán fürj" alt="japán fürj"">
					A japán fürj teste zömök, nyaka kissé hosszú, állebenye nincs. Lábai tollatlanok, de nyaka és feje tollal fedett. A kifejlett fürj súlya mindössze 18-20 dkg. A japán fürj többféle színváltozata ismert: vadszínezetű, foltos-tarka, barna, búza, fehér, fehér-vadas, csoki színűek. Tojásátmérője: 6,5-7 mm.&nbsp;A kifejlett japán fürj átlagos testtömege: 18-20 dkg.</p>
					<p>A japán fürj kakasok melltollazata egyszínű, míg a tojóké pettyezett, ez 4-5 hetes korra realizálódik. Egyéb színváltozatú fürjek nemét csak az ivarérettségi kor elérésekor lehet megállapítani, a kakasok kloákája feletti dudor alapján. A japán fürj ivarérettsége tojóknál a 42. naptól, kakasoknál szintén 42. naptól kezdődik.</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>A fürjtojók intenzív tartásnál (fűtött, világított helyen) 6 hetesen, természetes tartásnál 10-12 hetesen kezdenek el tojni; éves tojáshozamuk körülbelül 250 tojás, melyek átlagtömege 16-17 gramm. A fürjtojás a legegészségesebb baromfitojás, a tyúktojáshoz képest fehérjetartalma magasabb, több vitamint és ásványi anyagot, mindemellett kevesebb koleszterint tartalmaz. A japán fürj tojásának gyógyhatása felmérhetetlen, de kitűnő kozmetikum is.</p>',
			],
			'furj-tartasa'                     => ['Fürj tartása', '<h1>Fürj tartása</h1>
					<p>A fürj tartás kapcsán beszélhetünk intenzív és extenzív tartásról. A lényeg, hogy állatbarát környezet és bánásmód valósuljon meg, hogy a fürjek egészséges fejlődését ne gátoljuk. Szem előtt kell tartani a fürjek alapvető életszükségleteinek biztosítását, lehetőleg oly módon, hogy ez közelítsen a természetben élő vad fajtársaik életmódjához.</p>
					<p>Extenzív tartás esetén élnek és táplálkoznak a fürjek eredeti életmódjukhoz leginkább hasonló körülmények között. Bár a fürj jól repülő madár, a szökésveszély miatt zárva tartható csak, pl. épületen belül vagy kint a szabadban, de zártan úgy, hogy extenzív tartásmódnál a mozgásigényük maximálisan meg van hagyva. Az így tartott japán fürj élénk, egészséges, kedvére mozoghat, kapirgálhat, fürdőzhet.</p>
					<p>Az extenzív tartáson belül beszélhetünk még ökológiai vagy bio tartásról, amikor szigorú szabályok és feltételek betartása mellett zajlik a fürj tartása, a fürjtenyészet, s eredményeképp a fürj tojása és húsa mentes minden ipari előállítású takarmánytól és teljesen mentes minden kémiai vegyszertől, gyógyszertől.</p>
					<p>
					<img src="/uploads/furj-tartasa.jpg" style="float:left; margin-right:15px; height:333px; width=500px;" alt="Fürj tartása">
					A fürj intenzív tartása kihasználja azt, hogy a japán fürj nem helyigényes, egy négyzetméter területen 20 fürj is biztonságosan eltartható, holott természetesen minél nagyobb a helyük, annál jobban érzik magukat. Intenzív tartás esetén tehát a fürjeket zárt ketrecekben tartják, sokszor nagyüzemi tápot esznek. A sok fehérjével dúsított gyors hízlalástól (a fürj ilyen tartás mellett 6 hét alatt eléri a vágósúlyt), kevés mozgástól a mellük zsíros lesz és húsuk íze is veszt zamatából.</p>
					<p>A fürj tartása kapcsán van néhány megszívlelendő tanács. A fürjek nagyon szeretnek homokban fürdeni, ezért ha örömet akarunk szerezni neki, helyezhetünk egy kis tálkában homokot a ketrecbe.<br>
					Tojófészek kiépítése nem szükséges, de megpróbálkozhatunk vele, akár egy kisebb kartondoboz szénával, szalmával vagy puha faforgáccsal kibélelve is megteszi.</p>
					<p>&nbsp;</p>
					<p>A fürj tartása mellett a takarmányozása kapcsán a legfőbb elírás, hogy ha táppal etetünk, annak fehérjetartalma legalább 18%-os legyen, ilyen pl. a fácán indító- és nevelő táp, ez tökéletes a fürjek táplálására is. De ne feledkezzünk el a vitaminozásról sem, erre a célra kiváló termékek állnak rendelkezésre és ahhoz, hogy az állatok jó kondícióban, egészségesen éljenek, állatnak való ecet vízbe adagolását is javasoljuk. Ezeket havonta egyszer egy héten keresztül az ivóvizükbe keverve kell adagolni. A fürjek nagyon szeretik az apró magvakat, pl. repce, köles, valamint a zöldeket, pl. tyúkhúr.</p>',
			],
			'furj-tenyesztese'                 => ['Fürj tenyésztése', '<h1>Fürj tenyésztése</h1>
					<p>A japán fürj gyors fejlődésének köszönhetően – 35 napos korában már ivarérett -, 40-45 napos kortól bevonható a tenyésztésbe. A tenyészállomány takarmányába vagy vizébe Laktiferm M+C adagolása ajánlott a sikeres hozam érdekében.</p>
					<p>A fürj tenyésztése kapcsán hasznos tudni, hogy a fürj ritkán kotlik, ezért tojásait keltetőgéppel vagy törpetyúkkal tudjuk kikeltetni. Keltetési célra 2 hetesnél régebbi tojásokat ne használjunk. A keltetőben a hőmérséklet a 8. napig 37,8 °C, majd 37,5 °C, utolsó két napon pedig mindössze 37,2-37,3 °C legyen.</p>
					<p>A páratartalom 55-65%, az utolsó két napban 75-80% legyen. A tojásokat napi kétszer meg kell forgatni. Ezen előírások betartása mellett a kelési eredmény 80-85%-os.</p>
					<p>A japán fürj csibéje &nbsp;tojásából a 17. napon, keltetőgépből 17-18. napon bújik elő. Az új tartásmódnak köszönhetően nem ritka, hogy maga a fürjkotlós kelti ki a csibéit és azok a 18-19. napon ki is kelnek. A kikelt csibék azonnal át f, majd áthelyezhetőek a számukra előkészített előnevelő, ahol 38 fok van, itt folytatják életük első fejlődési szakaszát. Ez a folyamat a tollasodásig tart, mely teljes időtartama kb. 3 hónap, ez idő alatt a hőfok folyamatos csökkentésére is figyelmet kell fordítani. A csibék melegítésére fa, műanyag vagy karton anyagú nevelődoboz esetében használhatunk hagyományos égőt, de sokkal megfelelőbb az infralámpa, melynek fénye enyhén fertőtlenít, s színe nyugtatóan hat a csibékre. A hőfok folyamatos csökkentése úgy történik, hogy 2 naponta 1 fokkal csökkentjük a kezdőhőmérsékletet (38 °C fok) egészen két hetes korukig. 3 hetes kortól elegendő a fix 22-24 °C fok. Amennyiben a hőmérséklet nem megfelelő a fürjek számára, túl magas hőmérséklet esetén lihegnek, amennyiben pedig túl hideg nekik, összebújnak, ezekből látható, hogy milyen irányba kell módosítani a hőmérsékletet.</p>
					<p>A japán fürj tenyésztése kapcsán felmerülő kérdés, hogy hol tartsam, mivel etessem, mikor kezd el tojni, mennyi idő után lesz ehető a fürjhús stb. Ezekre a kérdésekre az alábbiakban válaszolunk, de mindenképpen javasoljuk elolvasni Tóth Sándor „A japánfürj és tenyésztése” című szakirodalmat.</p>
					<p>A japán fürj tartható zárt és nyitott helyen, természetesen a kirepülés elkerülésének figyelembevételével alakítsuk ki a nyitott helyen történő tartás körülményeit is. Szabadban történő tartás esetén árnyékos és szélmentes helyen tartsuk, a lezúduló eső elvezetéséről gondoskodva. Télen a szabadon tartott fürjet is zárt helyiségbe kell helyezni.</p>
					<p>A fürj táplálása kapcsán a legfontosabb figyelni arra, hogy étrendjükben fehérjék, ásványi anyagok, nyomelemek, vitaminok aránya megfelelő legyen. A táppal történő etetéshez kiváló a fácán indító- és nevelő táp, figyeljünk annak fehérjetartalmára, legalább 18%-os legyen. Vitaminozásra kiváló termékek közül választhatunk, mint Jolovit, Laktiferm M+C, Columba stb. Ezeket az ivóvízbe keverten kell adagolni, havi egy héten keresztül. A fürjek etetése kiegészíthető apró magvak és zöldek által.</p>
					<p>Tenyészetből vásárolt japánfürjek optimális tartásmód mellett általában a 8-10. héten kezdenek tojni. Első tojásuk tenyésztésre nem alkalmas, de étkezési célokra igen. Kifejezetten tojástermelési céllal tartott fürjek esetében célszerű tojófészek elhelyezése.</p>
					<p>Egyes fürjtípusokat kizárólag hústermelésre tenyésztenek. 40 napos kortól lehet a fürjet táplálkozás céljából levágni és húsát felhasználni. Fürjhúsból ízletes levesek, sültek készíthetők.</p>',
			],
			'furjtojas-tarto'                  => ['Fürjtojás tartó', '<h1>Fürjtojás tartó</h1>
					<p>Fürjtojás tárolására alkalmas tojástartó áttetsző műanyagból készül, csukható tetővel van ellátva és matricázható.</p>
					<p>Méretét, illetve a tárolt tojás mennyiségét tekintve van 15 db fürjtojás tárolására alkalmas.</p>
					<p>
					<img style="height:747px; width:500px;" src="/uploads/furjtojas_15_darabos.jpg" alt="Fürjtojás 15 darabos">
					</p>',
			],
			'furj-ketrecek'                    => ['Fürj ketrecek', '<h1>Fürj ketrecek</h1>
					<p>A fürj tartásához biztonságos, praktikus ketrecre van szükségünk</p>
					<p><strong>A fürj ketrec ajánlott mérete</strong>: 125 x 60 x 35 centiméter.</p>
					<p><strong>Padozat</strong>: Alapesetben rács, de a rács funkciója csak annyi hogy megtartsa a belehelyezett padlót, ami lehet OSB, CK lap, karton papír vagy műanyag háló. A padozat tálcaként is kihúzható a takarítást megkönnyítendő.</p>
					<h2><b>Fürj ketrec készítés</b></h2>
					<p>A fürj ketrec készítés első lépése a ketrec aljának és tetejének elkészítése. Célszerű a falra szerelni, megakadályozván az aljzaton esetlegesen előforduló kisállatok (egerek, patkányok, bogarak) ketrecbe jutását. De jó megoldás, ha lábakon álló ketreceket készítünk. Mérete falra szereléskor a helyiség szélességétől függően pl. 200 cm hosszú, mélysége 90 cm mély, magassága 50 cm. A ketrec alját és felső lapját egy erős vázra ráerősítve helyezzük a falra. Az ajtók felhelyezésénél figyeljünk arra, hogy ha nagy takarításra kerülne sor az egyik ajtó egy mozdulattal könnyen levehető legyen. A rácsozat maximum 1 cm lyukbőségű legyen.</p>
					<p>A fürj itató elmaradhatatlan kelléke a ketrecnek. Ahány tenyésztő, annyi fajta itató megoldás létezik. Van ki kívül, van ki belül, más lógatva vagy a padlóból kiindulva, olcsó vagy éppen drága berendezést használ itatásra. Kis ráfordítással, kreatívan mi magunk is készíthetünk itatóberendezést egyszerű üdítős flakon felhasználásával. Hogyan?</p>
					<p>Szükségeltetik 1 db, nyulaknak szánt itatószelep, 1 db tetszés szerinti literes ásványvizes flakon kupakkal, spárga.</p>
					<p>Első lépésként az ásványvizes flakon kupakját előbb vékony fúróval, majd 8,5-ös fúróval óvatosan sorja menetesre kifúrjuk, majd az itatószelepből eltávolítjuk az erős rugót, s rugó nélkül összerakjuk a szelepet és óvatosan a kupakba csavarozzuk. A flakon aljára spárgát kötünk, s egy kampóra akasztjuk. Ezután teletöltjük vízzel a flakont, és megfordítva, fejjel lefelé felakasztjuk. A fürjek a szelepen keresztül csipkedik ki a fizet a flakonból, s a felesleges víz összegyűjtésére a flakon alá edényt helyezhetünk, közepére célszerű nehezéket is tenni, nehogy felboruljon. Az itt összegyűlő koszos víz egyszerűen kiönthető.</p>',
			],
			'furjtojas-kura'                   => ['Fürjtojás kúra', '<h1>Fürjtojás kúra</h1>
					<p>A természetgyógyászat körében nem ismeretlen a fürjtojás kúra. A fürjtojás kúraszerű fogyasztása jótékony hatással van az emberi szervezetre, számos betegség megelőzésében és kezelésében alkalmazható.</p>
					<p>A fürjtojás kúra lényege abból áll, hogy a fürjtojásokat éhgyomorra, nyersen kell elfogyasztani, csak így érvényesül jótékony hatásuk. A kúraszerű alkalmazás logikusan napi szinten történő fogyasztást jelent, egy alkalommal felnőtteknek 4-5, gyerekeknek 2-3 darab fürjtojás elfogyasztásával.</p>
					<p>
					<img src="/uploads/furjtojas-kura-300x200.jpg" style="float:left; height:200px; width:300px; margin-right:15px;" alt="Fürjtojás kúra">
					</p>
					<p>A fürjtojás kúra ideje a kezelendő problémától függ, de általánosságban elmondható, hogy több hetet vesz igénybe, 1-4 hónap szünet beiktatása után történő folytatással.</p>
					<p>A nyers, friss fürjtojást tehát éhgyomorra, étkezés előtt fél órával fogyasszuk, üssük fel egy pohárba a tojást, öntsünk rá egy kis vizet és igyuk meg. Az elviselhetőbb íz kedvéért kikeverhető mézzel vagy cukorral is. Fogyasztás előtt alaposan mossuk meg a tojásokat. Utána csak 2 óra elteltével szabad étkezni a felnőtteknek, a fiataloknak egy óra múlva, a gyermekeknek fél óra múlva. Felnőtteknek és fiataloknak 240 ill. 120 darab fürjtojás kúraszerű használata ajánlott, gyermekeknek 30-90 darab.</p>
					<p>&nbsp;</p>
					<p>A 120 darabból álló fürjtojás kúra az alábbi betegségek gyógyításában segít: magas vérnyomás, gyomorfekély, emésztési zavarok, kötőhártya-gyulladás, csalánkiütés, szamárköhögés.</p>
					<p>A 240 darabból álló kúra: tuberkulózis, a máj betegségei, cukorbaj, allergiák, asztma, vérszegénység, nemi impotencia, köszvény, migrén, neuraszténia, koleszterin, érelmeszesedés, kóros elhízás, szénanátha, vesebántalmak kezelésében segít.</p>
					<p>&nbsp;</p>
					<table cellspacing="0" cellpadding="0" border="1">
					<tbody>
					<tr>
					<td width="83" valign="top"><b>Életkor</b></td>
					<td width="95" valign="top"><b>Napok száma</b></td>
					<td width="85" valign="top"><b>Tojások száma</b></td>
					<td width="208" valign="top"><b>Tojások az első 5 napban</b></td>
					<td width="144" valign="top"><b>Fogyasztás a kúra végéig</b></td>
					</tr>
					<tr>
					<td width="83" valign="top">Felnőtt</td>
					<td width="95" valign="top">49</td>
					<td width="85" valign="top">240</td>
					<td width="208" valign="top">3 3 4 5 5 naponta</td>
					<td width="144" valign="top">5 tojás a 49. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">Felnőtt</td>
					<td width="95" valign="top">25</td>
					<td width="85" valign="top">120</td>
					<td width="208" valign="top">3 3 4 5 5 naponta</td>
					<td width="144" valign="top">5 tojás a 25. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">16-18 év</td>
					<td width="95" valign="top">25</td>
					<td width="85" valign="top">120</td>
					<td width="208" valign="top">3 3 4 5 5 naponta</td>
					<td width="144" valign="top">5 tojás a 25. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">11-15 év</td>
					<td width="95" valign="top">31</td>
					<td width="85" valign="top">120</td>
					<td width="208" valign="top">3 3 4 5 5 naponta</td>
					<td width="144" valign="top">4 tojás a 31. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">8-10 év</td>
					<td width="95" valign="top">30</td>
					<td width="85" valign="top">90</td>
					<td width="208" valign="top">3 3 3 3 4 naponta</td>
					<td width="144" valign="top">3 tojás a 30. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">4-7 év</td>
					<td width="95" valign="top">20</td>
					<td width="85" valign="top">60</td>
					<td width="208" valign="top">3 3 3 3 3 naponta</td>
					<td width="144" valign="top">3 tojás a 20. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">1-3 év</td>
					<td width="95" valign="top">30</td>
					<td width="85" valign="top">60</td>
					<td width="208" valign="top">3 3 3 3 3 naponta</td>
					<td width="144" valign="top">2 tojás a 30. napig</td>
					</tr>
					<tr>
					<td width="83" valign="top">3 hó – 1 év</td>
					<td width="95" valign="top">30</td>
					<td width="85" valign="top">30</td>
					<td width="208" valign="top">1 1 1 1 1 naponta</td>
					<td width="144" valign="top">1 tojás a 30. napig</td>
					</tr>
					</tbody>
					</table>
					<p>&nbsp;</p>
					<p>A vázolt fürjtojás kúra kétszer-háromszor megismétlendő, 1-4 hónapos szünet közbeiktatásával a biztos és tartós eredmény kedvéért. Egészséges emberek is nyugodtan fogyaszthatják, megelőzés céljából, ősszel-télen az immunrendszer védekezőképességének megerősítésére kimondottan ajánlott.</p>',
			],
			'furjtojas-hatasa'                 => ['Fürjtojás hatása', '<h1>Fürjtojás hatása></h1>
					<p>Ha az emberi szervezetből hiányzik a szükséges ásványi anyag, vitamin vagy aminosav készlet, romló egészségi állapothoz és betegségekhez vezethet. A vitamin és nyomelem-pótlás mellett az esszenciális aminosavak rendszeres használata hozzájárul a szervezet egyensúlyának visszaállításához és egészséges működéséhez.</p>
					<p>A nyers fürjtojás fogyasztásának számos gyógyhatást tulajdonítanak. A fürjtojás kúra jótékony hatását már két hét múlva elkezdi kifejteni, de tartós eredmény természetesen csak rendszeres, megszakítás nélküli, ténylegesen kúraszerű fogyasztás után várható. A gyógyító fürjtojás hatása nyers fogyasztás esetén a leghatékonyabb, de a szervezetre gyakorolt pozitív élettani hatását főtt tojásként is kifejti, viszont figyeljünk a főzési időre, mert 15 perces hőkezelés után a vitaminok teljesen megsemmisülnek benne.</p>
					<p>
					<img src="/uploads/furjtojas-hatasa.jpg" style="float:left; height:238px; width:431px; margin-right:15px;" title="Fürjtojás hatása" alt="Fürjtojás hatása">
					A fürjtojás gyógyhatásai az alábbi területeken mutatkoznak meg: vérnyomásra, keringési zavarokra gyakorolt pozitív hatás, antihisztamin-hatás, gyulladáscsökkentő, gyomorszekréciót javító hatás. A fürjtojás igen kedvező hatással bír a szívre, májra, vesére, gyomorra és a vérkeringésre is. Csökkenti a magas vérnyomást, de ugyanígy hatékony gyomorfekély, érelmeszesedés, cukorbetegség, vérszegénység, asztma, tüdőbaj, fejfájás, szívinfarktus esetében. Adott betegség illetve kezelési terület függvénye, hogy hány tojásra van szükség az eredményes kikúráláshoz. Például a magas vérnyomás, emésztési zavarok, gyomorfekély, érelmeszesedés 120, a cukorbetegség, migrénes fejfájás, májbetegség, asztma, vérszegénység, vesebetegség 240 tojást igényel. Gyermekek és felnőttek számára is megmutatkozik a fürjtojás hatása.</p>
					<p>&nbsp;</p>
					<p>Gyermekeknél akut légzőszervi fetőzések megszüntetésében, nátha, influenza esetén sikeresen alkalmazható. Megelőzés céljából is kitűnő, felfrissít és fiatalon tartja, valamint ellenállóvá teszi a szervezetet.</p>
					<p>A fürjtojás hatása élettani is, hisz ott koncentrálódik az emberi szervezetben, ahol szükséges, kipótolja a szervezeti biológiai működéséhez szükséges meglévő hiányosságokat. A fürjtojás kúra alatt azonban életmódbeli változtatások betartása is szükséges, mint dohányzás mellőzése, koffeintartalmú italok és alkohol elhagyása, így erősíthető a test és állítható helyre az immunrendszer egyensúlya.</p>
					<p>Jótékony beltartalma mellett a fürjtojás hatása kiemelendő azon a területen is, hogy energiaértéke igen magas, zsírsavjának 2/3-a telítetlen, 1/3-a telített zsírsav. A telítetlen zsírsavak jótékony hatást gyakorolnak az emberi szervezetre. Az omega-3 és omega-6 zsírsavak esszenciális zsírsavak, melyeket szervezetünk nem képes előállítani, csak táplálékkal tudjuk bejuttatni.</p>
					<p>A japán fürj tojása vitaminokban is igen gazdag, megtalálhatóak benne az alábbi vitaminok: A-vitamin, Karotinoidok, Béta-karotin, E-vitamin, B1-vitamin, B2-vitamin, B6-vitamin, B12-vitamin, Nikotinsav, Folsav, Pantonénsav, Biotin, C-vitamin.</p>',
			],
			'furjtojas-tapasztalatok'          => ['Fürjtojás fogyasztásával kapcsolatos tapasztalatok', '<h1>Fürjtojás fogyasztásával kapcsolatos tapasztalatok</h1>
					<p>„Egy magazinban találtam rá a fürjtojás kúra hasznosságára. A cikk arról szólt, hogy japán, amerikai és orosz klinikákon folytatott kutatások kimutatták, hogy a fürj tojása számos betegségre gyógyhatással van. Én évek óta érelmeszesedéssel küzdök, mely bizony megkeseríti a mindennapjaim. A cikkben leírtak szerint 240 darab nyers fürjtojást kell elfogyasztani egy kúra alkalmával, napi 5 darab mennyiséggel, majd szünet után a kúra megismételendő. Nagy reménnyel fordultam a szokatlan „gyógyszerhez”, máris fürjtojás bevásárló-körútra indultam.</p>
					<p>
					<img src="/uploads/furjtojas.jpg" style="float:left; height:245px; width:368px; margin-right:15px;" title="Fürjtojás" alt="Fürjtojás">
					Másnap belevágtam a kúrába. Már a kúra felénél kezdtem jobban érezni magam, s ezután fokozatosan tért vissza az erőm, energikusabbá váltam. Ez nagy bizakodással töltött el. A 240. tojás elfogyasztása után kezdődött a szünet periódusa, s ekkor újra jelentkezett a gyengeség rajtam. A második kúra idején ismét egyre jobban lettem, de a biztonság kedvéért a második kúra után, néhány hét szünetet tartva felvállaltam egy harmadik kúrát is. A három menetes fürjtojás kúra után kontroll vizsgálatra mentem, ahol a korábbi leleteken mutatkozó eltéréseknek nyoma sem volt. A tizenegy évvel ezelőtt gyógyíthatatlannak minősített betegségemnek nyoma sincs. A fürjtojás kúra óta eltelt 2 esztendő alatt sem tértek vissza a tüneteim, és vérképem, valamint vizsgálati értékeim a normál állományban vannak.”</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>„Férjemmel hosszabb ideje gyermeket tervezünk, de a gyermekáldás folyamatosan elkerül minket. Már ott jártunk, hogy meddőségi intézethez fordulunk és belevágunk egy lombik programba, amikor a reumámat kezelő természetgyógyászom javasolta, hogy végezzünk el egy fürjtojás kúrát. Soha nem hallottam még erről a dologról és megvallom, idegenkedve fogadtam a lehetőséget. Mind én, mind a férjem végigcsináltuk a fürjtojás kúrát, maximálisan betartva az előírásokat és a szünetet. A kontroll orvosi ellenőrző vizsgálaton megállapították, hogy férjem spermaszáma növekedett. Rövid idő múlva sikeresen megfogantam és spontán megszületett ma már 9 hónapos kislányunk. Azóta vettünk néhány fürjet otthonra és a mindennapi étkezésünk részévé vált a fürjtojás fogyasztása, amit csak ajánlani tudok a hasonló problémával küzdő pároknak.”</p>
					<p>&nbsp;</p>
					<p>„Egy kontroll orvosi vérképvizsgálaton derült fény arra, hogy vércukor szintem a megengedett érték négyszerese. Teljesen letaglózott a hír, hisz nem vagyok gyógyszerpárti, ezért örültem nagyon, amikor egy internetes fórumon rátaláltam a fürjtojás kúrára. Kapva a lehetőségen nekiláttam a 240 tojásos fürjtojás kúrának. Olvastam, hogy a fürjtojás kúra a cukorbetegség mellett számos egyéb baj esetén megoldást hozhat és megelőző céllal is alkalmazható. Nagyon érdekelt a dolog, bár nem tudtam mit várhatok tőle. Reggelente, éhgyomorra, nyersen, ízesítés nélkül megittam a napi öt darab fürjtojást, és betartottam, hogy csak 1,5-2 óra múlva reggelizhetek. Sokszor azt vettem észre, hogy egyáltalán nem vagyok éhes, annyira eltelített ez a tojásmennyiség, és csak délben éreztem éhségérzetet. Már egy hónap után felére csökkent a vércukor értékem. Természetesen megtartottam a szakorvosi tanácsot is, és beleegyeztem a legenyhébb gyógyszer (Meforal) szedésébe, s táplálkozási szokásaimon is változtattam, tudatosan étkeztem. A második hónapban tovább süllyedtek az értékeim, harmadik hónapban pedig 6,5-re csökkent a reggeli előtt mért vércukor szintem úgy, hogy két- és fél hónap után már csak fél szem tablettát vettem be esténként. A 250. darab fürjtojás elfogyasztása utáni négyhetes szünetben kíváncsian vártam hogyan változik vércukor értékem és örömmel konstatáltam, hogy sem a gyógyszer felezése, sem a fürjtojás-kúra szüneteltetése kapcsán nem romlottak az értékeim, hanem stagnáltak. Az egy hónapos kényszerszünet után alig vártam, hogy folytathassam a kúrát, s a második kúra végére vércukor értékem teljesen a normál állományba került és azóta sem volt problémám. Félve, de elhagytam a kapott gyógyszerem is, és vércukor szintem beállt az 5,8-6,2 közötti állományba és az eltelt 4 hónap óta sem emelkedett vissza. Vércukor értékem optimalizálásán túl a fürjtojás kúrának köszönhetem, hogy több energiám van napközben, reggelente magamtól ébredek, nemi aktivitásom is nőtt. A fürjtojás fogyasztás azóta is életem részévé vált, továbbra is nyersen fogyasztok alkalmilag pár darab fürjtojást.” – Forrás: Borbély László / <a title="Furjhaz.hu" href="http://www.furjhaz.hu" target="_blank" rel="nofollow">Furjhaz.hu',
			],
			'miert-a-furjtojas'                => ['Miért a fürjtojás?', '<h1>Miért a fürjtojás?</h1>
					<p>A tojás az emberek legjelentősebb táplálékforrása. Ez így van napjainkban és így volt mindig is. A tojás mindig is egyszerűbben megszerezhető volt, mint például a hús, s mind tápértéke, mind fehérje- és zsírtartalma vetekszik a húséval. Számos szárnyas tojását fogyaszthatjuk, mégis az emberi étkezésben a tyúktojás fogyasztása a legelterjedtebb.</p>
					<p>A fürjtojás tojásfehérje tartalma az összes többi emberi fogyasztásra alkalmas madár tojásától nagyobb. Míg pl. a tyúktojás 55% fehérjét tartalmaz, a fürj tojása 60% tojásfehérje tartalmú. Az egészségtudatos életmódra törekvő emberek többsége felfedezte már a fürjtojás abszolút elsőbbségét, amiben nemcsak tojásfehérje koncentrációjának mennyisége, hanem számos egyéb vitamintartalma is kiemelhető. Napjainkban egyre inkább előtérbe helyeződik a fürjtojás, szemben a hagyományos tyúktojással, a japán fürjet a jövő baromfijaként emlegetik.</p>
					<p>Miért van szükségünk fehérjére, ezáltal miért hasznost tehát az emberi szervezet számára a tojásfehérje és a fürjtojás fehérjéje?</p>
					<p>A fehérje a legjobb aminosav. Fehérjékre a szervezet minden sejtjének szüksége van, mindenféle szövet, szerv, izom növekedése és az elpusztult sejtek pótlása fehérjét kíván.&nbsp; A tojásfehérje, más néven albumin élettani jelentősége abban emelhető ki leginkább, hogy a baromfi a kevésbé értékes növényi fehérjék fogyasztása révén nagy értékű tökéletes fehérjét állít elő, erre az emberi szervezet nem képes. A fürjtojás igazi kincs, a legmagasabb biológia értékkel rendelkező fehérje-forrás. Egyetlen fürjtojás fehérjéje közel 3 dkg húsnak felel meg. A tojásfehérje pozitívuma, hogy könnyen és maximálisan emészthető, különösen főtt állapotban, s átalakítás, azaz sütés-főzés közben sem veszít értékéből. A fürjtojás fogyasztható nyersen, főzve, sütve, aszpikba, feldolgozva és füstölve egyaránt.</p>
					<p>A fürjtojás tojásfehérje koncentrációja magasabb, mint a tyúktojásé, emellett beltartalma és magas vitaminértéke, valamint alacsony koleszterinszintje miatt is jóval hasznosabb az emberi szervezetnek, mint a hétköznapokban elterjedt tyúktojás. Kiemelendő tulajdonsága továbbá az eltarthatósága, hűtőben tárolva 90 napig, szobahőmérsékelten 30 napig őrzi meg szavatosságát.</p>
					<p>Egy japán fürj éves tojáshozama megközelítőleg 300 darabra tehető.</p>
			'],
			'cegunkrol'                        => ['Céginformációk', '<h1>Céginformációk:</h1>
					<ul>
					<li>Cégünk hivatalos elnevezése: TAMAGO Fürjtojás Feldolgozó Korlátolt felelősségű társaság; Hatályos: 1991/03/08-tól</li>
					<li>A cég rövidített elnevezés: TAMAGO Kft.; Hatályos: 1991/03/08-tól</li>
					<li>A cég székhelye: 1163 Budapest, Albán u. 1.; Hatályos: 1991/03/08-tól</li>
					<li>Cégjegyzékszám: 01-09-072900; Hatályos: 2006/10/16-tól</li>
					<li>Adószám: 10459223-2-01; Hatályos: 1991/08/05-től</li>
					<li>Cégünk 25 éve a fürjtojás specialistája és az egész Európai Unióban egyedül mi működtetünk engedéllyel fürjtojás feldolgozó üzemet.</li>
					</ul>
					<p>
					<img src="/uploads/TamagoKFT-furjtojas-feldolgozas-169x300.jpg" style="float:left; height:300px; width:169px; margin-right:15px;" alt="Tamago Kft. Fürjtojás feldolgozás">
					</p>
					<p>&nbsp;</p>
					<h2>Tulajdonosok</h2>
					<p>A TAMAGO Kft. tulajdonosa Hiroe Akihisa ügyvezető, aki Csömörön él. Csömör többnemzetiségű település, de azt talán kevesen tudják, hogy japán családok is élnek a faluban.</p>
					<p>Hiroe Akihisa már 40 éve, 1972 óta él hazánkban. Hosszú éveken keresztül a Mezőgazdasági és Élelmiszeripari Minisztérium támogatása mellett mezőgazdasági szakértőként dolgozott. A japán fürj naposcsibék nemének megállapítását célzó speciális eljárást még hazájában, Japában tanulta meg.</p>
					<p>1973-ban ismerkedett meg későbbi feleségével, Erzsébet asszonnyal, akivel Budapesten, a XVI. kerületben éltek. Csömörre úgy kerültek, hogy lovuk számára kerestek megfelelő helyet. Csömörön mindenki Sakura néven ismeri őket, utcájuk neve alapján.</p>
					<p>&nbsp;</p>
					<p>Szabadalmazott eljárása alapján magyar tőkéstársakkal „Arany Tamagocsan” (füstölt fürjtojás) gyártásába kezdett Sashalmon. Az általa vezetett TAMAGO Kft. füstölt és konzerv fürjtojás készítésével foglalkozik. Nagyrészt Hiroe Akihisa-nák köszönhetőn a füstölt fürjtojás Magyarországon az európai ízlést is kielégítő ínyencséggé nőtte ki magát, jól illeszkedik a magyar konyha ízeihez.</p>
					<p>A TAMAGO Kft. az egyetlen nagy múltú, fürjtojás feldolgozó Magyarországon, többszörösen díjazott terméke a füstölt fürjtojás és a konzerv fürjtojás. A cég 1989 óta van jelen a köztudatban.</p>
					<h3>A cég története</h3>
					<p>A cég alapítója Hiroe Akihisha 1972-ben érkezett Magyarországra, a mezőgazdasági minisztérium által alkalmazott szakértőként dolgozott évtizedeken át. A Tamago Kft-t 1991-ben négyen alapították, az alapítók között van magyar felesége is. Hiroe Akihisha célja az volt, hogy a Japánban kiemelten alkalmazott fürjtojást Magyarországon is elterjessze, például a gyermekek iskolai étkeztetésében vagy kórházakban a kiemelkedő gyógyhatásai miatt.</p>
					<h4>Profil</h4>
					<p>A TAMAGO Kft. szolgáltatásai: fürjtojás feldolgozás, füstölt és konzerv fürjtojás készítése, nyers fürj étkezési és tenyésztojás árusítása, illetve konyhakész pecsenyefürj forgalmazása.</p>
					<p>A TAMAGO Kft. 1991 óta van jelen Magyarországon a köztudatban. Mi vagyunk az egyedüli fürjtojás feldolgozó cég Magyarországon, aki füstölt és konzerv fürjtojást nagy mennyiségben állít elő.</p>
					<p>Tevékenységünk több évtizedes szakmai tapasztalatra és arra a szilárd meggyőződésre épül, hogy ügyfeleink érdekeinek messzemenő figyelembe vételével hosszú távon garantáljuk a sikeres és eredményes kiszolgálást, együttműködést. Vállalatunk megbízhatóan, költséghatékony és környezetbarát módon optimalizálja megrendelői ellátását.</p>
					<p>A TAMAGO KFt. által megfogalmazott és vallott értékek teszik cégünket a partnerek igényét maradéktalanul teljesítő, a folyamatos fejlődés iránt elkötelezett szolgáltatóvá.</p>
					<p>Célunk, hogy ügyfeleink számára olyan színvonalú kiszolgálást nyújtsunk, mely minőségi fürjtojás (étkezési tojás, tenyésztojás) vásárlását teszi lehetővé.</p>
					<p>Cégünk 1991 óta a magyarországi vásárlók együttműködő partnerévé vált és a jövőben is fő törekvésünk ezen színvonal fenntartása.</p>
					<h5>Díjaink</h5>
					<ul>
					<li>OMÉK’96 élelmiszeripari nagydíjas</li>
					<li>FOODAPEST ’96 Élelmiszeripari kiállítás – Újdonság díj</li>
					</ul>',
			],
			'kapcsolat'                        => ['Kapcsolat', '
					<div class="right_map">
						<iframe scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=hu&geocode=&q=1163 Budapest, Albán Street 1, Hungary|47.5087086,19.175843699999973;t=m&z=10&iwloc=A&output=embed&iwloc=near" height="300" frameborder="0" width="400"></iframe>
					</div>
					<p>Tamago Kft.<br>
					1161 Budapest, Albán utca 1.<br>
					Telefon: (06-1)-403-0459<br>
					E-mail: info@furjtojas.eu<br>
					Web: www.furjtojas.eu</p>
					<p>Fürjtojás rendelése&nbsp;telefonon: (06)-30-655-8977</p>
		'],
		];
	}
	
	/**
	 * Get SimpleShop contents.
	 * @return array [['label' => 'categoryLabel', 'products' => ['label' => 'productLabel', 'desscription' => 'productDescription', 'price' => 666], ...], ...]
	 */
	protected function getSimpleShopContents() {
		return [[
			        'label'    => 'Füstölt fürjtojás',
			        'products' => [[
				                       'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 10 darabos',
				                       'description' => '10 darabos kiszerelés',
				                       'price'       => 1221,
				                       'image'       => 'fustolt-furjtojas-10darabos-300x169.jpg',
			                       ], [
				                       'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 10 darabos',
				                       'description' => '10 db. / kiszerelés SZŐLŐMAGOLAJBAN',
				                       'price'       => 1551,
				                       'image'       => 'fustolt-furjtojas-10darabos-300x169.jpg',
			                       ], [
				                       'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 50 darabos',
				                       'description' => '50 darabos kiszerelés',
				                       'price'       => 5115,
				                       'image'       => 'fustolt-furjtojas-50darabos-300x169.jpg',
			                       ], [
				                       'label'       => 'Arany Tamagochan - Főtt, füstölt fürjtojás 50 darabos',
				                       'description' => '50 db. / kiszerelés SZŐLŐMAGOLAJBAN',
				                       'price'       => 5775,
				                       'image'       => 'fustolt-furjtojas-50darabos-300x169.jpg',
			                       ]],
		        ], [
			        'label'    => 'Főtt fürjtojás',
			        'products' => [[
				                       'label'       => 'Natúr főtt fürjtojás sólében (Himalája sóval)',
				                       'description' => '50 db. / kiszerelés',
				                       'price'       => 3883,
				                       'image'       => 'natur-fott-furjtojas-300x169.jpg',
			                       ], [
				                       'label'       => 'Főtt fürjtojás konzerv - 10 darabos',
				                       'description' => '10 darab tojás, sós lében 	',
				                       'price'       => 1001,
				                       'image'       => 'natur-konzerv-furjtojas-10db-260x300.jpg',
			                       ], [
				                       'label'       => 'Főtt fürjtojás konzerv - 35 darabos',
				                       'description' => '35 darab tojás, sós lében',
				                       'price'       => 3113,
				                       'image'       => 'natur-konzerv-furjtojas-35db-205x300.jpg',
			                       ]],
		        ], [
			        'label'    => 'Nyers fürjtojás',
			        'products' => [[
				                       'label'       => 'Nyers fürjtojás - 15 darabos',
				                       'description' => '15 darabos kiszerelés',
				                       'price'       => 474,
				                       'image'       => 'nyers-furjtojas-15db-265x300.jpg',
			                       ], [
				                       'label'       => 'Fürj tenyésztojás - 15 darabos',
				                       'description' => '15 darabos kiszerelés',
				                       'price'       => 1331,
				                       'image'       => 'furj-tenyesztojas-300x210.jpg',
			                       ]],
		        ]];
	}
	
}
