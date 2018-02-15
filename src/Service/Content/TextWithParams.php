<?php

namespace App\Service\Content;

use App\Egf\Util;

/**
 * Class TextWithParams
 * It add parameter values to a text.
 * TODO
 */
class TextWithParams {

	public function getExtendedByArray($text, $parameters) {
		$aPlaceholders = Util::getPlaceholdersFromString($text);
		$values       = [];
		// Iterate the dynamic parameters.
		foreach ($aPlaceholders as $key) {
			// Trim the string.
			$trimmedKey = trim($key, "{ }");
			// If the entity has a method like this or it's a property chain , then load the data.
			if (isset($parameters[$trimmedKey])) {
				$values[$key] = $parameters[$trimmedKey];
			}
			// Method wasn't found and it's not a chain of properties.
			else {
				throw new \Exception("Parameter array doesn't have the asked property! \n Identifier Code: {$trimmedKeykey} \n In text: {$key}");
			}
		}
		// Update the string with the data from the parameter array.
		foreach ($values as $key => $val) {
			$text = str_replace($key, $val, $text);
		}
		
		
		return $text;
	}
	
	/**
	 * @param $text
	 * @param $object
	 * @return mixed
	 */
	public function getExtendedByObject($text, $object) {
		return Util::extendStringWithDynamicParameters($text, $object);
	}

}